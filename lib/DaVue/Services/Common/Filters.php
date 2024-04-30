<?php

namespace SuiteCRM\DaVue\Services\Common;

use BeanFactory;
use SearchForm;

class Filters
{
    public function getRelateFilter($params){
        return $this->generateData($params);
    }

    public function getListViewFilter($params)
    {
        // Processing fields that have the option to select the type of filtering.
        // In the box, the filter type is always written to $_REQUEST, even if it wasn't there.
        $filterChangingFieldTypes = array('currency', 'date', 'datetime', 'datetimecombo');
        foreach ($params['fields'] as $fieldName => $field) {
            if (isset($field['type']) && in_array($field['type'], $filterChangingFieldTypes)) {
                if (isset($_REQUEST[$fieldName . '_range_choice'])) {
                    $params['fields'][$fieldName . '_range_choice'] = array('value' => $_REQUEST[$fieldName . '_range_choice']);
                } else {
                    $params['fields'][$fieldName . '_range_choice'] = array('value' => '=');
                }
            }
        }

        if ($params['viewTab'] == 'basic'){
            $addFormType = 'advanced';
        }else{
            $addFormType = 'basic';
        }

        $addFormData = $this->getPrepareSearchForm($params['module'], $addFormType . '_search');

        $currentForm = $this->generateData($params);
        $addForm = $this->generateData($addFormData);

        $result = [
            'layout' => [
                $params['viewTab'] => $currentForm['layout'],
                $addFormType => $addForm['layout'],
            ],
            'fieldMetadata' => [
                $params['viewTab'] => $currentForm['fieldMetadata'],
                $addFormType => $addForm['fieldMetadata'],
            ],
            'values' => [
                $params['viewTab'] => $currentForm['values'],
                $addFormType => $addForm['values'],
            ],
        ];

        if (!empty($_REQUEST['subAction'])) {
            foreach ($result['fieldMetadata']['advanced'] as $key => $value) {
                $result['values'][strtolower($_REQUEST['subAction'])][$key] = null;
            }
        }

        return $result;
    }

    private function generateData($params): array
    {
        $layout = [];
        $fieldMetadata = [];
        $values = [];

        foreach ($params['formData'] as $index => $field) {

            $fieldName = $field['field']['name'];

            /********* LAYOUT ******/
            $layout[] = $fieldName;

            /********* METADATA ******/
            $vardefs = empty($params['fields'][$fieldName]) ? [] : $params['fields'][$fieldName];
            $metadata = empty($field['field']) ? [] : $field['field'];
            $customMetadata = empty($params['customFields'][$fieldName]) ? [] : $params['customFields'][$fieldName];
            $fieldMetadata[$fieldName] = array_merge($vardefs, $metadata, $customMetadata);

            $function = $fieldMetadata[$fieldName]['function'];
            if (is_array($function) && isset($function['name'])) {
                $function_name = $fieldMetadata[$fieldName]['function']['name'];
            } else {
                $function_name = $fieldMetadata[$fieldName]['function'];
            }

            if (!empty($fieldMetadata[$fieldName]['function']['include'])) {
                require_once($fieldMetadata[$fieldName]['function']['include']);
            }

            if (is_array($function) && (isset($function['params']) || is_array($function['params']))) {
                $fieldMetadata[$fieldName]['options'] = call_user_func_array($function_name, $function['params']);
            }

            unset($fieldMetadata[$fieldName]['value']);

            /********* VALUES ******/
            if (isset($params['customFields'][$fieldName])){
                $values[$fieldName] = $params['customFields'][$fieldName]['value'];//$params["customFields"]["account_name"]["value"]
            } else {
                $values[$fieldName] = $params['fields'][$fieldName]['value'];
            }
        }

        $fieldValues = [];
        foreach ($params['fields'] as $key => $field){
            if (!empty($field['value'])){
                $fieldValues[$key] = $field['value'];
            }
        }

        return [
            'layout' => $layout,
            'fieldMetadata' => $fieldMetadata,
            'values' => array_merge($fieldValues, $values)
        ];
    }

    /**
     * Getting data for forms with the necessary postfixes
     * @see ViewList::prepareSearchForm
     *
     * @param $moduleName
     * @param $searchType - 'basic_search' | 'advanced_search'
     * @return array
     */
    public function getPrepareSearchForm($moduleName, $searchType): array
    {
        $moduleBean = BeanFactory::getBean($moduleName);
        $viewDefs[$moduleName] = array();
        $searchMetaData = SearchForm::retrieveSearchDefs($moduleName);
        $searchForm = new SearchForm($moduleBean, $moduleName);

        $searchForm->setup($searchMetaData['searchdefs'], $searchMetaData['searchFields'], '', $searchType, $viewDefs);

        $result = array(
            'fields' => $searchForm->fieldDefs,
            'formData' => $searchForm->formData
        );

        // The standard basic_search does not have data from the saved Search Form, so we get it forcibly
        if ($searchType == 'advanced_search') {
            $this->savedSearchForm = $searchForm->displaySavedSearch(true);
        }

        return $result;
    }
}
