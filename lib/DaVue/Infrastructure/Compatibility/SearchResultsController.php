<?php

namespace SuiteCRM\DaVue\Infrastructure\Compatibility;

use SuiteCRM\Search\SearchQuery;
use SuiteCRM\Search\SearchResults;
use SuiteCRM\Search\UI\SearchResultsController as BoxSearchResultsController;

class SearchResultsController extends BoxSearchResultsController
{
    public function __construct(SearchQuery $query, SearchResults $results)
    {
        parent::__construct($query, $results);
        $this->view->setTemplateFile('lib/Search/UI/templates/search.results.tpl');
    }
}
