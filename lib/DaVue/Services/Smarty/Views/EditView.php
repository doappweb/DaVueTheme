<?php

namespace SuiteCRM\DaVue\Services\Smarty\Views;

use SuiteCRM\DaVue\Domain\Modules\AOR_ReportHandler;
use SuiteCRM\DaVue\Domain\Modules\PDF_TemplateHandler;
use SuiteCRM\DaVue\Domain\Modules\UsersHandler;
use SuiteCRM\DaVue\Domain\Modules\VCalsHandler;
use SuiteCRM\DaVue\Services\Common\FieldFunctions;
use SuiteCRM\DaVue\Services\Common\Utils;

class EditView implements ViewHandlerInterface
{
    /** @var AOR_ReportHandler */
    private $aorReportHandler;

    /** @var FieldFunctions $fieldFunctions */
    private $fieldFunctions;

    /** @var PDF_TemplateHandler */
    private $pdfTemplateHandler;

    /** @var VCalsHandler */
    private $vCalsHandler;

    /** @var UsersHandler */
    private $usersHandler;

    /** @var Utils $utils */
    private $utils;

    private $actionButtons = [];
    private $result = [];
    private $params;
    private $pagination = [];
    private $tabDefs = [];

    public function __construct(
        AOR_ReportHandler $aorReportHandler,
        FieldFunctions $fieldFunctions,
        PDF_TemplateHandler $pdfTemplateHandler,
        VCalsHandler $vCalsHandler,
        UsersHandler $usersHandler,
        Utils $utils
    ) {
        $this->aorReportHandler = $aorReportHandler;
        $this->fieldFunctions = $fieldFunctions;
        $this->pdfTemplateHandler = $pdfTemplateHandler;
        $this->vCalsHandler = $vCalsHandler;
        $this->usersHandler = $usersHandler;
        $this->utils = $utils;

    }

    public function handle($params): array
    {
        if (!is_array($params)){
            return $this->result;
        }

        $this->params = $params;
        $this->preHandler();
        $this->generate();
        $this->postHandler();

        return $this->result;
    }

    private function preHandler()
    {
        // In some models, these parameters are not specified in the metadata files,
        // and the display logic is written in the template files in case they are missing
        if (false !== $this->params['tabDefs']) {
            $this->tabDefs = $this->params['tabDefs'];
        } else {
            foreach ($this->params['sectionPanels'] as $panelName => $panelParams) {
                $this->tabDefs[$panelName] = array(
                    'newTab' => false,
                    'panelDefault' => 'expanded',
                );
            }
        }

        $this->pagination = json_decode($this->params['PAGINATION'], true);

        // @see include/EditView/actions_buttons.tpl
        if (!empty($this->params['form']['buttons'])) {

            foreach ($this->params['form']['buttons'] as $button) {
                if (is_string($button)) {
                    $this->actionButtons[] = $button;
                }
                // Processing buttons that are make via custom Code in metadata files
                if (isset($button['customCode'])) {
                    $buttonId = $this->utils->parseHtmlAttribute($button['customCode'], 'id');
                    if (null !== $buttonId) {
                        $buttonId = 'ID_' . strtoupper(str_replace(array('{', '}', '$'), '', $buttonId));
                        $this->actionButtons[] = array(
                            'name' => $buttonId,
                            'originalCustomCode' => $button['customCode'],
                        );
                    } else {
                        // The only place in the box where the customCode button didn't id - Tasks module
                        $buttonTitle = $this->utils->parseHtmlAttribute($button['customCode'], 'title');
                        if (null !== $buttonTitle && '{$APP.LBL_CLOSE_AND_CREATE_BUTTON_TITLE}' === $buttonTitle) {
                            $this->actionButtons[] = array(
                                'name' => 'ID_CLOSE_AND_CREATE_BUTTON',
                                'originalCustomCode' => $button['customCode'],
                            );
                        }

                        $GLOBALS['log']->fatal(__METHOD__ . ', ' . __LINE__ . ": The button with the customCode could not be processed - there is no id attribute. Module: {$_REQUEST['module']}, record: {$_REQUEST['record']}");
                    }
                }
            }
        } elseif (!empty($this->params['ACTION_BUTTON_FOOTER'])) {
            // Users module
            foreach ($this->params['ACTION_BUTTON_FOOTER'] as $button) {
                $id = $this->utils->parseHtmlAttribute($button, 'id');
                if (null !== $id) {
                    $this->actionButtons[] = $id;
                }
            }
        } else {
            $this->actionButtons = array('SAVE', 'CANCEL');
        }
        if ($this->params['SHOW_VCR_CONTROL'] && !empty($this->pagination['nextLink'])) {
            $this->actionButtons[] = 'SAVE_AND_CONTINUE';
        }
        if (true === $this->params['isAuditEnabled'] && empty($this->params['form']['hideAudit'])) {
            $this->actionButtons[] = 'AUDIT';
        }

        // Different logic for fields with the "function" attribute
        foreach ($this->params['fields'] as $field => $fieldDef) {

            // Processing fields that do not have the "function" attribute, but have a customCode in the metadata,
            // which makes it make sense to process them exactly the same as if they had the "function" attribute
            // (i.e. a separate widget will be created for them).
            if ('related_doc_rev_number' === $fieldDef['name'] && 'Documents' === $this->params['module']) {
                $this->params['fields'][$field]['function'] = 'related_doc_rev_number';
            }

            if (isset($fieldDef['function'])) {
                $this->params['fields'][$field] =  $this->fieldFunctions->implement($fieldDef, $this->params['bean']);
            }

            // For Cases
            if ('name' === $fieldDef['name'] && 'Cases' === $this->params['module']) {
                $this->params['fields'][$field]['type'] = 'Cases-EditView-name';
            }

            if ('suggestion_box' === $fieldDef['name'] && 'Cases' === $this->params['module']) {
                $this->params['fields'][$field]['type'] = 'Cases-EditView-suggestion_box';
            }
        }
    }

    private function generate()
    {
        $this->result = array(
            'pageData' => array(
                'recordName' => $this->utils->getRecordName(array('moduleName' => $this->params['module'], 'recordId' => $_REQUEST['record'])),
                // Do need to display the "Save and Continue" button and also display pagination
                'showVCRControl'            => (bool)$this->params['SHOW_VCR_CONTROL'],
                'pagination'                => $this->pagination,
                'actionButtons'             => $this->actionButtons,
                // (1/false) do need display at least one of the panels as a tab
                'useTabs'                   => (bool)$this->params['useTabs'], // <------- maybe not be used
                'id'                        => $this->params['id'],
                'offset'                    => $this->params['offset'],
                'returnModule'              => $this->params['returnModule'],
                'returnAction'              => $this->params['returnAction'],
                'returnId'                  => $this->params['returnId'],
                'isDuplicate'               => $this->params['isDuplicate'],
                'module'                    => $this->params['module'],
                'showDetailData'            => $this->params['showDetailData'],
                'showSectionPanelsTitles'   => $this->params['showSectionPanelsTitles'], // <------- maybe not be used
                'view'                      => $this->params['view'],
                'built_in_buttons'          => $this->params['built_in_buttons'],
                'bean' => array(
                    'objectName'    => $this->params['bean']->object_name,
                    'moduleDir'     => $this->params['bean']->module_name,
                    'moduleName'    => $this->params['bean']->module_dir
                ),
            ),
            'viewData' => array(
                'panelsFields' => $this->utils->resortSectionPanels($this->params['sectionPanels'], $this->params['view'], $this->params['module']),
                'panelsMetadata' => $this->tabDefs,
            ),
            // Field vardefs. If there is a filled value, it will be in the 'value' attribute.
            'beanData' => $this->params['fields'],
        );
    }

    private function postHandler()
    {
        global $current_user;

        // Some modules (Calls, Meetings) have a separate panel with a list of invitees.
        // The data for it, in the original, is written to js, parsed and rendered on it.
        // It simulates the receipt of this data
        if (isset($this->params['JSON_CONFIG_JAVASCRIPT'])) {
            $this->result['pageData']['reminderInvite'] = $this->vCalsHandler->getVCalFreeBusy($this->params['module'], $this->params['id']);
            $this->result['pageData']['statusesAcceptingCurrentInvitation'] = $this->vCalsHandler->getStatusesAcceptingCurrentInvitation($this->params['module'], $this->params['id']);
        }

        // In the box of the module, Users of the "Advanced" and "User Rights" tabs are formed not through metadata, as usual, but by hardcode in templates.
        // The code below simulates the vardefs and metadata for the fields of these tabs
        if ('Users' === $this->params['module']) {
            $customPanelsMetadata = $this->usersHandler->getEditCustomPanelsMetadata($this->params);
            foreach ($customPanelsMetadata as $panelName => $panelMetadata) {
                $this->result['viewData']['panelsMetadata'][$panelName] = $panelMetadata;
            }
            $customFieldsMetadata = $this->usersHandler->getEditCustomFieldsMetadata($this->params);
            foreach ($customFieldsMetadata as $panelName => $fieldMetadata) {
                $this->result['viewData']['panelsFields'][$panelName] = $fieldMetadata;
            }
            $customFieldsBeanData = $this->usersHandler->getEditCustomVardefs($this->params);
            foreach ($customFieldsBeanData as $fieldName => $fieldDef) {
                $this->result['beanData'][$fieldName] = $fieldDef;
            }

            // UserType field
            $this->result['beanData']['UserType']['value'] = $this->params['bean']->user_type;
            $this->result['beanData']['UserType']['options'] = $this->usersHandler->getUserTypeFieldOptions($this->params);
            if (!is_admin($current_user)) {
                $this->result['beanData']['UserType']['readonly'] = true;
            }
            unset($this->result['viewData']['panelsFields']['LBL_USER_INFORMATION']['UserType']['field']['customCode']);
            unset($this->result['viewData']['panelsFields']['LBL_USER_INFORMATION']['UserType']['field']['type']);
        }

        // Additional data for the AOR_Reports module, which are included in the box in the view file.
        // The methods of the box immediately generated html, so they had to be rewritten
        if ('AOR_Reports' === $this->params['module']) {
            $this->result['pageData']['fieldLines'] = $this->aorReportHandler->aorReportsGetFieldLines($this->params['bean']);
            $this->result['pageData']['conditionLines'] = $this->aorReportHandler->aorReportsGetConditionLines($this->params['bean']);
            $this->result['pageData']['chartLines'] = $this->aorReportHandler->aorReportsGetChartLines($this->params['bean']);
        }

        if('AOS_PDF_Templates' === $this->params['module']){
            $insertFieldOptions = $this->pdfTemplateHandler->getInsertFieldOptions();
            $this->result['viewData']['panelsFields']['DEFAULT']['insert_fields']['moduleOptions'] = $insertFieldOptions['moduleOptions'];
            $this->result['viewData']['panelsFields']['DEFAULT']['insert_fields']['regularOptions'] = $insertFieldOptions['regularOptions'];

            $sampleFieldOptions = $this->pdfTemplateHandler->getSampleFieldOptions();
            $this->result['viewData']['panelsFields']['DEFAULT']['sample']['options'] = $sampleFieldOptions['options'];
            $this->result['viewData']['panelsFields']['DEFAULT']['sample']['sampleData'] = $sampleFieldOptions['sampleData'];
        }
    }
}
