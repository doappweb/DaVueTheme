<?php

namespace SuiteCRM\DaVue\Services\Api;
use Exception;
use SubpanelQuickCreate;
use SuiteCRM\DaVue\Domain\QuickCreate\CalendarQuickCreate;
use SuiteCRM\DaVue\Services\Common\FieldFunctions;
use SuiteCRM\DaVue\Services\Common\Utils;

class QuickCreateViewService
{
    /** @var FieldFunctions $fieldFunctions */
    private $fieldFunctions;

    /** @var Utils $utils */
    private $utils;

    public function __construct(FieldFunctions $fieldFunctions, Utils $utils)
    {
        $this->fieldFunctions = $fieldFunctions;
        $this->utils = $utils;
    }

    /**
     * @usage http://localhost/index.php?module=Home&VueAjax=1&method=quickCreateView&arg[]
     * @param $args
     * @return array
     * @throws Exception
     */
    public function quickCreateView($args): array
    {
        if (!isset($args['targetModule'])) {
            throw new Exception("'targetModule' argument was not defined");
        }

        $targetModule = $args['targetModule'];


        // 394-SFR
        if (isset($args['targetId'])) {
            $targetId = $args['targetId'];
        } else {
            $targetId = null;
        }

        global $disable_date_format, $timedate, $current_user;

        ob_start();
        if ($targetModule === 'FP_events') {
            $sqc = new CalendarQuickCreate($targetModule, 'QuickCreate');
        } else {
            $sqc = new SubpanelQuickCreate($targetModule, 'QuickCreate');
        }
        ob_end_clean();

        // Converting date field values to the user's settings format
        $disable_date_format = null;  // This flag is enabled by default and it does not allow getting date and time formats from the user settings

        $tabDefs = array();
        foreach (array_keys($sqc->ev->sectionPanels) as $panelName) {
            $tabDefs[$panelName] = array(
                'newTab' => false,
                'panelDefault' => 'expanded',
            );
        }

        // Different logic for fields with a "function" attribute
        foreach ($sqc->ev->fieldDefs as $def => $fieldDef) {

            if ('related_doc_rev_number' === $fieldDef['name'] && 'Documents' === $sqc->ev->module) {
                $fieldDef['function'] = 'related_doc_rev_number';
            }

            if (isset($fieldDef['function'])) {
                $sqc->ev->fieldDefs[$def] = $this->fieldFunctions->implement($fieldDef, $sqc->ev->focus);
            }
        }

        $result = array(
            'pageData' => array(
                'recordName' => null,

                // indicates whether to display the "Save and Continue" button, as well as display pagination
                'showVCRControl' => false,

                'pagination' => null,
                'actionButtons' => null,

                // (1/false) indicates whether at least one of the panels should be displayed as a tab
                'useTabs' => false, // <------- could be unnecessary
                'id' => $targetId,
                'offset' => 0,
                'returnModule' => null,
                'returnAction' => null,
                'returnId' => null,
                'isDuplicate' => false,
                'module' => $targetModule,
                'showDetailData' => null,
                'showSectionPanelsTitles' => null, // <------- could be unnecessary
                'view' => 'quickcreate',
                'built_in_buttons' => null,
                'bean' => array(
                    'objectName' => $sqc->ev->focus->object_name,
                    'moduleDir' => $sqc->ev->focus->module_dir,
                    'moduleName' => $sqc->ev->focus->module_name
                ),
            ),
            'viewData' => array(
                'panelsFields' => $this->utils->resortSectionPanels($sqc->ev->sectionPanels, 'quickCreate', 'Cases'),

                // Panel metadata: panel display mode (panel or tab) and default value (collapsed/expanded)
                'panelsMetadata' => $tabDefs,
            ),
            'beanData' => $sqc->ev->fieldDefs,
        );

        return $result;
    }
}
