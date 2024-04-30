<?php

/**
 * Part of the DaVueTheme package
 *
 * @usage themes/DaVue/tpls/header.tpl
 */
function smarty_function_da_query_params()
{
    \SuiteCRM\DaVue\Infrastructure\DI\Container::getInstance()->get('@SmartyData')->add('smartyGet', $_GET);
    \SuiteCRM\DaVue\Infrastructure\DI\Container::getInstance()->get('@SmartyData')->add('smartyRequest', $_REQUEST);
}
