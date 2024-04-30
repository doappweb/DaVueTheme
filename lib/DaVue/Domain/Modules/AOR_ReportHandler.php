<?php

namespace SuiteCRM\DaVue\Domain\Modules;

use AOR_Report;
use BeanFactory;
use LoggerManager;
use SuiteCRM\DaVue\Services\Common\FieldFunctions;
use SuiteCRM\Exception\Exception;

class AOR_ReportHandler
{
    /** @var FieldFunctions $fieldFunctions */
    private $fieldFunctions;

    public function __construct(FieldFunctions $fieldFunctions)
    {
        $this->fieldFunctions = $fieldFunctions;
    }

    /**
     * Fields used in the report.
     * Copy private method from box
     * @return array
     * @see modules/AOR_Reports/views/view.edit.php::getFieldLines()
     */
    public function aorReportsGetFieldLines($bean)
    {
        if (!$bean->id) {
            return array();
        }
        $sql = "SELECT id FROM aor_fields WHERE aor_report_id = '" . $bean->id . "' AND deleted = 0 ORDER BY field_order ASC";
        $result = $bean->db->query($sql);

        $fields = array();
        while ($row = $bean->db->fetchByAssoc($result)) {
            $field_name = BeanFactory::newBean('AOR_Fields');
            $field_name->retrieve($row['id']);
            $field_name->module_path = implode(":", unserialize(base64_decode($field_name->module_path)));
            $arr = $field_name->toArray();


            $display = getDisplayForField($field_name->module_path, $field_name->field, $bean->report_module);
            $arr['field_type'] = $display['type'];

            $arr['module_path_display'] = $display['module'];
            $arr['field_label'] = $display['field'];
            $fields[] = $arr;
        }
        return $fields;
    }


    /**
     * Conditions used in the report.
     * Copy private method from box
     * @return array
     * @see modules/AOR_Reports/views/view.edit.php::getConditionLines()
     */
    public function aorReportsGetConditionLines($bean)
    {
        if (!$bean->id) {
            return array();
        }
        $sql = "SELECT id FROM aor_conditions WHERE aor_report_id = '" . $bean->id . "' AND deleted = 0 ORDER BY condition_order ASC";
        $result = $bean->db->query($sql);
        $conditions = array();
        while ($row = $bean->db->fetchByAssoc($result)) {
            $condition_name = BeanFactory::newBean('AOR_Conditions');
            $condition_name->retrieve($row['id']);
            if (!$condition_name->parenthesis) {
                $condition_name->module_path = implode(":", unserialize(base64_decode($condition_name->module_path)));
            }
            if ($condition_name->value_type == 'Date') {
                $condition_name->value = unserialize(base64_decode($condition_name->value));
            }
            $condition_item = $condition_name->toArray();

            if (!$condition_name->parenthesis) {
                $display = getDisplayForField($condition_name->module_path, $condition_name->field, $bean->report_module);
                $condition_item['module_path_display'] = $display['module'];
                $condition_item['field_label'] = $display['field'];
            }
            if (isset($conditions[$condition_item['condition_order']])) {
                $conditions[] = $condition_item;
            } else {
                $conditions[$condition_item['condition_order']] = $condition_item;
            }
        }
        return $conditions;
    }


    /**
     * Graph data used in the report.
     * Copy private method from box
     * @return array
     * @see modules/AOR_Reports/views/view.edit.php::getChartLines()
     */
    public function aorReportsGetChartLines($bean)
    {
        $charts = array();
        if (!$bean->id) {
            return array();
        }
        foreach ($bean->get_linked_beans('aor_charts', 'AOR_Charts') as $chart) {
            $charts[] = $chart->toArray();
        }
        return $charts;
    }


    /**
     * Report data with groups
     *
     * @param $bean
     * @param $offset
     * @param $links
     * @param $extra
     * @param $subgroup
     * @return array
     * @see modules/AOR_Reports/AOR_Report.php::build_group_report()
     */
    public function aorReportsBuildGroupReport($bean, $offset = -1, $links = true, $extra = array(), $subgroup = '')
    {
        global $db, $beanList, $timedate;

        $result = array(
            'isGroupsUsed' => false,
            'data' => array(),
        );

        $query_array = array();
        $module = new $beanList[$bean->report_module]();

        // id of the field that is used in the report as the "Main group"
        $sql = "SELECT id FROM aor_fields WHERE aor_report_id = '" . $bean->id . "' AND group_display = 1 AND deleted = 0 ORDER BY field_order ASC";
        $baseGroupFieldId = $db->getOne($sql);

        if (!$baseGroupFieldId) {
            $query_array['select'][] = $module->table_name . ".id AS '" . $module->table_name . "_id'";
        }

        // Logic for reports that have a field selected as the Main Group
        if ($baseGroupFieldId != '' && empty($subgroup)) {
            $result['isGroupsUsed'] = true;

            $baseGroupField = BeanFactory::newBean('AOR_Fields');
            $baseGroupField->retrieve($baseGroupFieldId);

            $field_label = str_replace(' ', '_', $baseGroupField->label);

            $path = unserialize(base64_decode($baseGroupField->module_path));

            $field_module = $module;
            $table_alias = $field_module->table_name;
            if (!empty($path[0]) && $path[0] != $module->module_dir) {
                foreach ($path as $rel) {
                    $new_field_module = new $beanList[getRelatedModule($field_module->module_dir, $rel)];
                    $oldAlias = $table_alias;
                    $table_alias = $table_alias . ":" . $rel;

                    $query_array = $bean->build_report_query_join(
                        $rel,
                        $table_alias,
                        $oldAlias,
                        $field_module,
                        'relationship',
                        $query_array,
                        $new_field_module
                    );
                    $field_module = $new_field_module;
                }
            }

            $data = $field_module->field_defs[$baseGroupField->field];

            if ($data['type'] == 'relate' && isset($data['id_name'])) {
                $baseGroupField->field = $data['id_name'];
            }

            if (
                $data['type'] == 'currency'
                && !stripos($baseGroupField->field, '_USD')
                && isset($field_module->field_defs['currency_id'])
            ) {
                if (
                    (
                        isset($field_module->field_defs['currency_id']['source'])
                        && $field_module->field_defs['currency_id']['source'] == 'custom_fields'
                    )
                ) {
                    $query_array['select'][$table_alias . '_currency_id'] = $table_alias . '_cstm' . ".currency_id AS '" . $table_alias . "_currency_id'";
                } else {
                    $query_array['select'][$table_alias . '_currency_id'] = $table_alias . ".currency_id AS '" . $table_alias . "_currency_id'";
                }
            }

            if ((isset($data['source']) && $data['source'] == 'custom_fields')) {
                $select_field = $db->quoteIdentifier($table_alias . '_cstm') . '.' . $baseGroupField->field;
                // Fix for #1251 - added a missing parameter to the function call
                $query_array = $bean->build_report_query_join(
                    $table_alias . '_cstm',
                    $table_alias . '_cstm',
                    $table_alias,
                    $field_module,
                    'custom',
                    $query_array
                );
            } else {
                $select_field = $db->quoteIdentifier($table_alias) . '.' . $baseGroupField->field;
            }

            if ($baseGroupField->sort_by != '') {
                $query_array['sort_by'][] = $field_label . ' ' . $baseGroupField->sort_by;
            }

            if ($baseGroupField->format && in_array($data['type'], array('date', 'datetime', 'datetimecombo'))) {
                if (in_array($data['type'], array('datetime', 'datetimecombo'))) {
                    $select_field = $db->convert($select_field, 'add_tz_offset');
                }
                $select_field = $db->convert(
                    $select_field,
                    'date_format',
                    array($timedate->getCalFormat($baseGroupField->format))
                );
            }

            if ($baseGroupField->field_function != null) {
                $select_field = $baseGroupField->field_function . '(' . $select_field . ')';
            }

            $query_array['group_by'][] = $select_field;

            $query_array['select'][] = $select_field . " AS '" . $field_label . "'";
            if (isset($extra['select']) && $extra['select']) {
                foreach ($extra['select'] as $selectField => $selectAlias) {
                    if ($selectAlias) {
                        $query_array['select'][] = $selectField . " AS " . $selectAlias;
                    } else {
                        $query_array['select'][] = $selectField;
                    }
                }
            }
            $query_array['where'][] = $select_field . " IS NOT NULL AND ";
            if (isset($extra['where']) && $extra['where']) {
                $query_array['where'][] = implode(' AND ', $extra['where']) . ' AND ';
            }

            $query_array = $bean->build_report_query_where($query_array);

            $query = '';
            foreach ($query_array['select'] as $select) {
                $query .= ($query == '' ? 'SELECT ' : ', ') . $select;
            }

            $query .= ' FROM ' . $module->table_name . ' ';

            if (isset($query_array['join'])) {
                foreach ($query_array['join'] as $join) {
                    $query .= $join;
                }
            }
            if (isset($query_array['where'])) {
                $query_where = '';
                foreach ($query_array['where'] as $where) {
                    $query_where .= ($query_where == '' ? 'WHERE ' : ' ') . $where;
                }

                // remove empty parenthesis and fix query syntax
                $safe = 0;
                $query_where_clean = '';
                while ($query_where_clean != $query_where) {
                    $query_where_clean = $query_where;
                    $query_where = preg_replace('/\b(AND|OR)\s*\(\s*\)|[^\w+\s*]\(\s*\)/i', '', $query_where_clean);
                    $safe++;
                    if ($safe > 100) {
                        $GLOBALS['log']->fatal('Invalid report query conditions');
                        break;
                    }
                }

                $query .= ' ' . $query_where;
            }

            if (isset($query_array['group_by'])) {
                $query_group_by = '';
                foreach ($query_array['group_by'] as $group_by) {
                    $query_group_by .= ($query_group_by == '' ? 'GROUP BY ' : ', ') . $group_by;
                }
                $query .= ' ' . $query_group_by;
            }

            if (isset($query_array['sort_by'])) {
                $query_sort_by = '';
                foreach ($query_array['sort_by'] as $sort_by) {
                    $query_sort_by .= ($query_sort_by == '' ? 'ORDER BY ' : ', ') . $sort_by;
                }
                $query .= ' ' . $query_sort_by;
            }
            $sqlResult = $db->query($query);

            while ($row = $db->fetchByAssoc($sqlResult)) {
                $groupValue = $row[$field_label];
                if (empty(trim($groupValue))) {
                    $groupValue = '_empty';
                }

                $subGroupResult = $this->aorReportsBuildReportData($bean, $offset, $links, $groupValue, create_guid(), $extra);
                $result['data'][$groupValue] = $subGroupResult;
            }
        }

        if (false === $result['isGroupsUsed']) {
            $result['data'] = $this->aorReportsBuildReportData($bean, $offset, $links, $subgroup, create_guid(), $extra);
        }

        return $result;
    }


    /**
     * Report data within a group (one table).
     * If the report has a breakdown by main group, then this method should be called for each group
     *
     * @param $bean
     * @param $offset
     * @param $links
     * @param $group_value - the value of the main group field for the current subgroup
     * @param $tableIdentifier
     * @param $extra
     * @return array
     * @see modules/AOR_Reports/AOR_Report.php::build_report_html()
     */
    private function aorReportsBuildReportData($bean, $offset = -1, $links = true, $group_value = '', $tableIdentifier = '', $extra = array())
    {
        global $db, $sugar_config, $beanList;

        // We take the changeable parameters from the request, which allow you to dynamically set the conditions of the report
        if (!isset($bean->user_parameters)) {
            $bean->user_parameters = requestToUserParameters($bean);
        }

        $_group_value = $db->quote($group_value);

        $report_sql = $bean->build_report_query($_group_value, $extra);

        // Fix for issue 1232 - items listed in a single report, should adhere to the same standard as ListView items.
        if ($sugar_config['list_max_entries_per_page'] != '') {
            $max_rows = $sugar_config['list_max_entries_per_page'];
        } else {
            $max_rows = 20;
        }

        // See if the report actually has any fields, if not we don't want to run any queries since we can't show anything
        $fieldCount = count($bean->getReportFields());
        if (!$fieldCount) {
            $GLOBALS['log']->info('Running report "' . $bean->name . '" with 0 fields');
        }


        $total_rows = 0;
        if ($fieldCount) {
            $count_sql = explode('ORDER BY', $report_sql);
            $count_query = 'SELECT count(*) c FROM (' . $count_sql[0] . ') as n';

            // We have a count query.  Run it and get the results.
            $result = $db->query($count_query);
            $assoc = $db->fetchByAssoc($result);
            if (!empty($assoc['c'])) {
                $total_rows = $assoc['c'];
            }
        }


        $sql = "SELECT id FROM aor_fields WHERE aor_report_id = '" . $bean->id . "' AND deleted = 0 ORDER BY field_order ASC";
        $result = $db->query($sql);

        $fields = array();
        $i = 0;
        while ($row = $db->fetchByAssoc($result)) {
            $field = BeanFactory::newBean('AOR_Fields');
            $field->retrieve($row['id']);

            $path = unserialize(base64_decode($field->module_path));

            $field_bean = new $beanList[$bean->report_module]();

            $field_module = $bean->report_module;
            $field_alias = $field_bean->table_name;
            if ($path[0] != $bean->report_module) {
                foreach ($path as $rel) {
                    if (empty($rel)) {
                        continue;
                    }
                    $field_module = getRelatedModule($field_module, $rel);
                    $field_alias = $field_alias . ':' . $rel;
                }
            }
            $label = str_replace(' ', '_', $field->label) . $i;
            $fields[$label]['field'] = $field->field;
            $fields[$label]['label'] = $field->label;
            $fields[$label]['display'] = $field->display;
            $fields[$label]['function'] = $field->field_function;
            $fields[$label]['module'] = $field_module;
            $fields[$label]['alias'] = $field_alias;
            $fields[$label]['link'] = $field->link;
            $fields[$label]['total'] = $field->total;
            $fields[$label]['format'] = $field->format;
            $fields[$label]['params'] = [];

//            if ($fields[$label]['display']) {
//                // Fix #5427
//                $html .= "<th scope='col'>";
//                // End
//                $html .= "<div>";
//                $html .= $field->label;
//                $html .= "</div></th>";
//            }
            ++$i;
        }


        if ($offset >= 0) {
            $lastOffsetOnPage = 0;
            $prevOffset = 0;
            $nextOffset = 0;
            $lastOffset = 0;

            if ($total_rows > 0) {
                $lastOffsetOnPage = (($offset + $max_rows) < $total_rows) ? $offset + $max_rows : $total_rows;
                $prevOffset = ($offset - $max_rows) < 0 ? -1 : $offset - $max_rows;
                $nextOffset = $offset + $max_rows;
                if (is_int($total_rows / $max_rows)) {
                    $lastOffset = $max_rows * ($total_rows / $max_rows - 1);
                } else {
                    $lastOffset = $max_rows * (int)floor($total_rows / $max_rows);
                }
            }
        }


        if ($fieldCount) {
//            if ($offset >= 0) {
//                $result = $db->limitQuery($report_sql, $offset, $max_rows);
//            } else {
            $result = $db->query($report_sql);
//            }
        }

        $displayColumns = array();
        foreach ($fields as $name => $att) {
            if ($att['display']) {
                $displayColumns[$name] = $att;
                $displayColumns[$name]['type'] = $field_bean->field_defs[$att['field']]['type'];
            }
        }

        $isShowTotal = false;
        $totals = array();
        $fieldsData = array();
        $rowIndex = 0;
        while ($fieldCount && $row = $db->fetchByAssoc($result)) {
            foreach ($fields as $name => $att) {
                if ($att['display']) {
                    if ($att['total']) {
                        $isShowTotal = true;
                        $totals[$name][] = $row[$name];
                    }
                }
            }

            $fieldsData[$rowIndex] = $row;
            foreach ($fieldsData[$rowIndex] as $key => $value) {
                if (str_contains($key, '_id')) {
                    unset($fieldsData[$rowIndex][$key]);
                }
            }

            $rowIndex++;
        }

        // I did the calculation of the totals in a separate loop, as in a box, in order to use the boxed calculateTotal method
        $totalsCalculated = array();
        if ($isShowTotal) {
            foreach ($fields as $label => $att) {
                if ($att['display'] && $att['total'] && isset($totals[$label])) {
                    $totalType = $att['total'];
                    $totalsCalculated[$label] = $bean->calculateTotal($totalType, $totals[$label]);
                }
            }
        }

        // ==== Pagination >>>>
        $currentOffset = $offset;

        $paginationUrlTemplate = "index.php?VueAjax=1&method=aorReportsBuildGroupReport&arg[record]={$bean->id}&arg[offset]=";
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
        if ($lastOffset !== 0 && $total_rows != $lastOffsetOnPage) {
            $paginationUrls['endPage'] = $paginationUrlTemplate . $lastOffset;
        }
        // <<<< pagination ====

        $result = array(
            'pageData' => array(
                'pagination' => array(
                    'current' => $currentOffset,
                    'next' => $nextOffset,  // It can take the value -1
                    'prev' => $prevOffset,  // It can take the value -1
                    'end' => $lastOffset,

                    'totalCounted' => true,
                    'total' => $total_rows,
                    'lastOffsetOnPage' => $lastOffsetOnPage,  // In this case, not the index, but the ordinal number, i.e. the index+1
                ),
                'urls' => $paginationUrls,
            ),
            'viewData' => array(
                'displayColumns' => $displayColumns,
                'data' => $fieldsData,
                'isShowTotal' => $isShowTotal,
                'totals' => $totalsCalculated,
            ),
        );

        return $result;
    }


    /**
     * Configurable conditions (parameters) used in the report.
     * Copy private method from box
     * @return array
     * @see modules/AOR_Reports/views/view.detail.php::getReportParameters()
     */
    public function aorReportsGetConditionParams($bean)
    {
        if (!$bean->id) {
            return array();
        }
        $conditions = $bean->get_linked_beans('aor_conditions', 'AOR_Conditions', 'condition_order');
        $parameters = array();
        foreach ($conditions as $condition) {
            if (!$condition->parameter) {
                continue;
            }
            if ('' !== $condition->module_path) {
                $condition->module_path = implode(":", unserialize(base64_decode($condition->module_path)));
            }
            if ($condition->value_type == 'Date') {
                $condition->value = unserialize(base64_decode($condition->value));
            }
            $condition_item = $condition->toArray();
            $display = getDisplayForField($condition->module_path, $condition->field, $bean->report_module);
            $condition_item['module_path_display'] = $display['module'];
            $condition_item['field_label'] = $display['field'];
            if (!empty($bean->user_parameters[$condition->id])) {
                $param = $bean->user_parameters[$condition->id];
                $condition_item['operator'] = $param['operator'];
                $condition_item['value_type'] = $param['type'];
                $condition_item['value'] = $param['value'];
            }
            if (isset($parameters[$condition_item['condition_order']])) {
                $parameters[] = $condition_item;
            } else {
                $parameters[$condition_item['condition_order']] = $condition_item;
            }
        }
        return $parameters;
    }


    /**
     * Data required to plot the report
     * @param $bean
     * @param $chartIds
     * @param $chartType
     * @return array
     * @see modules/AOR_Reports/AOR_Report.php::build_report_chart()
     */
    public function aorReportsBuildReportChart($bean, $chartIds = null, $chartType = AOR_Report::CHART_TYPE_PCHART)
    {
        global $db, $beanList;
        $linkedCharts = $bean->get_linked_beans('aor_charts', 'AOR_Charts');
        if (!$linkedCharts) {
            //No charts to display
            LoggerManager::getLogger()->warn('No charts to display to build report chart for AOR Report.');
            return array();
        }

        $sql = "SELECT id FROM aor_fields WHERE aor_report_id = '" . $bean->id . "' AND deleted = 0 ORDER BY field_order ASC";
        $result = $db->query($sql);

        $fields = array();
        $i = 0;

        $isMainGroupUsed = false;
        $mainGroupField = null;

        while ($row = $db->fetchByAssoc($result)) {
            $field = BeanFactory::newBean('AOR_Fields');
            $field->retrieve($row['id']);

            $path = unserialize(base64_decode($field->module_path));

            $fieldModuleBean = new $beanList[$bean->report_module]();

            $fieldModuleName = $bean->report_module;
            $fieldAlias = $fieldModuleBean->table_name;
            if ($path[0] != $bean->report_module) {
                foreach ($path as $rel) {
                    if (empty($rel)) {
                        continue;
                    }
                    $fieldModuleName = getRelatedModule($fieldModuleName, $rel);
                    $fieldAlias = $fieldAlias . ':' . $rel;
                }
            }
            $label = str_replace(' ', '_', $field->label) . $i;
            $fields[$label]['field'] = $field->field;
            $fields[$label]['label'] = $field->label;
            $fields[$label]['display'] = $field->display;
            $fields[$label]['function'] = $field->field_function;
            $fields[$label]['module'] = $fieldModuleName;
            $fields[$label]['alias'] = $fieldAlias;
            $fields[$label]['link'] = $field->link;
            $fields[$label]['total'] = $field->total;

            $fields[$label]['format'] = $field->format;

            // get the main group
            if ($field->group_display) {

                // if we have a main group already thats wrong cause only one main grouping field possible
                if (!is_null($mainGroupField)) {
                    $GLOBALS['log']->fatal('main group already found');
                }

                $isMainGroupUsed = true;
                $mainGroupField = $field;
            }
            ++$i;
        }

        $query = $bean->build_report_query();
        $result = $db->query($query);
        $data = array();
        while ($row = $db->fetchByAssoc($result, false)) {
            foreach ($fields as $name => $att) {
                $currency_id = $row[$att['alias'] . '_currency_id'] ?? '';

                if ($att['function'] != 'COUNT' && empty($att['format']) && !is_numeric($row[$name])) {
                    $row[$name] = trim(strip_tags(getModuleField(
                        $att['module'],
                        $att['field'],
                        $att['field'],
                        'DetailView',
                        $row[$name],
                        '',
                        $currency_id
                    )));
                }
            }
            $data[] = $row;
        }
        $fields = $bean->getReportFields();

        $result = array(
            'values' => $data,
            'fieldsData' => array(),
            'isMainGroupUsed' => $isMainGroupUsed,
            'mainGroupFieldIndex' => $mainGroupField->field_order,
            'charts' => array(),
        );

        foreach ($fields as $index => $field) {
            $result['fieldsData'][$index] = array(
                'id' => $field->id,
                'fieldOrder' => $field->field_order,
                'fieldName' => $field->field,
                'label' => $field->label,
                'display' => $field->display,
            );
        }

        foreach ($linkedCharts as $chart) {
            if ($chartIds !== null && !in_array($chart->id, $chartIds)) {
                continue;
            }

            $result['charts'][] = array(
                'id' => $chart->id,
                'name' => $chart->name,
                'x_field' => $chart->x_field,
                'y_field' => $chart->y_field,
                'type' => $chart->type,
            );
        }

        return $result;
    }


    /**
     * Options of the Function column in the Fields editing section for a specific field of a specific module.
     *
     * @param $args
     * @return array
     * @see modules/AOR_Reports/controller.php :: action_getModuleFunctionField()
     * @usage http://localhost/index.php?VueAjax=1&method=getModuleFunctionField&arg[aor_module]=Meetings&arg[aor_fieldname]=assigned_user_name
     */
    private function getModuleFunctionField($args)
    {
        global $app_list_strings;
        $result = $app_list_strings['aor_function_list'];

        return $result;
    }


    /**
     * Options of the Operator column in the Conditions editing section for a specific field of a specific module
     * @param $args
     * @return array
     * @see modules/AOR_Reports/controller.php :: action_getModuleOperatorField()
     * @usage http://localhost/index.php?VueAjax=1&method=getModuleOperatorField&arg[aor_module]=Meetings&arg[aor_fieldname]=assigned_user_name&arg[rel_field]=accounts
     */
    private function getModuleOperatorField($args)
    {
        require_once 'modules/AOW_WorkFlow/aow_utils.php';
        global $app_list_strings, $beanFiles, $beanList;

        $moduleName = $args['aor_module'];
        $relFieldName = $args['rel_field'];
        $fieldName = $args['aor_fieldname'];

        if (!empty($relFieldName)) {
            $moduleName = getRelatedModule($moduleName, $relFieldName);
        }

        $focus = new $beanList[$moduleName];
        $vardef = $focus->getFieldDefinition($fieldName);

        switch ($vardef['type']) {
            case 'double':
            case 'decimal':
            case 'float':
            case 'int':
            case 'tinyint':
            case 'short':
            case 'long':
            case 'ulong':
            case 'uint':
            case 'datetimecombo':
            case 'datetime':
            case 'date':
            case 'currency':
                $valid_opp = array(
                    'Equal_To',
                    'Not_Equal_To',
                    'Greater_Than',
                    'Less_Than',
                    'Greater_Than_or_Equal_To',
                    'Less_Than_or_Equal_To'
                );
                break;
            case 'enum':
                $valid_opp = array('Equal_To', 'Not_Equal_To');
                break;
            case 'multienum':
                $valid_opp = array('Equal_To', 'Not_Equal_To', 'Contains');
                break;
            default:
                $valid_opp = array('Equal_To', 'Not_Equal_To', 'Contains', 'Starts_With', 'Ends_With',);
                break;
        }

        foreach ($app_list_strings['aor_operator_list'] as $key => $keyValue) {
            if (!in_array($key, $valid_opp)) {
                unset($app_list_strings['aor_operator_list'][$key]);
            }
        }

        $result = $app_list_strings['aor_operator_list'];

        return $result;
    }


    /**
     * Options of the Type column in the Conditions editing section for a specific field of a specific module
     * @param $args
     * @return array
     * @see modules/AOR_Reports/controller.php::action_getFieldTypeOptions()
     * @usage http://localhost/index.php?VueAjax=1&method=getFieldTypeOptions&arg[aor_module]=Meetings&arg[aor_fieldname]=assigned_user_name&arg[rel_field]=accounts
     */
    private function getFieldTypeOptions($args)
    {
        require_once 'modules/AOW_WorkFlow/aow_utils.php';
        global $app_list_strings, $beanFiles, $beanList;

        $moduleName = $args['aor_module'];
        $relFieldName = $args['rel_field'];
        $fieldName = $args['aor_fieldname'];

        if (!empty($relFieldName)) {
            $relModuleName = getRelatedModule($moduleName, $relFieldName);
        }

        $focus = new $beanList[$moduleName];
        $vardef = $focus->getFieldDefinition($fieldName);

        switch ($vardef['type']) {
            case 'double':
            case 'decimal':
            case 'float':
            case 'int':
            case 'tinyint':
            case 'short':
            case 'long':
            case 'ulong':
            case 'uint':
            case 'currency':
                $valid_opp = array('Value', 'Field');
                break;
            case 'date':
            case 'datetime':
            case 'datetimecombo':
                $valid_opp = array('Value', 'Field', 'Date', 'Period');
                break;
            case 'enum':
            case 'dynamicenum':
            case 'multienum':
                $valid_opp = array('Value', 'Field', 'Multi');
                break;
            default:
                // Added to compare fields like assinged to with the current user
                if ((isset($vardef['module']) && $vardef['module'] == "Users") || $vardef['name'] = 'id') {
                    $valid_opp = array('Value', 'Field', 'CurrentUserID');
                } else {
                    $valid_opp = array('Value', 'Field');
                }

                break;
        }

        foreach ($app_list_strings['aor_condition_type_list'] as $key => $keyValue) {
            if (!in_array($key, $valid_opp)) {
                unset($app_list_strings['aor_condition_type_list'][$key]);
            }
        }

        $result = $app_list_strings['aor_condition_type_list'];

        return $result;
    }


    /**
     * Gets the field's wardefs for substituting its widget in the Value column in the Conditions editing section, taking into account the module and the value in the Type column.
     * If the value in the column is Type = "Current User ID", it returns an empty array.
     * @param $args
     * @return array
     * @see modules/AOR_Reports/controller.php::action_getModuleFieldType()
     * @usage http://localhost/index.php?VueAjax=1&method=getModuleFieldType&arg[aor_module]=Meetings&arg[aor_fieldname]=industry&arg[rel_field]=accounts&arg[aor_type]=Value
     */
    private function getModuleFieldType($args)
    {
        require_once 'modules/AOW_WorkFlow/aow_utils.php';
        global $app_list_strings, $beanFiles, $beanList;

        $moduleName = $args['aor_module'];
        $relFieldName = $args['rel_field'];
        $fieldName = $args['aor_fieldname'];
        $altModuleName = $args['alt_module'];  // I'm not sure if this parameter is used, but I left it as in the box
        $fieldType = $args['aor_type'];

        $relatedModuleName = $moduleName;
        if (!empty($relFieldName)) {
            $relatedModuleName = getRelatedModule($moduleName, $relFieldName);
        }

        switch ($fieldType) {
            case 'Field':
                if (!empty($altModuleName)) {
                    $moduleName = $altModuleName;
                }
                $result = getModuleFields($moduleName, 'JSON');
                break;
            case 'Date':
                $optionsWhen = $app_list_strings['aow_date_options'];
                unset($optionsWhen['field']);
                if (isset($beanList[$moduleName]) && $beanList[$moduleName]) {
                    $moduleBean = new $beanList[$moduleName]();
                    foreach ($moduleBean->field_defs as $name => $arr) {
                        if ($arr['type'] == 'date' || $arr['type'] == 'datetime' || $arr['type'] == 'datetimecombo') {
                            if (isset($arr['vname']) && $arr['vname'] != '') {
                                $optionsWhen[$name] = translate($arr['vname'], $moduleBean->module_dir);
                            } else {
                                $optionsWhen[$name] = $name;
                            }
                        }
                    } //End loop.
                }

                $optionsPeriod = $app_list_strings['aow_date_type_list'];
                if (!file_exists('modules/AOBH_BusinessHours/AOBH_BusinessHours.php')) {
                    unset($optionsPeriod['business_hours']);
                }

                $result = array(
                    'name' => 'customDateField',
                    'type' => 'Reports-EditView-customDateField',
                    'optionsWhen' => $optionsWhen,
                    'optionsArithmetic' => $app_list_strings['aow_date_operator'],
                    'optionsPeriod' => $optionsPeriod,
                );
                break;
            case 'Period':
                $result = array(
                    'name' => 'customPeriodField',
                    'type' => 'enum',
                    'options' => $app_list_strings['date_time_period_list'],
                );
                break;
            case 'CurrentUserID':
                $result = array();
                break;
            case 'Multi':
            case 'Value':
            default:
                $focus = new $beanList[$relatedModuleName];
                $vardef = $focus->getFieldDefinition($fieldName);

                if (isset($vardef['function'])) {
                    $vardef = $this->fieldFunctions->implement($vardef, $focus);
                }

                if (isset($vardef['options']) && is_string($vardef['options'])) {
                    $vardef['options'] = translate($vardef['options']);
                }

                $result = $vardef;
                break;
        }

        if ('Multi' === $fieldType) {
            $result['type'] = 'multienum';
        }

        return $result;
    }


    /**
     * Entrypoint for getting report data inside a specific group with a specified offset.
     * It is used when switching pagination.
     * @param $args
     * @return array
     * @throws Exception
     * @usage http://localhost/index.php?VueAjax=1&method=aorReportsGetReportData&arg[record]=6a19b649-cb36-d67f-139a-6566dff58923&arg[offset]=20&arg[group]=Planned
     */
    private function aorReportsGetReportData($args)
    {
        $offset = $args['offset'];
        $groupValue = $args['group'];
        $recordId = $args['record'];

        $bean = BeanFactory::getBean('AOR_Reports');
        if (null === $bean->retrieve($recordId)) {
            throw new Exception("No report with id = '$recordId' exists");
        }

        return $this->aorReportsBuildReportData($bean, $offset, true, $groupValue, create_guid(), array());
    }

}
