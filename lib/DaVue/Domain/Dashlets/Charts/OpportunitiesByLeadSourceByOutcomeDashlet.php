<?php

namespace SuiteCRM\DaVue\Domain\Dashlets\Charts;

use BeanFactory;
use Dashlet;
use SuiteCRM\DaVue\Domain\Dashlets\DashletHandlerInterface;

class OpportunitiesByLeadSourceByOutcomeDashlet implements DashletHandlerInterface
{
    public $lsbo_lead_sources = array();
    public $lsbo_ids          = array();

    protected $_seedName = 'Opportunities';

    /* @var int */
    protected $height;

    /* @var string */
    protected $title;

    /* @var array */
    protected $currency;

    /* @var array */
    protected $_searchFields;

    /* @var array */
    protected $autoRefreshOptions;

    /* @var string */
    protected $id;

    public function configure(Dashlet $focus): array
    {
        $this->buildSettings($focus->lsbo_lead_sources, $focus->lsbo_ids);

        return array(
            'basicSettings' => array(
                'dashletTitle' => array(
                    'name' => 'dashletTitle',
                    'type' => 'varchar',
                    'label' => translate('LBL_DASHLET_OPT_TITLE', 'Home'),
                    'value' => $focus->title,
                ),
                'autoRefresh' => array(
                    'name' => 'autoRefresh',
                    'type' => 'enum',
                    'options' => $this->autoRefreshOptions,
                    'label' => 'LBL_DASHLET_CONFIGURE_AUTOREFRESH',
                    'value' => $focus->autoRefresh,
                ),

            ),
            'filters' => array(
                'lsbo_lead_sources' => array(
                    'name' => 'lsbo_lead_sources',
                    'type' => 'multienum',
                    'options' => $this->_searchFields['lsbo_lead_sources']['options'],
                    'label' => translate('LBL_LEAD_SOURCES', 'Charts'),
                    'value' => $this->_searchFields['lsbo_lead_sources']['values'],
                ),
                'lsbo_ids' => array(
                    'name' => 'lsbo_ids',
                    'type' => 'multienum',
                    'options' => $this->_searchFields['lsbo_ids']['options'],
                    'label' => translate('LBL_USERS', 'Charts'),
                    'value' => $this->_searchFields['lsbo_ids']['values'],
                ),
            ),
        );
    }

    /**
     * @param array $lsbo_lead_sources
     * @param array $lsbo_ids
     * @return void
     */
    protected function buildSettings(array $lsbo_lead_sources, array $lsbo_ids): void
    {
        global $app_list_strings;

        $this->autoRefreshOptions = $this->getAutoRefreshOptions();

        $selected_lead_sources = array();
        $selected_ids = array();

        if (!empty($lsbo_lead_sources) && count($lsbo_lead_sources) > 0) {
            foreach ($lsbo_lead_sources as $key) {
                $selected_lead_sources[] = $key;
            }
        } else {
            $selected_lead_sources = array_keys($app_list_strings['lead_source_dom']);
        }

        if (!empty($lsbo_ids) && count($lsbo_ids) > 0) {
            foreach ($lsbo_ids as $key) {
                $selected_ids[] = $key;
            }
        } else {
            $selected_ids = array_keys(get_user_array(false));
        }

        $this->_searchFields['lsbo_lead_sources']['options'] = array_filter($app_list_strings['lead_source_dom']);
        $this->_searchFields['lsbo_lead_sources']['values'] = $selected_lead_sources;

        $this->_searchFields['lsbo_ids']['options'] = get_user_array(false);
        $this->_searchFields['lsbo_ids']['values'] = $selected_ids;
    }

    /**
     * Returns the available auto refresh settings you can set a dashlet to
     *
     * @return array options available
     */
    protected function getAutoRefreshOptions(): array
    {
        $options = $GLOBALS['app_list_strings']['dashlet_auto_refresh_options'];

        if (isset($GLOBALS['sugar_config']['dashlet_auto_refresh_min'])) {
            foreach ($options as $time => $desc) {
                if ($time !== -1 && $time < $GLOBALS['sugar_config']['dashlet_auto_refresh_min']) {
                    unset($options[$time]);
                }
            }
        }

        return $options;
    }

    public function display($dashletId, $dashletData, $dashletOptions, $dashletType): array
    {
        $this->id = $dashletId;
        $this->title = $this->getTitle($dashletData, $dashletOptions['title']);
        $this->setProps($dashletOptions);

        return array(
            'id' => $this->id,
            'label' => $this->title,
            'type' => $dashletType,
            'options' => array(
                'height' => 500,
            ),

            'pageData' => array(
                'urls' => array(
                    'startPage' => null,
                    'prevPage' => null,
                    'nextPage' => null,
                    'endPage' => null,
                ),
                'bean' => array(
                    'moduleDir'  => 'Opportunities',
                    'moduleName' => 'Opportunities',
                ),
                'offsets' => array(
                    'lastOffsetOnPage' => null,
                ),
            ),
            'viewData' => array(
                'displayColumns' => array(),
                'data' => $this->getDashletChart(),
            ),
        );
    }

    /**
     * @param string|null $dashletTitle
     * @param array $dashletData
     * @return string
     */
    protected function getTitle(array $dashletData, string $dashletTitle = null): string
    {
        $dashletHeaderData = json_decode($dashletData['displayHeader'], true);

        if (!empty($dashletTitle)) {
            return $dashletTitle;
        } else {
            return $dashletHeaderData['label'];
        }
    }

    /**
     * Setting needs properties in dashlet class
     * @param array|null $dashletOptions
     * @return void
     */
    protected function setProps(array $dashletOptions = null): void
    {
        if (isset($dashletOptions)) {
            foreach ($dashletOptions as $key => $value) {
                $this->$key = $value;
            }
        }
    }

    /**
     * Return full dashlet data in format frontend needs
     * @return array
     */
    protected function getDashletChart(): array
    {
        $this->setCurrency();

        $data = $this->getChartData($this->constructQuery());
        $data = $this->sortData($data, 'lead_source', true, 'sales_stage', true, true);

        return $this->rebuildChartData($data);
    }

    /* @return void */
    protected function setCurrency(): void
    {
        global $current_user;

        $this->currency = BeanFactory::newBean('Currencies');

        $currency_symbol = $this->currency->getDefaultCurrencySymbol();
        $this->currency->retrieve($this->currency->retrieveIDBySymbol($currency_symbol));

        if ($current_user->getPreference('currency')) {
            $this->currency->retrieve($current_user->getPreference('currency'));
        }
    }

    /* @return string */
    protected function constructQuery(): string
    {
        $query = "SELECT lead_source,sales_stage,sum(amount_usdollar/1000) as total, ";
        $query .= " count(*) as opp_count FROM opportunities WHERE opportunities.deleted=0";
        if (count($this->lsbo_ids) > 0) {
            $query .= " AND opportunities.assigned_user_id IN ('" . implode("','", $this->lsbo_ids) . "') ";
        }
        if (count($this->lsbo_lead_sources) > 0) {
            $query .= " AND opportunities.lead_source IN ('" . implode("','", $this->lsbo_lead_sources) . "') ";
        } else {
            $query .= " AND opportunities.lead_source IN ('" . implode("','", array_keys($GLOBALS['app_list_strings']['lead_source_dom'])) . "') ";
        }
        $query .= " GROUP BY sales_stage,lead_source ORDER BY lead_source,sales_stage";

        return $query;
    }

    /**
     * @see DashletGenericChart original method
     * @param string $query
     * @return array
     */
    protected function getChartData(string $query): array
    {
        global $db;

        $dataSet = array();
        $result = $db->query($query);

        $row = $db->fetchByAssoc($result);

        while ($row != null) {
            $dataSet[] = $row;
            $row = $db->fetchByAssoc($result);
        }

        return $dataSet;
    }

    /**
     * @see DashletGenericChart original methods
     * @param array $data_set
     * @param string|null $keyColName1
     * @param bool $translate1
     * @param string|null $keyColName2
     * @param bool $translate2
     * @param bool $ifSort2
     * @return array
     *
     */
    protected function sortData(
        array $data_set,
        string $keyColName1 = null,
        bool $translate1 = false,
        string $keyColName2 = null,
        bool $translate2 = false,
        bool $ifSort2 = false
    ): array
    {
        global $app_list_strings;

        $sortBy1[] = array();

        foreach ($data_set as $row) {
            $sortBy1[]  = $row[$keyColName1];
        }

        $sortBy1 = array_unique($sortBy1);

        if ($translate1) {
            $temp_sortBy1 = array();

            foreach (array_keys($app_list_strings[$keyColName1.'_dom']) as $sortBy1_value) {
                if (in_array($sortBy1_value, $sortBy1)) {
                    $temp_sortBy1[] = $sortBy1_value;
                }
            }

            $sortBy1 = $temp_sortBy1;
        }

        if (isset($sortBy1[0]) && $sortBy1[0] === array()) {
            unset($sortBy1[0]);
        }

        if ($ifSort2 === false) {
            $sortBy2 = array(0);
        }

        if ($keyColName2 !== null) {
            $sortBy2 = array();

            foreach ($data_set as $row) {
                $sortBy2[] = $row[$keyColName2];
            }

            $sortBy2 = array_unique($sortBy2);

            if ($translate2) {
                $temp_sortBy2 = array();

                foreach (array_keys($app_list_strings[$keyColName2.'_dom']) as $sortBy2_value) {
                    if (in_array($sortBy2_value, $sortBy2)) {
                        $temp_sortBy2[] = $sortBy2_value;
                    }
                }

                $sortBy2 = $temp_sortBy2;
            }
        }

        $data = array();

        foreach ($sortBy1 as $sort1) {
            foreach ($sortBy2 as $sort2) {
                if ($ifSort2) {
                    $a = 0;
                }
                foreach ($data_set as $key => $value) {
                    if ($value[$keyColName1] === $sort1 && (!$ifSort2 || $value[$keyColName2] === $sort2)) {
                        if ($translate1) {
                            $value[$keyColName1.'_dom_option'] = $value[$keyColName1];
                            $value[$keyColName1] = $app_list_strings[$keyColName1.'_dom'][$value[$keyColName1]];
                        }

                        if ($translate2) {
                            $value[$keyColName2.'_dom_option'] = $value[$keyColName2];
                            $value[$keyColName2] = $app_list_strings[$keyColName2.'_dom'][$value[$keyColName2]];
                        }

                        $data[] = $value;

                        unset($data_set[$key]);

                        $a = 1;
                    }
                }

                if ($ifSort2 && $a === 0) {
                    $val = array();
                    $val['total'] = 0;
                    $val['count'] = 0;

                    if ($translate1) {
                        $val[$keyColName1] = $app_list_strings[$keyColName1.'_dom'][$sort1];
                        $val[$keyColName1.'_dom_option'] = $sort1;
                    } else {
                        $val[$keyColName1] = $sort1;
                    }

                    if ($translate2) {
                        $val[$keyColName2] = $app_list_strings[$keyColName2.'_dom'][$sort2];
                        $val[$keyColName2.'_dom_option'] = $sort2;
                    } elseif ($keyColName2 !== null) {
                        $val[$keyColName2] = $sort2;
                    }

                    $data[] = $val;
                }
            }
        }

        return $data;
    }

    /**
     * Rebuild data in chart
     * @param array $data
     * @return array
     */
    protected function rebuildChartData(array $data): array
    {
        $salesStageLabel = translate('LBL_SALES_STAGES', 'Charts');
        $amountLabel = translate('LBL_LIST_AMOUNT', 'Opportunities');
        $leadSourceLabel = translate('LBL_LEAD_SOURCE', 'Opportunities');

        $x_field = '0';
        $y_field = '1';
        $main_field = '2';

        $reportData = $this->buildReportData(
            $data,
            $x_field,
            $y_field,
            $main_field,
            $salesStageLabel,
            $amountLabel,
            $leadSourceLabel
        );

        return array(
            'chart' => array(
                'id' => $this->id,
                'name' => $this->title,
                'type' => 'stacked_bar',
                'x_field' => $x_field,
                'y_field' => $y_field,
            ),
            'reportData' => array(
                'data' => $reportData,
                'isGroupsUsed' => true,
            ),
            'fieldsData' => array(
                '0' => array(
                    'display' => '1',
                    'fieldName' => 'sales_stage',
                    'fieldOrder' => $x_field,
                    'label' => $salesStageLabel,
                ),
                '1' => array(
                    'display' => '1',
                    'fieldName' => 'amount',
                    'fieldOrder' => $y_field,
                    'label' => $amountLabel,
                ),
                '2' => array(
                    'display' => '1',
                    'fieldName' => 'lead_source',
                    'fieldOrder' => $main_field,
                    'label' => $leadSourceLabel,
                ),
            ),
            'mainGroupFieldIndex' => $main_field,
        );
    }

    /**
     * Collect $reportData
     * @param array $data
     * @param string $x_field
     * @param string $y_field
     * @param string $main_field
     * @param string $x_field_label
     * @param string $y_field_label
     * @param string $main_field_label
     * @return array
     */
    protected function buildReportData(
        array  $data,
        string $x_field,
        string $y_field,
        string $main_field,
        string $x_field_label,
        string $y_field_label,
        string $main_field_label
    ): array
    {
        $reportData = array();

        $xFieldKey = str_replace(' ', '_', $x_field_label) . $x_field;
        $yFieldKey = str_replace(' ', '_', $y_field_label) . $y_field;
        $mainFieldKey = str_replace(' ', '_', $main_field_label) . $main_field;


        foreach ($data as $i) {
            if (!empty($i['sales_stage'])) {
                if (empty($reportData[$i['sales_stage']])) {
                    $reportData[$i['sales_stage']] = array(
                        'pageData' => array(),
                        'viewData' => array(
                            'data' => array(),
                            'displayColumns' => array(
                                $xFieldKey => array(
                                    'alias' => 'opportunities:lead_source',
                                    'display' => '1',
                                    'field' => 'lead_source',
                                    'format' => '',
                                    'function' => '',
                                    'label' => $x_field_label,
                                    'link' => '0',
                                    'module' => $this->_seedName,
                                    'params' => array(),
                                    'total' => '',
                                    'type' => null
                                ),
                                $yFieldKey => array(
                                    'alias' => 'opportunities:amount',
                                    'display' => '1',
                                    'field' => 'amount',
                                    'format' => '',
                                    'function' => '',
                                    'label' => $y_field_label,
                                    'link' => '0',
                                    'module' => $this->_seedName,
                                    'params' => array(),
                                    'total' => 'COUNT',
                                    'type' => null
                                ),
                                $mainFieldKey => array(
                                    'alias' => 'opportunities:sales_stage',
                                    'display' => '1',
                                    'field' => 'sales_stage',
                                    'format' => '',
                                    'function' => '',
                                    'label' => $main_field_label,
                                    'link' => '0',
                                    'module' => $this->_seedName,
                                    'params' => array(),
                                    'total' => '',
                                    'type' => null
                                ),
                            ),
                            'isShowTotal' => true,
                            'totals' => array(
                                $yFieldKey => (int)$i['total']
                            ),
                        )
                    );
                }
                $reportData[$i['sales_stage']]['viewData']['data'][] = array(
                    $xFieldKey => $i['lead_source'],
                    $yFieldKey => (int)$i['total'],
                    $mainFieldKey => $i['sales_stage'],
                );
            }
        }

        return $reportData;
    }
}
