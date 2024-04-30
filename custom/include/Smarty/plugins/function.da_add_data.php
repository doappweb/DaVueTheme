<?php

/**
 * Part of the DaVueTheme package
 *
 * @usage {da_add_data dataKey='include.EditView' dataValue='tpl_vars'}
 */
function smarty_function_da_add_data($arg, &$smarty)
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

    \SuiteCRM\DaVue\Infrastructure\DI\Container::getInstance()->get('@SmartyData')->add($dataKey, $dataSrc);
}
