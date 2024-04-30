<?php

/**
 * Part of the DaVueTheme package
 *
 * @usage {da_smarty_handler dataKey='sugarVCR' dataValue='tpl_vars'}
 */
function smarty_function_da_smarty_handler($arg, &$smarty)
{
    $dataKey = $arg['dataKey'];

    $dataSrc = null;
    $params = $arg['dataSrc'];

    if (property_exists($smarty, $params)){
        $dataSrc = $smarty->$params;
    } else {
        $params = '_' . $arg['dataSrc'];
    }

    if (!$dataSrc && property_exists($smarty, $params)){
        $dataSrc = $smarty->$params;
    }

    if (!$dataSrc){
        $dataSrc = [];
    }

    \SuiteCRM\DaVue\Infrastructure\DI\Container::getInstance()->get('@SmartyHandler')->handler($dataKey, $dataSrc);
}
