<?php

namespace SuiteCRM\DaVue\Services\Smarty\Views;

use InvalidArgumentException;
use LoggerManager;
use SuiteCRM\DaVue\Infrastructure\Compatibility\SearchResultsController;
use SuiteCRM\DaVue\Services\Common\Utils;
use SuiteCRM\Search\SearchQuery;
use SuiteCRM\Search\SearchWrapper;
use SuiteCRM\Utility\StringUtils;

class UnifiedSearch implements ViewHandlerInterface
{
    /** @var Utils $utils */
    private $utils;

    private $params;
    private $result = [];

    private $rowProperties = [];
    private $idIndexArr = [];
    private $data = [];
    private $engines;
    private $sizes;

    public function __construct(Utils $utils)
    {
        $this->utils = $utils;
    }

    public function unifiedReSearch(){
        $query = SearchQuery::fromGetRequest();
        $engine = $query->getEngine() ?: SearchWrapper::getDefaultEngine();
        $result = SearchWrapper::search($engine, $query);
        $reSearch = new SearchResultsController($query, $result);
        $reSearch->display();
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

    private function preHandler()
    {
        // Filling the pageData[idIndex] array is similar to ListView
        foreach ($this->params['results']->getHits() as $moduleName => $hit) {
            foreach ($hit as $rowIndex => $recordId) {
                $this->idIndexArr[$moduleName][$recordId] = array(0 => $rowIndex);
            }
        }

        // Filling the viewData[data] array with all values, even those that are not displayed on the search page.
        // Made for compatibility with receiving data on the frontend on ListView
        foreach ($this->params['resultsAsBean'] as $moduleName => $beans) {
            foreach ($beans as $rowIndex => $bean) {
                foreach (array_keys($bean->field_defs) as $fieldName) {
                    if (is_object($this->params['resultsAsBean'][$moduleName][$rowIndex]->$fieldName)) {
                        continue;
                    }

                    $fieldNameUpper = strtoupper($fieldName);
                    $this->data[$moduleName][$rowIndex][$fieldNameUpper] = $this->params['resultsAsBean'][$moduleName][$rowIndex]->$fieldName;
                }
            }
        }

        // We fill in only those field values that are in the header of the table of the corresponding module
        foreach ($this->params['headers'] as $moduleName => $moduleHeaders) {
            foreach ($this->params['resultsAsBean'][$moduleName] as $rowIndex => $fields) {
                $bean = \BeanFactory::getBean($moduleName);
                $fieldDefs = $bean->getFieldDefinitions();
                foreach ($moduleHeaders as $fieldNameUpper => $fieldProperties) {
                    $fieldName = $fieldProperties['field'];

                    if (empty($fieldName)) {
                        // Fields that do not have values in the bean
                        $fieldName = strtolower($fieldNameUpper);
                        $this->rowProperties[$moduleName][$fields->id][$fieldName] = array(
                            'link' => false,
                        );
                        $this->data[$moduleName][$rowIndex][$fieldNameUpper] = '';
                        continue;
                    }

                    if (
                        (isset($fieldProperties['link']) && $fieldProperties['link']
                            || 'assigned_user_name' === $fieldName || 'created_by_name' === $fieldName)
                        && $this->params['resultsAsBean'][$moduleName][$rowIndex]->$fieldName
                    ) {
                        if(gettype($this->params['resultsAsBean'][$moduleName][$rowIndex]->$fieldName) === 'NULL'){
                            $htmlLink = '';
                        } else{
                            $htmlLink = $this->utils->parseHtmlAttribute($this->params['resultsAsBean'][$moduleName][$rowIndex]->$fieldName, 'href');
                        }
                        $this->params['headers'][$moduleName][$fieldNameUpper] = array_merge($fieldDefs[$fieldName], $this->params['headers'][$moduleName][$fieldNameUpper]);

                        $this->rowProperties[$moduleName][$fields->id][$fieldName] = array(
                            'link' => true,

                            // We remove everything from the link up to 'index.php?' inclusive
                            'href' => preg_replace("/^.*index\.php\?/", '', $htmlLink, 1),
                        );
                        $this->data[$moduleName][$rowIndex][$fieldNameUpper] = strip_tags($this->params['resultsAsBean'][$moduleName][$rowIndex]->$fieldName);
                    } else {
                        $this->rowProperties[$moduleName][$fields->id][$fieldName] = array(
                            'link' => false,
                        );
                        $this->data[$moduleName][$rowIndex][$fieldNameUpper] = $this->params['resultsAsBean'][$moduleName][$rowIndex]->$fieldName;
                    }

                    // vname
                    $this->params['headers'][$moduleName][$fieldNameUpper]['vname'] = $fieldProperties['label'];
                    $this->params['headers'][$moduleName][$fieldNameUpper]['sortable'] = false;

                    if('EMAIL1' === $fieldNameUpper){
                        unset($this->params['headers'][$moduleName][$fieldNameUpper]['customCode']);
                        unset($this->params['headers'][$moduleName][$fieldNameUpper]['function']);
                        unset($this->params['headers'][$moduleName][$fieldNameUpper]['link']);
                        $this->params['headers'][$moduleName][$fieldNameUpper]['type'] = 'varchar';
                    }
                }
            }
        }

        $this->sizes = $this->makeSizesFromConfig();
        $this->engines = $this->getEnginesOptions();
    }

    private function generate()
    {
        $this->result = array(
            'pageData' => array(
                'pagination' => $this->params['pagination'],  // TODO: in the box, the pagination is broken and all entries are displayed on the same page
                'total' => $this->params['total'],
                'searchTime' => $this->params['results']->getSearchTime(),
                'rowProperties' => $this->rowProperties,
                'idIndex' => $this->idIndexArr,
                'sizeOptions' => $this->sizes,
                'engineOptions' => !empty($this->engines) ? $this->engines : false,
            ),
            'viewData' => array(
                'displayColumns' => $this->params['headers'],
                'data' => $this->data,
            ),
        );
    }

    /**
     * Makes an array with the page size from the sugar config.
     *
     * @return array
     */
    private function makeSizesFromConfig(): ?array
    {
        global $sugar_config;

        if (!isset($sugar_config['search']['pagination']['min'])) {
            LoggerManager::getLogger()->warn('Configuration does not contains value for search pagination min');
        }

        if (!isset($sugar_config['search']['pagination']['step'])) {
            LoggerManager::getLogger()->warn('Configuration does not contains value for search pagination step');
        }

        if (!isset($sugar_config['search']['pagination']['max'])) {
            LoggerManager::getLogger()->warn('Configuration does not contains value for search pagination max');
        }

        $min = $sugar_config['search']['pagination']['min'] ?? null;
        $step = $sugar_config['search']['pagination']['step'] ?? null;
        $max = $sugar_config['search']['pagination']['max'] ?? null;

        try {
            return $this->makeSizes($min, $step, $max);
        } catch (InvalidArgumentException $exception) {
            return $this->makeSizes(10, 10, 50);
        }
    }

    /**
     * Makes an array with the page size from the given parameters.
     *
     * @param int|null $min
     * @param int|null $step
     * @param int|null $max
     *
     * @return array
     * @throws InvalidArgumentException in case of failure
     */
    private function makeSizes(?int $min, ?int $step, ?int $max): array
    {
        $min = (int)$min;
        $step = (int)$step;
        $max = (int)$max;

        if (!is_int($min) || !is_int($step) || !is_int($max)) {
            throw new InvalidArgumentException('Arguments must be integers');
        }

        if ($max === 0 || $step === 0 || $min === 0) {
            throw new InvalidArgumentException('Arguments cannot be zero');
        }

        if ($min > $max) {
            throw new InvalidArgumentException('$min must be smaller than $max');
        }

        $sizes = [];

        for ($it = $min; $it <= $max; $it += $step) {
            $sizes[$it] = $it;
        }

        return $sizes;
    }

    /**
     * Makes an array with engines from the sugar config.
     *
     * @return array
     */
    private function getEnginesOptions(){
        global $sugar_config;

        $engines = [];

        foreach (SearchWrapper::getEngines() as $engine) {
            $engines[$engine] = StringUtils::camelToTranslation($engine);
        }

        if ($sugar_config['search']['ElasticSearch']['enabled'] === false) {
            unset($engines['ElasticSearchEngine']);
        }

        $currentEngine = SearchWrapper::getDefaultEngine();

        if ($currentEngine === 'BasicSearchEngine' || $currentEngine === 'ElasticSearchEngine') {
            $engines = [];
        }

        return $engines;
    }
}
