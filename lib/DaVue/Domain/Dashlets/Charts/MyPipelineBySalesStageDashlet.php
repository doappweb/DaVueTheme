<?php

namespace SuiteCRM\DaVue\Domain\Dashlets\Charts;

use BeanFactory;
use Dashlet;
use DBManagerFactory;
use SuiteCRM\DaVue\Domain\Dashlets\DashletHandlerInterface;

class MyPipelineBySalesStageDashlet implements DashletHandlerInterface
{
    protected $mypbss_date_start;
    protected $mypbss_date_end;
    protected $mypbss_sales_stages = array();

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
        $this->buildSettings($focus->mypbss_sales_stages, $focus->mypbss_date_start, $focus->mypbss_date_end);

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
                'mypbss_sales_stages' => array(
                    'name' => 'mypbss_sales_stages',
                    'type' => 'multienum',
                    'options' => $this->_searchFields['mypbss_sales_stages']['options'],
                    'label' => translate('LBL_LEAD_SOURCES', 'Charts'),
                    'value' => $this->_searchFields['mypbss_sales_stages']['values'],
                ),
                'mypbss_date_start' => array(
                    'name' => 'mypbss_date_start',
                    'type' => 'date',
                    'label' => translate('LBL_CLOSE_DATE_START', 'Charts'),
                    'value' => $this->_searchFields['mypbss_date_start']['values'],
                ),
                'mypbss_date_end' => array(
                    'name' => 'mypbss_date_end',
                    'type' => 'date',
                    'label' => translate('LBL_CLOSE_DATE_END', 'Charts'),
                    'value' => $this->_searchFields['mypbss_date_end']['values'],
                ),
            ),
        );
    }

    /**
     * Build settings for dashlet configuration
     * @param array $mypbss_sales_stages
     * @param string $mypbss_date_start
     * @param string $mypbss_date_end
     * @return void
     */
    protected function buildSettings(array $mypbss_sales_stages, string $mypbss_date_start, string $mypbss_date_end): void
    {
        global $app_list_strings, $timedate,  $disable_date_format;

        $disable_date_format = false;

        $this->autoRefreshOptions = $this->getAutoRefreshOptions();

        $selected_sales_stages = array();
        if (!empty($mypbss_sales_stages) && count($mypbss_sales_stages) > 0) {
            foreach ($mypbss_sales_stages as $key) {
                $selected_sales_stages[] = $key;
            }
        } else {
            $selected_sales_stages = array_keys($app_list_strings['sales_stage_dom']);
        }

        $this->_searchFields['mypbss_sales_stages']['options'] = array_filter($app_list_strings['sales_stage_dom']);
        $this->_searchFields['mypbss_sales_stages']['values']  = $selected_sales_stages;


        $this->_searchFields['mypbss_date_start']['values'] = $timedate->to_display($mypbss_date_start, $timedate::DB_DATE_FORMAT, $timedate->get_date_format());
        $this->_searchFields['mypbss_date_end']['values']   = $timedate->to_display($mypbss_date_end,   $timedate::DB_DATE_FORMAT, $timedate->get_date_format());
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

        if (empty($dashletOptions['mypbss_date_start'])) {
            $dashletOptions['mypbss_date_start'] = $timedate->nowDbDate();
        }
        if (empty($dashletOptions['mypbss_date_end'])) {
            $dashletOptions['mypbss_date_end'] = $timedate->asDbDate($timedate->getNow()->modify("+6 months"));
        }
        if (empty($dashletOptions['title'])) {
            $dashletOptions['title'] = translate('LBL_MY_PIPELINE_FORM_TITLE', 'Home');
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

    /* @return void*/
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

        $query  = "SELECT opportunities.sales_stage, users.user_name, opportunities.assigned_user_id,";
        $query .= " count(*) AS opp_count, sum((amount_usdollar*" . $conversion_rate . ")/1000) AS total FROM users,opportunities";
        $query .= " WHERE opportunities.assigned_user_id IN ('{$GLOBALS['current_user']->id}')";
        $query .= " AND opportunities.date_closed >= " . DBManagerFactory::getInstance()->convert("'" . $this->mypbss_date_start . "'", 'date');
        $query .= " AND opportunities.date_closed <= " . DBManagerFactory::getInstance()->convert("'" . $this->mypbss_date_end   . "'", 'date');
        $query .= " AND opportunities.assigned_user_id = users.id AND opportunities.deleted=0";

        if (count($this->mypbss_sales_stages) > 0) {
            $query .= " AND opportunities.sales_stage IN ('" . implode("','", $this->mypbss_sales_stages) . "')";
        }
        $query .= " GROUP BY opportunities.sales_stage ,users.user_name,opportunities.assigned_user_id";

        return $query;
    }

    /**
     * Collect chart data
     * @param  $query string
     * @return array
     */
    protected function getChartData( string $query): array
    {
        global $app_list_strings, $db;

        $data = array();
        $temp_data = array();
        $selected_datax = array();

        $user_sales_stage = $this->mypbss_sales_stages;
        $tempx = $user_sales_stage;

        //set $datax using selected sales stage keys
        if (count($tempx) > 0) {
            foreach ($tempx as $key) {
                $datax[$key] = $app_list_strings['sales_stage_dom'][$key];
                array_push($selected_datax, $key);
            }
        } else {
            $datax = $app_list_strings['sales_stage_dom'];
            $selected_datax = array_keys($app_list_strings['sales_stage_dom']);
        }

        $result = $db->query($query);
        $row = $db->fetchByAssoc($result, false);

        while ($row != null) {
            $temp_data[] = $row;
            $row = $db->fetchByAssoc($result, false);
        }

        // reorder and set the array based on the order of selected_datax
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

        $reportData = $this->buildReportData($data, $x_field, $y_field, $salesStageLabel, $amountLabel);

        return array(
            'chart' => array(
                'id'      => $this->id,
                'name'    => $this->title,
                'type'    => 'bar',
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
            $reportData[$i['sales_stage']] = array(
                'pageData' => array(),
                'viewData' => array(
                    'data' => array(
                        array(
                            $xFieldKey => $i['sales_stage'],
                            $yFieldKey => (int)$i['total'],
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
