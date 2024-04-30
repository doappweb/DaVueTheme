<?php

namespace SuiteCRM\DaVue;

use SuiteCRM\DaVue\Infrastructure\DI\Container;

class Route
{
    public static function appInit()
    {
        Container::getInstance()->get(App::class);
    }

    public static function redirect(){

        global $sugar_config;

        $site_url = $sugar_config['site_url'];

        // Set View and parameters
        $get = $_GET;

        if (empty($get['module'])){
            $get['module'] = $sugar_config['default_module'];
        }

        if (empty($get['action'])){
            $get['action'] = $sugar_config['default_action'];
        }

        if ($get['action'] == 'index'){
            $get['action'] = 'ListView';
        }

        $reqParams = http_build_query($get);

        // Set environment
        $redirectLink = false;
        $port = '';

        if (isset($sugar_config['frontDevSmarty']) && $sugar_config['frontDevSmarty']) {
            $redirectLink = false;
        } else {

            if (isset($sugar_config['frontDev']) && $sugar_config['frontDev']) {
                $port = ':3000';
            }

            if (strpos($_SERVER['REQUEST_URI'], '/app/?') === false) {
                $redirectLink = $site_url . $port . '/app/?' . $reqParams;
            }
        }

        echo $redirectLink;
    }
}

