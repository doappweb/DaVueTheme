<?php

namespace SuiteCRM\DaVue\Domain\Dashlets\Charts;

use BeanFactory;
use Dashlet;
use DBManagerFactory;
use SuiteCRM\DaVue\Domain\Dashlets\DashletHandlerInterface;

class PipelineBySalesStageDashlet implements DashletHandlerInterface
{
    public $pbss_date_start;
    public $pbss_date_end;
    public $pbss_sales_stages = array();

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
        $this->buildSettings($focus->pbss_sales_stages, $focus->pbss_date_start, $focus->pbss_date_end);

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
                'pbss_sales_stages' => array(
                    'name' => 'pbss_sales_stages',
                    'type' => 'multienum',
                    'options' => $this->_searchFields['pbss_sales_stages']['options'],
                    'label' => translate('LBL_LEAD_SOURCES', 'Charts'),
                    'value' => $this->_searchFields['pbss_sales_stages']['values'],
                ),
                'pbss_date_start' => array(
                    'name' => 'pbss_date_start',
                    'type' => 'date',
                    'label' => translate('LBL_CLOSE_DATE_START', 'Charts'),
                    'value' => $this->_searchFields['pbss_date_start']['values'],
                ),
                'pbss_date_end' => array(
                    'name' => 'pbss_date_end',
                    'type' => 'date',
                    'label' => translate('LBL_CLOSE_DATE_END', 'Charts'),
                    'value' => $this->_searchFields['pbss_date_end']['values'],
                ),
            ),
        );
    }

    /**
     * @param array $pbss_sales_stages
     * @param string $pbss_date_start
     * @param string $pbss_date_end
     * @return void
     */
    protected function buildSettings(array $pbss_sales_stages, string $pbss_date_start, string $pbss_date_end): void
    {
        global $app_list_strings, $timedate, $disable_date_format;

        $disable_date_format = false;

        $this->autoRefreshOptions = $this->getAutoRefreshOptions();

        $selected_sales_stages = array();
        if (!empty($pbss_sales_stages) && count($pbss_sales_stages) > 0) {
            foreach ($pbss_sales_stages as $key) {
                $selected_sales_stages[] = $key;
            }
        } else {
            $selected_sales_stages = array_keys($app_list_strings['sales_stage_dom']);
        }

        $this->_searchFields['pbss_sales_stages']['options'] = array_filter($app_list_strings['sales_stage_dom']);
        $this->_searchFields['pbss_sales_stages']['values']  = $selected_sales_stages;


        $this->_searchFields['pbss_date_start']['values'] = $timedate->to_display($pbss_date_start, $timedate::DB_DATE_FORMAT, $timedate->get_date_format());
        $this->_searchFields['pbss_date_end']['values']   = $timedate->to_display($pbss_date_end,   $timedate::DB_DATE_FORMAT, $timedate->get_date_format());
    }

    /**
     * Returns the available auto refresh settings you can set a dashlet to
     * @return array options available
     */
    protected function getAutoRefreshOptions()
    {
        $options = $GLOBALS['app_list_strings']['dashlet_auto_refresh_options'];

        if (isset($GLOBALS['sugar_config']['dashlet_auto_refresh_min'])) {
            foreach ($options as $time => $desc) {
                if ($time != -1 && $time < $GLOBALS['sugar_config']['dashlet_auto_refresh_min']) {
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
        global $timedate;

        if (empty($dashletOptions['pbss_date_start'])) {
            $dashletOptions['pbss_date_start'] = $timedate->nowDbDate();
        }

        if (empty($dashletOptions['pbss_date_end'])) {
            $dashletOptions['pbss_date_end'] = $timedate->asDbDate($timedate->getNow()->modify("+6 months"));
        }

        if (empty($dashletOptions['title'])) {
            $dashletOptions['title'] = translate('LBL_RGraph_PIPELINE_FORM_TITLE', 'Home');
        }

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
        $conversion_rate = $this->currency->conversion_rate;

        $query  = "SELECT opportunities.sales_stage,count(*) AS opp_count,sum((amount_usdollar*" . $conversion_rate . ")/1000) AS total FROM users,opportunities";
        $query .= " WHERE opportunities.date_closed >= " . DBManagerFactory::getInstance()->convert("'" . $this->pbss_date_start . "'", 'date');
        $query .= " AND opportunities.date_closed <= "   . DBManagerFactory::getInstance()->convert("'" . $this->pbss_date_end   . "'", 'date');
        $query .= " AND opportunities.assigned_user_id = users.id AND opportunities.deleted=0";

        $query .= " GROUP BY opportunities.sales_stage";

        return $query;
    }

    /**
     * @param string $query
     * @return array
     */
    protected function getChartData(string $query): array
    {
        global $app_list_strings, $db;

        $data = array();
        $temp_data = array();
        $selected_datax = array();

        $user_sales_stage = $this->pbss_sales_stages;
        $tempx = $user_sales_stage;

        if (count($tempx) > 0) {
            foreach ($tempx as $key) {
                $datax[$key] = $app_list_strings['sales_stage_dom'][$key];
                $selected_datax[] = $key;
            }
        } else {
            $selected_datax = array_keys($app_list_strings['sales_stage_dom']);
        }

        $result = $db->query($query);

        while ($row = $db->fetchByAssoc($result, false)) {
            $temp_data[] = $row;
        }

        foreach ($selected_datax as $sales_stage) {
            foreach ($temp_data as $key => $value) {
                if ($value['sales_stage'] == $sales_stage) {
                    $value['sales_stage'] = $app_list_strings['sales_stage_dom'][$value['sales_stage']];
                    $value['key'] = $sales_stage;
                    $value['value'] = $value['sales_stage'];
                    $data[] = $value;
                    unset($temp_data[$key]);
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
        $salesStageLabel  = translate('LBL_SALES_STAGES', 'Charts');
        $amountLabel      = translate('LBL_LIST_AMOUNT', 'Opportunities');

        $x_field = '0';
        $y_field = '1';
        $regroupedData = $this->regroupData($data);

        $reportData = $this->buildReportData($regroupedData, $x_field, $y_field, $salesStageLabel, $amountLabel);

        return array(
            'chart' => array(
                'id'      => $this->id,
                'name'    => $this->title,
                'type'    => 'funnel',
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
                    'fieldName'  => 'sales_stage',
                    'fieldOrder' => $x_field,
                    'label'      => $salesStageLabel,
                ),
                '1' => array(
                    'display'    => '1',
                    'fieldName'  => 'opp_count',
                    'fieldOrder' => $y_field,
                    'label'      => $amountLabel,
                ),
            ),
            'mainGroupFieldIndex' => '0',
        );
    }

    /**
     * Grouping date matches with sales stages for funnel
     * @param array $data
     * @return array
     */
    protected function regroupData(array $data): array
    {
        $funnels_stage = array(
            'ALL' => array(
                'opp_count' => 0,
                'text' => array(
                    'ALL' => 'ALL',
                ),
            ),
            'Prospecting' => array(
                'opp_count' => 0,
                'text' => array(
                    'Prospecting'         => '',
                    'Id. Decision Makers' => '',
                    'Needs Analysis'      => '',
                    'Qualification'       => '',
                    'Value Proposition'   => '',
                    'Perception Analysis' => '',
                ),
                'label' => translate('LBL_DA_CHART_PROSPECTING'),
            ),
            'Negotiation' => array(
                'opp_count' => 0,
                'text' => array(
                    'Proposal/Price Quote' => '',
                    'Negotiation/Review'   => '',
                ),
                'label' => translate('LBL_DA_CHART_NEGOTIATION'),

            ),
            'Fulfillment' => array(
                'opp_count' => 0,
                'text' => array(
                    'Assembling1' => '',
                    'Shipping'    => '',
                    'Closed Lost' => '',
                ),
                'label' => translate('LBL_DA_CHART_FULFILLMENT'),

            ),
            'Closed Won' => array(
                'opp_count' => 0,
                'text' => array(
                    'Closed Won' => ''
                ),
                'label' => translate('LBL_DA_CHART_CLOSED_WON'),

            ),
        );

        foreach ($data as $sales_stage) {
            $funnels_stage['ALL']['opp_count'] += (int)$sales_stage['opp_count'];
            foreach ($funnels_stage as $stage => $value) {
                if (in_array($sales_stage['key'], array_keys($value['text']))) {
                    $funnels_stage[$stage]['text'][$sales_stage['key']] = $sales_stage['sales_stage'];
                    $funnels_stage[$stage]['opp_count'] += (int)$sales_stage['opp_count'];
                }
            }
        }

        unset($funnels_stage['ALL']);

        return $funnels_stage;
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

        foreach ($data as $stage => $value) {

            if (empty($value['opp_count']) || $value['opp_count'] === 0){
                continue;
            }

            $reportData[$stage] = array(
                'pageData' => array(),
                'viewData' => array(
                    'data' => array(
                        array(
                            $xFieldKey => $stage,
                            $yFieldKey => (int)$value['opp_count'],
                        )
                    ),
                    'displayColumns' => array(
                        $xFieldKey => array(
                            'alias'    => 'opportunities:sales_stage',
                            'display'  => '1',
                            'field'    => 'sales_stage',
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
                            'alias'    => '',
                            'display'  => '1',
                            'field'    => 'count',
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
                        $yFieldKey => (int)$value['opp_count']
                    ),
                    'text'  => $value['text'],
                    'label' => $value['label'],
                ),
            );
        }

        return $reportData;
    }
}
