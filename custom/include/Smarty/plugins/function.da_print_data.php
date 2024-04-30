<?php

/**
 * Part of the DaVueTheme package
 *
 * @usage {da_print_data dataValue='tpl_vars'}
 */
function smarty_function_da_print_data($arg, &$smarty)
{
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

    echo json_encode($dataSrc);
}
