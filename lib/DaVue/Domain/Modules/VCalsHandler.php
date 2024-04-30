<?php

namespace SuiteCRM\DaVue\Domain\Modules;

use BeanFactory;
use json_config;
use CalendarActivity;
use SuiteCRM\DaVue\App;

class VCalsHandler
{
    /** @var App $app */
    private $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    /**
     * List of invited participants with information about their employment
     * It is used to output the scheduler to the EditView of the Calls and Meetings modules.
     * @param string $moduleName
     * @param string|null $recordId
     * @return array
     * @see include/json_config.php::get_static_json_server() - the list of invitees
     * @see modules/vCals/vCal.php::get_vcal_freebusy() - user activities
     */
    public function getVCalFreeBusy(string $moduleName, string $recordId): array
    {
        global $current_user;

        if ('' === $recordId) {
            $bean = BeanFactory::newBean($moduleName);

            $reminderInvite = array(
                'module' => $moduleName,
                'users_arr' => array(
                    0 => array(
                        'module' => 'Users',
                        'fields' => $current_user->toArray(),
                    )
                ),
                'fields' => $bean->toArray(),
            );
            unset($reminderInvite['users_arr'][0]['fields']['user_hash']);  // Maybe used
        } else {
            // the list of invitees
            $json_config = new json_config();
            $reminderInvite = $json_config->meeting_retrieve($moduleName, $recordId);
        }

        // information about the activities of each invited participant
        foreach ($reminderInvite['users_arr'] as &$user_arr) {

            if ($user_arr['module'] !== 'User') {
                $user_arr['freebusy'] = array();
                continue;
            }

            $user_arr['freebusy'] = $this->getUserEmployment($user_arr['fields']['id']);
        }

        // remove excess garbage
        unset($reminderInvite['contacts_arr']);
        unset($reminderInvite['leads_arr']);
        unset($reminderInvite['orig_users_arr_hash']);

        return $reminderInvite;
    }

    /**
     * Get the activity (freebusy) of a specific user by Id
     *
     * @param string $userId
     * @return array
     * @see modules/vCals/vCal.php::get_vcal_freebusy() - user activities
     */
    public function getUserEmployment(string $userId): array
    {
        global $locale, $timedate, $sugar_config;

        $user = BeanFactory::getBean('Users', $userId);
        $userName = $locale->getLocaleFormattedName($user->first_name, $user->last_name);

        // get current date for the user
        $nowDateTime = $timedate->getNow(true);

        // get start date ( 1 day ago )
        $startDateTime = $nowDateTime->get("yesterday");

        // get date 2 months from start date
        $timeOffset = 2;
        if (isset($sugar_config['vcal_time']) && $sugar_config['vcal_time'] != 0 && $sugar_config['vcal_time'] < 13) {
            $timeOffset = $sugar_config['vcal_time'];
        }
        $endDateTime = $startDateTime->get("+$timeOffset months");

        $ical_array = array();
        $ical_array['userName'] = $userName;
        $ical_array['dateStart'] = $startDateTime->getTimestamp() + $this->app->userTZOffsetSeconds;
        $ical_array['dateEnd'] = $endDateTime->getTimestamp() + $this->app->userTZOffsetSeconds;
        $ical_array['dateStamp'] = $nowDateTime->getTimestamp() + $this->app->userTZOffsetSeconds;
        $ical_array['activities'] = array();

        // If the parameter "vcal_time" == 0 in the config, then there is no point in looking for activity, because the time interval is empty
        if ($timeOffset != 0) {
            $activityList = array(
                "Meetings" => array(
                    "showCompleted" => true,
                    "start" => "date_start",
                    "end" => "date_end"
                ),
                "Calls" => array(
                    "showCompleted" => true,
                    "start" => "date_start",
                    "end" => "date_end"
                ),
            );

            $acts_arr = CalendarActivity::get_activities($activityList, $user->id, false, $startDateTime, $endDateTime, 'freebusy');

            foreach ($acts_arr as $act) {
                if (empty($act->start_time)) {
                    $act->start_time = $timedate->fromUser($act->sugar_bean->date_start, $user);
                }

                if (empty($act->end_time)) {
                    $act->end_time = $timedate->fromUser($act->sugar_bean->date_finish, $user);
                }

                $ical_array['activities'][$act->sugar_bean->id] = array(
                    'startDateTime' => $act->start_time->getTimestamp() + $this->app->userTZOffsetSeconds,  // In box $act->start_time->format($vCal::UTC_FORMAT);
                    'endDateTime' => $act->end_time->getTimestamp() + $this->app->userTZOffsetSeconds,
                    'type' => get_class($act->sugar_bean),
                );
            }
        }

        return $ical_array;
    }

    /**
     * The invitation acceptance statuses of each of the invited participants of the current meeting/call
     * @param string $moduleName
     * @param string $recordId
     * @return array
     */
    public function getStatusesAcceptingCurrentInvitation(string $moduleName, string $recordId): array
    {
        global $db;

        if ('Calls' === $moduleName) {
            $tablePrefix = 'call';
        } else {
            $tablePrefix = 'meeting';
        }

        $sql = "
            SELECT 'user' AS 'invitee_type', user_id AS 'invitee_id', accept_status
            FROM {$tablePrefix}s_users
            WHERE {$tablePrefix}_id = '$recordId'
                AND deleted = 0
            UNION ALL
            SELECT 'contact' AS 'invitee_type', contact_id AS 'invitee_id', accept_status
            FROM {$tablePrefix}s_contacts
            WHERE {$tablePrefix}_id = '$recordId'
                AND deleted = 0
            UNION ALL
            SELECT 'lead' AS 'invitee_type', lead_id AS 'invitee_id', accept_status
            FROM {$tablePrefix}s_leads
            WHERE {$tablePrefix}_id = '$recordId'
                AND deleted = 0
        ";
        $dbAnswer = $db->query($sql);
        $result = array();
        while ($row = $db->fetchByAssoc($dbAnswer)) {
            $result[] = array(
                'inviteeId' => $row['invitee_id'],
                'inviteeType' => $row['invitee_type'],
                'acceptStatus' => $row['accept_status'],
            );
        }

        return $result;
    }
}
