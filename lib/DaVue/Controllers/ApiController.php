<?php

/**
 * index.php?action=Login&module=Users&VueAjax=1
 *
 * DEBUG
 * http://localhost/index.php?action=Login&module=Users&VueAjax=1&debug=1&method=getMenu
 *
 * http://localhost/index.php?VueAjax=1&debug=1&method=getMenu
 */

namespace SuiteCRM\DaVue\Controllers;

use Exception;
use SuiteCRM;
use SuiteCRM\DaVue\App;

class ApiController implements ControllerInterface
{
    /** @var App */
    private $app;

    private $response;

    /** @var array  */
    private $apiEntryPoints;

    public function __construct(App $app)
    {
        $this->app = $app;
        $this->apiEntryPoints = $app->getConfig('apiEntryPoints');

        $this->response();
    }

    public function response(): void
    {
        $this->generateResponse();
        $response = json_encode($this->response);
        echo html_entity_decode($response, ENT_NOQUOTES | ENT_SUBSTITUTE | ENT_HTML401, 'UTF-8');
        exit();
    }

    /**
     * @return void
     * @throws \ReflectionException
     */
    private function generateResponse(): void
    {
        $method = $_REQUEST['method'];

        if (empty($method)) {
            $this->response = [
                'auth' => $this->app->auth,
                'server' => $this->app->getServerInfo(),
                'error' => 'Method is not set'
            ];

            return;
        }

        if ($serviceId = $this->apiEntryPoints[$method]) {
            $service = $this->app->getService($serviceId);
        } else {
            $service = null;
        }

        if ($service && method_exists($service, $method)) {

            //$this->appPopulate();

            if (isset($_REQUEST['arg']) && !empty($_REQUEST['arg'])) {
                $arg = $_REQUEST['arg'];
            } else {
                $arg = array();
            }

            try {
                $this->response = [
                    'auth' => $this->app->auth,
                    'server' => $this->app->getServerInfo(),
                    'data' => $service->$method($arg)
                ];
            } catch (Exception $error) {
                $this->response = json_encode(array(
                    'auth' => $this->app->auth,
                    'server' => $this->app->getServerInfo(),
                    'error' => $error->getMessage()
                ));
            }

            // For debug only
            if ($_REQUEST['debug']) {
                echo '<pre>';
                print_r(json_decode($this->response, true));
                echo '</pre>';
            }
        } else {
            $this->response = [
                'auth' => $this->app->auth,
                'server' => $this->app->getServerInfo(),
                'error' => 'Unknown method'
            ];
        }
    }

    /**
     * For refactoring
     *
     * @return void
     */
    private function appPopulate()
    {
        global $app, $sugar_config;

        $module = $sugar_config['default_module'];

        if (!empty($_REQUEST['module'])) {
            $module = $_REQUEST['module'];
        }

        $app->loadLanguages();
        $app->setupResourceManagement($module);
        $app->controller->setup();
        $app->controller->loadBean();
    }
}
