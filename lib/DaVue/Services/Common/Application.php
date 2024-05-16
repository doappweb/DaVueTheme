<?php

namespace SuiteCRM\DaVue\Services\Common;

use ACLController;
use BeanFactory;
use GroupedTabStructure;
use SugarView;

class Application
{
    /**
     * System settings and application language string
     */
    public function getSystem(array $arg = []): array
    {
        global $sugar_config, $current_language;

        if (empty($arg['lang'])) {
            $lang = $current_language;
        }
        else{
            $lang = $arg['lang'];
        }

        $result = [];

        $result['app_lang'] = return_application_language($lang);
        if (!empty($GLOBALS['system_config']->settings['system_name'])) {
            $result['app_lang']['LBL_BROWSER_TITLE'] = $GLOBALS['system_config']->settings['system_name'];
        }
        $result['app_curLang'] = $lang;
        $result['app_list_lang'] = return_app_list_strings_language($lang);
        $result['app_langHeader'] = get_language_header();
        $result['app_config'] = [
            'languages' => $sugar_config['languages'],
            'name_formats' => $sugar_config['name_formats'],
            'date_formats' => $sugar_config['date_formats'],
            'time_formats' => $sugar_config['time_formats'],
            'dashlet_auto_refresh_min' => $sugar_config['dashlet_auto_refresh_min'],
            'dashlet_display_row_options' => $sugar_config['dashlet_display_row_options'],
            'datef' => $sugar_config['datef'],
            'default_action' => $sugar_config['default_action'],
            'default_charset' => $sugar_config['default_charset'],
            'default_currency_iso4217' => $sugar_config['default_currency_iso4217'],
            'default_currency_name' => $sugar_config['default_currency_name'],
            'default_currency_significant_digits' => $sugar_config['default_currency_significant_digits'],
            'default_currency_symbol' => $sugar_config['default_currency_symbol'],
            'default_date_format' => $sugar_config['default_date_format'],
            'default_decimal_seperator' => $sugar_config['default_decimal_seperator'],
            'default_email_charset' => $sugar_config['default_email_charset'],
            'default_email_client' => $sugar_config['default_email_client'],
            'default_email_editor' => $sugar_config['default_email_editor'],
            'default_export_charset' => $sugar_config['default_export_charset'],
            'default_language' => $sugar_config['default_language'],
            'default_locale_name_format' => $sugar_config['default_locale_name_format'],
            'default_max_tabs' => $sugar_config['default_max_tabs'],
            'default_module' => $sugar_config['default_module'],
            'default_module_favicon' => $sugar_config['default_module_favicon'],
            'default_navigation_paradigm' => $sugar_config['default_navigation_paradigm'],
            'default_number_grouping_seperator' => $sugar_config['default_number_grouping_seperator'],
            'default_password' => $sugar_config['default_password'],
            'default_subpanel_links' => $sugar_config['default_subpanel_links'],
            'default_subpanel_tabs' => $sugar_config['default_subpanel_tabs'],
            'default_swap_last_viewed' => $sugar_config['default_swap_last_viewed'],
            'default_swap_shortcuts' => $sugar_config['default_swap_shortcuts'],
            'default_theme' => $sugar_config['default_theme'],
            'default_time_format' => $sugar_config['default_time_format'],
            'site_url' => $sugar_config['site_url'],
        ];

        return $result;
    }

    /**
     * Full list of modules with:
     * - name and css keys,
     * - action menu
     * - module language strings
     * - access level
     */
    public function getModules(): array
    {
        global $current_language, $beanList, $moduleList, $app_list_strings;

        $availModules = array_keys(array_merge(array_flip($moduleList), $beanList));
        $availModules[] = 'Activities';

        $sugar = new SugarView();

        $result = [];
        foreach ($availModules as $moduleKey){

            $actions = $sugar->getMenu($moduleKey);
            $actionsMenu = [];
            foreach ($actions as $action){
                $actionsMenu[] = [
                    'lbl'   => $action[1],
                    'css_key'   => str_replace('_', '-', strtolower($action[2])),
                    'link'  => ltrim($action[0], 'index.php?')
                ];
            }

            $result[$moduleKey] = [
                'module_key' => $moduleKey,
                'module_label' => !empty($app_list_strings['moduleList'][$moduleKey]) ? $app_list_strings['moduleList'][$moduleKey] : '',
                'acls' => $this->checkModuleRoleAccess($moduleKey),
                'css_key' => str_replace('_', '-', strtolower($moduleKey)),
                'language' => return_module_language($current_language, $moduleKey),
                'action_menu' => $actionsMenu
            ];
        }

        return $result;
    }

    /**
     * Menu with access, sorting and grouping settings
     */
    public function getMenu(): array
    {
        global $current_user, $app_list_strings, $app_strings;

        $result = [];

        // Access
        $allModules = query_module_access_list($current_user);

        // Grouping
        $useGroupTabs = false;
        $userNavigationParadigm = $current_user->getPreference('navigation_paradigm');
        if (!isset($userNavigationParadigm)) {
            $userNavigationParadigm = $GLOBALS['sugar_config']['default_navigation_paradigm'];
        }
        if ('gm' === $userNavigationParadigm) {
            $useGroupTabs = true;
        }

        if ($useGroupTabs) {
            $groupedTabsClass = new GroupedTabStructure();
            $groupTabs = $groupedTabsClass->get_tab_structure(get_val_array($allModules));

            foreach ($groupTabs as $tab => $content) {
                $modules = [];
                foreach ($content['modules'] as $key => $value) {

                    $modules[$key] = [
                        'lbl'         => $value,
                        'key'           => str_replace('_', '-', strtolower($key)),
                        'extraparams'   => 'parentTab=' . $tab,
                        'link'    => "module=$key&action=ListView&parentTab=$tab",
                    ];
                }

                $result[] = [
                    'group'     => $tab,
                    'asGroup'   => true,
                    'all'       => false,
                    'modules'   => $modules
                ];
            }
        }

        $all = [];

        $tab = $app_strings['LBL_TABGROUP_ALL'];
        $all['group'] = $tab;
        $all['asGroup'] = $useGroupTabs;
        $all['all'] = true;
        foreach ($allModules as $module){

            $all['modules'][$module] = [
                'lbl'           => $app_list_strings['moduleList'][$module],
                'key'       => str_replace('_', '-', strtolower($module)),
                'extraparams'   => 'parentTab=' . $tab,
                'link'    => "module=$module&action=ListView&parentTab=$tab",
                'active'        => false
            ];
        }

        // Sorting
        if ($current_user->getPreference('sort_modules_by_name')) {
            uasort($all['modules'], function($a, $b){
                return ($a['lbl'] < $b['lbl']) ? -1 : 1;
            });
        }

        $result[] = $all;

        return $result;
    }

    /**
     * User preference and links
     */
    public function getUser(): array
    {
        global $current_user, $timedate, $disable_date_format;

        $disable_date_format = null;
        $userDateFormat = $timedate->get_date_format($current_user);
        $userTimeFormat = $timedate->get_time_format($current_user);

        $userCurrencyId = $current_user->getPreference('currency');
        $userCurrency = BeanFactory::getBean('Currencies', $userCurrencyId);
        $userCurrencySignificantDigits = $current_user->getPreference('default_currency_significant_digits');
        $user1000sSeparator = $current_user->getPreference('num_grp_sep');
        $userDecimalSymbol = $current_user->getPreference('dec_sep');

        if (!empty($current_user->photo)) {
            $userPhotoUrl = "entryPoint=download&id={$current_user->id}_photo&type=Users";
        } else {
            $userPhotoUrl = null;
        }

        $userGlobalControlLinks = $this->getUserGlobalControlLinks();

        $user = [
            'data' => [
                'id' => $current_user->id,
                'is_admin' => is_admin($current_user),
                'username' => $current_user->user_name,
                'firstName' => $current_user->first_name,
                'lastName' => $current_user->last_name,
                'photo' => $userPhotoUrl,
                'department' => $current_user->department,
                'title' => $current_user->title,
                'dateEntered' => $current_user->date_entered,
                'userDateFormat' => $userDateFormat,
                'userTimeFormat' => $userTimeFormat,
                'userCurrencyId' => $userCurrencyId,
                'currencyName' => $userCurrency->name,
                'currencySymbol' => $userCurrency->symbol,
                'currencySignificantDigits' => $userCurrencySignificantDigits,
                '1000sSeparator' => $user1000sSeparator,
                'decimalSymbol' => $userDecimalSymbol,
            ],
            'gcls' => $userGlobalControlLinks['gcls'],
            'logoutLink' => $userGlobalControlLinks['logoutLink'],
        ];

        return $user;
    }


    /**
     * User Config
     */
    public function getExtConfig(): array
    {
        return [
            'modules' => [
                'Home' => [
                    'actions' => [
                        'ListView'      => true,
                        'UnifiedSearch' => true,
                        'About'         => true,
                    ],
                    'subMenu' => [
                        'addDashlet' => [
                            'css_key' => 'add-dashlet',
                            'lbl' => [
                                'name'   => 'LBL_ADD_DASHLETS',
                                'module' => '',
                            ],
                            'click' => 'addDashlet',
                        ],
                        'addPage' => [
                            'css_key' => 'add-page',
                            'lbl' => [
                                'name'   => 'LBL_ADD_TAB',
                                'module' => '',
                            ],
                            'click' => 'addTab',
                        ],
                        'renamePage' => [
                            'css_key' => 'rename-page',
                            'lbl' => [
                                'name'   => 'LBL_DA_RENAME_TAB',
                                'module' => '',
                            ],
                            'click' => 'renameTab',
                        ],
                        'deletePage' => [
                            'css_key' => 'delete-page',
                            'lbl' => [
                                'name'   => 'LBL_DA_REMOVE_TAB',
                                'module' => '',
                            ],
                            'click' => 'removeTab',
                        ],
                    ]
                ],
                'Users' => [
                    'actions' => [
                        'EditView' => true,
                        'Login'    => true,
                    ],
                ],
                'Calls' => [
                    'actions' => [
                        'EditView' => true,
                    ],
                    'subActions' => [
                        'Kanban' => [
                            'requestOff' => false
                        ],
                    ],
                    'subMenu' => [
                        'Kanban' => $this->getSubMenuKanban('Calls'),
                        'Calendar' => $this->getSubMenuCalendar(),
                    ]
                ],
                'Meetings' => [
                    'actions' => [
                        'EditView' => true,
                    ],
                    'subActions' => [
                        'Kanban' => [
                            'requestOff' => false
                        ],
                    ],
                    'subMenu' => [
                        'Kanban' => $this->getSubMenuKanban('Meetings'),
                        'Calendar' => $this->getSubMenuCalendar(),
                    ]
                ],
                'Tasks' => [
                    'actions' => (object)[],
                    'subActions' => [
                        'Kanban' => [
                            'requestOff' => false
                        ],
                    ],
                    'subMenu' => [
                        'Kanban' => $this->getSubMenuKanban('Tasks'),
                        'Calendar' => $this->getSubMenuCalendar(),
                    ]
                ],
                'Opportunities' => [
                    'actions' => (object)[],
                    'subActions' => [
                        'Kanban' => [
                            'requestOff' => false
                        ],
                    ],
                    'subMenu' => [
                        'Kanban' => $this->getSubMenuKanban('Opportunities'),
                    ]
                ],
                'Leads' => [
                    'actions' => (object)[],
                    'subActions' => [
                        'Kanban' => [
                            'requestOff' => false
                        ],
                    ],
                    'subMenu' => [
                        'Kanban' => $this->getSubMenuKanban('Leads'),
                    ]
                ],
                'Bugs' => [
                    'actions' => (object)[],
                    'subActions' => [
                        'Kanban' => [
                            'requestOff' => false
                        ],
                    ],
                    'subMenu' => [
                        'Kanban' => $this->getSubMenuKanban('Bugs'),
                    ]
                ],
                'Project' => [
                    'actions' => [
                        'view_GanttChart' => true
                    ],
                ],
                'FP_events' => [
                    'actions' => (object)[],
                    'subMenu' => [
                        'Calendar' => $this->getSubMenuCalendar(),
                    ]
                ],
                'Calendar' => [
                    'actions' => [
                        'ListView' => true
                    ],
                    'subMenu' => [
                        'Today' => [
                            'css_key' => 'today',
                            'lbl' => [
                                'name'   => 'LBL_TODAY',
                                'module' => 'Home',
                            ],
                            'click' => false,
                            'query' => [
                                'module' => 'Calendar',
                                'action' => 'ListView',
                            ],
                        ],
                    ]
                ],
                'AOR_Reports' => [
                    'actions' => [
                        'DetailView' => true
                    ],
                ],
                'MergeRecords' => [
                    'actions' => [
                        'Step1' => true
                    ],
                ],
                'Administration' => [
                    'actions' => [
                        'Index' => true
                    ],
                ],
                'Cases' => [
                    'actions' => [
                        'EditView' => true,
                    ],
                ],
            ]
        ];
    }


    private function getSubMenuCalendar(): array
    {
        return [
            'css_key' => 'schedule-calendar',
            'lbl' => [
                'name'   => 'LBL_MODULE_NAME',
                'module' => 'Calendar',
            ],
            'click' => false,
            'query' => [
                'module' => 'Calendar',
                'action' => 'ListView',
            ],
        ];
    }

    /**
     * @param string $moduleName
     * @return array
     */
    private function getSubMenuKanban(string $moduleName): array
    {
        return [
            'css_key' => 'kanban',
            'lbl' => 'Kanban',
            'click' => false,
            'query' => [
                'module'    => $moduleName,
                'action'    => 'ListView',
                'subAction' => 'Kanban'
            ]
        ];
    }

    /**
     * @see include/MVC/View/SugarView.php
     */
    private function getUserGlobalControlLinks(): array
    {
        $logout = [];
        $gcls = [];
        $global_control_links = array();
        require("include/globalControlLinks.php");

        foreach ($global_control_links as $key => $value) {
            if ($key == 'users') {
                $logout = [
                    'LABEL' => key($value['linkinfo']),
                    'URL' => str_replace('index.php?', '', $value['linkinfo'][key($value['linkinfo'])]),
                    'SUBMENU' => [],
                ];

                continue;
            }

            foreach ($value as $linkattribute => $attributevalue) {
                // get the main link info
                if ($linkattribute == 'linkinfo') {
                    $gcls[$key] = [
                        "LABEL" => key($attributevalue),
                        "URL" => str_replace('index.php?', '', current($attributevalue)),
                        "SUBMENU" => [],
                    ];

                    if (substr($gcls[$key]["URL"], 0, 11) == "javascript:") {
                        $gcls[$key]["ONCLICK"] = substr($gcls[$key]["URL"], 11);
                        $gcls[$key]["URL"] = "javascript:void(0)";
                    }

                    if (isset($attributevalue['target'])) {
                        $gcls[$key]["TARGET"] = $attributevalue['target'];
                    }
                }
                // and now the sublinks
                if ($linkattribute === 'submenu' && is_array($attributevalue)) {
                    foreach ($attributevalue as $submenulinkkey => $submenulinkinfo) {
                        $gcls[$key]['SUBMENU'][$submenulinkkey] = array(
                            "LABEL" => key($submenulinkinfo),
                            "URL" => current($submenulinkinfo),
                        );
                    }
                    if (substr($gcls[$key]['SUBMENU'][$submenulinkkey]["URL"], 0, 11) === "javascript:") {
                        $gcls[$key]['SUBMENU'][$submenulinkkey]["ONCLICK"] =
                            substr($gcls[$key]['SUBMENU'][$submenulinkkey]["URL"], 11);
                        $gcls[$key]['SUBMENU'][$submenulinkkey]["URL"] = "javascript:void(0)";
                    }
                }
            }
        }

        return [
            'logoutLink' => $logout,
            'gcls' => $gcls,
        ];
    }

    /**
     * Check a module for acces to a set of available actions.
     */
    private function checkModuleRoleAccess(string $module): array
    {
        $results = [];
        $actions = ['edit','delete','list','view','import','export'];
        foreach ($actions as $action) {
            $access = ACLController::checkAccess($module, $action, true);
            $results[] = ['action' => $action, 'access' => $access];
        }

        return $results;
    }
}
