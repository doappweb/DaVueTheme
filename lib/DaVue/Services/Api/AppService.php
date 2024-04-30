<?php

namespace SuiteCRM\DaVue\Services\Api;

use BeanFactory;
use Exception;
use SugarFieldHandler;
use SuiteCRM\DaVue\Domain\Modules\VCalsHandler;
use SuiteCRM\DaVue\Services\Common\Application;

class AppService
{
    /** @var Application $application */
    private $application;

    /** @var VCalsHandler $vCalsHandler */
    private $vCalsHandler;

    public function __construct(Application $application, VCalsHandler $vCalsHandler)
    {
        $this->application = $application;
        $this->vCalsHandler = $vCalsHandler;
    }

    /**
     * Proxy method for one-time data retrieval when loading an application
     *
     * @usage http://localhost/app/?method=getApp&action=Login&module=Users&VueAjax=1
     */
    public function getApp(array $arg = []): array
    {
        return [
            'app'     => $this->application->getSystem($arg),
            'modules' => $this->application->getModules(),
            'menu'    => $this->application->getMenu(),
            'user'    => $this->application->getUser(),
        ];
    }

    /**
     * @usage http://localhost/app/?VueAjax=true&method=getUserThemeSettings&module=Home&action=ListView
     * @param $args
     * @return array|false
     */
    public function getUserThemeSettings($args)
    {
        global $current_user, $db;
        $sql = "
            SELECT contents
            FROM user_preferences
            WHERE assigned_user_id = '{$current_user->id}' AND category = 'da_theme_settings' AND deleted = 0
        ";

        $content = json_decode(base64_decode($db->getOne($sql)), true);
        if (!is_array($content)){
            return false;
        }

        return $content;
    }

    /**
     * @usage http://localhost/app/?VueAjax=true&method=setUserThemeSettings&module=Home&action=ListView
     * @return string
     */
    public function setUserThemeSettings(): string
    {
        global $current_user, $db;

        $content = base64_encode(htmlspecialchars_decode($_POST['content']));
        $date = gmdate("Y-m-d H:i:s", time());

        $sql = "
            SELECT id
            FROM user_preferences
            WHERE assigned_user_id = '{$current_user->id}' AND category = 'da_theme_settings' AND deleted = 0
        ";
        $id = $db->getOne($sql);
        if (false !== $id) {
            $sql = "
                UPDATE user_preferences 
                SET contents = '{$content}', date_modified = '{$date}'
                WHERE id = '{$id}'
                    AND deleted = 0
            ";
        } else {
            $params = array(
                'id'                => "'". create_guid() ."'",
                'category'          => "'da_theme_settings'",
                'deleted'           => 0,
                'date_entered'      => "'". $date  ."'",
                'date_modified'     => "'". $date  ."'",
                'assigned_user_id'  => "'". $current_user->id ."'",
                'contents'          => "'". $content ."'"
            );

            $keys = implode(',', array_keys($params));
            $values = implode(',', array_values($params));
            $sql = "INSERT INTO user_preferences ($keys) VALUES ($values)";
        }

        return $db->query($sql);
    }

    /**
     * 289-SFR
     * AdditionalDetailsRetrieve - response in JSON format
     *
     * @usage http://localhost/index.php?VueAjax=1&method=getAdditionalDetails&arg[]...
     * @param $args
     * @return array
     */
    public function getAdditionalDetails($args)
    {
        $retArray = array();

        global $beanList, $beanFiles, $current_user, $app_strings, $app_list_strings;

        $moduleDir = empty($args['bean']) ? '' : $args['bean'];
        $beanName = empty($beanList[$moduleDir]) ? '' : $beanList[$moduleDir];
        $id = empty($args['id']) ? '' : $args['id'];

        $additionalDetailsFile = 'modules/' . $moduleDir . '/metadata/additionalDetails.php';
        if (file_exists('custom/'.$additionalDetailsFile)) {
            $additionalDetailsFile = 'custom/'.$additionalDetailsFile;
        }

        if (
            empty($beanFiles[$beanName]) ||
            empty($id) ||
            !is_file($additionalDetailsFile)
        ) {
            return array();
        }

        require_once($additionalDetailsFile);
        $adFunction = 'additionalDetails' . $beanName;

        if (function_exists($adFunction)) {
            $json = getJSONobj();
            $bean = new $beanName();
            $bean->retrieve($id);

            //bug38901 - shows dropdown list label instead of database value
            foreach ($bean->field_name_map as $field => $value) {
                if ($value["type"] == "enum" && isset($app_list_strings[$value['options']][$bean->$field])) {
                    $bean->$field = $app_list_strings[$value['options']][$bean->$field];
                }
            }

            $arr = array_change_key_case($bean->toArray(), CASE_UPPER);

            $results = $adFunction($arr, $bean, $args);

            $retArray = array();
            $retArray['body'] = str_replace(array("\rn", "\r", "\n"), array('','','<br />'), $results['string']);

            if ($bean->ACLAccess('EditView')) {
                $retArray['editLink'] = true;
            }
            if ($bean->ACLAccess('DetailView')) {
                $retArray['viewLink'] = true;
            }
        }

        return $retArray;
    }

    /**
     * Get data to display the "Change Log"
     *
     * @throws Exception
     * @usage http://localhost/index.php?VueAjax=1&method=getAuditPopupPicker&arg[]...
     * @see modules/Audit/Popup_picker.php :: process_page()
     * @param $args
     * @return array
     */
    public function getAuditPopupPicker($args): array
    {
        if (empty($args['record']) || empty($args['module_name'])) {
            throw new Exception("The module_name and/or record parameter is not specified");
        }
        $recordId = $args['record'];
        $moduleName = $args['module_name'];

        $focus = BeanFactory::getBean($moduleName);
        $focus = $focus->retrieve($recordId);
        if (null === $focus) {
            throw new Exception("Couldn't get the bean for the module: " . htmlspecialchars($moduleName) . ", record: " . htmlspecialchars($recordId));
        }

        $auditObject = BeanFactory::newBean('Audit');
        $GLOBALS['focus'] = $focus;  // Because get_audit_list() expects focus as global
        $auditList =  $auditObject->get_audit_list();
        foreach ($auditList as &$audit) {
            if (empty($audit['before_value_string']) && !empty($audit['before_value_text'])) {
                $audit['before_value_string'] = $audit['before_value_text'];
            }
            if (empty($audit['after_value_string']) && !empty($audit['after_value_text'])) {
                $audit['after_value_string'] = $audit['after_value_text'];
            }

            // Let's run the audit data through the sugar field system
            if (isset($audit['data_type'])) {
                $vardef = array('name'=>'audit_field','type'=>$audit['data_type']);
                $field = SugarFieldHandler::getSugarField($audit['data_type']);
                $audit['before_value_string'] = $field->getChangeLogSmarty(
                    array($vardef['name'] => $audit['before_value_string']),
                    $vardef,
                    array(),
                    $vardef['name']
                );
                $audit['after_value_string'] = $field->getChangeLogSmarty(
                    array($vardef['name'] => $audit['after_value_string']),
                    $vardef,
                    array(),
                    $vardef['name']
                );
            }
        }

        // List of fields for which changes are tracked in the current module
        $auditedFields = $focus->getAuditEnabledFieldDefinitions();
        asort($auditedFields);
        $auditedFieldNames = array();
        if (count($auditedFields) > 0) {
            foreach ($auditedFields as $fieldName => $field) {
                $vname = '';
                if (isset($field['vname'])) {
                    $vname = $field['vname'];
                } elseif (isset($field['label'])) {
                    $vname = $field['label'];
                }
                $auditedFieldNames[$fieldName] = str_replace(':', '', $vname);
            }
        }

        $result = array(
            'type' => 'Audit',
            'header' => array(
                'show' => true,
                'data' => array(
                    'title' => 'LBL_CHANGE_LOG',
                ),
            ),
            'body' => array(
                'show' => true,
                'data' => array(
                    'pageData' => array(
                        'auditedFieldNames' => $auditedFieldNames,
                    ),
                    'viewData' => array(
                        'displayColumns' => array(
                            'field_name' => array(
                                'label' => 'LBL_FIELD_NAME'
                            ),
                            'before_value_string' => array(
                                'label' => 'LBL_OLD_NAME'
                            ),
                            'after_value_string' => array(
                                'label' => 'LBL_NEW_VALUE'
                            ),
                            'created_by' => array(
                                'label' => 'LBL_CREATED_BY'
                            ),
                            'date_created' => array(
                                'label' => 'LBL_LIST_DATE'
                            ),
                        ),
                        'data' => $auditList,
                    ),
                ),
            ),
            'footer' => array(
                'show' => false,
                'data' => array(),
            ),
        );

        return $result;
    }


    /**
     * Accepts an array of participants that the user is going to add to a meeting/call,
     * and returns an array of busyness for each participant
     *
     * @usage http://localhost:3000/index.php?VueAjax=1&method=getVCalFreeBusyByIds&args[]...
     * @see modules/vCals/vCal.php :: get_vcal_freebusy() - getting user activity
     * @param array $args
     * @return array
     */
    public function getVCalFreeBusyByIds(array $args): array
    {
        global $timedate, $current_user;

        $result = $_REQUEST['args'];

        foreach ($result as &$participant) {
            if ('Users' !== $participant['moduleName']) {
                $participant['freebusy'] = array();
                continue;
            }

            $participant['freebusy'] = $this->vCalsHandler->getUserEmployment($participant['recordId']);
        }

        return $result;
    }
}
