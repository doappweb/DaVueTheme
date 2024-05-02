<?php

namespace SuiteCRM\DaVue\Services\Common;

use BeanFactory;
use Document;
use DocumentRevision;
use Reminder;
use Sugar_Smarty;
use SugarBean;
use SugarEmailAddress;
use SuiteCRM\Exception\Exception;
use SurveyResponses;

class FieldFunctions
{
    /**
     * Perform different logic for fields with the "function" attribute
     * @param $fieldDef
     * @param $focus
     * @param null $specific - set specific handler
     * @return mixed
     */
    public function implement($fieldDef, $focus, $specific = null)
    {
        if ($specific){
            if (method_exists($this, $specific)) {
                return  $this->$specific($fieldDef, $focus);
            } else {
                return $fieldDef;
            }
        }

        if (is_array($fieldDef['function']) && isset($fieldDef['function']['name'])) {
            $functionName = $fieldDef['function']['name'];

            if (is_array($fieldDef['function']['name'])) {
                // A very rare case. In the box this is done with the getJobsList function
                $functionName = $fieldDef['function']['name'][1];
            }
        } else {
            $functionName = $fieldDef['function'];
        }

        // fix static field function
        $functionName = lcfirst(str_replace('::', '_', $functionName));

        if (method_exists($this, $functionName)) {
          return  $this->$functionName($fieldDef, $focus);
        } else {
            return $fieldDef;
        }
    }

    public function implementsFromArray($vardefs, $focus): array
    {
        foreach ($vardefs as $field => $defs){
            if (isset($defs['function'])) {
                $vardefs[$field] = $this->implement($defs, $focus);
            }
        }

        return $vardefs;
    }

    /**
     * @see modules/Currencies/Currency.php
     */
    private function getCurrencyDropDown(array $fieldDef, SugarBean $focus = null): array
    {
        $fieldDef['type'] = 'enum';
        $fieldDef['value'] = '';
        $fieldDef['massupdate'] = false;

        $currenciesData = $this->getCurrenciesData($focus);
        if (!empty($currenciesData)) {
            foreach ($currenciesData['options'] as $currencyOptionId => $currencyOptionData) {
                $fieldDef['options']["$currencyOptionId"] = "{$currencyOptionData['name']} : {$currencyOptionData['symbol']}";
                $fieldDef['conversionRate']["$currencyOptionId"] = $currencyOptionData['conversionRate'];
            }
            $fieldDef['value'] = $currenciesData['selectedOptionId'];
        }

        $fieldDef['focusCurrencyFieldNames'] = $this->getFocusCurrencyFieldNames($focus);

        return $fieldDef;
    }

    /**
     * @see modules/Currencies/Currency.php
     */
    private function getCurrencyNameDropDown(array $fieldDef): array
    {
        $fieldDef['type'] = 'enum';
        $fieldDef['value'] = '';
        $fieldDef['massupdate'] = false;

        $currenciesData = $this->getCurrenciesData(null);
        if (!empty($currenciesData)) {
            foreach ($currenciesData['options'] as $currencyOptionId => $currencyOptionData) {
                $fieldDef['options']["$currencyOptionId"] = $currencyOptionData['name'];
                $fieldDef['conversionRate']["$currencyOptionId"] = $currencyOptionData['conversionRate'];
            }
            $fieldDef['value'] = $currenciesData['selectedOptionId'];
        }

        $fieldDef['focusCurrencyFieldNames'] = $this->getFocusCurrencyFieldNames(null);

        return $fieldDef;
    }

    /**
     * @see modules/Currencies/Currency.php
     */
    private function getCurrencySymbolDropDown(array $fieldDef, SugarBean $focus = null): array
    {
        $fieldDef['type'] = 'enum';
        $fieldDef['value'] = '';
        $fieldDef['massupdate'] = false;

        $currenciesData = $this->getCurrenciesData($focus);
        if (!empty($currenciesData)) {
            foreach ($currenciesData['options'] as $currencyOptionId => $currencyOptionData) {
                $fieldDef['options']["$currencyOptionId"] = $currencyOptionData['symbol'];
                $fieldDef['conversionRate']["$currencyOptionId"] = $currencyOptionData['conversionRate'];
            }
            $fieldDef['value'] = $currenciesData['selectedOptionId'];
        }

        $fieldDef['focusCurrencyFieldNames'] = $this->getFocusCurrencyFieldNames($focus);

        return $fieldDef;
    }

    /**
     * Information about available currencies
     * isHelper
     */
    private function getCurrenciesData(SugarBean $focus = null): array
    {
        global $current_user;
        $result = array();

        if (empty($focus->id)) {
            $recordCurrencyId = $current_user->getPreference('currency');
            if (empty($recordCurrencyId)) {
                // -99 is the system default currency
                $recordCurrencyId = -99;
            }
        } else {
            $recordCurrencyId = $focus->currency_id;
        }

        $defaultCurrencyBean = BeanFactory::newBean('Currencies');
        $defaultCurrencyBean->retrieve('-99');
        $otherCurrencyBeans = $defaultCurrencyBean->get_full_list('name');
        if (null === $otherCurrencyBeans) {
            $otherCurrencyBeans = array();
        }
        $allCurrencyBeans = array_merge(array($defaultCurrencyBean), $otherCurrencyBeans);
        if (!empty($allCurrencyBeans)) {
            foreach ($allCurrencyBeans as $currencyBean) {
                if ($currencyBean->status == 'Active') {
                    $result['options']["{$currencyBean->id}"] = array(
                        'name' => $currencyBean->name,
                        'symbol' => $currencyBean->symbol,
                        'conversionRate' => $currencyBean->conversion_rate,
                    );
                    if ($currencyBean->id === $recordCurrencyId) {
                        $result['selectedOptionId'] = "{$currencyBean->id}";
                    }
                }
            }

            if (!isset($result['selectedOptionId'])) {
                $result['selectedOptionId'] = $allCurrencyBeans[0]->id;
            }
        }

        return $result;
    }

    /**
     * Fields as 'currency'.
     * isHelper
     */
    private function getFocusCurrencyFieldNames(SugarBean $focus = null): array
    {
        $currensyFieldNames = array();
        if (isset($focus->field_defs) && !empty($focus->field_defs)) {
            foreach ($focus->field_defs as $fieldName => $field_def) {
                if ($field_def['type'] === 'currency') {
                    $currensyFieldNames[] = $fieldName;
                }
            }
        }

        return $currensyFieldNames;
    }

    /**
     * @see modules/Employees/EmployeeStatus.php
     */
    private function getEmployeeStatusOptions(array $fieldDef, SugarBean $focus): array
    {
        global $current_user, $app_list_strings, $sugar_config;

        $fieldDef['type'] = 'enum';
        $fieldDef['options'] = $app_list_strings['employee_status_dom'];
        $fieldDef['value'] = $focus->employee_status;
        if (
            !is_admin($current_user)
            || (
                isset($sugar_config['default_user_name'])
                && $sugar_config['default_user_name'] == $current_user->user_name
                && isset($sugar_config['lock_default_user_name'])
                && $sugar_config['lock_default_user_name']
            )
        ) {
            $fieldDef['readonly'] = true;
        }

        return $fieldDef;
    }

    /**
     * @see modules/Calls/reschedule_history.php
     */
    private function reschedule_history(array $fieldDef, SugarBean $focus): array
    {
        global $app_list_strings, $mod_strings;
        $fieldDef['value'] = array();
        $query = "
        SELECT calls_reschedule.id 
        FROM calls_reschedule 
        JOIN users ON calls_reschedule.modified_user_id = users.id 
        WHERE call_id='".$focus->id."' 
        ORDER BY calls_reschedule.date_entered DESC
    ";
        $result = $focus->db->query($query);
        $reschedule = BeanFactory::newBean('Calls_Reschedule');
        while ($row = $focus->db->fetchByAssoc($result)) {
            $reschedule->retrieve($row['id']);
            $fieldDef['value'][] = $app_list_strings["call_reschedule_dom"][$reschedule->reason]
                .' - '.$reschedule->date_entered
                .' '.$mod_strings['LBL_RESCHEDULED_BY']
                .' '.$reschedule->created_by_name;
        }

        return $fieldDef;
    }

    /**
     * @see modules/Calls/reschedule_history.php
     */
    private function reschedule_count(array $fieldDef, SugarBean $focus): array
    {
        $fieldDef['value'] = $focus->reschedule_count;

        return $fieldDef;
    }

    /**
     * @see modules/Calls/CallHelper.php
     */
    private function getDurationMinutesOptions(array $fieldDef, SugarBean $focus): array
    {
        // Many of the transformations below have already been done before, unless this is a VueAjax request
        if (isset($_REQUEST['VueAjax'])) {
            if (isset($_REQUEST['duration_minutes'])) {
                $focus->duration_minutes = $_REQUEST['duration_minutes'];
            }

            if (!isset($focus->duration_minutes)) {
                $focus->duration_minutes = $focus->minutes_value_default;
            }

            global $timedate;
            //setting default date and time
            if (is_null($focus->date_start)) {
                $focus->date_start = $timedate->to_display_date(gmdate($timedate->get_date_time_format()));
            }
            if (is_null($focus->duration_hours)) {
                $focus->duration_hours = "0";
            }
            if (is_null($focus->duration_minutes)) {
                $focus->duration_minutes = "1";
            }
        }

        $fieldDef['options'] = $focus->minutes_values;
        $fieldDef['value'] = $focus->duration_minutes;

        return $fieldDef;
    }

    /**
     * @see modules/ProjectTask/ProjectTask.php
     */
    private function getUtilizationDropdown(array $fieldDef, SugarBean $focus): array
    {
        global $app_list_strings;
        $fieldDef['type'] = 'enum';
        $fieldDef['value'] = $focus->utilization;
        $fieldDef['options'] = $app_list_strings['project_task_utilization_options'];
        unset($fieldDef['function']);

        return $fieldDef;
    }

    /**
     * The conversion was performed in the process, the attribute is not relevant
     * @see modules/Documents/DocumentExternalApiDropDown.php
     */
    private function getDocumentsExternalApiDropDown(array $fieldDef, SugarBean $focus): array
    {
        // TODO: I did not test it on the settings page of the dashboard
        unset($fieldDef['function']);
        return $fieldDef;
    }

    /**
     * The conversion was performed in the process, the attribute is not relevant
     * @see modules/Meetings/Meeting.php
     */
    private function getMeetingsExternalApiDropDown(array $fieldDef, SugarBean $focus): array
    {
        // TODO: I did not test it on the settings page of the dashboard
        unset($fieldDef['function']);
        return $fieldDef;
    }

    /**
     * The conversion was performed in the process, the attribute is not relevant
     * @see modules/EAPM/EAPM.php
     */
    private function getEAPMExternalApiDropDown(array $fieldDef, SugarBean $focus): array
    {
        unset($fieldDef['function']);
        return $fieldDef;
    }

    /**
     * @see modules/AOP_Case_Updates/Case_Updates.php
     */
    private function display_case_attachments(array $fieldDef, SugarBean $focus): array
    {
        $fieldDef['value'] = array();
        $notes = $focus->get_linked_beans('notes', 'Notes');
        if ($notes) {
            foreach ($notes as $note) {
                $fieldDef['value'][$note->id] = $note->filename;
            }
        }

        return $fieldDef;
    }


    /**
     * @see modules/Surveys/Utils/utils.php
     */
    private function survey_url_display(array $fieldDef, SugarBean $focus): array
    {
        unset($fieldDef['function']);
        $fieldDef['type'] = 'url';
        if ($focus->status != 'Public') {
            $fieldDef['value'] = '';
        } else {
            global $sugar_config;
            $fieldDef['value'] = $sugar_config['site_url'] . "/index.php?entryPoint=survey&id=" . $focus->id;
        }

        return $fieldDef;
    }

    /**
     * @see modules/Surveys/Lines/Lines.php
     */
    private function survey_questions_display(array $fieldDef, SugarBean $focus): array
    {
        unset($fieldDef['value']);
        $questions = array();
        if (!empty($focus->id)) {
            $questionBeans = $focus->get_linked_beans('surveys_surveyquestions', 'SurveyQuestions', 'sort_order');
            if (!empty($questionBeans)) {
                foreach ($questionBeans as $questionBean) {
                    $question = array();
                    $question['id'] = $questionBean->id;
                    $question['name'] = $questionBean->name;
                    $question['type'] = $questionBean->type;
                    $question['sort_order'] = $questionBean->sort_order;
                    $question['options'] = array();
                    $optionBeans = $questionBean->get_linked_beans('surveyquestions_surveyquestionoptions', 'SurveyQuestionOptions', 'sort_order');
                    if (!empty($optionBeans)) {
                        foreach ($optionBeans as $option) {
                            $optionArr = array();
                            $optionArr['id'] = $option->id;
                            $optionArr['name'] = $option->name;
                            $question['options'][] = $optionArr;
                        }
                    }
                    $questions[] = $question;
                }
            }
        }
        $fieldDef['value'] = $questions;

        return $fieldDef;
    }

    /**
     * Module Emails. TODO: find out where this field is used
     * @see modules/Emails/include/displayIndicatorField.php
     */
    private function displayIndicatorField(array $fieldDef, SugarBean $focus): array
    {
        // In the original, it was assumed that $focus could be an array
        $fieldDef['value'] = array();

        $fieldDef['value']['newEmail'] = false;
        if ($focus->status == 'unread') {
            $fieldDef['value']['newEmail'] = true;
        }

        $fieldDef['value']['isImported'] = false;
        if ($focus->is_imported == true && $focus->inbound_email_record == $_REQUEST['inbound_email_record']) {
            $fieldDef['value']['isImported'] = true;
        }

        $fieldDef['value']['flagged'] = false;
        if ($focus->flagged == 1) {
            $fieldDef['value']['flagged'] = true;
        }

        return $fieldDef;
    }

    /**
     * Module Emails. TODO: find out where this field is used
     * @see modules/Emails/include/displaySubjectField.php
     */
    private function displaySubjectField(array $fieldDef, SugarBean $focus): array
    {
        // In the original, it was assumed that $focus could be an array
        $fieldDef['value'] = array();
        $fieldDef['options']['id'] = $focus->id;
        $fieldDef['options']['name'] = $focus->name;
        $fieldDef['options']['status'] = $focus->status;
        $fieldDef['options']['folder'] = $focus->folder;
        $fieldDef['options']['folder_type'] = $focus->folder_type;
        $fieldDef['options']['inbound_email_record'] = $focus->inbound_email_record;
        $fieldDef['options']['uid'] = $focus->uid;
        $fieldDef['options']['msgno'] = $focus->msgno;

        return $fieldDef;
    }

    /**
     * Module Emails. DetailView only
     * @see modules/Emails/include/displayAttachmentField.php
     */
    private function displayAttachmentField(array $fieldDef, SugarBean $focus): array
    {
        // In the original, it was assumed that $focus could be an array
        global $db;
        $attachments = array();

        if (!empty($focus->inbound_email_record && empty($focus->id))) {
            $inboundEmail = BeanFactory::getBean('InboundEmail', $db->quote($focus->inbound_email_record));
            $structure = $inboundEmail->getImap()->fetchStructure($focus->uid, FT_UID);

            if ($inboundEmail->messageStructureHasAttachment($structure)) {
                foreach ($structure->parts as $part) {
                    if (is_string($part->dparameters[0]->value)) {
                        $attachments[] = $part->dparameters[0]->value;
                    }
                }
            }
        }

        $fieldDef['value'] = $attachments;

        return $fieldDef;
    }

    /**
     * @see modules/Emails/include/displayHasAttachmentField.php
     */
    private function displayHasAttachmentField(array $fieldDef, SugarBean $focus): array
    {
        $fieldDef['value'] = false;
        if (!empty($focus->id)) {
            $focus->load_relationship('notes');
            $attachmentIds = $focus->notes->get();
            $fieldDef['value'] = (count($attachmentIds) > 0);
        }

        return $fieldDef;
    }

    /**
     * @see modules/Emails/include/displayEmailAddressOptInField.php
     */
    private function displayEmailAddressOptInField(array $fieldDef, SugarBean $focus): array
    {
        $fieldDef['value'] = '';
        if (!empty($focus->id)) {

            $addressField = 'from_name';
            if (empty($focus->from_name)) {
                $addressField = 'from_addr';
            }

            $emailAddress = $focus->getEmailAddressFromEmailField($addressField);
            if ($emailAddress instanceof SugarEmailAddress) {
                global $sugar_config;
                if (isset($sugar_config['email_enable_confirm_opt_in'])) {
                    $optInStatus = $emailAddress->getOptInStatus();
                    $fieldDef['value'] = $optInStatus;
                }
            }
        }

        return $fieldDef;
    }

    /**
     * @see include/SugarEmailAddress/getEmailAddressWidget.php
     */
    private function getEmailAddressWidget(array $fieldDef, SugarBean $focus): array
    {
        $module = $focus->module_dir;
        if ($_REQUEST['action'] === 'ConvertLead' && $module === "Contacts") {
            $module = "Leads";
        }

        $sea = new SugarEmailAddress();
        if (!($sea->smarty instanceof Sugar_Smarty)) {
            $sea->smarty = new Sugar_Smarty();
        }

        $module = $sea->getCorrectedModule($module);
        $id = $focus->id;

        if (isset($_POST['is_converted']) && $_POST['is_converted'] == true) {
            if (!isset($_POST['return_id'])) {
                $id = null;
            } else {
                $id = $_POST['return_id'];
            }
            if (!isset($_POST['return_module'])) {
                $module = '';
            } else {
                $module = $_POST['return_module'];
            }
        }

        $prefillDataArr = array();
        if (!empty($id)) {
            $prefillDataArr = $sea->getAddressesByGUID($id, $module);
            //When coming from convert leads, sometimes module is Contacts while the id is for a lead.
            if (empty($prefillDataArr) && $module == "Contacts") {
                $prefillDataArr = $sea->getAddressesByGUID($id, "Leads");
            }
            elseif (isset($_REQUEST['full_form']) && !empty($_REQUEST['emailAddressWidget'])) {
                $widget_id = isset($_REQUEST[$module . '_email_widget_id']) ? $_REQUEST[$module . '_email_widget_id'] : '0';
                $count = 0;
                $key = $module . $widget_id . 'emailAddress' . $count;
                while (isset($_REQUEST[$key])) {
                    $email = $_REQUEST[$key];
                    $prefillDataArr[] = array(
                        'email_address' => $email,
                        'primary_address' => isset($_REQUEST['emailAddressPrimaryFlag']) && $_REQUEST['emailAddressPrimaryFlag'] == $key,
                        'invalid_email' => isset($_REQUEST['emailAddressInvalidFlag']) && in_array(
                                $key,
                                $_REQUEST['emailAddressInvalidFlag']
                            ),
                        'opt_out' => isset($_REQUEST['emailAddressOptOutFlag']) && in_array(
                                $key,
                                $_REQUEST['emailAddressOptOutFlag']
                            ),
                        'reply_to_address' => false
                    );
                    $key = $module . $widget_id . 'emailAddress' . ++$count;
                } //while
            }
        }

        $fieldDef['value'] = $prefillDataArr;

        return $fieldDef;
    }

    /**
     * @see modules/SurveyResponses/Lines/Lines.php
     */
    private function question_responses_display(array $fieldDef, SurveyResponses $focus): array
    {
        $questionResponseBeans = $focus->get_linked_beans('surveyresponses_surveyquestionresponses', 'SurveyQuestionResponses');
        foreach ($questionResponseBeans as $questionResponseBean) {
            if (empty($questionResponseMap[$questionResponseBean->surveyquestion_id])) {
                $questionResponseMap[$questionResponseBean->surveyquestion_id] = array();
            }
            $questionResponseMap[$questionResponseBean->surveyquestion_id][] = $questionResponseBean;
        }

        $questionResponses = array();
        foreach ($questionResponseMap as $questionId => $questionResponseArr) {
            $data = array();
            $question = BeanFactory::getBean('SurveyQuestions', $questionId);
            $data['sort_order'] = $question->sort_order;
            $data['questionName'] = $question->name;
            $data['answer'] = convertQuestionResponseForDisplay($questionResponseArr, $question->type);
            $questionResponses[] = $data;
        }
        usort(
            $questionResponses,
            function ($a, $b) {
                return $a['sort_order'] - $b['sort_order'];
            }
        );

        $fieldDef['value'] = $questionResponses;

        return $fieldDef;
    }

    /**
     * @see modules/OutboundEmailAccounts/OutboundEmailAccounts.php
     */
    private function outboundEmailAccounts_getSendTestEmailBtn(array $fieldDef, SugarBean $focus): array
    {
        global $current_user;
        $admin = BeanFactory::newBean('Administration');
        $admin->retrieveSettings();
        $adminNotifyFromAddress = $admin->settings['notify_fromaddress'];
        isValidEmailAddress($adminNotifyFromAddress);
        $adminNotifyFromName = $admin->settings['notify_fromname'];

        $fieldDef['value'] = array(
            'currentUserEmail' => $current_user->email1,
            'adminNotifyFromAddress' => $adminNotifyFromAddress,
            'adminNotifyFromName' => $adminNotifyFromName,
        );

        return $fieldDef;
    }

    /**
     * The conversion was performed in the process, the attribute is not relevant
     * @see modules/Bugs/Bug.php
     */
    private function getReleaseDropDown(array $fieldDef, SugarBean $focus): array
    {
        unset($fieldDef['function']);
        return $fieldDef;
    }

    /**
     * The conversion was performed in the process, the attribute is not relevant
     * @see modules/Schedulers/Scheduler.php
     */
    private function getJobsList(array $fieldDef, SugarBean $focus): array
    {
        unset($fieldDef['function']);
        return $fieldDef;
    }

    /**
     * @see modules/Reminders/Reminder.php
     */
    private function reminder_getRemindersListView(array $fieldDef, SugarBean $focus): array
    {
        global $app_list_strings;
        $fieldDef['value'] = array(
            'reminder_time_options' => $app_list_strings['reminder_time_options'],
            'remindersData' => array(),
            'remindersDefaultValuesData' => Reminder::loadRemindersDefaultValuesData(),
        );

        if ($focus->id) {
            $fieldDef['value']['remindersData'] = Reminder::loadRemindersData($focus->module_name, $focus->id);
        }

        return $fieldDef;
    }

    /**
     * Configuration field of the Spots module.
     * All data is pulled up via ajax on http://localhost/index.php?module=Spots&action=getLeadsSpotsData&to_pdf=1
     * @see modules/Spots/ShowSpots.php
     */
    private function displaySpots(array $fieldDef, SugarBean $focus): array
    {
        $fieldDef['value'] = '';
        return $fieldDef;
    }

    /**
     * The "Add attachment" field of the Cases module. EditView only. There is no data, because the field only adds, not edits.
     * @see modules/AOP_Case_Updates/Case_Updates.php
     */
    private function display_update_form(array $fieldDef, SugarBean $focus): array
    {
        $fieldDef['value'] = '';
        return $fieldDef;
    }

    /**
     * @see modules/OutboundEmailAccounts/OutboundEmailAccounts.php
     */
    private function outboundEmailAccounts_getEmailProviderChooser(array $fieldDef, SugarBean $focus): array
    {
        $fieldDef['value'] = $focus->mail_smtptype;

        return $fieldDef;
    }

    /**
     * @see modules/AOS_Products_Quotes/Line_Items.php
     */
    private function display_shipping_vat(array $fieldDef, SugarBean $focus): array
    {
        global $app_list_strings;
        $fieldDef['options'] = $app_list_strings['vat_list'];
        $fieldDef['value'] = $focus->shipping_tax;

        return $fieldDef;
    }

    /**
     * @see Documents/views/view.edit.php::display()
     */
    private function related_doc_rev_number(array $fieldDef, Document $focus): array
    {
        unset($fieldDef['function']);
        $fieldDef['type'] = 'enum';
        $fieldDef['options'] = array();
        if (!empty($focus->related_doc_id)) {
            $fieldDef['options'] = DocumentRevision::get_document_revisions($focus->related_doc_id);
            $fieldDef['value'] = $focus->related_doc_rev_id;
        }

        return $fieldDef;
    }
}
