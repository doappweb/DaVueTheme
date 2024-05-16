<?php

namespace SuiteCRM\DaVue\Services\Smarty;

use SuiteCRM;
use SuiteCRM\DaVue\App;
use SuiteCRM\DaVue\Domain\Modules\CalendarHandler;
use SuiteCRM\DaVue\Services\Smarty\Views\ListView;

class SmartyHandler
{
    /** @var App */
    private $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    /**
     * Handler for call helper-methods from smarty
     * @param $methodName
     * @param $params
     * @return void
     */
    public function handler($methodName, $params = null)
    {
        if (!$this->app->auth) {
            echo json_encode(null);
            return;
        }

        if (method_exists($this, $methodName)) {
            if ($params) {

                // Data compatibility with 7.14.x
                $cleanData = [];
                foreach ($params as $key => $val) {
                    if (is_object($val)){
                        $cleanData[$key] = $val->value;
                    } else {
                        $cleanData = $params;
                        break;
                    }
                }

                $response = json_encode($this->$methodName($cleanData));
            } else {
                $response = json_encode($this->$methodName());
            }
            echo html_entity_decode($response, ENT_NOQUOTES | ENT_SUBSTITUTE | ENT_HTML401, 'UTF-8');
        } else {
            echo json_encode(null);
        }
    }

    /**
     * Call from Smarty tpl
     * Use on Dashlet or module=Calendar&action=index
     *
     * @usage themes/DaVue/modules/Calendar/tpls/main.tpl
     * @param $params
     * @return array
     */
    public function calendar($params): array
    {
        if ($_REQUEST['module'] == 'Home') {
            /** @var CalendarHandler $handler */
            $handler = $this->app->getService(CalendarHandler::class);
            return $handler->calendar($params);
        }

        $this->app->getService('@SmartyData')->add('modules.Calendar.main', $params);
        return [];
    }

    /**
     * Call from Smarty tpl
     * Use on Dashlet
     *
     * @usage themes/DaVue/include/Dashlets/DashletGenericDisplay.tpl
     * @param $params
     * @return array
     */
    public function listView($params): array
    {
        /** @var ListView $handler */
        $handler = $this->app->getService(ListView::class);
        return $handler->handle($params);
    }

    /**
     * Adapts data from template variables to the type of display listViewNoMassUpdate
     *
     * @usage themes/DaVue/include/ListView/ListViewNoMassUpdate.tpl
     * @param $tplVars
     * @return void
     */
    public function listViewNoMassUpdatePrepare(&$tplVars)
    {
        $tplVars['quickViewLinks'] = false;
        $tplVars['pageData']['access']['edit'] = false;
        foreach ($tplVars['pageData']['rowAccess'] as &$access) {
            $access['edit'] = false;
        }
        unset($access);
    }

    /**
     * Pagination for EditView and ListView
     *
     * @usage themes/DaVue/include/EditView/SugarVCR.tpl
     * @param $params
     * @return array
     */
    private function sugarVCR($params): array
    {
        $result = array();

        if (!is_array($params)){
            return $result;
        }

        $result = array(
            'previousLink' => str_replace('index.php?', '', $params['previous_link']),
            'nextLink' => str_replace('index.php?', '', $params['next_link']),
            'offset' => $params['offset'],
            'total' => $params['total'],
            'plus' => $params['plus']
        );

        return $result;
    }

    /**
     * Replacing the template content dashletHeader.tpl
     *
     * @usage themes/DaVue/include/Dashlets/DashletHeader.tpl
     * @param $params
     * @return array
     */
    private function dashletHeader($params): array
    {
        $result = array(
            'label' => $params['DASHLET_TITLE'],
            'id' => $params['DASHLET_ID'],
        );
        return $result;
    }
}
