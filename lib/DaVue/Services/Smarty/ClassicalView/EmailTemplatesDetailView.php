<?php

namespace SuiteCRM\DaVue\Services\Smarty\ClassicalView;

use BeanFactory;
use SuiteCRM\DaVue\Services\Smarty\ClassicalView\Utils\EmailTemplatesUtils;

class EmailTemplatesDetailView implements ClassicalViewInterface
{
    /** @var EmailTemplatesUtils  */
    private  $emailTemplatesUtils;

    public function __construct(EmailTemplatesUtils $emailTemplatesUtils)
    {
        $this->emailTemplatesUtils = $emailTemplatesUtils;
    }

    /**
     * @see modules/EmailTemplates/DetailView.php
     * @return array
     */
    public function handler(): array
    {
        global $current_view, $app_list_strings;

        $params = (array)$current_view;

        // ViewData
        $metadata = $this->emailTemplatesUtils->getEmailTemplatesMetadata('detailview');
        if (isset($metadata['DetailView']['templateMeta']['tabDefs'])) {
            $params['tabDefs'] = $metadata['DetailView']['templateMeta']['tabDefs'];
        } else {
            $params['tabDefs'] = array('DEFAULT' => array());
        }
        $params['sectionPanels'] = array();
        foreach ($params['tabDefs'] as $tab => $def) {
            foreach ($metadata["DetailView"]["panels"][strtolower($tab)] as $panel => $row) {
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

        $actionButtons = array();

        if ($focus->ACLAccess('editview')) {
            $actionButtons[] = 'EDIT';
            $actionButtons[] = 'DUPLICATE';
        }

        if ($focus->ACLAccess('delete')) {
            $actionButtons[] = 'DELETE';
        }
        $params['built_in_buttons'] = $actionButtons;
        $params['actionButtons'] = $actionButtons;

        $result = $this->emailTemplatesUtils->emailTemplatesResponseArray($params);

        // Add fields from template
        $result["viewData"]["panelsFields"]["DEFAULT"]["assigned_user_name"]["field"] = array(
            'name' => 'assigned_user_name',
            'label' => 'LBL_ASSIGNED_TO',
            'type' => 'relate',
        );
        $result["viewData"]["panelsFields"]["DEFAULT"]["date_entered"]["field"] = array(
            'name' => 'date_entered',
            'created_by_name' => $focus->created_by_name,
            'label' => 'LBL_DATE_ENTERED',
            'type' => 'EmailTemplates-DetailView-date_entered',
        );
        $result["viewData"]["panelsFields"]["DEFAULT"]["date_modified"]["field"] = array(
            'name' => 'date_modified',
            'modified_by_name' => $focus->modified_by_name,
            'label' => 'LBL_DATE_MODIFIED',
            'type' => 'EmailTemplates-DetailView-date_modified',
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

        // Set body_html as custom widget
        $result["viewData"]["panelsFields"]["DEFAULT"]["body_html"]["field"]["type"] = 'EmailTemplates-DetailView-body_html';
        $result["viewData"]["panelsFields"]["DEFAULT"]["body_html"]["field"]["label"] = 'LBL_BODY';

        // Remove artifacts
        unset($result["viewData"]["panelsFields"]["DEFAULT"]["tracker_url"]);

        return $result;
    }
}
