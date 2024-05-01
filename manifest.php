<?php
$manifest = array(
    'name' => 'daVue theme from DoApp',
    'description' => 'A reactive theme for SuiteCRM on VueJS',
    'type' => 'module',
    'is_uninstallable' => true,
    'acceptable_sugar_versions' =>  array (
        'regex_matches' => array( '.*', ),
        ),
    'acceptable_sugar_flavors' => array(
            'CE', 'PRO', 'ENT', 'CORP', 'ULT',
        ),
    'author' => 'doApp',
    'version' => '1.0.0-beta build:1714598826',
    'published_date' => '2024-05-02 00:27:09',
);

$installdefs = array(
    'id' => 'daVueTheme',
    'copy' => array(
        array('from' => '<basepath>/app', 'to' => 'app',),
        array('from' => '<basepath>/themes', 'to' => 'themes'),
        array('from' => '<basepath>/lib', 'to' => 'lib'),
        array('from' => '<basepath>/custom', 'to' => 'custom'),
    ),
    'pre_execute'=> array (
        0 => '<basepath>/scripts/pre_execute.php',
    ),
    'post_execute' => array (
    	0 => '<basepath>/scripts/post_execute.php',
    ),
    'post_uninstall' => array (
    	0 => '<basepath>/scripts/post_uninstall.php',
    ),
);
