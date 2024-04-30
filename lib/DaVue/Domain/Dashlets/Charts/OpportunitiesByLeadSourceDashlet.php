<?php

namespace SuiteCRM\DaVue\Domain\Dashlets\Charts;

use Dashlet;
use DBManagerFactory;
use SuiteCRM\DaVue\Domain\Dashlets\DashletHandlerInterface;

class OpportunitiesByLeadSourceDashlet implements DashletHandlerInterface
{
    public $pbls_lead_sources = array();
    public $pbls_ids          = array();

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
        $this->buildSettings($focus->pbls_lead_sources, $focus->pbls_ids);

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
                'pbls_lead_sources' => array(
                    'name' => 'pbls_lead_sources',
                    'type' => 'multienum',
                    'options' => $this->_searchFields['pbls_lead_sources']['options'],
                    'label' => translate('LBL_LEAD_SOURCES', 'Charts'),
                    'value' => $this->_searchFields['pbls_lead_sources']['values'],
                ),
                'pbls_ids' => array(
                    'name' => 'pbls_ids',
                    'type' => 'multienum',
                    'options' => $this->_searchFields['pbls_ids']['options'],
                    'label' => translate('LBL_USERS', 'Charts'),
                    'value' => $this->_searchFields['pbls_ids']['values'],
                ),
            ),
        );
    }

    /**
     * @param array $pbls_lead_sources
     * @param array $pbls_ids
     * @return void
     */
    protected function buildSettings(array $pbls_lead_sources, array $pbls_ids): void
    {
        global $app_list_strings;

        $this->autoRefreshOptions = $this->getAutoRefreshOptions();

        $selected_lead_sources = array();
        $selected_ids          = array();

        if (!empty($pbls_lead_sources) && count($pbls_lead_sources) > 0) {
            foreach ($pbls_lead_sources as $key) {
                $selected_lead_sources[] = $key;
            }
        } else {
            $selected_lead_sources = array_keys($app_list_strings['lead_source_dom']);
        }

        if (!empty($pbls_ids) && count($pbls_ids) > 0) {
            foreach ($pbls_ids as $key) {
                $selected_ids[] = $key;
            }
        } else {
            $selected_ids = array_keys(get_user_array(false));
        }

        $this->_searchFields['pbls_lead_sources']['options'] = array_filter($app_list_strings['lead_source_dom']);
        $this->_searchFields['pbls_lead_sources']['values']  = $selected_lead_sources;

        $this->_searchFields['pbls_ids']['options'] = get_user_array(false);
        $this->_searchFields['pbls_ids']['values']  = $selected_ids;
    }

    /**
     * Returns the available auto refresh settings you can set a dashlet to
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
        $data = $this->getChartData($this->constructQuery());

        return $this->rebuildChartData($data);
    }

    /* @return string */
    protected function constructQuery(): string
    {
        $query = "SELECT lead_source,sum(amount_usdollar/1000) as total,count(*) as opp_count ".
            "FROM opportunities ";
        $query .= "WHERE opportunities.deleted=0 ";
        if (count($this->pbls_ids) > 0) {
            $query .= "AND opportunities.assigned_user_id IN ('".implode("','", $this->pbls_ids)."') ";
        }
        if (count($this->pbls_lead_sources) > 0) {
            $query .= "AND opportunities.lead_source IN ('".implode("','", $this->pbls_lead_sources)."') ";
        } else {
            $query .= "AND opportunities.lead_source IN ('".implode("','", array_keys($GLOBALS['app_list_strings']['lead_source_dom']))."') ";
        }
        $query .= "GROUP BY lead_source ORDER BY total DESC";

        return $query;
    }

    /**
     * @param $query
     * @return array
     */
    protected function getChartData($query): array
    {
        global $app_list_strings;
        $db = DBManagerFactory::getInstance();
        $dataSet = [];
        $result = $db->query($query);

        $row = $db->fetchByAssoc($result);

        while ($row != null) {
            if (isset($row['lead_source']) && $app_list_strings['lead_source_dom'][$row['lead_source']]) {
                $row['lead_source_key'] = $row['lead_source'];
                $row['lead_source'] = $app_list_strings['lead_source_dom'][$row['lead_source']];
            }
            $dataSet[] = $row;
            $row = $db->fetchByAssoc($result);
        }
        return $dataSet;
    }

    /**
     * Rebuild data in chart
     * @param array $data
     * @return array
     */
    protected function rebuildChartData(array $data): array
    {
        $leadSourceLabel  = translate('LBL_AN_LEADS_LEAD_SOURCE', 'Spots');
        $amountLabel      = translate('LBL_LIST_AMOUNT', 'Opportunities');

        $x_field = '0';
        $y_field = '1';

        $reportData = $this->buildReportData($data, $x_field, $y_field, $leadSourceLabel, $amountLabel);

        return array(
            'chart' => array(
                'id'      => $this->id,
                'name'    => $this->title,
                'type'    => 'pie',
                'x_field' => $x_field,
                'y_field' => $y_field,
            ),
            'reportData' => array(
                'data'         => $reportData,
                'isGroupsUsed' => true,
            ),
            'fieldsData' => array(
                '0' => array(
                    'display'    => '1',
                    'fieldName'  => 'lead_source',
                    'fieldOrder' => $x_field,
                    'label'      => $leadSourceLabel,
                ),
                '1' => array(
                    'display'    => '1',
                    'fieldName'  => 'amount',
                    'fieldOrder' => $y_field,
                    'label'      => $amountLabel,
                ),
            ),
            'mainGroupFieldIndex' => '0',
        );
    }

    /**
     * Collect $reportData
     * @param array  $data
     * @param string $x_field
     * @param string $y_field
     * @param string $x_field_label
     * @param string $y_field_label
     * @return array
     */
    protected function buildReportData(
        array $data,
        string $x_field,
        string $y_field,
        string $x_field_label,
        string $y_field_label
    ): array
    {
        $reportData = array();

        $xFieldKey = str_replace(' ', '_', $x_field_label) . $x_field;
        $yFieldKey = str_replace(' ', '_', $y_field_label) . $y_field;

        foreach ($data as $i) {
            $reportData[$i['lead_source']] = array(
                'pageData' => array(),
                'viewData' => array(
                    'data' => array(
                        array(
                            $xFieldKey => $i['lead_source'],
                            $yFieldKey => (int)$i['total'],
                        )
                    ),
                    'displayColumns' => array(
                        $xFieldKey => array(
                            'alias'    => 'opportunities:lead_source',
                            'display'  => '1',
                            'field'    => 'lead_source',
                            'format'   => '',
                            'function' => '',
                            'label'    => $x_field_label,
                            'link'     => '0',
                            'module'   => $this->_seedName,
                            'params'   => array(),
                            'total'    => '',
                            'type'     => null
                        ),
                        $yFieldKey => array(
                            'alias'    => 'opportunities:amount',
                            'display'  => '1',
                            'field'    => 'amount',
                            'format'   => '',
                            'function' => '',
                            'label'    => $y_field_label,
                            'link'     => '0',
                            'module'   => $this->_seedName,
                            'params'   => array(),
                            'total'    => 'COUNT',
                            'type'     => null
                        ),
                    ),
                    'isShowTotal' => true,
                    'totals' => array(
                        $yFieldKey => (int)$i['total']
                    ),
                ),
            );
        }

        return $reportData;
    }
}
