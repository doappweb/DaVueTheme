<?php

namespace SuiteCRM\DaVue\Services\Smarty\Views;

use SuiteCRM\DaVue\App;
use SuiteCRM\DaVue\Domain\Dashlets\DashletHandlerInterface;
use SuiteCRM\DaVue\Services\Common\Dashlets;

class DashBoard implements ViewHandlerInterface
{
    /** @var App */
    private $app;
    private $dashlets;
    private $dashletHandlers;
    private $params;
    private $result = [];
    private $pageData = array();
    private $panelsData = array();
    private $panelsMetadata = array();

    public function __construct(App $app, Dashlets $dashlets)
    {
        $this->app = $app;
        $this->dashletHandlers = $app->getConfig('dashletHandlers');
        $this->dashlets = $dashlets;
    }

    public function handle($params): array
    {
        if (!is_array($params)){
            return $this->result;
        }

        $this->params = $params;
        $this->preHandler();
        $this->generate();

        return $this->result;
    }

    private function preHandler()
    {
        global $current_user;

        $userDashlets = $current_user->getPreference('dashlets', 'Home');

        if (!empty($this->params['dashboardPages'])) {
            foreach ($this->params['dashboardPages'] as $index => $tab) {
                $this->pageData[$index] = array(
                    'title' => $tab['pageTitle'],
                    'pageKey' => 'page_' . $index,
                    'columnsAmount' => null,  // This information will be loaded only if the user opens this page (tab)
                );

                // option for rename first tab
                if (0 === $index) {
                    $pages = $current_user->getPreference('pages', 'Home');
                    if ($pages[0]['pageTitle']) {
                        $this->pageData[$index]['title'] = $pages[0]['pageTitle'];
                    }
                }
            }
        }

        $activePage = $this->params['activePage'];
        $this->pageData[$activePage]['columnsAmount'] = count($this->params['columns']);

        // Data for each panel (dashlet)
        foreach ($this->params['columns'] as $columnIndex => $columnData) {
            foreach ($columnData['dashlets'] as $dashletId => $dashletData) {

                $dashletType = $this->dashlets->getDashletType($userDashlets[$dashletId]);

                // options may be empty if the settings of the dashboard have never been saved
                $dashletOptions = array();
                if (!empty($userDashlets[$dashletId]['options'])) {
                    $dashletOptions = $userDashlets[$dashletId]['options'];
                }

                if ($dashletHandlerId = $this->dashletHandlers[$dashletType]) {
                    /** @var DashletHandlerInterface $dashletHandler */
                    $dashletHandler = $this->app->getService($dashletHandlerId);
                    $this->panelsData[$dashletId] = $dashletHandler->display($dashletId, $dashletData, $dashletOptions, $dashletType);
                } else {
                    $GLOBALS['log']->fatal(__METHOD__ . ', ' . __LINE__ . ': There is no handler method for dashlet with type' . $dashletType);
                    $this->panelsData[$dashletId] = array(
                        'id' => $dashletId,
                        'label' => null,
                        'type' => $dashletType,
                        'options' => array(),
                        'pageData' => array(),
                        'viewData' => array(),
                    );
                }
            }
        }

        // Location dashlets (panels) on the current tab (page)
        foreach ($this->params['columns'] as $columnIndex => $columnData) {
            $this->panelsMetadata['page_' . $activePage][$columnIndex] = array(
                'width' => $columnData['width'],
                'panels' => array_keys($columnData['dashlets']),
            );
        }
    }

    private function generate()
    {
        $this->result = array(
            'activePage' => $this->params['activePage'],
            'lock_homepage' => $this->params["APP_CONFIG"]["lock_homepage"],
            'pageData' => $this->pageData,
            'viewData' => array(
                'panelsData' => $this->panelsData,
                'panelsMetadata' => $this->panelsMetadata,
            ),
        );
    }
}
