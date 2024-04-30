<?php

namespace SuiteCRM\DaVue\Services\Smarty\Views;

use AOR_Report;
use SuiteCRM\DaVue\Domain\Modules\AOR_ReportHandler;
use SuiteCRM\DaVue\Domain\Modules\UsersHandler;
use SuiteCRM\DaVue\Services\Common\FieldFunctions;
use SuiteCRM\DaVue\Services\Common\Utils;

class DetailView implements ViewHandlerInterface
{
    /** @var AOR_ReportHandler */
    private $aorReportHandler;

    /** @var FieldFunctions $fieldFunctions */
    private $fieldFunctions;

    /** @var UsersHandler */
    private $usersHandler;

    /** @var Utils $utils */
    private $utils;

    private $params;
    private $result = [];
    private $actionButtons = [];


    public function __construct(
        AOR_ReportHandler $aorReportHandler,
        FieldFunctions $fieldFunctions,
        UsersHandler $usersHandler,
        Utils $utils
    ) {
        $this->aorReportHandler = $aorReportHandler;
        $this->fieldFunctions = $fieldFunctions;
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

    private function preHandler(): void
    {
        // In the box, the action menu buttons were assembled in templates. This behavior is simulated here.
        // The template themes/SuiteP/include/DetailView/actions_menu.tpl is used as a basis
        if (!isset($this->params['form']['buttons'])) {
            $this->actionButtons = array('EDIT', 'DUPLICATE', 'DELETE');
        } else {
            $numStrButtons = 0;
            // string items
            foreach ($this->params['form']['buttons'] as $buttonName) {
                // built_in_buttons = array('CANCEL','DELETE','DUPLICATE','EDIT','FIND_DUPLICATES','SAVE','CONNECTOR')
                if (!is_array($buttonName) && in_array($buttonName, $this->params['built_in_buttons'])) {
                    $numStrButtons++;
                    $this->actionButtons[] = $buttonName;
                }
            }
            // array items
            if (count($this->params['form']['buttons']) > $numStrButtons) {
                foreach ($this->params['form']['buttons'] as $buttonName => $button) {
                    if (isset($button['sugar_html']['htmlOptions']['id'])) {

                        // Cases specific to the Employees module
                        if ('Employees' === $this->params['module']) {
                            if ('edit_button' === $button['sugar_html']['htmlOptions']['id']) {
                                if (isset($this->params['DISPLAY_EDIT']) && $this->params['DISPLAY_EDIT']) {
                                    $buttonName = 'EDIT';
                                } else {
                                    continue;
                                }
                            }
                            if ('duplicate_button' === $button['sugar_html']['htmlOptions']['id']) {
                                if (isset($this->params['DISPLAY_DUPLICATE']) && $this->params['DISPLAY_DUPLICATE']) {
                                    $buttonName = 'DUPLICATE';
                                } else {
                                    continue;
                                }
                            }
                            if ('delete_button' === $button['sugar_html']['htmlOptions']['id']) {
                                if (isset($this->params['DISPLAY_DELETE']) && $this->params['DISPLAY_DELETE']) {
                                    $buttonName = 'DELETE';
                                } else {
                                    continue;
                                }
                            }
                        } else {
                            $buttonName = strtoupper($button['sugar_html']['htmlOptions']['id']);
                        }
                    } elseif (is_numeric($buttonName) && isset($button['customCode'])) {
                        $value = $this->utils->parseHtmlAttribute($button['customCode'], 'value');

                        // If value is a string starting with '{$MOD.LBL_' and ending with '}', then we take what is between these substrings.
                        if (1 === preg_match('/^\{\$MOD\.LBL_([A-Z_0-9]*)\}$/', $value, $matches)) {
                            $buttonName = $matches[1];
                        } else {
                            $id = $this->utils->parseHtmlAttribute($button['customCode'], 'id');
                            if (null !== $id) {
                                $buttonName = $id;
                            } else {
                                // Cases specific to the Users module
                                if ('Users' === $this->params['module']) {
                                    $onclick = $this->utils->parseHtmlAttribute($button['customCode'], 'onclick');
                                    if (false !== strpos($onclick, '&reset_preferences=true')) {
                                        $buttonName = 'reset_user_preferences_footer';  // id for this button on editView
                                    } elseif (false !== strpos($onclick, '&reset_homepage=true')) {
                                        $buttonName = 'reset_homepage_footer';  // id for this button on editView
                                    }
                                }
                            }
                        }
                    }

                    if (is_array($button) && $button['customCode']) {
                        $this->actionButtons[] = array(
                            'name' => $buttonName,
                            'originalCustomCode' => $button['customCode'],
                        );
                    }
                }
            }
            // Audit button
            if (empty($this->params['form']['hideAudit'])) {
                if (
                    $this->params['bean']->aclAccess("detail")
                    && !empty($this->params['fields']['id']['value'])
                    && $this->params['isAuditEnabled']
                ) {
                    $this->actionButtons[] = 'AUDIT';
                }
            }
        }
        // Different logic for fields with the "function" attribute
        $this->params['fields'] = $this->fieldFunctions->implementsFromArray($this->params['fields'], $this->params['bean']);
    }

    private function generate(): void
    {
        $this->result = array(
            'pageData' => array(
                'recordName' => $this->utils->getRecordName(array('moduleName' => $this->params['module'], 'recordId' => $_REQUEST['record'])),
                'showVCRControl' => $this->params['panelCount'] == 0 && $this->params['SHOW_VCR_CONTROL'],
                'pagination' => json_decode($this->params['PAGINATION'], true),
                'useTabs' => (bool)$this->params['useTabs'],  // <------- maybe not be used
                // When the action menu is enabled and when there are only panels and no tabs, then the first panel needs to be made a tab so that the action menu looks right.
                'firstPanelAsTab' => $this->params['config']['enable_action_menu'] && $this->params['useTabs'],  // <------- maybe not be used
                'isActionsLikeDropDown' => (bool)$this->params['config']['enable_action_menu'],
                'actionButtons' => $this->actionButtons,
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
                'panelsMetadata' => $this->params['tabDefs'],
            ),
            // Field vardefs. If there is a filled value, it will be in the 'value' attribute.
            'beanData' => $this->params['fields'],
        );
    }

    private function postHandler(): void
    {
        if('DocumentRevisions' === $this->params['module'] && $this->params['fields']['document_name'] && $this->params['fields']['document_name']['value']){
            $this->result['beanData']['document_name']['value'] = strip_tags($this->result['beanData']['document_name']['value']);
            $this->result['beanData']['document_name']['type'] = 'relate';
            $this->result['beanData']['document_name']['id_name'] = 'document_id';
            $this->result['beanData']['document_name']['module'] = 'Documents';
        }

        // In the box of the module, Users of the "Advanced" and "User Rights" tabs are formed not through metadata, as usual, but by hardcode in templates.
        // The code below simulates the vardefs and metadata for the fields of these tabs
        if ('Users' === $this->params['module']) {
            $customPanelsMetadata = $this->usersHandler->getDetailCustomPanelsMetadata($this->params);

            foreach ($customPanelsMetadata as $panelName => $panelMetadata) {
                $this->result['viewData']['panelsMetadata'][$panelName] = $panelMetadata;
            }

            $customFieldsMetadata = $this->usersHandler->getDetailCustomFieldsMetadata($this->params);
            foreach ($customFieldsMetadata as $panelName => $fieldMetadata) {
                $this->result['viewData']['panelsFields'][$panelName] = $fieldMetadata;
            }

            $customFieldsBeanData = $this->usersHandler->getDetailCustomVardefs($this->params);
            foreach ($customFieldsBeanData as $fieldName => $fieldDef) {
                $this->result['beanData'][$fieldName] = $fieldDef;
            }

            // UserType field
            $this->result['beanData']['UserType']['value'] = $this->params['bean']->user_type;
            $this->result['beanData']['UserType']['options'] = $this->usersHandler->getUserTypeFieldOptions($this->params);
            unset($this->result['viewData']['panelsFields']['LBL_USER_INFORMATION']['UserType']['field']['customCode']);
            unset($this->result['viewData']['panelsFields']['LBL_USER_INFORMATION']['UserType']['field']['type']);
        }

        // Additional data for the AOR_Reports module, which are included in the box in the view file.
        // The methods of the box immediately generated html, so they had to be rewritten
        if ('AOR_Reports' === $this->params['module']) {
            $this->result['reportCharts'] = $this->aorReportHandler->aorReportsBuildReportChart($this->params['bean'], null, AOR_Report::CHART_TYPE_RGRAPH);
            $this->result['reportParams'] = $this->aorReportHandler->aorReportsGetConditionParams($this->params['bean']);
            $this->result['reportData'] = $this->aorReportHandler->aorReportsBuildGroupReport($this->params['bean'], 0);
        }
    }
}
