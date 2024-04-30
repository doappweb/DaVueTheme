<?php

namespace SuiteCRM\DaVue\Services\Smarty\ClassicalView\Utils;

use SuiteCRM\DaVue\Services\Common\Utils;

class EmailTemplatesUtils
{
    /** @var Utils $utils */
    private $utils;

    public function __construct(Utils $utils)
    {
        $this->utils = $utils;
    }

    public function getEmailTemplatesMetadata($view)
    {
        $result = null;

        if (file_exists('custom/modules/EmailTemplates/metadata/' . $view . 'defs.php')) {
            $file = 'custom/modules/EmailTemplates/metadata/' . $view . 'defs.php';
        } elseif (file_exists('modules/EmailTemplates/metadata/' . $view . 'defs.php')) {
            $file = 'modules/EmailTemplates/metadata/' . $view . 'defs.php';
        } else {
            $file = false;
        }

        if ($file) {
            include_once($file);
            if (!empty($viewdefs)) {
                $result = $viewdefs['EmailTemplates'];
            }
        }

        return $result;
    }

    /**
     * @param array $params
     * @return array
     */
    public function emailTemplatesResponseArray(array $params): array
    {
        return array(
            'pageData' => array(
                'recordName' => $this->utils->getRecordName(array('moduleName' => $params['module'], 'recordId' => $_REQUEST['record'])),
                'showVCRControl' => $params['panelCount'] == 0 && $params['SHOW_VCR_CONTROL'],
                'pagination' => $params['pagination'],
                'actionButtons' => $params['actionButtons'],
                'useTabs' => (bool)$params['useTabs'],  // <------- maybe not be used
                // When the action menu is enabled and when there are only panels and no tabs, then the first panel needs to be made a tab so that the action menu looks right.
                'firstPanelAsTab' => $params['config']['enable_action_menu'] && $params['useTabs'],  // <------- maybe not be used
                'isActionsLikeDropDown' => (bool)$params['config']['enable_action_menu'],
                'id' => $params['id'],
                'offset' => $params['offset'],
                'returnModule' => $params['returnModule'],
                'returnAction' => $params['returnAction'],
                'returnId' => $params['returnId'],
                'isDuplicate' => $params['isDuplicate'],
                'module' => $params['module'],
                'showDetailData' => $params['showDetailData'],
                'showSectionPanelsTitles' => $params['showSectionPanelsTitles'], // <------- maybe not be used
                'view' => $params['action'],
                'built_in_buttons' => $params['built_in_buttons'],
                'bean' => array(
                    'objectName' => $params['bean']->object_name,
                    'moduleDir' => $params['bean']->module_name,
                    'moduleName' => $params['bean']->module_dir
                ),
            ),
            'viewData' => array(
                'panelsFields' => $this->utils->resortSectionPanels($params['sectionPanels'], $params['view'], $params['module']),
                'panelsMetadata' => $params['tabDefs'],
            ),
            // Field vardefs. If there is a filled value, it will be in the 'value' attribute.
            'beanData' => $params['fields'],
        );
    }

    public function genDropDownValues($has_campaign): array
    {
        global $app_list_strings, $beanList, $beanFiles;

        $lblContactAndOthers = implode('/', array(
            isset($app_list_strings['moduleListSingular']['Contacts']) ? $app_list_strings['moduleListSingular']['Contacts'] : 'Contact',
            isset($app_list_strings['moduleListSingular']['Leads']) ? $app_list_strings['moduleListSingular']['Leads'] : 'Lead',
            isset($app_list_strings['moduleListSingular']['Prospects']) ? $app_list_strings['moduleListSingular']['Prospects'] : 'Target',
        ));

        $dropdown = array();

        if ($has_campaign) {
            $dropdown = array(
                'Contacts' => $lblContactAndOthers,
            );
        } else {
            array_multisort($app_list_strings['moduleList'], SORT_ASC, $app_list_strings['moduleList']);

            foreach ($app_list_strings['moduleList'] as $key => $name) {
                if (isset($beanList[$key]) && isset($beanFiles[$beanList[$key]]) && !str_begin($key, 'AOW_') && !str_begin($key, 'zr2_')) {
                    if ($key == 'Contacts') {
                        $dropdown[$key] = $lblContactAndOthers;
                    } else {
                        if (isset($app_list_strings['moduleListSingular'][$key])) {
                            $dropdown[$key] = $app_list_strings['moduleListSingular'][$key];
                        } else {
                            $dropdown[$key] = $app_list_strings['moduleList'][$key];
                        }
                    }
                }
            }
        }

        return $dropdown;
    }
}
