<?php

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

global $sugar_config, $app_strings;

$sugar_config['allowed_preview'] = array();
$sugar_config['disableAjaxUI'] = true;

$themedef = array(
    'name' => 'DaVue',
    'description' => 'DaVue Theme',
    'version' => array(
        'regex_matches' => array('.+'),
    ),
    'group_tabs' => true,
    'classic' => true,
    'configurable' => true,
    'config_options' => array(
        'display_sidebar' => array(
            'vname' => 'LBL_DISPLAY_SIDEBAR',
            'type' => 'bool',
            'default' => true,
        ),
    ),
);
