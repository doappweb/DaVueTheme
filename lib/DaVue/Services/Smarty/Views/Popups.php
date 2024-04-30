<?php

namespace SuiteCRM\DaVue\Services\Smarty\Views;

use SuiteCRM\DaVue\Services\Common\Filters;

class Popups implements ViewHandlerInterface
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

    private function preHandler()
    {
        // In the box, link for the endPage button is always generated - is not correct
        if ($this->params['pageData']['offsets']['lastOffsetOnPage'] == $this->params['pageData']['offsets']['total']) {
            unset($this->params['pageData']['urls']['endPage']);
        }
    }

    private function generate()
    {
        $this->result = array(
            'type' => 'relatePopup',
            'header' =>  array (
                'show' => true,
                'data' => array(
                    'title' => 'LBL_SEARCH_FORM_TITLE'
                )
            ),
            'body' => array(
                'show' => true,
                'filters' => $this->filters->getRelateFilter($this->params),
                'data' => array(
                    'beanData'          => $this->params['fields'],
                    'beanDataCustom'    => $this->params['customFields'],
                    'pageData'          => $this->params['pageData'],
                    'searchFields' => array(
                        'formData'  => $this->params['formData'],
                    ),
                    'viewData' => array(
                        'data'              => $this->params['data'],
                        'displayColumns'    => $this->params['displayColumns'],
                    ),
                )
            ),
            'footer' => array(
                'show' => false,
                'data' => array()
            )
        );
    }
}
