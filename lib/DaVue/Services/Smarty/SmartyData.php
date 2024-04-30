<?php

namespace SuiteCRM\DaVue\Services\Smarty;

use SuiteCRM\DaVue\App;

class SmartyData
{
    /** @var array  */
    private $collection = [];

    public function __construct(App $app)
    {
        if ($_REQUEST['VueAjax']) {
            return;
        }
    }

    public function add($tplName, $tplVars)
    {
        $this->collection[$tplName] = $tplVars;
    }

    public function get($tplName)
    {
        return $this->collection[$tplName];
    }

    public function getCollection(): array
    {
        return $this->collection;
    }
}
