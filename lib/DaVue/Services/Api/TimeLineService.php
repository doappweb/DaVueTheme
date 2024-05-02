<?php

namespace SuiteCRM\DaVue\Services\Api;

use BeanFactory;
use UploadFile;

class TimeLineService
{
    /**
     * @usage http://localhost/index.php?VueAjax=1&method=getTimeline&debug=1&arg[]...
     * @param $arg
     * @return array|array[]
     */
    public function getTimeline($arg): array
    {
        /**
         * This flag is enabled by default,
         * so dates are displayed in the database format,
         * instead of the format from the user settings
         **/
        global $disable_date_format;
        $disable_date_format = false;


        // global $mod_strings;

        global $current_language;
        $mod_strings = return_module_language($current_language, 'Activities');
        $mod_strings_cases = return_module_language($current_language, 'AOP_Case_Updates');

        global $app_strings;
        global $app_list_strings;
        global $timedate;



        $summary_list = array();
        $task_list = array();
        $meeting_list = array();
        $calls_list = array();
        $emails_list = array();
        $notes_list = array();

        $focus = BeanFactory::getBean($arg['module_name']);

        if (!empty($arg['record'])) {
            $result = $focus->retrieve($arg['record']);
            if ($result == null) {
                return array(
                    'error' =>  $app_strings['ERROR_NO_RECORD'],
                );
            }
        }

        $activitiesRels = array(
            'tasks' => 'Task',
            'meetings' => 'Meeting',
            'calls' => 'Call',
            'emails' => 'Email',
            'notes' => 'Note'
        );
        //Setup the arrays to store the linked records.
        foreach ($activitiesRels as $relMod => $beanName) {
            $varname = 'focus_' . $relMod . '_list';
            $$varname = array();
        }
        foreach ($focus->get_linked_fields() as $field => $def) {
            if ($focus->load_relationship($field)) {
                $relTable = BeanFactory::getBean($focus->$field->getRelatedModuleName())->table_name;
                if (array_key_exists($relTable, $activitiesRels)) {
                    $varname = 'focus_' . $relTable . '_list';
                    $$varname =
                        sugarArrayMerge($$varname, $focus->get_linked_beans($field, $activitiesRels[$relTable]));
                }
            }
        }

        foreach ($focus_tasks_list as $task) {
            if (!$task->ACLAccess('list')) {
                continue;
            }

            if (empty($task->date_due) || $task->date_due == '0000-00-00') {
                $date_due = '';
            } else {
                $date_due = $task->date_due;
            }

            if ($task->status !== "Not Started"
                && $task->status !== "In Progress"
                && $task->status !== "Pending Input") {
                $ts = '';
                if (!empty($task->fetched_row['date_due'])) {
                    //tasks can have an empty date due field
                    $ts = $timedate->fromDb($task->fetched_row['date_due'])->ts;
                }
                $summary_list[] = array('name' => $task->name,
                    'id' => $task->id,
                    'type' => "Task",
                    'direction' => '',
                    'module' => "Tasks",
                    'status' => $app_list_strings['task_status_dom'][$task->status],
                    'parent_id' => $task->parent_id,
                    'parent_type' => $task->parent_type,
                    'parent_name' => $task->parent_name,
                    'contact_id' => $task->contact_id,
                    'contact_name' => $task->contact_name,
                    'date_modified' => $date_due,
                    'description' => $this->getTimlineTaskDetails($task),
                    'date_type' => $app_strings['DATA_TYPE_DUE'],
                    'sort_value' => $ts,
                    'acl' => array(
                        'detail' => $task->ACLAccess('detail'),
                        'edit'   => $task->ACLAccess('edit'),
                        'delete' => $task->ACLAccess('delete')
                    )
                );
            } else {
                continue;

            }
        } // end Tasks

        foreach ($focus_meetings_list as $meeting) {
            if (!$meeting->ACLAccess('list')) {
                continue;
            }

            if ($meeting->status !== 'Planned') {

                if (empty($meeting->contact_id) && empty($meeting->contact_name)) {
                    $meeting_contacts = $meeting->get_linked_beans('contacts', 'Contact');
                    if (!empty($meeting_contacts[0]->id) && !empty($meeting_contacts[0]->name)) {
                        $meeting->contact_id = $meeting_contacts[0]->id;
                        $meeting->contact_name = $meeting_contacts[0]->name;
                    }
                }

                $summary_list[] = array(
                    'name' => $meeting->name,
                    'id' => $meeting->id,
                    'type' => $mod_strings['LBL_MEETING_TYPE'],
                    'direction' => '',
                    'module' => 'Meetings',
                    'status' => $app_list_strings['meeting_status_dom'][$meeting->status],
                    'parent_id' => $meeting->parent_id,
                    'parent_type' => $app_list_strings['parent_type_display'][$meeting->parent_type],
                    'parent_name' => $meeting->parent_name,
                    'contact_id' => $meeting->contact_id,
                    'contact_name' => $meeting->contact_name,
                    'date_modified' => $meeting->date_start,
                    'description' => $this->timelineFormatDescription($meeting->description),
                    'date_type' => $mod_strings['LBL_DATA_TYPE_START'],
                    'sort_value' => $timedate->fromDb($meeting->fetched_row['date_start'])->ts,
                    'acl' => array(
                        'detail' => $meeting->ACLAccess('detail'),
                        'edit'   => $meeting->ACLAccess('edit'),
                        'delete' => $meeting->ACLAccess('delete')
                    )
                );
            } else {

                continue;

            }
        } // end Meetings

        foreach ($focus_calls_list as $call) {
            if (!$call->ACLAccess('list')) {
                continue;
            }

            if ($call->status !== 'Planned') {

                if (empty($call->contact_id) && empty($call->contact_name)) {
                    $call_contacts = $call->get_linked_beans('contacts', 'Contact');
                    if (!empty($call_contacts[0]->id) && !empty($call_contacts[0]->name)) {
                        $call->contact_id = $call_contacts[0]->id;
                        $call->contact_name = $call_contacts[0]->name;
                    }
                }

                $summary_list[] = array(
                    'name' => $call->name,
                    'id' => $call->id,
                    'type' => $mod_strings['LBL_CALL_TYPE'],
                    'direction' => $app_list_strings['call_direction_dom'][$call->direction],
                    'module' => 'Calls',
                    'status' => $app_list_strings['call_status_dom'][$call->status],
                    'parent_id' => $call->parent_id,
                    'parent_type' => $app_list_strings['parent_type_display'][$call->parent_type],
                    'parent_name' => $call->parent_name,
                    'contact_id' => $call->contact_id,
                    'contact_name' => $call->contact_name,
                    'date_modified' => $call->date_start,
                    'description' => $this->timelineFormatDescription($call->description),
                    'date_type' => $mod_strings['LBL_DATA_TYPE_START'],
                    'sort_value' => $timedate->fromDb($call->fetched_row['date_start'])->ts,
                    'acl' => array(
                        'detail' => $call->ACLAccess('detail'),
                        'edit'   => $call->ACLAccess('edit'),
                        'delete' => $call->ACLAccess('delete')
                    )
                );
            } else {

                continue;
            }
        } // end Calls

        foreach ($focus_emails_list as $email) {
            if (!$email->ACLAccess('list')) {
                continue;
            }
            if (empty($email->contact_id) && empty($email->contact_name)) {
                $email_contacts = $email->get_linked_beans('contacts', 'Contact');
                if (!empty($email_contacts[0]->id) && !empty($email_contacts[0]->name)) {
                    $email->contact_id = $email_contacts[0]->id;
                    $email->contact_name = $email_contacts[0]->name;
                }
            }
            $ts = '';
            if (!empty($email->fetched_row['date_sent_received'])) {
                //emails can have an empty date sent field
                $ts = $timedate->fromDb($email->fetched_row['date_sent_received'])->ts;
            } elseif (!empty($email->fetched_row['date_entered'])) {
                $ts = $timedate->fromDb($email->fetched_row['date_entered'])->ts;
            }

            $summary_list[] = array(
                'name' => $email->name,
                'id' => $email->id,
                'type' => $mod_strings['LBL_EMAIL_TYPE'],
                'direction' => $app_list_strings['dom_email_types'][$email->type],
                'module' => 'Emails',
                'status' => '',
                'parent_id' => $email->parent_id,
                'parent_type' => $app_list_strings['parent_type_display'][$email->parent_type],
                'parent_name' => $email->parent_name,
                'contact_id' => $email->contact_id,
                'contact_name' => $email->contact_name,
                'date_modified' => $email->date_sent_received,
                'description' => $this->getTimelineEmailDetails($email),
                'date_type' => $mod_strings['LBL_DATA_TYPE_SENT'],
                'sort_value' => $ts,
                'acl' => array(
                    'detail' => $email->ACLAccess('detail'),
                    'edit'   => $email->ACLAccess('edit'),
                    'delete' => $email->ACLAccess('delete')
                )
            );
        } //end Emails

        foreach ($focus_notes_list as $note) {
            if (!$note->ACLAccess('list')) {
                continue;
            }
            if ($note->ACLAccess('view')) {
                $summary_list[] = array(
                    'name' => $note->name,
                    'id' => $note->id,
                    'type' => $mod_strings['LBL_NOTE_TYPE'],
                    'direction' => '',
                    'module' => 'Notes',
                    'status' => '',
                    'parent_id' => $note->parent_id,
                    'parent_type' => $app_list_strings['parent_type_display'][$note->parent_type],
                    'parent_name' => $note->parent_name,
                    'contact_id' => $note->contact_id,
                    'contact_name' => $note->contact_name,
                    'date_modified' => $note->date_modified,
                    'description' => $this->timelineFormatDescription($note->description),
                    'date_type' => $mod_strings['LBL_DATA_TYPE_MODIFIED'],
                    'sort_value' => strtotime($note->fetched_row['date_modified'] . ' GMT'),
                    'acl' => array(
                        'detail' => $note->ACLAccess('detail'),
                        'edit'   => $note->ACLAccess('edit'),
                        'delete' => $note->ACLAccess('delete')
                    )
                );
                if (!empty($note->filename)) {
                    $count = count($summary_list);
                    $count--;
                    $summary_list[$count]['filename'] = $note->filename;
                    $summary_list[$count]['fileurl'] = UploadFile::get_url($note->filename, $note->id);
                }
            }
        } // end Notes

        // Case Update List
        if('Cases' == $arg['module_name']){
            $update_list = array();
            $updates = $focus->get_linked_beans('aop_case_updates', 'AOP_Case_Updates');
            if ($updates) {
                usort(
                    $updates,
                    function ($a, $b) {
                        $aDate = $a->fetched_row['date_entered'];
                        $bDate = $b->fetched_row['date_entered'];
                        if ($aDate < $bDate) {
                            return 1;
                        } elseif ($aDate > $bDate) {
                            return -1;
                        }
                        return 0;
                    }
                );

                foreach ($updates as $update) {
                    $updateOptions = array();

                    if ($update->assigned_user_id) {
                        $updateOptions['creatorName'] = $update->getUpdateUser()->name;
                        if ($update->internal) {
                            $updateOptions['type'] = 'caseStyleInternal';
                            $updateOptions['status'] = $mod_strings_cases['LBL_INTERNAL'];
                        } else {
                            $updateOptions['type'] = 'caseStyleUser';
                            $updateOptions['status'] = $mod_strings_cases['LBL_MODULE_NAME'];
                        }
                    } elseif ($update->contact_id) {
                        $updateOptions['creatorName'] = $update->getUpdateContact()->name;
                        $updateOptions['type'] = 'caseStyleContact';
                    } else {
                        continue;
                    }

                    $updateOptions['id'] =$update->id;
                    $updateOptions['description'] = nl2br(html_entity_decode($update->description));
                    $updateOptions['date_entered'] = $update->date_entered;

                    $ts = $timedate->fromDb($update->fetched_row['date_entered'])->ts;

                    $updateOptions['attachments'] = array();
                    $notes = $update->get_linked_beans('notes', 'Notes');
                    if ($notes) {
                        foreach ($notes as $note) {
                            $updateOptions['attachments'][$note->id] = $note->filename;
                        }
                    }
                    $update_list[] = array(
                        'id' => $update->id,
                        'type' => $updateOptions['status'],
                        'module' => 'Cases',
                        'class' =>  $updateOptions['type'],
                        'contact_name' => $updateOptions['creatorName'],
                        'date_modified' => $updateOptions['date_entered'],
                        'description' => $updateOptions['description'],
                        'attachments' => $updateOptions['attachments'],
                        'sort_value' => $ts,
                        'acl' => array(
                            'detail' => true,
                            'edit'   => false,
                            'delete' => false
                        )
                    );
                }
            }
        }

        if (count($summary_list) > 0) {
            array_multisort(array_column($summary_list, 'sort_value'), SORT_DESC, $summary_list);

            foreach ($summary_list as $list) {
                if ($list['module'] === 'Tasks') {
                    $task_list[] = $list;
                } elseif ($list['module'] === 'Meetings') {
                    $meeting_list[] = $list;
                } elseif ($list['module'] === 'Calls') {
                    $calls_list[] = $list;
                } elseif ($list['module'] === 'Emails') {
                    $emails_list[] = $list;
                } elseif ($list['module'] === 'Notes') {
                    $notes_list[] = $list;
                }
            }
        }

        $result_array = array(
            'summary_list'  =>  $summary_list,
            'task_list'     =>  $task_list,
            'meeting_list'  =>  $meeting_list,
            'calls_list'    =>  $calls_list,
            'emails_list'   =>  $emails_list,
            'notes_list'    =>  $notes_list,
        );

        // Case Update List
        if('Cases' == $arg['module_name']){
            $update_summary_list = array_merge($summary_list, $update_list);
            if (count($update_summary_list) > 0) {
                array_multisort(array_column($update_summary_list, 'sort_value'), SORT_DESC, $update_summary_list);
            }
            $result_array['update_summary'] = $update_summary_list;
            $result_array['update_list'] = $update_list;
        }

        return $result_array;
    }

    /**
     * @param $email
     *
     * @return string
     */
    public function getTimelineEmailDetails($email)
    {
        $details = "";

        if (!empty($email->to_addrs)) {
            $details .= 'To: ' . $email->to_addrs . '<br>';
        }
        if (!empty($email->from_addr)) {
            $details .= 'From: ' . $email->from_addr . '<br>';
        }
        if (!empty($email->cc_addrs)) {
            $details .= 'CC: ' . $email->cc_addrs . '<br>';
        }
        if (!empty($email->from_addr) || !empty($email->cc_addrs) || !empty($email->to_addrs)) {
            $details .= '<br>';
        }

        // cn: bug 8433 - history does not distinguish b/t text/html emails
        $details .= empty($email->description_html) ? $this->timelineFormatDescription($email->description) :
            $this->timelineFormatDescription(strip_tags(br2nl(from_html($email->description_html))));

        return $details;
    }

    /**
     * @param $task
     *
     * @return string
     */
    public function getTimlineTaskDetails($task)
    {
        global $app_strings;

        $details = "";
        if (!empty($task->date_start) && $task->date_start != '0000-00-00') {
            $details .= $app_strings['DATA_TYPE_START'] . $task->date_start . '<br>';
            $details .= '<br>';
        }
        $details .= $this->timelineFormatDescription($task->description);

        return $details;
    }

    /**
     * @param $description
     *
     * @return string
     */
    public function timelineFormatDescription($description)
    {
        return nl2br($description);
    }

}
