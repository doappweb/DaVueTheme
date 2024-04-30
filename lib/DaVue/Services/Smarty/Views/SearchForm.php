<?php

namespace SuiteCRM\DaVue\Services\Smarty\Views;

use SavedSearch;
use SuiteCRM\DaVue\Services\Common\Filters;
use ViewList;

class SearchForm implements ViewHandlerInterface
{
    /** @var Filters */
    private $filters;

    private $params;
    private $result = [];

    public function __construct(Filters $filters)
    {
        $this->filters = $filters;
    }

    public function handle($params): array
    {
        if (!is_array($params)){
            return $this->result;
        }

        $this->params = $params;
        $this->preHandler();
        $this->generate();

        return $this->result;
    }


    private function preHandler() {
        // Preventing false triggering of a parameter
        if (isset($_REQUEST['searchFormTab']) && isset($_REQUEST['type_basic']) && $_REQUEST['type_basic'] === ''){
            unset($_REQUEST['type_basic']);
        }
    }

    private function generate() {
        $this->result = [
            'columnChooser'  => $this->getColumnChooser(),
            'filters'        => $this->filters->getListViewFilter($this->params),
            'searchData' => [
                'displayView'     => $this->params['displayView'],
                'displayType'     => $this->params['displayType'],
                'viewTab'         => $this->params['viewTab'],
                'searchInfoJson'  => $this->getSearchInfo($this->params['fields'], $this->params['viewTab'], $this->params['formData']),
                'savedSearchData' => $this->params['savedSearchData'],
            ],
        ];
    }

    /**
     * Returns a list of parameters with their values applied on the ListView search form.
     * This functionality is already ready in the box, but it works with errors there (see 74-SFR)
     *
     * @param array $fields
     * @param string $viewTab
     * @param array $formData
     * @return array
     * @see include/SearchForm/SearchForm2.php::getSearchInfo()
     */
    private function getSearchInfo(array $fields, string $viewTab, array $formData): array
    {
        global $app_strings, $mod_strings;
        $result = array();

        $searchFieldNames = array();
        foreach ($formData as $fieldData) {
            $searchFieldNames[] = $fieldData['field']['name'];
        }

        // Types of fields, with a choice of filtering type
        $filterChangingFieldTypes = array('currency', 'date', 'datetime', 'datetimecombo');

        foreach ($fields as $fieldName => $field) {
            if (in_array($fieldName, $searchFieldNames)) {
                preg_match('/_' . $viewTab . '$/', $fieldName, $match);  // $fieldName ends in "_$viewTab"
                if (!empty($match)) {
                    if (
                        isset($field['value']) && $field['value']
                        || isset($field['type']) && in_array($field['type'], $filterChangingFieldTypes)
                    ) {

                        if (isset($field['vname'])) {
                            if (isset($mod_strings[$field['vname']])) {
                                $labelText = $mod_strings[$field['vname']];
                            } elseif (isset($app_strings[$field['vname']])) {
                                $labelText = $app_strings[$field['vname']];
                            } else {
                                $labelText = $field['vname'];
                            }
                        } elseif (isset($field['label'])) {
                            if (isset($mod_strings[$field['label']])) {
                                $labelText = $mod_strings[$field['label']];
                            } elseif (isset($app_strings[$field['label']])) {
                                $labelText = $app_strings[$field['label']];
                            } else {
                                $labelText = $field['vname'];
                            }
                        }

                        if (!preg_match('/\:\s*/', $labelText)) {
                            // Add ":" to the end of the language string, if it is not there
                            $labelText .= ':';
                        }

                        if ($field['type'] === 'bool') {
                            $value = '✔';
                        }

                        elseif (in_array($field['type'], $filterChangingFieldTypes)) {
                            $rangeChoice = $fields[$fieldName . '_range_choice']['value'];
                            if ('between' === $rangeChoice) {
                                $startRange = $fields['start_range_' . $fieldName]['value'];
                                $endRange = $fields['end_range_' . $fieldName]['value'];
                                if (
                                    isset($startRange) && '' !== $startRange
                                    && isset($endRange) && '' !== $endRange
                                ) {
                                    $value = "{$field['options'][$rangeChoice]} $startRange, $endRange";
                                } else {
                                    continue;
                                }
                            } else {
                                $range = $fields['range_' . $fieldName]['value'];
                                if (isset($range) && '' !== $range) {
                                    $value = "{$field['options'][$rangeChoice]} $range";
                                } else {
                                    continue;
                                }
                            }
                        }

                        else {
                            $value = $field['value'];

                            if (is_array($value)) {
                                $values = array();
                                foreach ($value as $key) {
                                    if (isset($field['options'][$key]) && $field['options'][$key]) {
                                        $values[$key] = $field['options'][$key];
                                    } else {
                                        if (in_array($key, $value)) {
                                            foreach ($fields as $fvalue) {
                                                if (isset($fvalue['options']) && is_array($fvalue['options'])) {
                                                    foreach ($fvalue['options'] as $okey => $ovalue) {
                                                        if ($okey == $key) {
                                                            $values[$key] = $ovalue;
                                                            $fieldOptionValueFound = true;
                                                            break 2;
                                                        }
                                                    }
                                                }
                                            }
                                            if (!isset($fieldOptionValueFound)) {
                                                $values[$key] = $key;
                                            }
                                        } elseif (isset($value[$key])) {
                                            $values[$key] = $value[$key];
                                        } else {
                                            $values[$key] = '?';
                                        }
                                    }
                                }
                                $value = implode(', ', $values);
                            }
                        }

                        $result[$labelText] = $value;
                    }
                }
            }
        }

        return $result;
    }

    /**
     * Lists of displayed and available fields on the ListView.
     *
     * @see include/templates/TemplateGroupChooser.php
     * @return array
     */
    private function getColumnChooser(): array
    {
        $moduleName = $_REQUEST['module'];

        // Metadata for the current module
        $viewList = new ViewList();
        $viewList->module = $moduleName;
        $metadataFile = $viewList->getMetaDataFile();
        if (file_exists($metadataFile)) {
            require($metadataFile);
        }
        if (isset($listViewDefs)) {
            $viewList->listViewDefs = $listViewDefs;
        } else {
            return array(
                'displayedFields' => array(),
                'hiddenFields' => array(),
            );
        }

        $savedSearch = new SavedSearch($viewList->listViewDefs[$moduleName], null, 'DESC');
        $savedSearch->getTemplateGroupChooser($moduleName);
        $displayedFields = $savedSearch->lastTemplateGroupChooser->args['values_array'][0];
        $hiddenFields = $savedSearch->lastTemplateGroupChooser->args['values_array'][1];

        return array(
            'displayedFields' => $displayedFields,
            'hiddenFields' => $hiddenFields,
        );
    }

    /**
     * @deprecated
     *
     * @param $params
     * @return array
     */
    private function savedSearchForm($params): array
    {
        $result = array();

        if (!is_array($params)){
            return $result;
        }

        $result = array(
            'searchModule'      =>  $params['SEARCH_MODULE'],
            'selectedOrderBy'   =>  $params['selectedOrderBy'],
            'selectedSortOrder' =>  $params['selectedSortOrder'],
            'orderBySelectOnly' =>  $params['orderBySelectOnly'],
            'lastSavedView'     =>  $params['lastSavedView'],
        );

        return $result;
    }
}
