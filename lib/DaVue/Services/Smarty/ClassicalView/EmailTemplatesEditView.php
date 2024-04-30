<?php

namespace SuiteCRM\DaVue\Services\Smarty\ClassicalView;

use BeanFactory;
use SuiteCRM\DaVue\Services\Smarty\ClassicalView\Utils\EmailTemplatesUtils;

class EmailTemplatesEditView implements ClassicalViewInterface
{
    /** @var EmailTemplatesUtils  */
    private  $emailTemplatesUtils;

    public function __construct(EmailTemplatesUtils $emailTemplatesUtils)
    {
        $this->emailTemplatesUtils = $emailTemplatesUtils;
    }

    /**
     * @see modules/EmailTemplates/EditView.php
     * @return array
     */
    public function handler(): array
    {
        global $current_view, $app_list_strings;

        $params = (array)$current_view;

        // ViewData
        $metadata = $this->emailTemplatesUtils->getEmailTemplatesMetadata('editview');
        if (isset($metadata['EditView']['templateMeta']['tabDefs'])) {
            $params['tabDefs'] = $metadata['EditView']['templateMeta']['tabDefs'];
        } else {
            $params['tabDefs'] = array('DEFAULT' => array());
        }
        $params['sectionPanels'] = array();
        foreach ($params['tabDefs'] as $tab => $def) {
            foreach ($metadata["EditView"]["panels"][strtolower($tab)] as $panel => $row) {
                foreach ($row as $field) {
                    if (is_array($field)) {
                        $params['sectionPanels'][$tab][$panel][]['field'] = $field;
                    } elseif (!empty($field)) {
                        $params['sectionPanels'][$tab][$panel][]['field'] = array('name' => $field);
                    }
                }
            }
        }

        // BeanData
        $focus = $current_view->bean;

        if (isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
            $focus->id = "";
            $params['isDuplicate'] = true;
        }

        foreach ($focus->field_defs as $fielName => $fieldDef) {
            $params['fields'][$fielName] = $fieldDef;
            $params['fields'][$fielName]['value'] = $focus->$fielName;
            if ($fieldDef['options']) {
                $params['fields'][$fielName]['options'] = $app_list_strings[$fieldDef['options']];
            }
        }

        // EmailTemplates - type
        if ($focus->type === 'workflow') {
            $params['fields']['type']['options'] = $app_list_strings['emailTemplates_type_list'];
        } else {
            $params['fields']['type']['options'] = $app_list_strings['emailTemplates_type_list_no_workflow'];
        }

        // PageData
        $params['view'] = $params['action'];
        $params['id'] = $focus->id;
        $params['offset'] = $_REQUEST['offset'];
        $params['returnModule'] = $_REQUEST['return_module'];
        $params['returnAction'] = 'index';
        $params['pagination'] = json_decode($params['PAGINATION'], true);


        $actionButtons = array(
            0 => 'SAVE',
            1 => 'CANCEL'
        );
        $params['built_in_buttons'] = $actionButtons;
        $params['actionButtons'] = $actionButtons;

        $result = $this->emailTemplatesUtils->emailTemplatesResponseArray($params);

        // Set body_html as custom widget
        $result["viewData"]["panelsFields"]["DEFAULT"]["body_html"]["field"]["type"] = 'EmailTemplates-EditView-body_html';
        $result["viewData"]["panelsFields"]["DEFAULT"]["body_html"]["field"]["label"] = 'LBL_BODY';

        // Add fields from template
        $result["viewData"]["panelsFields"]["DEFAULT"]["assigned_user_name"]["field"] = array(
            'name' => 'assigned_user_name',
            'label' => 'LBL_ASSIGNED_TO',
            'type' => 'relate',
        );

        // Add attachements
        $note = BeanFactory::newBean('Notes');
        $where = "notes.parent_id='{$focus->id}'";
        $notes_list = $note->get_full_list("notes.name", $where, true);

        if (!isset($notes_list)) {
            $notes_list = array();
        }

        $attachments = array();
        for ($i = 0; $i < count($notes_list); $i++) {
            $attachments[$notes_list[$i]->id] = $notes_list[$i]->name;
        }

        $result["viewData"]["panelsFields"]["DEFAULT"]["ATTACHMENTS_JAVASCRIPT"]["field"]["label"] = 'LBL_ATTACHMENTS';
        $result["beanData"]["ATTACHMENTS_JAVASCRIPT"]["value"] = $attachments;

        // Add fields from template
        require_once("modules/EmailTemplates/templateFields.php");
        $has_campaign = true;
        if (!isset($_REQUEST['campaign_id']) || empty($_REQUEST['campaign_id'])) {
            $has_campaign = false;
        }


        $fieldDefs = substr(str_replace("var field_defs = ", "", generateFieldDefsJS2()), 0, -1);

        if ($has_campaign) {
            $defaultModule = 'Contacts';
        } else {
            $defaultModule = 'Accounts';
        }

        $result["viewData"]["panelsFields"]["DEFAULT"]["variable_module"]["field"] = array(
            'name' => 'variable_module',
            'label' => 'LBL_INSERT_VARIABLE',
            'type' => 'EmailTemplates-EditView-variable_module',
            'fieldDefs' => $fieldDefs,
            'options' => $this->emailTemplatesUtils->genDropDownValues($has_campaign),
            'defaultModule' => $defaultModule,
            'value' => '',
        );

        $result["beanData"]["variable_module"] = array();

        $orderedFields = array();
        foreach ($result["viewData"]["panelsFields"]["DEFAULT"] as $key => $value) {
            if ($key == 'body_html') {
                $orderedFields['assigned_user_name'] = $result["viewData"]["panelsFields"]["DEFAULT"]["assigned_user_name"];
                $orderedFields['variable_module'] = $result["viewData"]["panelsFields"]["DEFAULT"]["variable_module"];
            }
            $orderedFields[$key] = $value;
        }
        $result["viewData"]["panelsFields"]["DEFAULT"] = $orderedFields;

        // Remove artifacts
        unset($result["viewData"]["panelsFields"]["DEFAULT"]["tracker_url"]);

        return $result;

    }
}
