<?php

namespace SuiteCRM\DaVue\Services\Api;

use ACLController;
use BeanFactory;
use SuiteCRM\DaVue\Services\Common\FieldFunctions;

class MassUpdateService
{
    /** @var FieldFunctions $fieldFunctions */
    private $fieldFunctions;

    public function __construct(FieldFunctions $fieldFunctions)
    {
        $this->fieldFunctions = $fieldFunctions;
    }

    /**
     * Get data for massUpdate form on a ListView
     *
     * @usage http://localhost/index.php?action=ListView&module=Accounts&VueAjax=1&method=getMassUpdateForm
     * @see include/MassUpdate.php::getMassUpdateForm()
     * @return array
     */
    public function getMassUpdateForm(): array
    {
        global $app_strings, $current_user;

        // TODO: In the original, this is a method argument. Find out in what cases it should be true
        $hideDeleteIfNoFieldsAvailable = false;

        $result = array();
        $resultFields = array();
        $resultButtons = array();

        $moduleBean = BeanFactory::getBean($_REQUEST['module']);
        if (false === $moduleBean) {
            return $result;
        }

        if (
            $moduleBean->bean_implements('ACL')
            && (
                !ACLController::checkAccess($moduleBean->module_dir,'edit',true)
                || !ACLController::checkAccess($moduleBean->module_dir,'massupdate',true)
            )
        ) {
            return $result;
        }

        //======================================
        // Imitating original Kludge: when processing listview, it connects a script that hardcodes the attributes of some vardefs
        // @see data/SugarBean.php::create_new_list_query() - entry point
        // @see include/VarDefHandler/listvardefoverride.php - script
        if (isset($moduleBean->field_defs['assigned_user_name'])) {
            $moduleBean->field_defs['assigned_user_name'] = array_merge(
                $moduleBean->field_defs['assigned_user_name'],
                array(
                    'name' => 'assigned_user_name',
                    'rname'=>'user_name',
                    'vname' => 'LBL_ASSIGNED_TO',
                    'type' => 'relate',
                    'reportable' => false,
                    'source' => 'non-db',
                    'link' => 'assigned_user_link',
                    'id_name' => 'assigned_user_id',
                )
            );
        }
        if (isset($moduleBean->field_defs['created_by'])) {
            if (!isset($moduleBean->field_defs['created_by_name'])) {
                $moduleBean->field_defs['created_by_name'] = array();
            }
            $moduleBean->field_defs['created_by_name'] = array_merge(
                $moduleBean->field_defs['created_by_name'],
                array(
                    'name' => 'created_by_name',
                    'rname'=>'user_name',
                    'vname' => 'LBL_CREATED',
                    'type' => 'relate',
                    'reportable' => false,
                    'source' => 'non-db',
                    'link' => 'created_by_link'
                )
            );
        }
        if (isset($moduleBean->field_defs['modified_user_id'])) {
            if (!isset($moduleBean->field_defs['modified_by_name'])) {
                $moduleBean->field_defs['modified_by_name'] = array();
            }
            $moduleBean->field_defs['modified_by_name'] = array_merge(
                $moduleBean->field_defs['modified_by_name'],
                array(
                    'name' => 'modified_by_name',
                    'rname' => 'user_name',
                    'vname' => 'LBL_MODIFIED',
                    'type' => 'relate',
                    'reportable' => false,
                    'source' => 'non-db',
                    'link' => 'modified_user_link'
                )
            );
        }
        //======================================

        $field_count = 0;

        //These fields should never appear on mass update form
        static $banned = array(
            'date_modified' => 1,
            'date_entered' => 1,
            'created_by' => 1,
            'modified_user_id' => 1,
            'deleted' => 1,
            'modified_by_name' => 1,
            'assigned_user_id' => 1,  // as main field - assigned_user_name
        );

        if ($moduleBean->object_name == 'Contact') {
            /********** This field is in original, but its behavior is not implemented. Temporarily removed so that everything else could work **********
            $resultFields[] = array(
                'name' => 'Sync',
                'vname' => 'LBL_SYNC_CONTACT',
                'type' => 'enum',
                'options' => array(
                    'false' => $GLOBALS['app_list_strings']['checkbox_dom']['2'],
                    'true' => $GLOBALS['app_list_strings']['checkbox_dom']['1'],
                ),
            );
             **********/
        } elseif ($moduleBean->object_name == 'Employee' || $moduleBean->object_name == 'User') {
            //The Kludge for the employee_status field in the original
            // @see include/MassUpdate.php,
            // @see include/SearchForm/SearchForm2.php::_build_field_defs()
            $moduleBean->field_defs['employee_status']['type'] = 'enum';
            $moduleBean->field_defs['employee_status']['massupdate'] = true;
            $moduleBean->field_defs['employee_status']['options'] = 'employee_status_dom';
            unset($moduleBean->field_defs['employee_status']['function']);
        } elseif ($moduleBean->object_name == 'InboundEmail') {
            $moduleBean->field_defs['status']['type'] = 'enum';
            $moduleBean->field_defs['status']['options'] = 'user_status_dom';
        }

        foreach ($moduleBean->field_defs as $fieldName => $field) {

            // Different logic for fields with a "function" attribute
            if (isset($field['function'])) {
                $moduleBean->field_defs[$fieldName] = $this->fieldFunctions->implement($field, $moduleBean);
            }

            if (!isset($banned[$field['name']]) && (!isset($field['massupdate']) || !empty($field['massupdate']))) {

                if (isset($field['custom_type'])) {
                    $field['type'] = $field['custom_type'];
                }

                if (isset($field['type'])) {
                    switch ($field["type"]) {
                        case "int":
                            if (!empty($field['massupdate']) && empty($field['auto_increment'])) {
                                $resultFields[] = $field;
                            }
                            break;
                        case "relate":
                            // In the original there was a kludge for the relate type: the field handler method could
                            // return an empty string instead of html, then it would not be added to the form
                            if (isset($field['module'])) {
                                switch ($field['module']) {
                                    case 'Users':
                                    case 'Employee':
                                    case 'Accounts':
                                    case 'Contacts':
                                    case 'Releases':
                                        $resultFields[] = $field;
                                        break;
                                    default:
                                        if (!empty($field['massupdate'])) {
                                            $resultFields[] = $field;
                                        }
                                        break;
                                }
                            }
                            break;
                        case "parent":
                            if (!empty($field['options']) && is_string($field['options'])) {
                                $translatedOptions = translate($field['options']);
                                $field['options'] = $translatedOptions;
                                $moduleBean->field_defs[$fieldName]['options'] = $translatedOptions;
                                $moduleBean->field_defs[$field['type_name']]['options'] = $translatedOptions;
                            }
                            $resultFields[] = $field;
                            break;
                        case "contact_id":
                        case "assigned_user_name":
                        case "account_id":
                        case "account_name":
                        case "bool":
                        case "datetimecombo":
                        case "datetime":
                        case "date":
                            $resultFields[] = $field;
                            break;
                        case "enum":
                        case "dynamicenum":
                        case "multienum":
                        case "radioenum":
                            if (!empty($field['options']) && is_string($field['options'])) {
                                $translatedOptions = translate($field['options']);

                                $additionalOptions = array(
                                    '' => translate('LBL_NONE'),
                                    '__SugarMassUpdateClearField__' => '',
                                );
                                unset($translatedOptions['']);
                                $translatedOptions = array_merge($additionalOptions, $translatedOptions);

                                $field['options'] = $translatedOptions;
                                $moduleBean->field_defs[$fieldName]['options'] = $translatedOptions;
                            }
                            $resultFields[] = $field;
                            break;
                        default:
                            break;
                    }
                }
                $field_count++;
            }
        }

        /********** Temporarily removed **********
        if (in_array($moduleBean->object_name, array('Contact', 'Account', 'Lead', 'Prospect'))) {
            $optOutPrimaryEmail = array(
                'name' => 'optout_primary',
                'vname' => 'LBL_OPT_OUT_FLAG_PRIMARY',
                'type' => 'function',  // actually enum. It's done to avoid processing at the frontend for now
                'options' => array(
                    'false' => $GLOBALS['app_list_strings']['checkbox_dom']['2'],
                    'true' => $GLOBALS['app_list_strings']['checkbox_dom']['1'],
                ),
            );
            $resultFields[] = $optOutPrimaryEmail;

            $configurator = new Configurator();
            if ($configurator->isConfirmOptInEnabled() || $configurator->isOptInEnabled()) {
                $optInPrimaryEmail = array(
                    'name' => 'optin_primary',
                    'vname' => 'LBL_OPT_IN_FLAG_PRIMARY',
                    'type' => 'function',  // actually enum. It's done to avoid processing at the frontend for now
                    'options' => array(
                        'false' => $GLOBALS['app_list_strings']['checkbox_dom']['2'],
                        'true' => $GLOBALS['app_list_strings']['checkbox_dom']['1'],
                    ),
                );
                $resultFields[] = $optInPrimaryEmail;
            }
        }
         **********/

        if ($field_count > 0) {
            $resultButtons[] = array(
                'id' => 'update_button',
                'type' => 'submit',
                'name' => 'Update',
                'value' => translate('LBL_UPDATE'),
                'onclick' => "return sListView.send_mass_update(\"selected\", \"{$app_strings['LBL_LISTVIEW_NO_SELECTED']}\")",
            );
            $resultButtons[] = array(
                'id' => 'cancel_button',
                'type' => 'button',
                'name' => 'Cancel',
                'value' => $GLOBALS['app_strings']['LBL_CANCEL_BUTTON_LABEL'],
                'onclick' => "javascript:toggleMassUpdateForm();",
            );

            // only for My Inbox views - to allow CSRs to have an "Archive" emails feature to get the email "out" of their inbox.
            if (
                $moduleBean->object_name == 'Email'
                && (isset($_REQUEST['assigned_user_id']) && !empty($_REQUEST['assigned_user_id']))
                && (isset($_REQUEST['type']) && !empty($_REQUEST['type']) && $_REQUEST['type'] == 'inbound')
            ) {
                $resultButtons[] = array(
                    'id' => null,
                    'type' => 'button',
                    'name' => 'archive',
                    'value' => translate('LBL_ARCHIVE'),
                    'onclick' => "setArchived();",
                );
                $resultButtons[] = array(
                    'id' => null,
                    'type' => 'hidden',
                    'name' => 'ie_assigned_user_id',
                    'value' => "{$current_user->id}",
                );
                $resultButtons[] = array(
                    'id' => null,
                    'type' => 'hidden',
                    'name' => 'ie_type',
                    'value' => 'inbound',
                );
            }
        } else {
            // If fields are not found, display either a form that still allows bulk deletion, or simply display a message stating that the fields are not available.
            if ($moduleBean->ACLAccess('Delete', true) && !$hideDeleteIfNoFieldsAvailable) {
                $resultButtons[] = array(
                    'id' => null,
                    'name' => 'Delete',
                    'type' => 'submit',
                    'value' => translate('LBL_DELETE'),
                    'onclick' => "return confirm('".translate('NTC_DELETE_CONFIRMATION_MULTIPLE')."')",
                );
            }
        }

        $result = array(
            'pageData' => array(
                'actionButtons' => $resultButtons,
            ),
            'viewData' => array(
                'panelsFields' => $resultFields,
            ),
            'beanData' => $moduleBean->field_defs,
        );

        return $result;
    }

}
