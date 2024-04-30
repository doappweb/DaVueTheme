<?php

namespace SuiteCRM\DaVue\Domain\Modules;

use BeanFactory;
use Calendar;

class CalendarHandler
{
    public function calendar($params): array
    {
        global $timedate, $app_list_strings, $db;
        // --------- Prepare calendar settings >>>>>
        // @see Calendar/CalendarDisplay.php :: load_settings_template()
        $calendar = new Calendar($params['view']);
        list($d_start_hour, $d_start_min) =  explode(":", $calendar->day_start_time);
        list($d_end_hour, $d_end_min) =  explode(":", $calendar->day_end_time);
        $time_pref = $timedate->get_time_format();

        $meridiemOptions = array();
        if (strpos($time_pref, 'a') || strpos($time_pref, 'A')) {
            $hoursOptions = array(
                '01'=>'01', '02'=>'02', '03'=>'03', '04'=>'04', '05'=>'05', '06'=>'06',
                '07'=>'07', '08'=>'08', '09'=>'09', '10'=>'10', '11'=>'11', '12'=>'12'
            );

            $startMeridiem = $endMeridiem = 'am';
            if ($d_start_hour === '00') {
                $d_start_hour = '12';
            } elseif ($d_start_hour === '12') {
                $startMeridiem = 'pm';
            }
            if ($d_start_hour > 12) {
                $d_start_hour -= 12;
                if ($d_start_hour < 10) {
                    $d_start_hour = '0' . $d_start_hour;
                } else {
                    $d_start_hour = (string)$d_start_hour;
                }
                $startMeridiem = 'pm';
            }

            if ($d_end_hour == 0) {
                $d_end_hour = 12;
            } elseif ($d_end_hour == 12) {
                $endMeridiem = 'pm';
            }
            if ($d_end_hour > 12) {
                $d_end_hour -= 12;
                if ($d_end_hour < 10) {
                    $d_end_hour = '0' . $d_end_hour;
                } else {
                    $d_end_hour = (string)$d_end_hour;
                }
                $endMeridiem = 'pm';
            }

            if (strpos($time_pref, 'a')) {
                $meridiemOptions = $app_list_strings['dom_meridiem_lowercase'];
            } else {
                $meridiemOptions = $app_list_strings['dom_meridiem_uppercase'];
                $startMeridiem = strtoupper($startMeridiem);
                $endMeridiem = strtoupper($endMeridiem);
            }
        } else {
            $hoursOptions = array(
                '00', '01', '02', '03', '04', '05',
                '06', '07', '08', '09', '10', '11',
                '12', '13', '14', '15', '16', '17',
                '18', '19', '20', '21', '22', '23',
                '24'
            );
            $startMeridiem = $endMeridiem = '';
        }
        // <<<<< prepare calendar settings  ---------

        if ($params['post']) {
            $paramsAstr = $_POST['a_str'];
        } else {
            $paramsAstr = $params['a_str'];
        }

        $a_Str = html_entity_decode($paramsAstr, ENT_QUOTES);

        $activitiesByUsers = json_decode($a_Str, true);
        foreach ($activitiesByUsers as $userId => &$activities) {
            foreach ($activities as &$activity) {
                $activity['timestampEnd'] = $activity['timestamp'] + 3600 * $activity['duration_hours'] + 60 * $activity['duration_minutes'];

                $activityBean = BeanFactory::getBean($activity['module_name'], $activity['record']);
                $module = strtolower($activity['module_name']);

                $result = $db->query("SELECT name, date_start FROM $module WHERE id='{$activity['record']}'");
                $result = $db->fetchRow($result);

                $activity['name'] = $result['name'];

                if ($activity['module_name'] === 'Tasks' && $result['date_start']) {
                    $activity['timestamp'] = $timedate->asUserTs($timedate->fromDb($result['date_start']));
                }
            }
        }

        $sharedUsers = array();
        if (isset($params['shared_ids'])) {
            foreach ($params['shared_ids'] as $userId) {
                $userBean = BeanFactory::getBean('Users', $userId);
                $sharedUsers[$userId] = array(
                    'userName' => $userBean->user_name,
                    'fullName' => $userBean->full_name,
                );
            }
        }

        $result = array(
            'view' => $params['view'],
            'startWeekdayDayIndex' => $params['start_weekday'],  // 0 is Sunday, 1 is Monday.

            'availableSharedUsers' => get_user_array(false),
            'sharedUsers' => $sharedUsers,

            'activities' => $activitiesByUsers,

            'calendarSettings' => array(
                'display_timeslots' => $params['display_timeslots'],  // Display time slots in Day and Week views
                'shared_calendar_separate' => $params['shared_calendar_separate'],
                'hoursOptions' => $hoursOptions,
                'minutesOptions' => array('0'=>'00','15'=>'15','30'=>'30','45'=>'45'),
                'meridiemOptions' => $meridiemOptions,
                'dateStartHours' => $d_start_hour,
                'dateStartMinutes' => $d_start_min,
                'dateStartMeridiem' => $startMeridiem,
                'dateEndHours' => $d_end_hour,
                'dateEndMinutes' => $d_end_min,
                'dateEndMeridiem' => $endMeridiem,
                'showCalls' => $params['show_calls'],
                'showTasks' => $params['show_tasks'],
                'showCompleted' => $params['show_completed'],
                'activityColors' => $params['activity'],
                'selectedDay' => $params['day'],
                'selectedMonth' => $params['month'],
                'selectedYear' => $params['year'],
            ),
        );

        return $result;
    }

    /**
     * @param $activityBean
     * @return array|null
     */
    public function buildActivityItems($activityBean): ?array
    {
        global $current_user, $timedate;

        if (empty($activityBean)) {
            return null;
        }

        if ($activityBean->module_dir === 'Tasks') {
            $dateEnd = $activityBean->date_due;
        } else if ($activityBean->module_dir === 'FP_events') {
            $dateEnd = $activityBean->end_date;
        } else {
            $dateEnd = $activityBean->date_end;
        }

        if ($activityBean->module_dir === 'FP_events') {
            $dateStart = $activityBean->start_date;
        } else {
            $dateStart = $activityBean->date_start;
        }

        return [
            "user_id" => $current_user->id,
            "module_name" => $activityBean->module_dir,
            "type" => strtolower($activityBean->object_name),
            "assigned_user_id" => $activityBean->assigned_user_id,
            "record" => $activityBean->id,
            "name"=> $activityBean->name,
            "description" => $activityBean->description,
            "duration_minutes" => $activityBean->duration_minutes,
            "duration_hours" => $activityBean->duration_hours,
            "detail" => $activityBean->ACLAccess('detail'),
            "edit" => $activityBean->ACLAccess('edit'),
            "status" => $activityBean->status,
            "parent_name" => $activityBean->parent_name,
            "parent_id" => $activityBean->parent_id,
            "parent_type" => $activityBean->parent_type,
            "timestamp" => $timedate->asUserTs($timedate->fromDb($dateStart)),
            "timestampEnd" => $timedate->asUserTs($timedate->fromDb($dateEnd))
        ];
    }
}
