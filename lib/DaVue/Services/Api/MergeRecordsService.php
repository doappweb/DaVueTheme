<?php

namespace SuiteCRM\DaVue\Services\Api;
use BeanFactory;
use Exception;
use ListViewSmarty;

/**
 * Functionality for merging duplicates
 */
class MergeRecordsService
{
    /**
     * Ajax-handler for all stages of combining duplicates. During operation, transfers control to child methods
     *
     *  If the main record was switched to another, arg: change_parent, change_parent_id, merged_ids[]
     *  If one of the records to be merged has been deleted, arg: remove, remove_id, merged_ids[]
     *  In the usual case. Id of non-main records, arg: merged_ids[]
     *
     * @usage http://localhost/index.php?VueAjax=1&method=mergeRecords&arg[]
     * @param array $args
     * @return array
     * @throws Exception
     */
    public function mergeRecords(array $args)
    {
        $action = $args['action'];
        $args['return_id'] = htmlspecialchars(remove_xss($args['return_id']), ENT_QUOTES | ENT_HTML5);
        $args['return_action'] = htmlspecialchars(remove_xss($args['return_action']), ENT_QUOTES | ENT_HTML5);
        $args['return_module'] = htmlspecialchars(remove_xss($args['return_module']), ENT_QUOTES | ENT_HTML5);

        $stepHandler = 'mergeRecords_' . $action;
        if (method_exists($this, $stepHandler)) {
            return $this->$stepHandler($args);
        } else {
            throw new Exception('Unknown action for the MergeRecords module');
        }
    }


    /**
     * Handler for the first step of merging duplicates - selecting similarity criteria
     *
     * @param $args
     * @return array
     * @see MergeRecords/Step1.php
     */
    private function mergeRecords_Step1($args)
    {
        global $sugar_config, $app_list_strings;

        //get instance of master record and retrieve related record and items
        $focus = BeanFactory::newBean('MergeRecords');
        $focus->merge_module = $args['return_module'];
        $focus->load_merge_bean($focus->merge_module, true, $args['record']);

        //get all available column fields
        $avail_fields = array();
        $sel_fields = array();
        $temp_field_array = $focus->merge_bean->field_defs;
        $bean_data = array();
        foreach ($temp_field_array as $field_def) {
            if (isset($field_def['merge_filter'])) {
                if (strtolower($field_def['merge_filter']) == 'enabled' or strtolower($field_def['merge_filter']) == 'selected') {
                    $col_name = $field_def['name'];

                    if (!isset($focus->merge_bean_strings[$field_def['vname']])) {
                        $col_label = $col_name;
                    } else {
                        $col_label = str_replace(':', '', $focus->merge_bean_strings[$field_def['vname']]);
                    }

                    if (strtolower($field_def['merge_filter']) == 'selected') {
                        $sel_fields[$col_name] = $col_label;
                    } else {
                        $avail_fields[$col_name] = $col_label;
                    }

                    $bean_data[$col_name] = $focus->merge_bean->$col_name;
                }
            }
        }

        //set the url
        $port = null;
        if (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] != 80 && $_SERVER['SERVER_PORT'] != 443) {
            $port = $_SERVER['SERVER_PORT'];
        }

        //process preloaded filter
        $pre_loaded = array();
        foreach ($sel_fields as $colName => $colLabel) {
            $pre_loaded[$colName] = array(
                'label' => $colLabel,
                'value' => $bean_data[$colName],
            );
        }

        $result = array(
            'pageData' => array(
                'recordName' => $focus->merge_bean->get_summary_text(),
                'module' => $focus->merge_module,
                'id' => $focus->merge_bean->id,
                'returnId' => $args['return_id'],
                'returnAction' => $args['return_action'],
                'returnModule' => $args['return_module'],
                'url' => appendPortToHost($sugar_config['site_url'], $port),
            ),
            'viewData' => array(
                'fieldAvailOptions' => $avail_fields,
                'operatorOptions' => $app_list_strings['merge_operators_dom'],
                'preLoadedFields' => $pre_loaded,
            ),
            'beanData' => $bean_data,
        );

        return $result;
    }


    /**
     * Handler for the second step of merging duplicates - selecting records to merge
     *
     * @param $args
     * @return array
     * @see MergeRecords/Step2.php
     */
    private function mergeRecords_Step2($args)
    {
        $returnModule = $args['return_module'];
        $focus = BeanFactory::newBean('MergeRecords');
        $focus->load_merge_bean($returnModule, true, $args['record']);
        $focus->populate_search_params($args);
        $where_clauses = $focus->create_where_statement();
        $where = $focus->generate_where_statement($where_clauses);

        $metadataFile = null;
        $foundViewDefs = false;
        if (file_exists('custom/modules/' . $returnModule . '/metadata/listviewdefs.php')) {
            $metadataFile = 'custom/modules/' . $returnModule . '/metadata/listviewdefs.php';
            $foundViewDefs = true;
        } else {
            if (file_exists('custom/modules/' . $returnModule . '/metadata/metafiles.php')) {
                require_once('custom/modules/' . $returnModule . '/metadata/metafiles.php');
                if (!empty($metafiles[$returnModule]['listviewdefs'])) {
                    $metadataFile = $metafiles[$returnModule]['listviewdefs'];
                    $foundViewDefs = true;
                }
            } elseif (file_exists('modules/' . $returnModule . '/metadata/metafiles.php')) {
                require_once('modules/' . $returnModule . '/metadata/metafiles.php');
                if (!empty($metafiles[$returnModule]['listviewdefs'])) {
                    $metadataFile = $metafiles[$returnModule]['listviewdefs'];
                    $foundViewDefs = true;
                }
            }
        }
        if (!$foundViewDefs && file_exists('modules/' . $returnModule . '/metadata/listviewdefs.php')) {
            $metadataFile = 'modules/' . $returnModule . '/metadata/listviewdefs.php';
        }

        /**
         * @var $listViewDefs
         */
        require_once($metadataFile);

        $displayColumns = array();
        if (!empty($args['displayColumns'])) {
            foreach (explode('|', $args['displayColumns']) as $num => $col) {
                if (!empty($listViewDefs[$returnModule][$col])) {
                    $displayColumns[$col] = $listViewDefs[$returnModule][$col];
                }
            }
        } else {
            foreach ($listViewDefs[$returnModule] as $col => $params) {
                if (!empty($params['default']) && $params['default']) {
                    $displayColumns[$col] = $params;
                }
            }
        }

        $params = array('massupdate' => true, 'export' => false, 'handleMassupdate' => false);
        $ListView = new ListViewSmarty();
        $ListView->should_process = true;
        $ListView->mergeduplicates = false;
        $ListView->export = false;
        $ListView->delete = false;
        $ListView->displayColumns = $displayColumns;
        $ListView->lvd->listviewName = $focus->merge_module; //27633, this will make the $module to be merge_module instead of 'MergeRecords'. Then the key of  offset and orderby will be correct.
        $where = $focus->generate_where_statement($focus->create_where_statement());
        $ListView->showMassupdateFields = false;
        $ListView->email = false;
        $ListView->setup($focus->merge_bean, 'include/ListView/ListViewGeneric.tpl', $where, $params);
        $ListView->force_mass_update = true;
        $ListView->show_mass_update_form = false;
        $ListView->show_export_button = false;
        $ListView->keep_mass_update_form_open = true;

        $result = array(
            'editViewLinksEnable' => !empty($ListView->quickViewLinks),
            "selectRecordsEnable" => (bool)$ListView->multiSelect,
            'recordName' => $focus->merge_bean->get_summary_text(),
            'pageData' => $ListView->data['pageData'],
            "selectedRecordsActions" => null,
            'viewData' => array(
                'displayColumns' => $ListView->displayColumns,
                'data' => $ListView->data['data'],
            ),
        );

        return $result;
    }


    /**
     * The handler for the third step of merging duplicates - selecting the final field values
     *
     * @param $args
     * @return array
     * @see modules/MergeRecords/Step3.php
     */
    private function mergeRecords_Step3($args)
    {
        global $app_strings;

        $displayColumns = array();
        $rows = array();
        $mergeRecordNames = array();
        $similarSectionFields = array();
        $diffSectionFields = array();

        // Array of mergeId records to merge with the current record
        $mergeIds = array();
        if (isset($args['change_parent']) && $args['change_parent'] == '1') {
            // If you previously selected another record on the page as the main record
            $baseRecordId = $args['change_parent_id'];
            foreach ($args['merged_ids'] as $id) {
                if ($id != $baseRecordId) {
                    $mergeIds[] = $id;
                }
            }
            $mergeIds[] = $args['record'];
        } elseif (isset($args['remove']) && $args['remove'] == '1') {
            // If one of the records to be merged from the list was previously removed from the page
            $baseRecordId = $args['record'];
            $removedId = $args['remove_id'];
            foreach ($args['merged_ids'] as $id) {
                if ($id != $removedId) {
                    $mergeIds[] = $id;
                }
            }
        } else {
            $baseRecordId = $args['record'];
            foreach ($args['mass'] as $id) {
                $mergeIds[] = $id;
            }
        }

        $focus = BeanFactory::newBean('MergeRecords');
        $focus->load_merge_bean($args['return_module'], true, $baseRecordId);

        // Collecting bean array for each of adjacent records from which it will be possible to obtain data in the future
        require_once($focus->merge_bean_file_path);
        $mergeBeanArray = array();
        foreach ($mergeIds as $mergeRecordId) {
            $mergeBeanArray[$mergeRecordId] = new $focus->merge_bean_class();
            $mergeBeanArray[$mergeRecordId]->retrieve($mergeRecordId);
            $mergeRecordNames[$mergeRecordId] = $mergeBeanArray[$mergeRecordId]->get_summary_text();
        }

        // process each of the fields
        $focusFieldDefs = $focus->merge_bean->field_defs;
        foreach ($focusFieldDefs as $field_def) {
            if ($this->isInvolvedInMerger($field_def)) {
                $fieldName = $field_def['name'];
                $fieldValue = $focus->merge_bean->$fieldName;

                // Which section should the field be assigned to: with identical or different values
                $haveDiffField = false;
                foreach ($mergeBeanArray as $mergeBean) {
                    if ($mergeBean->$fieldName != $fieldValue) {
                        $diffSectionFields[] = $fieldName;
                        $haveDiffField = true;
                        break;
                    }
                }
                if (false === $haveDiffField) {
                    $similarSectionFields[] = $fieldName;
                }

                if (isset($focus->merge_bean_strings[$field_def['vname']]) && $focus->merge_bean_strings[$field_def['vname']] != '') {
                    $fieldLabel = $focus->merge_bean_strings[$field_def['vname']];
                } elseif (isset($app_strings[$field_def['vname']]) && $app_strings[$field_def['vname']] != '') {
                    $fieldLabel = $app_strings[$field_def['vname']];
                } else {
                    $fieldLabel = $fieldName;
                }

                if (isset($field_def['custom_type']) && $field_def['custom_type'] != '') {
                    $fieldType = $field_def['custom_type'];
                } else {
                    $fieldType = $field_def['type'];
                }
                if (preg_match('/.*?_address_street$/', $fieldName)) {
                    $fieldType = 'text';
                }

                $fieldNameUpper = strtoupper($fieldName);
                $displayColumns[$fieldNameUpper] = $field_def;
                $displayColumns[$fieldNameUpper]['label'] = $fieldLabel;  // It is possible that label makes sense to look for at the frontend
                $displayColumns[$fieldNameUpper]['type'] = $fieldType;

                if (!empty($focus->merge_bean->required_fields[$fieldName]) || $fieldName === 'team_name') {
                    $displayColumns[$fieldNameUpper]['required'] = true;
                }

                switch ($fieldType) {
                    case ('bool'):
                        if (($fieldValue == '1' || $fieldValue == 'yes' || $fieldValue == 'on') && !empty($fieldValue)) {
                            $rows[0][$fieldNameUpper] = true;
                        } else {
                            $rows[0][$fieldNameUpper] = false;
                        }
                        break;
                    case ('name'):
                    case ('varchar'):
                    case ('phone'):
                    case ('num'):
                    case ('email'):
                    case ('custom_fields'):
                    case ('url'):
                    case ('int'):
                    case ('float'):
                    case ('double'):
                    case ('currency'):
                    case ('text'):
                    case ('date'):
                    case ('datetime'):
                    case ('datetimecombo'):
                        $rows[0][$fieldNameUpper] = $fieldValue;
                        break;
                    case ('enum'):
                        $displayColumns[$fieldNameUpper]['options'] = translate($displayColumns[$fieldNameUpper]['options'], $focus->merge_bean->moduleDir);
                        $rows[0][$fieldNameUpper] = $fieldValue;
                        break;
                    case ('multienum'):
                        $displayColumns[$fieldNameUpper]['options'] = translate($displayColumns[$fieldNameUpper]['options'], $focus->merge_bean->moduleDir);
                        $fieldValue = unencodeMultienum($fieldValue);
                        $rows[0][$fieldNameUpper] = $fieldValue;
                        break;
                    //popup fields need to be fixed.., cant automate with vardefs
                    case ('relate'):
                    case ('link'):
                        $tempId = $field_def['id_name'];
                        if (empty($fieldValue)) {
                            $related_name = $this->getRelatedNameByIdName($field_def, $focus->merge_bean->$tempId);
                            if ($related_name !== false) {
                                $fieldValue = $related_name;
                            }
                        }
                        $rows[0][$fieldNameUpper] = $fieldValue;
                        break;
                    default:
                        break;
                }

                // Processing the values of the same field in adjacent records
                foreach ($mergeIds as $mergeIdIndex => $mergeId) {
                    $rowIndex = $mergeIdIndex + 1;
                    switch ($fieldType) {
                        case ('bool'):
                            if (
                                (
                                    $mergeBeanArray[$mergeId]->$fieldName == '1'
                                    || $mergeBeanArray[$mergeId]->$fieldName == 'yes'
                                    || $mergeBeanArray[$mergeId]->$fieldName == 'on'
                                )
                                && !empty($mergeBeanArray[$mergeId]->$fieldName)
                            ) {
                                $rows[$rowIndex][$fieldNameUpper] = true;
                            } else {
                                $rows[$rowIndex][$fieldNameUpper] = false;
                            }
                            break;
                        case ('multienum'):
                            $rows[$rowIndex][$fieldNameUpper] = unencodeMultienum($mergeBeanArray[$mergeId]->$fieldName);
                            break;
                        case ('relate'):
                        case ('link'):
                            $tempId = $field_def['id_name'];
                            if (empty($mergeBeanArray[$mergeId]->$fieldName) && !empty($mergeBeanArray[$mergeId]->$tempId)) {
                                $related_name = $this->getRelatedNameByIdName($field_def, $mergeBeanArray[$mergeId]->$tempId);
                                if ($related_name !== false) {
                                    $mergeBeanArray[$mergeId]->$fieldName = $related_name;
                                }
                            }
                            $rows[$rowIndex][$fieldNameUpper] = $mergeBeanArray[$mergeId]->$fieldName;
                            break;
                        default:
                            $rows[$rowIndex][$fieldNameUpper] = $mergeBeanArray[$mergeId]->$fieldName;
                            break;
                    }
                }
            }
        }

        //Jenny - Bug 8386 - The object_name couldn't be found because it was searching for
        // 'Case' instead of 'aCase'.
        if ($focus->merge_bean->object_name == 'Case') {
            $focus->merge_bean->object_name = 'aCase';
        }


        $result = array(
            'recordName' => $focus->merge_bean->get_summary_text(),

            // A separate array of adjacent records' ids for the set primary and remove actions
            'mergeIds' => $mergeIds,

            // An array of names of adjacent records.
            // Necessary when displaying a confirmation message before starting the merging process
            'mergeRecordNames' => $mergeRecordNames,

            'pageData' => array(
                'urls' => array(
                    'startPage' => null,
                    'prevPage' => null,
                    'nextPage' => null,
                    'endPage' => null,
                ),
                'bean' => array(
                    'moduleDir' => $focus->merge_bean->module_dir,
                    'moduleName' => $focus->merge_bean->module_name,
                    'objectName' => $focus->merge_bean->object_name,
                ),
                'offsets' => array(
                    'lastOffsetOnPage' => null,
                ),
            ),
            'viewData' => array(
                'panelsFields' => array(
                    'LBL_DIFF_COL_VALUES' => $diffSectionFields,
                    'LBL_SAME_COL_VALUES' => $similarSectionFields,
                ),
                'displayColumns' => $displayColumns,
                'data' => $rows,
            ),
        );

        return $result;
    }


    /**
     * check whether the field should be included in the merge
     *
     * @param array $field_def
     * @return bool
     * @see modules/MergeRecords/Step3.php :: show_field()
     */
    private function isInvolvedInMerger($field_def)
    {
        // The following attributes will be ignored during the merge process
        $invalidAttributeByName = array(
            'date_entered' => 'date_entered',
            'date_modified' => 'date_modified',
            'modified_user_id' => 'modified_user_id',
            'created_by' => 'created_by',
            'deleted' => 'deleted'
        );

        // filter condition for fields in vardefs that can participate in merging
        $filterForValidEditableAttributes = array(
            array('type' => 'datetimecombo', 'source' => 'db'),
            array('type' => 'datetime', 'source' => 'db'),
            array('type' => 'varchar', 'source' => 'db'),
            array('type' => 'enum', 'source' => 'db'),
            array('type' => 'multienum', 'source' => 'db'),
            array('type' => 'text', 'source' => 'db'),
            array('type' => 'date', 'source' => 'db'),
            array('type' => 'time', 'source' => 'db'),
            array('type' => 'bool', 'source' => 'db'),
            array('type' => 'int', 'source' => 'db'),
            array('type' => 'long', 'source' => 'db'),
            array('type' => 'double', 'source' => 'db'),
            array('type' => 'float', 'source' => 'db'),
            array('type' => 'short', 'source' => 'db'),
            array('dbType' => 'varchar', 'source' => 'db'),
            array('dbType' => 'double', 'source' => 'db'),
            array('type' => 'relate'),
        );

        if (isset($invalidAttributeByName[$field_def['name']])) {
            // The field is not allowed to be merged
            return false;
        }

        if (isset($field_def['duplicate_merge'])) {
            if ($field_def['duplicate_merge'] == 'disabled' || $field_def['duplicate_merge'] == false) {
                return false;
            }
            if ($field_def['duplicate_merge'] == 'enabled' || $field_def['duplicate_merge'] == true) {
                return true;
            }
        }

        // Auto-incrementing fields do not participate in merging
        if (isset($field_def['auto_increment']) && $field_def['auto_increment'] == true) {
            return false;
        }

        // set the required attribute values in $field_def
        // TODO: check whether it should exactly be in this method
        if (!isset($field_def['source']) || empty($field_def['source'])) {
            $field_def['source'] = 'db';
        }
        if (!isset($field_def['dbType']) || empty($field_def['dbType']) && isset($field_def['type'])) {
            $field_def['dbType'] = $field_def['type'];
        }

        // TODO: add a comment what's going on here
        foreach ($filterForValidEditableAttributes as $attributeSet) {
            $b_all = false;
            foreach ($attributeSet as $attr => $value) {
                if (isset($field_def[$attr]) && $field_def[$attr] == $value) {
                    $b_all = true;
                } else {
                    $b_all = false;
                    break;
                }
            }
            if ($b_all) {
                return true;
            }
        }

        return false;
    }


    /**
     * Get the value of a related record for a field with type relate or link
     *
     * @param $field_def
     * @param $id_value
     * @return false|mixed - false - if the value is missing or could not be retrieved
     * @see modules/MergeRecords/Step3.php :: get_related_name()
     */
    private function getRelatedNameByIdName($field_def, $id_value)
    {
        global $beanList, $beanFiles, $db;

        if (!empty($field_def['rname']) && !empty($field_def['id_name']) && !empty($field_def['table'])) {
            if (!empty($id_value)) {

                //default the column name to rname in vardefs
                $colName = $field_def['rname'];

                //if this module is non db and has a module set, then check to see if this field should be concatenated
                if (!empty($field_def['module']) && $field_def['source'] == 'non-db') {
                    $beanName = $beanList[$field_def['module']];
                    $focus = new $beanName();
                    if (!empty($focus->field_defs[$field_def['rname']])) {
                        $related_def = $focus->field_defs[$field_def['rname']];

                        //if field defs has concat field array set, then concatenate values
                        if (isset($related_def['db_concat_fields']) && !empty($related_def['db_concat_fields'])) {
                            $colName = $focus->db->concat($field_def['table'], $related_def['db_concat_fields']);
                        }
                    }
                }

                $query = "SELECT $colName FROM {$field_def['table']} WHERE id='$id_value'";
                $result = $db->query($query);
                $row = $db->fetchByAssoc($result);
                if (!empty($row[$field_def['rname']])) {
                    return $row[$field_def['rname']];
                }
            }
        }
        return false;
    }
}
