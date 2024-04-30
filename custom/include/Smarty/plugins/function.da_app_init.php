<?php

/**
 * Part of the DaVueTheme package
 *
 * @usage themes/DaVue/tpls/header.tpl
 */
function smarty_function_da_app_init()
{
    \SuiteCRM\DaVue\Route::appInit();
    ob_start();
}
