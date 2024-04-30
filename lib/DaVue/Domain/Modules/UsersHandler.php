<?php

namespace SuiteCRM\DaVue\Domain\Modules;

use ACLAction;
use BeanFactory;
use SugarThemeRegistry;
use SuiteCRM\DaVue\Services\Common\FieldFunctions;
use TabController;
use TemplateGroupChooser;

class UsersHandler
{
    /** @var FieldFunctions $fieldFunctions */
    private $fieldFunctions;

    public function __construct(FieldFunctions $fieldFunctions)
    {
        $this->fieldFunctions = $fieldFunctions;
    }

    /**
     * List of panels/tabs with their parameters on the DetailView that are not added via metadata
     * @return array[]
     */
    public function getDetailCustomPanelsMetadata($templateVars)
    {
        $result = array(
            'LBL_ADVANCED' => array(
                'newTab' => true,
                'panelDefault' => 'collapsed',
            ),
        );

        if ($templateVars['SHOW_ROLES']) {
            $result['LBL_USER_ACCESS'] = array(
                'newTab' => true,
                'panelDefault' => 'collapsed',
            );
        }

        return $result;
    }


    /**
     * List of fields on the DetailView for the corresponding panels/tabs that are not added via metadata
     * @param $templateVars
     * @return array
     */
    public function getDetailCustomFieldsMetadata($templateVars)
    {
        $result = array(
            'LBL_ADVANCED' => $this->getDetailAdvancedFieldsMetadata($templateVars),
        );

        if ($templateVars['SHOW_ROLES']) {
            $result['LBL_USER_ACCESS']['permissions']['field'] = array(
                'name' => 'permissions',
            );
        }

        return $result;
    }


    /**
     * Vardefs of fields on the DetailView that are not added via metadata
     * @param $templateVars
     * @return array
     */
    public function getDetailCustomVardefs($templateVars)
    {
        global $sugar_config, $locale, $app_list_strings;

        // ==== $MOD.LBL_USER_SETTINGS ====
        $result['receive_notifications'] = array(
            'name' => 'receive_notifications',
            'vname' => 'LBL_RECEIVE_NOTIFICATIONS',
            'type' => 'checkbox',
            'value' => (bool)$templateVars['RECEIVE_NOTIFICATIONS'],
        );
        $result['mailmerge_on'] = array(
            'name' => 'mailmerge_on',
            'vname' => 'LBL_MAILMERGE',
            'type' => 'checkbox',
            'value' => (bool)$templateVars['MAILMERGE_ON'],
        );
        $result['settings_url'] = array(
            'name' => 'settings_url',
            'vname' => 'LBL_SETTINGS_URL',
            'type' => 'varchar',
            'value' => $templateVars['SETTINGS_URL'],
        );
        $result['export_delimiter'] = array(
            'name' => 'export_delimiter',
            'vname' => 'LBL_EXPORT_DELIMITER',
            'type' => 'varchar',
            'value' => $templateVars['EXPORT_DELIMITER'],
        );
        $result['export_charset_display'] = array(
            'name' => 'export_charset_display',
            'vname' => 'LBL_EXPORT_CHARSET',
            'type' => 'enum',
            'value' => $templateVars['EXPORT_CHARSET_DISPLAY'],
            'options' => translate('charset_dom'),
        );

        if ($templateVars['DISPLAY_EXTERNAL_AUTH']) {
            $result['external_auth_only'] = array(
                'name' => 'external_auth_only',
                'vname' => 'EXTERNAL_AUTH_CLASS',
                'type' => 'checkbox',
                'value' => $templateVars['EXTERNAL_AUTH_ONLY_CHECKED'],
            );
        }
        $result['reminders'] = array(
            'name' => 'reminders',
            'vname' => 'LBL_REMINDER',
            'type' => 'Users-DetailView-reminders',
            'value' => array(
                'popup' => array(
                    'isEnable' => (bool)$templateVars['REMINDER_CHECKED'],
                    'selectedTime' => $templateVars['REMINDER_TIME'],
                ),
                'emailInvitees' => array(
                    'isEnable' => (bool)$templateVars['EMAIL_REMINDER_CHECKED'],
                    'selectedTime' => $templateVars['EMAIL_REMINDER_TIME'],
                ),
            ),
            'options' => $templateVars['REMINDER_TIME_OPTIONS'],
        );
        $result['use_real_names'] = array(
            'name' => 'use_real_names',
            'vname' => 'LBL_USE_REAL_NAMES',
            'type' => 'checkbox',
            'value' => (bool)$templateVars['USE_REAL_NAMES'],  // null|"CHECKED"
        );

        // ==== $MOD.LBL_USER_LOCALE ====
        $result['dateformat'] = array(
            'name' => 'dateformat',
            'vname' => 'LBL_DATE_FORMAT',
            'type' => 'enum',
            'options' => $sugar_config['date_formats'],

            // @see modules/Users/UserViewHelper.php :: setupAdvancedTabLocaleSettings()
            'value' => $locale->getPrecedentPreference(
                $templateVars['bean']->id ? 'datef' : 'default_date_format',
                $templateVars['bean']
            ),
        );
        $result['timeformat'] = array(
            'name' => 'timeformat',
            'vname' => 'LBL_TIME_FORMAT',
            'type' => 'enum',
            'options' => $sugar_config['time_formats'],

            // @see modules/Users/UserViewHelper.php :: setupAdvancedTabLocaleSettings()
            'value' => $locale->getPrecedentPreference(
                $templateVars['bean']->id ? 'timef' : 'default_time_format',
                $templateVars['bean']
            ),
        );
        $result['timezone'] = array(
            'name' => 'timezone',
            'vname' => 'LBL_TIMEZONE',
            'type' => 'enum',
            'value' => $templateVars['TIMEZONE_CURRENT'],
            'options' => $templateVars['TIMEZONEOPTIONS'],
        );

        $result['currency'] = array(
            'name' => 'currency',
            'vname' => 'LBL_CURRENCY',
        );
        // There is no method in the box that would return an array of options for the system's currencies not in html form,
        // but we have a handler for the currency field with the function type in the theme
        $result['currency'] = $this->fieldFunctions->implement($result['currency'], $templateVars['bean'], 'getCurrencyDropDown');

        $result['default_currency_significant_digits'] = array(
            'name' => 'default_currency_significant_digits',
            'vname' => 'LBL_CURRENCY_SIG_DIGITS',
            'type' => 'enum',

            // @see modules/Users/UserViewHelper.php :: setupAdvancedTabLocaleSettings()
            'value' => $locale->getPrecedentPreference('default_currency_significant_digits', $templateVars['bean']),
            'options' => array(0, 1, 2, 3, 4, 5, 6),
        );
        $result['num_grp_sep'] = array(
            'name' => 'num_grp_sep',
            'vname' => 'LBL_NUMBER_GROUPING_SEP',
            'type' => 'varchar',
            'value' => $templateVars['NUM_GRP_SEP'],
        );

        $result['dec_sep'] = array(
            'name' => 'dec_sep',
            'vname' => 'LBL_DECIMAL_SEP',
            'type' => 'name',
            'value' => $templateVars['DEC_SEP'],  // In the box on the DetailView here is a description of the field instead of the value
        );

        // @see modules/Users/UserViewHelper.php :: setupAdvancedTabLocaleSettings()
        $nformat = $locale->getPrecedentPreference('default_locale_name_format', $templateVars['bean']);
        if (!array_key_exists($nformat, $sugar_config['name_formats'])) {
            $nformat = $sugar_config['default_locale_name_format'];
        }
        $result['default_locale_name_format'] = array(
            'name' => 'default_locale_name_format',
            'vname' => 'LBL_LOCALE_DEFAULT_NAME_FORMAT',
            'type' => 'enum',
            'value' => $nformat,
            'options' => $locale->getUsableLocaleNameOptions($sugar_config['name_formats']),
        );

        // ==== $MOD.LBL_CALENDAR_OPTIONS ====
        $result['calendar_publish_key'] = array(
            'name' => 'calendar_publish_key',
            'vname' => 'LBL_PUBLISH_KEY',
            'type' => 'varchar',
            'value' => $templateVars['CALENDAR_PUBLISH_KEY'],
        );
        $result['cal_pub_key_span'] = array(
            'name' => 'cal_pub_key_span',
            'vname' => 'LBL_YOUR_PUBLISH_URL',
            'type' => 'name',
            'value' => $templateVars['CALENDAR_PUBLISH_KEY'] ? strip_tags($templateVars['CALENDAR_PUBLISH_URL']) : translate('LBL_NO_KEY'),
        );
        $result['search_pub_key_span'] = array(
            'name' => 'search_pub_key_span',
            'vname' => 'LBL_SEARCH_URL',
            'type' => 'name',
            'value' => $templateVars['CALENDAR_PUBLISH_KEY'] ? strip_tags($templateVars['CALENDAR_SEARCH_URL']) : translate('LBL_NO_KEY'),
        );
        $result['ical_pub_key_span'] = array(
            'name' => 'ical_pub_key_span',
            'vname' => 'LBL_ICAL_PUB_URL',
            'type' => 'name',
            'value' => $templateVars['CALENDAR_PUBLISH_KEY'] ? strip_tags($templateVars['CALENDAR_ICAL_URL']) : translate('LBL_NO_KEY'),
        );

        // @see modules/Users/UserViewHelper.php :: setupAdvancedTabLocaleSettings()
        // FG - Bug 4236 - Managed First Day of Week
        $fdowDays = array();
        foreach ($app_list_strings['dom_cal_day_long'] as $d) {
            if ($d != "") {
                $fdowDays[] = $d;
            }
        }
        $currentFDOW = $templateVars['bean']->get_first_day_of_week();
        if (!isset($currentFDOW)) {
            $currentFDOW = 0;
        }
        $result['fdow'] = array(
            'name' => 'fdow',
            'vname' => 'LBL_FDOW',
            'type' => 'enum',
            'value' => $currentFDOW,
            'options' => $fdowDays,
        );

        // ==== $MOD.LBL_GOOGLE_API_SETTINGS ====
        $result['google_api_token'] = array(
            'name' => 'google_api_token',
            'vname' => 'LBL_GOOGLE_API_TOKEN',
            'type' => 'varchar',
            'value' => $templateVars['GOOGLE_API_TOKEN'],
        );
        $result['google_sync_calendar_enable'] = array(
            'name' => 'google_sync_calendar_enable',
            'vname' => 'LBL_GSYNC_CAL',
            'type' => 'checkbox',
            'value' => (bool)$templateVars['GSYNC_CAL'],
        );

        // ==== $MOD.LBL_LAYOUT_OPTIONS ====
        $result['use_group_tabs'] = array(
            'name' => 'use_group_tabs',
            'vname' => 'LBL_USE_GROUP_TABS',
            'type' => 'checkbox',
            'value' => (bool)$templateVars['USE_GROUP_TABS'],
        );

        // ==== the Access Rights tab ====
        if ($templateVars['SHOW_ROLES']) {
            $result = array_merge($result, $this->getDetailPermissionsVardefs());
        }

        return $result;
    }


    /**
     * Metadata of fields for the "Advanced" tab on the DetailView
     * @param $templateVars
     * @return array
     */
    private function getDetailAdvancedFieldsMetadata($templateVars)
    {
        // ==== $MOD.LBL_USER_SETTINGS ====
        $panelsFields['receive_notifications'] = array(
            'field' => array(
                'name' => 'receive_notifications',
                'tabindex' => '12',
            ),
        );
        $panelsFields['mailmerge_on'] = array(
            'field' => array(
                'name' => 'mailmerge_on',
                'tabindex' => '3',
            ),
        );
        $panelsFields['settings_url'] = array(
            'field' => array(
                'name' => 'settings_url',
            ),
        );
        $panelsFields['export_delimiter'] = array(
            'field' => array(
                'name' => 'export_delimiter',
            ),
        );
        $panelsFields['export_charset_display'] = array(
            'field' => array(
                'name' => 'export_charset_display',
            ),
        );

        if ($templateVars['DISPLAY_EXTERNAL_AUTH']) {
            $panelsFields['external_auth_only'] = array(
                'field' => array(
                    'name' => 'external_auth_only',
                ),
            );
        }
        $panelsFields['reminders'] = array(
            'field' => array(
                'name' => 'reminders',
            ),
        );
        $panelsFields['use_real_names'] = array(
            'field' => array(
                'name' => 'use_real_names',
            ),
        );

        // ==== $MOD.LBL_USER_LOCALE ====
        $panelsFields['dateformat'] = array(
            'field' => array(
                'name' => 'dateformat',
            ),
        );
        $panelsFields['timeformat'] = array(
            'field' => array(
                'name' => 'timeformat',
            ),
        );
        $panelsFields['timezone'] = array(
            'field' => array(
                'name' => 'timezone',
            ),
        );
        $panelsFields['currency'] = array(
            'field' => array(
                'name' => 'currency',
            ),
        );
        $panelsFields['default_currency_significant_digits'] = array(
            'field' => array(
                'name' => 'default_currency_significant_digits',
            ),
        );

        $panelsFields['num_grp_sep'] = array(
            'field' => array(
                'name' => 'num_grp_sep',
            ),
        );
        $panelsFields['dec_sep'] = array(
            'field' => array(
                'name' => 'dec_sep',
            ),
        );
        $panelsFields['default_locale_name_format'] = array(
            'field' => array(
                'name' => 'default_locale_name_format',
                'type' => 'Users-DetailView-default_locale_name_format',  // to display additional text inside the field
            ),
        );

        // ==== $MOD.LBL_CALENDAR_OPTIONS ====
        $panelsFields['calendar_publish_key'] = array(
            'field' => array(
                'name' => 'calendar_publish_key',
            ),
        );
        $panelsFields['cal_pub_key_span'] = array(
            'field' => array(
                'name' => 'cal_pub_key_span',
            ),
        );
        $panelsFields['search_pub_key_span'] = array(
            'field' => array(
                'name' => 'search_pub_key_span',
            ),
        );
        $panelsFields['ical_pub_key_span'] = array(
            'field' => array(
                'name' => 'ical_pub_key_span',
            ),
        );
        $panelsFields['fdow'] = array(
            'field' => array(
                'name' => 'fdow',
            ),
        );

        // ==== $MOD.LBL_GOOGLE_API_SETTINGS ====
        $panelsFields['google_api_token'] = array(
            'field' => array(
                'name' => 'google_api_token',
            ),
        );
        $panelsFields['google_sync_calendar_enable'] = array(
            'field' => array(
                'name' => 'google_sync_calendar_enable',
            ),
        );

        // ==== $MOD.LBL_LAYOUT_OPTIONS ====
        $panelsFields['use_group_tabs'] = array(
            'field' => array(
                'tabindex' => '12',
                'name' => 'use_group_tabs',
            ),
        );

        return $panelsFields;
    }


    /**
     * Data for the "Access Rights" tab on the DetailView of the Users module.
     *
     * @param $templateVars
     * @return array
     * @see modules/ACLRoles/DetailUserAccess.php
     * @see modules/ACLRoles/DetailViewBody.tpl
     */
    private function getDetailPermissionsVardefs()
    {
        global $current_user, $modInvisList;

        $focus = BeanFactory::newBean('Users');
        $focus->retrieve($_REQUEST['record']);

        $categories = ACLAction::getUserActions($_REQUEST['record'], true);

        //clear out any removed tabs from user display
        if (!$current_user->isAdminForModule('Users')) {
            $tabs = $focus->getPreference('display_tabs');
            if (!empty($tabs)) {
                foreach (array_keys($categories) as $moduleName) {
                    if (!in_array($moduleName, $tabs) && !in_array($moduleName, $modInvisList)) {
                        unset($categories[$moduleName]);
                    }
                }
            }
        }

        $names = ACLAction::setupCategoriesMatrix($categories);

        $result['permissions'] = array(
            'name' => 'permissions',
            'vname' => '',
            'type' => 'Users-DetailView-permissions',
            'value' => array(
                'actionNames' => $names,
                'categories' => $categories,
            ),
        );

        return $result;
    }

    /**
     * List of options for the User Type field.
     *
     * @param $templateVars
     * @return array
     * @see modules/Users/UserViewHelper.php :: setupUserTypeDropdown()
     */
    public function getUserTypeFieldOptions($templateVars)
    {
        $userTypes = array(
            'RegularUser' => array(
                'label' => translate('LBL_REGULAR_USER', 'Users'),
            ),
            'GROUP' => array(
                'label' => translate('LBL_GROUP_USER', 'Users'),
            ),
            'Administrator' => array(
                'label' => translate('LBL_ADMIN_USER', 'Users'),
            ),
        );

        if ($templateVars['bean']->user_type == 'GROUP' || $templateVars['bean']->user_type == 'PORTAL_ONLY') {
            $availableUserTypes = array($templateVars['bean']->user_type);
        } else {
            if ($templateVars['USER_ADMIN']) {
                $availableUserTypes = array('RegularUser');
            } elseif ($templateVars['ADMIN_EDIT_SELF']) {
                $availableUserTypes = array('Administrator');
            } elseif ($templateVars['IS_SUPER_ADMIN']) {
                $availableUserTypes = array(
                    'RegularUser',
                    'Administrator',
                );
            } else {
                $availableUserTypes = array($templateVars['bean']->user_type);
            }
        }

        $result = array();
        foreach ($availableUserTypes as $currType) {
            $result[$currType] = $userTypes[$currType]['label'];
        }

        return $result;
    }

    /**
     * List of panels/tabs with their parameters on EditView that are not added through metadata
     * @return array[]
     * @see modules/Users/tpls/EditViewFooter.tpl
     */
    public function getEditCustomPanelsMetadata($templateVars)
    {
        $result = array();
        $result['LBL_MAIL_OPTIONS_TITLE'] = array(
            'newTab' => true,
            'panelDefault' => 'collapsed',
        );
        if ($templateVars['CHANGE_PWD']) {
            $result['LBL_PASSWORD'] = array(
                'newTab' => true,
                'panelDefault' => 'collapsed',
            );
        }
        if ($templateVars['SHOW_THEMES']) {
            $result['LBL_THEME'] = array(
                'newTab' => true,
                'panelDefault' => 'collapsed',
            );
        }
        $result['LBL_ADVANCED'] = array(
            'newTab' => true,
            'panelDefault' => 'collapsed',
        );
        if ($templateVars['ID']) {
            $result['LBL_EAPM_SUBPANEL_TITLE'] = array(
                'newTab' => true,
                'panelDefault' => 'collapsed',
            );
        }
        $result['LBL_LAYOUT_OPTIONS'] = array(
            'newTab' => true,
            'panelDefault' => 'collapsed',
        );

        return $result;
    }

    /**
     * List of fields on EditView for the corresponding panels/tabs that are not added via metadata
     * @param $templateVars
     * @return array
     */
    public function getEditCustomFieldsMetadata($templateVars)
    {
        $result = array();
//       ==== MOD.LBL_MAIL_OPTIONS_TITLE ====
        $result['LBL_MAIL_OPTIONS_TITLE'] = array();
        $result['LBL_MAIL_OPTIONS_TITLE']['email1']['field'] = array(
            'name' => 'email1',
            'tabindex' => '2',
        );
        if (!isset($templateVars['HIDE_FOR_GROUP_AND_PORTAL']) || 'none' !== $templateVars['HIDE_FOR_GROUP_AND_PORTAL']) {
            $result['LBL_MAIL_OPTIONS_TITLE']['email_link_type']['field'] = array(
                'name' => 'email_link_type',
                'tabindex' => '2',
            );
        }
        $result['LBL_MAIL_OPTIONS_TITLE']['editor_type']['field'] = array(
            'name' => 'editor_type',
            'tabindex' => '2',
        );

        if ($templateVars['CHANGE_PWD']) {
            $result['LBL_PASSWORD'] = array();
            if (!$templateVars['IS_ADMIN'] || $templateVars['ADMIN_EDIT_SELF']) {
                $result['LBL_PASSWORD']['old_password']['field'] = array(
                    'name' => 'old_password',
                    'tabindex' => '2',
                );
            }
            $result['LBL_PASSWORD']['new_password']['field'] = array(
                'name' => 'new_password',
                'tabindex' => '2',
            );
            $result['LBL_PASSWORD']['confirm_new_password']['field'] = array(
                'name' => 'confirm_new_password',
                'tabindex' => '2',
            );
        }

        if ($templateVars['SHOW_THEMES']) {
            $result['LBL_THEME'] = array();
            if (!isset($templateVars['HIDE_FOR_GROUP_AND_PORTAL']) || 'none' !== $templateVars['HIDE_FOR_GROUP_AND_PORTAL']) {
                $result['LBL_THEME']['user_theme_picker']['field'] = array(
                    'name' => 'user_theme_picker',
                );
            }
        }

        $result['LBL_ADVANCED'] = array();
        if (!isset($templateVars['HIDE_FOR_GROUP_AND_PORTAL']) || 'none' !== $templateVars['HIDE_FOR_GROUP_AND_PORTAL']) {

            $result['LBL_ADVANCED']['export_delimiter']['field'] = array(
                'name' => 'export_delimiter',
            );

            $result['LBL_ADVANCED']['default_export_charset']['field'] = array(
                'name' => 'default_export_charset',
            );

            $result['LBL_ADVANCED']['receive_notifications']['field'] = array(
                'name' => 'receive_notifications',
            );

            $result['LBL_ADVANCED']['use_real_names']['field'] = array(
                'name' => 'use_real_names',
            );

            $result['LBL_ADVANCED']['reminders']['field'] = array(
                'name' => 'reminders',
            );

            $result['LBL_ADVANCED']['mailmerge_on']['field'] = array(
                'name' => 'mailmerge_on',
            );

            if (!empty($templateVars['EXTERNAL_AUTH_CLASS']) && !empty($templateVars['IS_ADMIN'])) {
                $result['LBL_ADVANCED']['external_auth_only']['field'] = array(
                    'name' => 'external_auth_only',
                );
            }

            // ==== time/dateformat ====
            $result['LBL_ADVANCED']['dateformat']['field'] = array(
                'name' => 'dateformat',
            );

            $result['LBL_ADVANCED']['timeformat']['field'] = array(
                'name' => 'timeformat',
            );

            $result['LBL_ADVANCED']['timezone']['field'] = array(
                'name' => 'timezone',
            );

            $result['LBL_ADVANCED']['default_locale_name_format']['field'] = array(
                'name' => 'default_locale_name_format',
            );

            // ==== currency ====
            $result['LBL_ADVANCED']['currency']['field'] = array(
                'name' => 'currency',
            );

            $result['LBL_ADVANCED']['default_currency_significant_digits']['field'] = array(
                'name' => 'default_currency_significant_digits',
            );

            $result['LBL_ADVANCED']['sigDigitsExample']['field'] = array(
                'name' => 'sigDigitsExample',
            );

            if ($templateVars['IS_ADMIN']) {
                $result['LBL_ADVANCED']['ut']['field'] = array(
                    'name' => 'ut',
                );
            }

            $result['LBL_ADVANCED']['num_grp_sep']['field'] = array(
                'name' => 'num_grp_sep',
            );

            $result['LBL_ADVANCED']['dec_sep']['field'] = array(
                'name' => 'dec_sep',
            );

            //===  calendar_options ===
            $result['LBL_ADVANCED']['calendar_publish_key']['field'] = array(
                'name' => 'calendar_publish_key',
            );

            $result['LBL_ADVANCED']['cal_pub_key_span']['field'] = array(
                'name' => 'cal_pub_key_span',
            );

            $result['LBL_ADVANCED']['search_pub_key_span']['field'] = array(
                'name' => 'search_pub_key_span',
            );

            $result['LBL_ADVANCED']['ical_pub_key_span']['field'] = array(
                'name' => 'ical_pub_key_span',
            );

            $result['LBL_ADVANCED']['fdow']['field'] = array(
                'name' => 'fdow',
            );
        }

        //===  google_options ===
        if (!isset($templateVars['HIDE_IF_GAUTH_UNCONFIGURED']) && 'none' !== $templateVars['HIDE_IF_GAUTH_UNCONFIGURED']) {
            $result['LBL_ADVANCED']['google_api_token']['field'] = array(
                'name' => 'google_api_token',
            );
        }

        if ($templateVars['ID']) {
            $result['LBL_EAPM_SUBPANEL_TITLE'] = array();
        }

        $result['LBL_LAYOUT_OPTIONS'] = array();
        if (!isset($templateVars['HIDE_FOR_GROUP_AND_PORTAL']) || 'none' !== $templateVars['HIDE_FOR_GROUP_AND_PORTAL']) {
            // ==== $MOD.LBL_SUBTHEME ====
            if (isset($templateVars['SUBTHEMES'])) {
                $result['LBL_LAYOUT_OPTIONS']['subtheme']['field'] = array(
                    'name' => 'subtheme',
                );
            }

            if (!isset($templateVars['DISPLAY_GROUP_TAB']) || 'none' !== $templateVars['DISPLAY_GROUP_TAB']) {
                $result['LBL_LAYOUT_OPTIONS']['use_group_tabs']['field'] = array(
                    'name' => 'use_group_tabs',
                );
            }
            $result['LBL_LAYOUT_OPTIONS']['tab_chooser']['field'] = array(
                'name' => 'tab_chooser',
            );

            $result['LBL_LAYOUT_OPTIONS']['sort_modules_by_name']['field'] = array(
                'name' => 'sort_modules_by_name',
            );

            $result['LBL_LAYOUT_OPTIONS']['user_subpanel_tabs']['field'] = array(
                'name' => 'user_subpanel_tabs',
            );

            $result['LBL_LAYOUT_OPTIONS']['user_count_collapsed_subpanels']['field'] = array(
                'name' => 'user_count_collapsed_subpanels',
            );
        }

        return $result;
    }

    public function getEditCustomVardefs($templateVars)
    {
        global $sugar_config, $locale, $app_list_strings;

        $result = array();

        // ==== $MOD.LBL_CHANGE_PASSWORD_TITLE ====
        if (!$templateVars['IS_ADMIN'] || $templateVars['ADMIN_EDIT_SELF']) {
            $result['old_password'] = array(
                'name' => 'old_password',
                'vname' => 'LBL_OLD_PASSWORD',
                'type' => 'password',
                'value' => '',
            );
        }

        $result['new_password'] = array(
            'name' => 'new_password',
            'vname' => 'LBL_NEW_PASSWORD',
            'type' => 'password',
            'value' => '',
        );
        if ($templateVars['REQUIRED_PASSWORD']) {
            $result['new_password']['required'] = true;
        }
        if ($templateVars['PWDSETTINGS']) {
            $result['new_password']['pwd_settings'] = $templateVars['PWDSETTINGS'];
        }
        if ($templateVars['REGEX']) {
            $result['new_password']['REGEX'] = $templateVars['REGEX'];
        }

        $result['confirm_new_password'] = array(
            'name' => 'confirm_new_password',
            'vname' => 'LBL_CONFIRM_PASSWORD',
            'type' => 'password',
            'value' => '',
        );

        // ==== $MOD.LBL_THEME ====
        if (!isset($templateVars['HIDE_FOR_GROUP_AND_PORTAL']) || 'none' !== $templateVars['HIDE_FOR_GROUP_AND_PORTAL']) {
            $themeOptions = array();
            foreach (SugarThemeRegistry::availableThemes() as $themeName => $themeLabel) {
                $themeOptions[$themeName] = array(
                    'label' => $themeLabel,
//                    'preview' => ,  // TODO: finish it
                );
            }
            $result['user_theme_picker'] = array(
                'name' => 'user_theme_picker',
                'vname' => '',
                'type' => 'Users-EditView-user_theme_picker',
                'value' => $templateVars['bean']->getPreference('user_theme'),
                'options' => $themeOptions,
            );

            $result['export_delimiter'] = array(
                'name' => 'export_delimiter',
                'vname' => 'LBL_EXPORT_DELIMITER',
                'type' => 'varchar',
                'value' => $templateVars['EXPORT_DELIMITER'],
            );

            $result['default_export_charset'] = array(
                'name' => 'default_export_charset',
                'vname' => 'LBL_EXPORT_CHARSET',
                'type' => 'enum',
                'value' => $templateVars['EXPORT_CHARSET_DISPLAY'],
                'options' => translate('charset_dom'),
            );

            $result['receive_notifications'] = array(
                'name' => 'receive_notifications',
                'vname' => 'LBL_RECEIVE_NOTIFICATIONS',
                'type' => 'bool',
                'value' => (bool)$templateVars['RECEIVE_NOTIFICATIONS'],
            );

            $result['reminders'] = array(
                'name' => 'reminders',
                'vname' => 'LBL_REMINDER',
                'type' => 'Users-EditView-reminders',
                'value' => array(
                    'popup' => array(
                        'isEnable' => (bool)$templateVars['REMINDER_CHECKED'],
                        'selectedTime' => $templateVars['REMINDER_TIME'],
                    ),
                    'emailInvitees' => array(
                        'isEnable' => (bool)$templateVars['EMAIL_REMINDER_CHECKED'],
                        'selectedTime' => $templateVars['EMAIL_REMINDER_TIME'],
                    ),
                ),
                'options' => $templateVars['REMINDER_TIME_OPTIONS'],
            );

            $result['use_real_names'] = array(
                'name' => 'use_real_names',
                'vname' => 'LBL_USE_REAL_NAMES',
                'type' => 'bool',
                'value' => (bool)$templateVars['USE_REAL_NAMES'],  // null|"CHECKED"
            );

            $result['mailmerge_on'] = array(
                'name' => 'mailmerge_on',
                'vname' => 'LBL_MAILMERGE',
                'type' => 'bool',
                'value' => (bool)$templateVars['MAILMERGE_ON'],
            );

            if (!empty($templateVars['EXTERNAL_AUTH_CLASS']) && !empty($templateVars['IS_ADMIN'])) {
                $result['external_auth_only'] = array(
                    'name' => 'external_auth_only',
                    'vname' => 'EXTERNAL_AUTH_CLASS',
                    'type' => 'bool',
                    'value' => (bool)$templateVars['EXTERNAL_AUTH_ONLY_CHECKED'],  // null|"CHECKED"
                );
            }
            // ==== time/dateformat ====
            $result['dateformat'] = array(
                'name' => 'dateformat',
                'vname' => 'LBL_DATE_FORMAT',
                'type' => 'enum',
                'options' => $sugar_config['date_formats'],
                'value' => $locale->getPrecedentPreference(
                    $templateVars['bean']->id ? 'datef' : 'default_date_format',
                    $templateVars['bean']
                ),
            );

            $result['timezone'] = array(
                'name' => 'timezone',
                'vname' => 'LBL_TIMEZONE',
                'type' => 'enum',
                'value' => $templateVars['TIMEZONE_CURRENT'],
                'options' => $templateVars['TIMEZONEOPTIONS'],
            );

            $result['timeformat'] = array(
                'name' => 'timeformat',
                'vname' => 'LBL_TIME_FORMAT',
                'type' => 'enum',
                'options' => $sugar_config['time_formats'],
                'value' => $locale->getPrecedentPreference(
                    $templateVars['bean']->id ? 'timef' : 'default_time_format', $templateVars['bean']),
            );

            // ==== currency ====
            $result['currency'] = array(
                'name' => 'currency',
                'vname' => 'LBL_CURRENCY',
            );

            $result['currency'] = $this->fieldFunctions->implement($result['currency'], $templateVars['bean'], 'getCurrencyDropDown');


            $result['default_currency_significant_digits'] = array(
                'name' => 'default_currency_significant_digits',
                'vname' => 'LBL_CURRENCY_SIG_DIGITS',
                'type' => 'enum',
                'value' => $locale->getPrecedentPreference('default_currency_significant_digits', $templateVars['bean']),
                'options' => array(0, 1, 2, 3, 4, 5, 6),
            );

            $result['sigDigitsExample'] = array(
                'name' => 'sigDigitsExample',
                'vname' => 'LBL_LOCALE_EXAMPLE_NAME_FORMAT',
                'type' => 'Users-EditView-sigDigitsExample',
                'value' => 123456789,
            );

            if ($templateVars['IS_ADMIN']) {
                $result['ut'] = array(
                    'name' => 'ut',
                    'vname' => 'LBL_PROMPT_TIMEZONE',
                    'type' => 'bool',
                    'value' => (bool)$templateVars['PROMPTTZ'],  // null|"CHECKED"
                );
            }

            $result['num_grp_sep'] = array(
                'name' => 'num_grp_sep',
                'vname' => 'LBL_NUMBER_GROUPING_SEP',
                'type' => 'varchar',
                'value' => $templateVars['NUM_GRP_SEP'],
            );

            $nformat = $locale->getPrecedentPreference('default_locale_name_format', $templateVars['bean']);
            if (!array_key_exists($nformat, $sugar_config['name_formats'])) {
                $nformat = $sugar_config['default_locale_name_format'];
            }

            $result['default_locale_name_format'] = array(
                'name' => 'default_locale_name_format',
                'vname' => 'LBL_LOCALE_DEFAULT_NAME_FORMAT',
                'type' => 'enum',
                'value' => $nformat,
                'options' => $locale->getUsableLocaleNameOptions($sugar_config['name_formats']),
            );

            $result['dec_sep'] = array(
                'name' => 'dec_sep',
                'vname' => 'LBL_DECIMAL_SEP',
                'type' => 'name',
                'value' => $templateVars['DEC_SEP'],
            );

            // ==== $MOD.LBL_CALENDAR_OPTIONS ====
            $result['calendar_publish_key'] = array(
                'name' => 'calendar_publish_key',
                'vname' => 'LBL_PUBLISH_KEY',
                'type' => 'varchar',
                'value' => $templateVars['CALENDAR_PUBLISH_KEY'],
            );

            $result['cal_pub_key_span'] = array(
                'name' => 'cal_pub_key_span',
                'vname' => 'LBL_YOUR_PUBLISH_URL',
                'type' => 'name',
                'readonly' => true,
                'value' => $templateVars['CALENDAR_PUBLISH_KEY'] ? strip_tags($templateVars['CALENDAR_PUBLISH_URL']) : translate('LBL_NO_KEY'),
            );

            $result['search_pub_key_span'] = array(
                'name' => 'search_pub_key_span',
                'vname' => 'LBL_SEARCH_URL',
                'type' => 'name',
                'readonly' => true,
                'value' => $templateVars['CALENDAR_PUBLISH_KEY'] ? strip_tags($templateVars['CALENDAR_SEARCH_URL']) : translate('LBL_NO_KEY'),
            );

            $result['ical_pub_key_span'] = array(
                'name' => 'ical_pub_key_span',
                'vname' => 'LBL_ICAL_PUB_URL',
                'type' => 'name',
                'readonly' => true,
                'value' => $templateVars['CALENDAR_PUBLISH_KEY'] ? strip_tags($templateVars['CALENDAR_ICAL_URL']) : translate('LBL_NO_KEY'),
            );

            $fdowDays = array();
            foreach ($app_list_strings['dom_cal_day_long'] as $d) {
                if ($d != "") {
                    $fdowDays[] = $d;
                }
            }
            $currentFDOW = $templateVars['bean']->get_first_day_of_week();
            if (!isset($currentFDOW)) {
                $currentFDOW = 0;
            }
            $result['fdow'] = array(
                'name' => 'fdow',
                'vname' => 'LBL_FDOW',
                'type' => 'enum',
                'value' => $currentFDOW,
                'options' => $fdowDays,
            );

            // ==== $MOD.LBL_SUBTHEME ====
            if (isset($templateVars['SUBTHEMES'])) {
                $result['subtheme'] = array(
                    'name' => 'subtheme',
                    'vname' => 'LBL_SUBTHEME',
                    'type' => 'enum',
                    'value' => $templateVars['SUBTHEME'],
                    'options' => $templateVars['SUBTHEMES']
                );
            }

            if (!isset($templateVars['DISPLAY_GROUP_TAB']) || 'none' !== $templateVars['DISPLAY_GROUP_TAB']) {
                if ($templateVars['USE_GROUP_TABS']) {
                    $useGrpValue = 'gm';
                } else {
                    $useGrpValue = 'm';
                }
                $result['use_group_tabs'] = array(
                    'name' => 'use_group_tabs',
                    'vname' => 'LBL_USE_GROUP_TABS',
                    'type' => 'Users-EditView-use_group_tabs',
                    'value' => $useGrpValue,
                );
            }

            $result['tab_chooser'] = array(
                'name' => 'tab_chooser',
                'vname' => 'tab_chooser',
                'type' => 'Users-EditView-tab_chooser',
                'value' => $this->getUserTabChooserOptions($templateVars),
            );
            $result['sort_modules_by_name'] = array(
                'name' => 'sort_modules_by_name',
                'vname' => 'LBL_SORT_MODULES',
                'type' => 'bool',
                'value' => (bool)$templateVars['SORT_MODULES_BY_NAME'],
            );
            $result['user_subpanel_tabs'] = array(
                'name' => 'user_subpanel_tabs',
                'vname' => 'LBL_SUBPANEL_TABS',
                'type' => 'bool',
                'value' => (bool)$templateVars['SUBPANEL_TABS'],
            );
            $result['user_count_collapsed_subpanels'] = array(
                'name' => 'user_count_collapsed_subpanels',
                'vname' => 'LBL_COUNT_COLLAPSED_SUBPANELS',
                'type' => 'bool',
                'value' => (bool)$templateVars['COUNT_COLLAPSED_SUBPANELS'],
            );
        }

//            ===  google_options ===
        if (!isset($templateVars['HIDE_IF_GAUTH_UNCONFIGURED']) && 'none' !== $templateVars['HIDE_IF_GAUTH_UNCONFIGURED']) {
            $result['google_api_token'] = array(
                'name' => 'google_api_token',
                'vname' => 'LBL_GOOGLE_API_TOKEN',
                'type' => 'Users-EditView-google_api_token',
                'value' => $templateVars['GOOGLE_API_TOKEN'],
            );
        }

        // ==== $MOD.LBL_MAIL_OPTIONS_TITLE ====
        $raw_email_link_type = $templateVars['bean']->getPreference('email_link_type');
        $result['email_link_type'] = array(
            'name' => 'email_link_type',
            'vname' => 'LBL_EMAIL_LINK_TYPE',
            'type' => 'enum',
            'options' => $app_list_strings['dom_email_link_type'],
            'value' => $raw_email_link_type,
        );
        $rawEditorType = $templateVars['bean']->getEditorType();
        $result['editor_type'] = array(
            'name' => 'editor_type',
            'vname' => 'LBL_EDITOR_TYPE',
            'type' => 'enum',
            'options' => $app_list_strings['dom_editor_type'],
            'value' => $rawEditorType,
        );

        return $result;
    }

    /**
     * List of hidden and available modules for the TabChooser field.
     * @param $templateVars
     * @return array
     * @see modules/Users/UserViewHelper.php :: setupAdvancedTabNavSettings()
     */
    public function getUserTabChooserOptions($templateVars)
    {
        global $app_list_strings;

        require_once(get_custom_file_if_exists('include/templates/TemplateGroupChooser.php'));
        require_once(get_custom_file_if_exists('modules/MySettings/TabController.php'));
        $chooser = new TemplateGroupChooser();
        $controller = new TabController();


        if ($templateVars['IS_ADMIN'] || $controller->get_users_can_edit()) {
            $chooser->display_hide_tabs = true;
        } else {
            $chooser->display_hide_tabs = false;
        }
        $result = array(
            'displayedFields' => array(),
            'hiddenFields' => array(),
            'removedFields' => array(),
        );
        $chooser->args['id'] = 'edit_tabs';
        $chooser->args['values_array'] = $controller->get_tabs($templateVars['bean']);
        foreach ($chooser->args['values_array'][0] as $key => $value) {
            $result['displayedFields'][] = [$key, $app_list_strings['moduleList'][$key]];
        }

        foreach ($chooser->args['values_array'][1] as $key => $value) {
            $result['hiddenFields'][] = [$key, $app_list_strings['moduleList'][$key]];
        }

        foreach ($chooser->args['values_array'][2] as $key => $value) {
            $result['removed'][] = [$key, $app_list_strings['moduleList'][$key]];
        }

        return $result;
    }





}
