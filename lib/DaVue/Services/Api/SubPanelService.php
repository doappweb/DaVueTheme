<?php

namespace SuiteCRM\DaVue\Services\Api;

use BeanFactory;
use Exception;
use SecurityGroup;
use SubPanel;
use SubPanelDefinitions;
use SugarFieldHandler;

class SubPanelService
{
    /**
     * Get the contents of a specific subpanel.
     *
     * @usage http://localhost/index.php?action=DetailView&module=Contacts&record=111d84c5-25c6-0f12-00f8-64466076502c&VueAjax=1&method=getSubpanelDynamic&arg[subpanel]=history
     * @param $args
     * @return array
     * @throws Exception
     */
    public function getSubpanelDynamic($args): array
    {
        global $beanList, $disable_date_format, $timedate;

        // This flag is enabled by default, that's why dates are displayed in the standard database format instead of
        // the format specified in the user settings
        $disable_date_format = false;

        if (empty($_REQUEST['module'])) {
            throw new Exception("'module' was not defined");
        }

        if (empty($_REQUEST['record'])) {
            throw new Exception("'record' was not defined");
        }

        if (!isset($beanList[$_REQUEST['module']])) {
            throw new Exception("'" . htmlspecialchars($_REQUEST['module']) . "' is not defined in \$beanList");
        }

        if (!isset($args['subpanel'])) {
            throw new Exception("Subpanel '" . htmlspecialchars($args['subpanel']) . "' was not defined");
        }

        $subpanel = $args['subpanel'];
        $record = $_REQUEST['record'];
        $module = $_REQUEST['module'];

        $collection = array();

        if (isset($args['collection_basic']) && $args['collection_basic'][0] !== 'null') {
            $collection = explode(',', $args['collection_basic'][0]);
        }

        if (empty($args['inline'])) {
//            insert_popup_header();
        }

        $layout_def_key = '';
        if (!empty($args['layout_def_key'])) {
            $layout_def_key = $args['layout_def_key'];
        }

        $bean = BeanFactory::getBean($module);
        $spd = new SubPanelDefinitions($bean);
        $aSubPanelObject = $spd->load_subpanel($subpanel, false, false, '', $collection);
        $subpanel_object = new SubPanel($module, $record, $subpanel, $aSubPanelObject, $layout_def_key, $collection);
        $subpanel_object->setTemplateFile('tpls/empty.tpl');
        ob_start();
        $subpanel_object->display(false);
        ob_end_clean();

        // ====Above was the original code from include/SubPanel/SubPanelViewer.php with minor changes, below is the custom one====

        $bean->retrieve($record);
        $layout_manager = $subpanel_object->listview->getLayoutManager();
        $smartyTemplate = $subpanel_object->listview->getSmartyTemplate();
        $subpanelDefs = $subpanel_object->subpanel_defs->_instance_properties;
        $sortBy = $subpanel_object->listview->sortby;
        $sortOrder = $subpanel_object->listview->sort_order;
        if ($aSubPanelObject->isCollection()) {
            $thepanel = $aSubPanelObject->get_header_panel_def();
            $subpanel_list = $aSubPanelObject->sub_subpanels;
        } else {
            $thepanel = $aSubPanelObject;
            $subpanel_list[] = $aSubPanelObject;
        }

        // @see include/ListView/ListViewSubPanel::process_dynamic_listview_header()
//        $tableHeadColumns = array();  // Old implementation
        $colData = array();

        $layout_manager->setAttribute('context', 'HeaderCell');
        foreach ($thepanel->get_list_fields() as $column_name => $widget_args) {
            $usage = empty($widget_args['usage']) ? '' : $widget_args['usage'];
            if ($usage != 'query_only' || !empty($widget_args['force_query_only_display'])) {
                $imgArrow = '';
                if (
                    $sortBy == $column_name
                    || (isset($widget_args['sort_by']) && str_replace('.', '_', $widget_args['sort_by']) == $sortBy)
                ) {
                    $imgArrow = "_down";
                    if ($sortOrder == 'asc') {
                        $imgArrow = "_up";
                    }
                }

                if (!preg_match("/_button/i", $column_name)) {
                    $widget_args['name'] = $column_name;
                    $widget_args['sort'] = $imgArrow;
                    $widget_args['start_link_wrapper'] = $subpanel_object->listview->start_link_wrapper;
                    $widget_args['end_link_wrapper'] = $subpanel_object->listview->end_link_wrapper;
                    $widget_args['subpanel_module'] = $subpanel_object->listview->subpanel_module;

                    // TODO: The next step would be rendering:
//                    $tableHeadColumns[] = $layout_manager->widgetDisplay($widget_args);

//                    $tableHeadColumns[] = $widget_args;  // Old implementation

                    $colData[strtoupper($column_name)] = $widget_args;
                }
            }
        }

        /*************** 292-SFR
         *
         *   // @see include/SubPanel/SubPanelTiles::get_buttons()
         *   $topButtons = array();
         *   if (is_array($subpanelDefs['top_buttons'])){
         *       foreach ($subpanelDefs['top_buttons'] as $widgetData) {
         *           $widgetData['action'] = $_REQUEST['action'];
         *           $widgetData['focus'] = $bean;
         *           $widgetData['subpanel_definition'] = $aSubPanelObject;
         *
         *           // The module to which the button action belongs. For example, in the Contacts module in the History subpanel, the action may relate to the Notes module
         *           $widgetData['module'] = $aSubPanelObject->get_inst_prop_value('module');
         *
         *           // TODO: $widgetData contains all the necessary data and then goes through the rendering procedure depending on (string)$widgetData['widget_class']
         *           // @see include/SubPanel/SubPanelTiles::get_buttons() line 446.
         *           // $button = $layout_manager->widgetDisplay($widgetData);
         *           // if ($button) {
         *           //     $topButtons[] = $button;
         *           // }
         *           $topButtons[] = array('widget_class' => $widgetData['widget_class']);  // TODO: This is the only different parameter inside $widgetData
         *       }
         *   }
         *
         ***************/

        // ==== Collecting data by table rows ====
        // @see include/ListView/ListViewSubPanel::process_dynamic_listview_rows()
//        $rows = $smartyTemplate->_tpl_vars['ROWS'];
//        $rowButtons = $smartyTemplate->_tpl_vars['ROWS_BUTTONS'];
        $rows = array();
        $rowButtons = array();
        foreach ($subpanel_list as $this_subpanel) {
            if ($this_subpanel->is_fill_in_additional_fields()) {
                $fill_additional_fields[] = $this_subpanel->bean_name;  // TODO:?
                $fill_additional_fields[$this_subpanel->bean_name] = true;
            }
        }
        $html_varName = $subpanel_object->listview->subpanel_module . "_CELL";
        $count = 0;
        $offset = ($subpanel_object->listview->getOffset($html_varName)) === false ? 0 : $subpanel_object->listview->getOffset($html_varName);
        $oddRow = true;
        $processed_ids = array();

        // 292-SFR
        $rowData = array();

        foreach ($subpanel_object->listview->response['list'] as $aVal => $aItem) {
            $aItem->check_date_relationships_load();
            if (!empty($fill_additional_fields[$aItem->object_name]) || ($aItem->object_name == 'Case' && !empty($fill_additional_fields['aCase']))) {
                $aItem->fill_in_additional_list_fields();
            }
            $aItem->call_custom_logic("process_record");
            $parentData = $subpanel_object->listview->response['parent_data'];
            if (isset($parentData[$aItem->id])) {
                $aItem->parent_name = $parentData[$aItem->id]['parent_name'];
                if (!empty($parentData[$aItem->id]['parent_name_owner'])) {
                    $aItem->parent_name_owner = $parentData[$aItem->id]['parent_name_owner'];
                    $aItem->parent_name_mod = $parentData[$aItem->id]['parent_name_mod'];
                }
            }
            $fields = $aItem->get_list_view_data();

            // 292-SFR - Fix original kludge - Task::get_list_view_data
            if ($aItem->module_name == 'Tasks' && !empty($fields['DATE_END'])){
                $dateDue = $timedate->to_display_date_time($fields['DATE_END']);
                $fields['DATE_DUE'] = $dateDue;
            }

            // 292-SFR
            $rowData[$aVal] = $fields;

            if (isset($processed_ids[$aItem->id])) {
                continue;
            } else {
                $processed_ids[$aItem->id] = 1;
            }
            $fields['OFFSET'] = ((int)$offset + $count + 1);
            if ($subpanel_object->listview->shouldProcess) {
                if ($aItem->ACLAccess('EditView')) {
                    $rows[$aVal][0] = "<input type='checkbox' class='checkbox' name='mass[]' value='" . $fields['ID'] . "' />";
                } else {
                    $rows[$aVal][0] = '';
                }
//                $smartyTemplate->assign('CHECKALL', "<input type='checkbox'  title='" . $GLOBALS['app_strings']['LBL_SELECT_ALL_TITLE'] . "' class='checkbox' name='massall' id='massall' value='' onclick='sListView.check_all(document.MassUpdate, \"mass[]\", this.checked);' />");
            }
            $oddRow = !$oddRow;
            $layout_manager->setAttribute('context', 'List');
            $layout_manager->setAttribute('image_path', $subpanel_object->listview->local_image_path);
            $layout_manager->setAttribute('module_name', $aSubPanelObject->_instance_properties['module']);
            if (!empty($subpanel_object->listview->child_focus)) {
                $layout_manager->setAttribute('related_module_name', $subpanel_object->listview->child_focus->module_dir);
            }
            if ($aSubPanelObject->isCollection()) {
                $thepanel = $aSubPanelObject->sub_subpanels[$aItem->panel_name];
            } else {
                $thepanel = $aSubPanelObject;
            }

            /* BEGIN - SECURITY GROUPS */
            global $current_user;
            if (is_admin($current_user)) {
                $aclaccess_is_owner = true;
            } else {
                $aclaccess_is_owner = $aItem->isOwner($current_user->id);
            }

            $aclaccess_in_group = SecurityGroup::groupHasAccess($aItem->module_dir, $aItem->id);

            $field_acl['DetailView'] = $aItem->ACLAccess('DetailView', $aclaccess_is_owner, $aclaccess_in_group);
            $field_acl['ListView'] = $aItem->ACLAccess('ListView', $aclaccess_is_owner, $aclaccess_in_group);
            $field_acl['EditView'] = $aItem->ACLAccess('EditView', $aclaccess_is_owner, $aclaccess_in_group);
            /* END - SECURITY GROUPS */

            $linked_field = $thepanel->get_data_source_name();
            $linked_field_set = $thepanel->get_data_source_name(true);

            foreach ($thepanel->get_list_fields() as $field_name => $list_field) {
                //add linked field attribute to the array.
                $list_field['linked_field'] = $linked_field;
                $list_field['linked_field_set'] = $linked_field_set;

                $usage = empty($list_field['usage']) ? '' : $list_field['usage'];
                if ($usage == 'query_only' && !empty($list_field['force_query_only_display'])) {
                    // The value for the current cell is in the query, but should appear as a blank space.
                    // This is useful, for example, for subpanels based on several modules (like History),
                    // when the field exists in only one of the modules, but the column must be displayed anyway.
                    $count++;
                    $rows[$aVal][$field_name] = '&nbsp;';
                } else {
                    if ($usage != 'query_only') {
                        $list_field['name'] = $field_name;

                        $module_field = $field_name . '_mod';
                        $owner_field = $field_name . '_owner';
                        if (!empty($aItem->$module_field)) {
                            $list_field['owner_id'] = $aItem->$owner_field;
                            $list_field['owner_module'] = $aItem->$module_field;
                        } else {
                            $list_field['owner_id'] = false;
                            $list_field['owner_module'] = false;
                        }
                        if (isset($list_field['alias'])) {
                            $list_field['name'] = $list_field['alias'];
                            // Clone field def from origin field def to alias field def
                            $alias_field_def = $aItem->field_defs[$field_name];
                            $alias_field_def['name'] = $list_field['alias'];
                            // Add alias field def into bean to can render field in subpanel
                            $aItem->field_defs[$list_field['alias']] = $alias_field_def;
                            if (!isset($fields[strtoupper($list_field['alias'])]) || empty($fields[strtoupper($list_field['alias'])])) {
                                global $timedate;
                                $fields[strtoupper($list_field['alias'])] = (!empty($aItem->$field_name)) ? $aItem->$field_name : $timedate->to_display_date_time($aItem->{$list_field['alias']});
                            }
                        } else {
                            $list_field['name'] = $field_name;
                        }

                        /*************** 292-SFR
                         *
                         * $list_field['fields'] = $fields;
                         *
                         *****************/

                        $list_field['module'] = $aItem->module_dir;
                        $list_field['start_link_wrapper'] = $subpanel_object->listview->start_link_wrapper;
                        $list_field['end_link_wrapper'] = $subpanel_object->listview->end_link_wrapper;
                        $list_field['subpanel_id'] = $subpanel_object->listview->subpanel_id;
                        $list_field += $field_acl;

                        // 292-SFR Checking rights for links to NON-MAIN MODULE records
                        if (isset($list_field['widget_class'])
                            && $list_field['widget_class'] == 'SubPanelDetailViewLink'
                            && !is_admin($current_user)
                        ) {
                            if (isset($list_field['owner_id']) && $list_field['owner_id'] != $current_user->id){
                                $checkModule = $list_field['target_module'];
                                $checkRecord = $rowData[$aVal][strtoupper($list_field['target_record_key'])];

                                if (!empty($checkModule) && !empty($checkRecord)){
                                    $checkBean = BeanFactory::getBean($checkModule, $checkRecord);
                                    $list_field['acl_Detail'] = $checkBean->ACLAccess('detail');
                                }
                            }
                        }

                        // 343-SFR 345-SFR
                        // Highlighting the DATE DUE field depending on the current date
                        if ( ($list_field['name'] == 'date_due' || $list_field['name'] == 'date_end')&&
                            $list_field['subpanel_id'] == 'activities' &&
                            ($list_field['module'] === 'Calls' || $list_field['module'] === 'Meetings' || $list_field['module'] === 'Tasks') ) {

                            $checkRecord = $rowData[$aVal]['DATE_DUE'];
                            $activityClass = '';

                            if (!empty($checkRecord)){

                                $today = $timedate->nowDb();
                                $dateDueDBFormat = $timedate->to_db($checkRecord);

                                if ($dateDueDBFormat < $today) {
                                    $activityClass = 'overdueTask';
                                }
                                elseif ($dateDueDBFormat = $today){
                                    $activityClass = 'todaysTask';
                                }
                                else{
                                    $activityClass = 'futureTask';
                                }
                            }

                            $list_field['class'] = $activityClass;

                            if(isset($colData['DATE_DUE'])){
                                $colData['DATE_DUE']['type'] = 'dateDue_widget';
                            }
                            else{
                                $colData['DATE_END']['type'] = 'dateDue_widget';
                            }

                        }



                        if (isset($aItem->field_defs[strtolower($list_field['name'])])) {
                            // Check whether SugarField exists for this type of field.
                            // If not, then the old SugarWidget class will be used to display it.
                            // This is done for backward compatibility.
                            $vardef = $aItem->field_defs[strtolower($list_field['name'])];
                            if (isset($vardef['type'])) {
                                $fieldType = isset($vardef['custom_type']) ? $vardef['custom_type'] : $vardef['type'];
                                $tmpField = SugarFieldHandler::getSugarField($fieldType, true);
                            } else {
                                $tmpField = null;
                            }

                            if ($tmpField != null) {
//                                $rows[$aVal][$field_name] = SugarFieldHandler::displaySmarty($list_field['fields'], $vardef, 'ListView', $list_field);  // Was in original code
                                $rows[$aVal][$field_name] = $list_field;
                            } else {
                                // There is no SugarField for this particular field type. The old SugarWidget class will be used for display.
//                                $rows[$aVal][$field_name] = $layout_manager->widgetDisplay($list_field);  // Was in original code
                                $rows[$aVal][$field_name] = $list_field;
                            }
                            if (isset($list_field['widget_class']) && $list_field['widget_class'] == 'SubPanelDetailViewLink') {
                                // For now, we need to refer to the old SugarWidgets class so that it can generate a proper link with all the various edge cases handled.

                                /*************** 292-SFR
                                 * $list_field['fields'][$field_name] = $rows[$aVal][$field_name];
                                 * if ('full_name' == $field_name) {//bug #32465
                                 *     $list_field['fields'][strtoupper($field_name)] = $rows[$aVal][$field_name];
                                 * }
                                 ***************/

                                // vardef source is non db, assign the field name to varname for processing of column.
                                if (!empty($vardef['source']) && $vardef['source'] == 'non-db') {
                                    $list_field['varname'] = $field_name;
                                }
//                                $rows[$aVal][$field_name] = $layout_manager->widgetDisplay($list_field);  // as box
                                $rows[$aVal][$field_name] = $list_field;
                            } else {
                                if (isset($list_field['widget_class']) && $list_field['widget_class'] == 'SubPanelEmailLink') {
                                    if (isset($list_field['fields']['EMAIL1_LINK'])) {

                                        /*************** 292-SFR
                                         *
                                         * $rows[$aVal][$field_name] = $list_field['fields']['EMAIL1_LINK'];
                                         *
                                         ***************/
                                    } else {
//                                        $rows[$aVal][$field_name] = $layout_manager->widgetDisplay($list_field);  // as box
                                        $rows[$aVal][$field_name] = $list_field;
                                    }
                                }
                            }

                            $count++;
                            if (empty($rows)) {
                                $rows[$aVal][$field_name] = '&nbsp;';
                            }
                        } else {
                            // Field is icon
                            if (isset($list_field['widget_class']) && $list_field['widget_class'] == "SubPanelIcon") {
                                $count++;
//                                $rows[$aVal][$field_name] = $layout_manager->widgetDisplay($list_field);  // as box
                                $rows[$aVal][$field_name] = $list_field;

                                if (empty($rows[$aVal][$field_name])) {
                                    $rows[$aVal][$field_name] = '&nbsp;';
                                }
                            }

                            // The field being processed is one of the context action buttons for the table row
                            elseif (preg_match("/button/i", $list_field['name'])) {
                                $thisButtonDoEdit = in_array($list_field['name'], array("edit_button", "close_button", "remove_button"));
                                if (
                                    (
                                        $thisButtonDoEdit && $field_acl['EditView']
                                        || !$thisButtonDoEdit
                                    )

                                    // TODO: is the widget for the button supposed to return false? There was no such check for other table cells
                                    && '' != ($_content = $layout_manager->widgetDisplay($list_field))
                                ) {
//                                    $rowButtons[$aVal][] = $_content;  // Was in original code
                                    $rowButtons[$aVal][] = $list_field;
                                    unset($_content);
                                } else {
                                    $rowButtons[$aVal][] = '';  // TODO: Maybe then not add anything to the list at all?
                                }
                            }

                            // The current field type does not exist in field_defs. Also, this is not an icon or a contextual action button
                            else {
                                $count++;
//                                $rows[$aVal][$field_name] = $layout_manager->widgetDisplay($list_field);  // Was in original code
                                $rows[$aVal][$field_name] = $list_field;
                                if (empty($rows[$aVal][$field_name])) {
                                    $rows[$aVal][$field_name] = '&nbsp;';
                                }
                            }
                        }
                    }
                }
            }
            $aItem->setupCustomFields($aItem->module_dir);
            $aItem->custom_fields->populateAllXTPL($smartyTemplate, 'detail', $html_varName, $fields);
            $count++;
        }
        // ==== The collection of data on table rows has ended ====

        // TODO: Find out what it is. In this form, it is output to the template in the DISPLAY_SPS variable
        if ($subpanel_object->search_query == '' && empty($subpanel_object->collections)) {
            $displaySPS = 'display:none';
        } else {
            $displaySPS = '';
        }

        // ==== Pagination - START ====
        $rowCount = $subpanel_object->listview->response['row_count'];
        $recordsPerPage = $subpanel_object->listview->records_per_page;

        if (0 == $rowCount) {
            $amountPages = 0;
        } else {
            $amountPages = (int)floor(($rowCount - 1) / $recordsPerPage) + 1;
        }

        $currentOffset = (int)$subpanel_object->listview->response['current_offset'];

        $nextOffset = $subpanel_object->listview->response['next_offset'];
        if ($nextOffset > $rowCount) {
            $nextOffset = -1;
        }

        $prevOffset = $subpanel_object->listview->response['previous_offset'];
        if ($prevOffset < 0) {
            $prevOffset = -1;
        }

        $lastOffset = ($amountPages - 1) * $recordsPerPage;

        $lastOffsetOnPage = $nextOffset;
        if (-1 === $nextOffset) {
            $lastOffsetOnPage = $rowCount;
        }

        $paginationUrlTemplate = "index.php?action=DetailView&module=$module&record=$record&VueAjax=1&method=getSubpanelDynamic&arg[subpanel]=$subpanel&{$module}_{$subpanel}_CELL_offset=";
        $paginationUrls = array();
        if ($currentOffset !== 0) {
            $paginationUrls['startPage'] = $paginationUrlTemplate . '0';
        }
        if ($prevOffset !== -1) {
            $paginationUrls['prevPage'] = $paginationUrlTemplate . $prevOffset;
        }
        if ($nextOffset !== -1) {
            $paginationUrls['nextPage'] = $paginationUrlTemplate . $nextOffset;
        }
        if ($lastOffset !== 0 && $rowCount !== $lastOffsetOnPage) {
            $paginationUrls['endPage'] = $paginationUrlTemplate . $lastOffset;
        }
        // ==== Pagination - END ====

        $result = array(
            'pageData' => array(
                'pagination' => array(
                    'current' => $currentOffset,
                    'next' => $nextOffset,
                    'prev' => $prevOffset,
                    'end' => $lastOffset,

                    'totalCounted' => true,
                    'total' => $rowCount,
                    'lastOffsetOnPage' => $lastOffsetOnPage,
                ),
                'urls' => $paginationUrls,
                'sortOrder' => $sortOrder,
                'sortBy' => $sortBy,
                'rowProperties'  => $rows,
                'rowButtons'     => $rowButtons,
            ),
            'viewData' => array(
                // 292-SFR '_displayColumns'=> $tableHeadColumns,
                'displayColumns' => $colData,
                'data'           => $rowData,
            ),
            // 292-SFR 'topButtons' => $topButtons,

            'displaySPS' => $displaySPS,
            'recordsPerPage' => $recordsPerPage,  // May be useless

            // Could be useless, as this data should be on frontend:
            // 292-SFR 'label' => $subpanelDefs['title_key'],  // Subpanel title
            // 292-SFR 'order' => $subpanelDefs['order'],  // Display priority relative to other subpanels on the page
        );

        return $result;
    }

    /**
     * @deprecated
     *
     * Saves the specified state of the subpanel
     *
     * @usage http://localhost/index.php?module=Contacts&VueAjax=1&method=setSubpanelExpand&arg[]...
     * @param $args['expand'] - 0|1 - IMPORTANT!
     * @return string[]
     */
    public function setSubpanelExpand($args){

        global $current_user, $db;

        $result = false;

        if (empty($args['module'])){
            $module = $_REQUEST['module'];
        }else{
            $module = $args['module'];
        }

        $subpanel = $args['subpanel'];

        if (!empty($args['expand']) && $args['expand'] != 'false' && $args['expand'] != '0'){
            $expand = 1;
        }else{
            $expand = 0;
        }

        $category = $module . '_' . $subpanel . '_SP';
        $date = gmdate("Y-m-d H:i:s", time());

        $sql = "SELECT id FROM user_preferences 
         WHERE category = '{$category}' 
           AND assigned_user_id = '{$current_user->id}' 
           AND deleted = 0";

        $id = $db->getOne($sql);

        if ($id !== false){
            $sql = "UPDATE user_preferences SET contents = '{$expand}', date_modified = '{$date}'
                        WHERE id = '{$id}'
                          AND deleted = 0";
        }else{

            $params = array(
                'id'                => "'". create_guid() ."'",
                'category'          => "'". $category ."'",
                'deleted'           => 0,
                'date_entered'      => "'". $date  ."'",
                'date_modified'     => "'". $date  ."'",
                'assigned_user_id'  => "'". $current_user->id ."'",
                'contents'          => $expand
            );

            $keys = implode(',', array_keys($params));
            $values = implode(',', array_values($params));
            $sql = "INSERT INTO user_preferences ($keys) VALUES ($values)";
        }

        if ($db->query($sql)){
            $result = 'success';
        }else{
            $result = 'failure';
        }

        return array(
            'saveSubpanel' => $result
        );

    }
}
