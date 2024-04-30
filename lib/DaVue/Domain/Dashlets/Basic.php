<?php

namespace SuiteCRM\DaVue\Domain\Dashlets;

use Dashlet;

class Basic implements DashletHandlerInterface
{
    /**
     * Obtaining data to configure a basic dashlet of this type
     * @param Dashlet $focus
     * @return array
     */
    public function configure(Dashlet $focus): array
    {
        global $sugar_config, $app_list_strings, $app_strings, $current_language;

        // Getting options for the autoRefresh field.
        // The code is similar to the original $focus->getAutoRefreshOptions() method, but it is protected
        // and cannot be used externally
        $autoRefreshOptions = $app_list_strings['dashlet_auto_refresh_options'];
        if (isset($app_list_strings['dashlet_auto_refresh_min'])) {
            foreach ($autoRefreshOptions as $time => $desc) {
                if ($time != -1 && $time < $app_list_strings['dashlet_auto_refresh_min']) {
                    unset($autoRefreshOptions[$time]);
                }
            }
        }

        $displayRows = array();

        foreach ($sugar_config['dashlet_display_row_options'] as $value) {
            $displayRows[$value] = $value;
        }

        $result = array(
            'basicSettings' => array(
                'dashletTitle' => array(
                    'label' => 'LBL_DASHLET_CONFIGURE_TITLE',
                    'name' => 'dashletTitle',
                    'value' => $focus->title,
                    'type' => 'varchar',
                ),
                'displayRows' => array(
                    'label' => 'LBL_DASHLET_CONFIGURE_DISPLAY_ROWS',
                    'name' => 'displayRows',
                    'value' => $focus->displayRows,
                    'type' => 'enum',
                    'options' => $displayRows,
                ),
                'autoRefresh' => array(
                    'label' => 'LBL_DASHLET_CONFIGURE_AUTOREFRESH',
                    'name' => 'autoRefresh',
                    'value' => $focus->autoRefresh === '0' ? '-1' : $focus->autoRefresh,
                    'type' => 'enum',
                    'options' => $autoRefreshOptions,
                ),
            ),
            'columnChooser' => $this->getColumnChooser($focus),
            'filters' => array(),
        );

        if (true === $focus->showMyItemsOnly) {
            $result['filters']['myItemsOnly'] = array(
                'label' => 'LBL_DASHLET_CONFIGURE_MY_ITEMS_ONLY',
                'name' => 'myItemsOnly',
                'value' => $focus->myItemsOnly,
                'type' => 'bool'
            );
        }

        foreach ($focus->searchFields as $fieldName => $fieldParams) {
            if (isset($fieldParams['label'])) {
                $fieldLabel = $fieldParams['label'];
            } else {
                $fieldLabel = $focus->seedBean->field_defs[$fieldName]['vname'];
            }

            if (!empty($focus->filters[$fieldName])) {
                $fieldValue = $focus->filters[$fieldName];
            } else {
                $fieldValue = $focus->searchFields[$fieldName]['default'];
            }

            $result['filters'][$fieldName] = array(
                'label' => $fieldLabel,
                'name' => $fieldName,
                'value' => $fieldValue,
                'type' => $focus->seedBean->field_defs[$fieldName]['type'],
            );
            if (isset($focus->seedBean->field_defs[$fieldName]['options'])) {
                $result['filters'][$fieldName]['options'] = translate($focus->seedBean->field_defs[$fieldName]['options'], $focus->seedBean->module_dir);
            }

            /*
             * Kludge for box compatibility
             */
            if ('assigned_user_id' === $fieldName) {
                if (isset($focus->filters[$fieldName])) {
                    $fieldValue = $focus->filters[$fieldName];
                } else {
                    $fieldValue = array();
                }
                $result['filters'][$fieldName]['value'] = $fieldValue;
                $result['filters'][$fieldName]['type'] = 'multienum';
                $result['filters'][$fieldName]['options'] = get_user_array(false);
            }
            if (
                'datetime' === $focus->seedBean->field_defs[$fieldName]['type']
                || 'datetimecombo' === $focus->seedBean->field_defs[$fieldName]['type']
            ) {
                // In include/generic/SugarWidgets/SugarWidgetFielddatetime.php :: displayInput()
                // the options are overwritten with these values, but there they are immediately turned into html
                $home_mod_strings = return_module_language($current_language, 'Home');
                $result['filters'][$fieldName]['options'] = array(
                    '' => $app_strings['LBL_NONE'],
                    'TP_today' => $home_mod_strings['LBL_TODAY'],
                    'TP_yesterday' => $home_mod_strings['LBL_YESTERDAY'],
                    'TP_tomorrow' => $home_mod_strings['LBL_TOMORROW'],
                    'TP_this_month' => $home_mod_strings['LBL_THIS_MONTH'],
                    'TP_this_year' => $home_mod_strings['LBL_THIS_YEAR'],
                    'TP_last_30_days' => $home_mod_strings['LBL_LAST_30_DAYS'],
                    'TP_last_7_days' => $home_mod_strings['LBL_LAST_7_DAYS'],
                    'TP_last_month' => $home_mod_strings['LBL_LAST_MONTH'],
                    'TP_last_year' => $home_mod_strings['LBL_LAST_YEAR'],
                    'TP_next_30_days' => $home_mod_strings['LBL_NEXT_30_DAYS'],
                    'TP_next_7_days' => $home_mod_strings['LBL_NEXT_7_DAYS'],
                    'TP_next_month' => $home_mod_strings['LBL_NEXT_MONTH'],
                    'TP_next_year' => $home_mod_strings['LBL_NEXT_YEAR'],
                );

                // 420-SFR - These fields return a value as object {type: $fieldValue}
                if (!empty($focus->filters[$fieldName]['type'])) {
                    $result['filters'][$fieldName]['value'] = $focus->filters[$fieldName]['type'];
                }
            }
        }

        return $result;
    }

    /**
     * @param string $dashletId
     * @param array $dashletData
     * @param array $dashletOptions
     * @param string $dashletType
     * @return array
     */
    public function display($dashletId, $dashletData, $dashletOptions, $dashletType): array
    {
        $dashletHeaderData = json_decode($dashletData['displayHeader'], true);
        $dashletGenericDisplayData = json_decode($dashletData['display'], true);

        // The My Calls/My Meetings dashlets have an "Accept Link" column of type varchar, which can contain HTML buttons
        // if the event is scheduled and the user has not confirmed his participation.
        // We change the field type to widget and substitute the "_showWidget" stub instead of the HTML code.
        if (!empty($dashletGenericDisplayData['viewData']['displayColumns'])
            && array_key_exists('SET_ACCEPT_LINKS', $dashletGenericDisplayData['viewData']['displayColumns'])) {
            $dashletGenericDisplayData['viewData']['displayColumns']['SET_ACCEPT_LINKS']['type'] = 'setAcceptLinks';
            foreach ($dashletGenericDisplayData['viewData']['data'] as &$fields) {
                if ($fields['ACCEPT_STATUS'] == '') {
                    $fields['SET_ACCEPT_LINKS'] = '';
                } elseif ($fields['ACCEPT_STATUS'] === 'none') {
                    $fields['SET_ACCEPT_LINKS'] = '_showWidget';
                }
            }
        }

        // In the box, links for pagination buttons are generated only if the button should be active, but for some
        // reason, for the endPage button, a link is always generated.
        if ($dashletGenericDisplayData['pageData']['offsets']['lastOffsetOnPage'] == $dashletGenericDisplayData['pageData']['offsets']['total']) {
            unset($dashletGenericDisplayData['pageData']['urls']['endPage']);
        }

        $result = array(
            'id' => $dashletId,  // Probably useless
            'label' => $dashletHeaderData['label'],
            'type' => $dashletType,
            'options' => $dashletOptions,

            'editViewLinksEnable' => $dashletGenericDisplayData['editViewLinksEnable'],
            'selectRecordsEnable' => $dashletGenericDisplayData['selectRecordsEnable'],
            'pageData' => $dashletGenericDisplayData['pageData'],
            'selectedRecordsActions' => $dashletGenericDisplayData['selectedRecordsActions'],
            'viewData' => array(
                'displayColumns' => $dashletGenericDisplayData['viewData']['displayColumns'],
                'data' => $dashletGenericDisplayData['viewData']['data'],
            ),
        );

        return $result;
    }

    /**
     * Get lists of fields displayed and available for display for a basic similar dashlet
     *
     * @param Dashlet $focus
     * @return array
     * @see DashletGeneric::processDisplayOptions()
     */
    private function getColumnChooser(Dashlet $focus): array
    {
        $displayedFields = array();
        $hiddenFields = array();

        // If the dashlet settings have never been saved, then $focus->displayColumns is empty
        if ($focus->displayColumns) {
            foreach ($focus->displayColumns as $name) {
                $displayedFields[$name] = trim($focus->columns[$name]['label'], ':');
            }
            foreach (array_diff(array_keys($focus->columns), array_values($focus->displayColumns)) as $name) {
                $hiddenFields[$name] = trim($focus->columns[$name]['label'], ':');
            }
        } else {
            foreach ($focus->columns as $name => $val) {
                if (!empty($val['default']) && $val['default']) {
                    $displayedFields[$name] = trim($focus->columns[$name]['label'], ':');
                } else {
                    $hiddenFields[$name] = trim($focus->columns[$name]['label'], ':');
                }
            }
        }

        return array(
            'displayedFields' => $displayedFields,
            'hiddenFields' => $hiddenFields,
        );
    }

}
