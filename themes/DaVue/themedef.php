<?php
/************************
 * Usage Rights
 *
 * DaVueTheme is licensed under the MIT license.
 *
 * This allows you to do pretty much anything you want as long as you include the copyright in "all copies or substantial portions of the Software."
 * Attribution is not required (though very much appreciated).
 *
 * What you are allowed to do with DaVueTheme:
 * - Use in commercial projects.
 * - Use in personal/private projects.
 * - Modify and change the work.
 * - Distribute the code.
 * - Sublicense: incorporate the work into something that has a more restrictive license.
 *
 * What you are not allowed to do with DaVueTheme.
 * - The work is provided "as is". You may not hold the author liable.
 *
 * Contacts: doappweb.com E-mail: info@doappweb.com
 ************************/

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
