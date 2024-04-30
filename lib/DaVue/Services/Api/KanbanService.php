<?php

namespace SuiteCRM\DaVue\Services\Api;

use BeanFactory;
use SearchForm;
use SuiteCRM\DaVue\Services\Common\Filters;

class KanbanService
{
    /** @var Filters */
    private $filters;

    public function __construct(Filters $filters)
    {
        $this->filters = $filters;
    }

    /**
     * Getting search fields (Filter)
     *
     * @param $arg
     * @return array
     */
    public function getKanbanSearchForm($arg): array
    {
        $searchData = $this->filters->getPrepareSearchForm($arg['module_name'], 'advanced_search');
        $filter = $this->filters->getRelateFilter($searchData);

        $result = [
            'layout' => array(
                'advanced'=> $filter['layout'],
            ),
            'fieldMetadata' => array(
                'advanced'=> $filter['fieldMetadata'],
            ),
            'values' => array(
                'advanced'=> $filter['values'],
            ),
        ];

        return array(
            'filters' => $result,
        );
    }


    /**
     * Receiving data for Kanban
     *
     * @param $arg
     * @return array
     * @usage http://localhost/index.php?VueAjax=1&method=getKanbanData&arg[]...
     */
    public function getKanbanData($arg)
    {
        $moduleBean = BeanFactory::getBean($arg['module_name']);
        $viewDefs[$arg['module_name']] = array();
        $searchMetaData = SearchForm::retrieveSearchDefs($arg['module_name']);
        $searchForm = new SearchForm($moduleBean, $arg['module_name'], 'index');

        $searchForm->setup($searchMetaData['searchdefs'], $searchMetaData['searchFields'], '', 'advanced_search', $viewDefs);

        $searchForm->populateFromRequest();

        $recordAccess = array();
        $recordList = array();
        $searchQuery = '';

        $where_clauses = $searchForm->generateSearchWhere(true, $moduleBean->module_dir);

        if (count($where_clauses) > 0) {
            $searchQuery = 'AND ' . '(' . implode(' ) AND ( ', $where_clauses) . ')';
        }

        // If getBeanData === true (true/false), return beanData only
        if ($arg['get_bean_data']) {
            return array(
                'beanData' => $moduleBean->field_defs,
            );
        }

        if ($arg['order']) {
            $order = $arg['order'];
        } else {
            $order = 'date_modified';
        }

        if ($arg['offset']) {
            $offset = $arg['offset'];
        } else {
            $offset = '0';
        }

        if ($arg['sortDirection'] !== '') {
            $sortDirection = $arg['sortDirection'];
        } else {
            $sortDirection = 'DESC';
        }

        $tableName = strtolower($arg['module_name']);


        $beanList = $moduleBean->get_list(
            $order . ' ' . $sortDirection,
            "$tableName.{$arg['status_field_name']} = '{$arg['status_field_value']}' $searchQuery",
            $offset
        );


        // If getRowCount === true (true/false), return rowCount only
        if ($arg['get_row_count']) {
            return array(
                'rowCount' => $beanList['row_count'],
            );
        }

        if ($beanList) {
            foreach ($beanList['list'] as $record) {
                $recordList[$record->id] = $record->toArray();
                $recordAccess[$record->id] = array(
                    'detail' => $record->ACLAccess('detail'),
                    'edit' => $record->ACLAccess('edit'),
                    'delete' => $record->ACLAccess('delete')
                );

            }
        }


        return array(
            'beanData' => $moduleBean->field_defs,
            'pageData' => array(
                'sortDirection' => $sortDirection,
            ),
            'viewData' => array(
                $arg['status_field_value'] => array(
                    'recordAccess' => $recordAccess,
                    'recordList' => $recordList,
                    'nextOffset' => $beanList['next_offset'] ?? '0',
                    'currentOffset' => $beanList['current_offset'] ?? '0',
                    'rowCount' => $beanList['row_count'],
                )
            ),
        );
    }

    /**
     * @usage http://localhost/index.php?method=saveKanban&VueAjax=true
     * @param $arg
     * @return false|string
     */
    public function saveKanban($arg)
    {

        if (empty($_POST['module']) || empty($_POST['record']) || empty($_POST['field'])) {
            return false;
        }

        $field = $_POST['field'];
        $bean = BeanFactory::getBean($_POST['module'], $_POST['record']);

        if ($bean->$field == $_POST['value']) {
            return false;
        }

        $bean->$field = $_POST['value'];
        return $bean->save();
    }

}
