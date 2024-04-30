<?php

namespace SuiteCRM\DaVue\Services\Api;

use DashletsDialog;
use Exception;
use SuiteCRM\DaVue\App;
use SuiteCRM\DaVue\Domain\Dashlets\DashletHandlerInterface;
use SuiteCRM\DaVue\Domain\Dashlets\SugarFeedDashlet;
use SuiteCRM\DaVue\Services\Common\Dashlets;

class HomeService
{
    /** @var App */
    private $app;
    private $sugarFeedDashlet;
    private $dashlets;
    private $dashletHandlers;

    public function __construct(App $app, SugarFeedDashlet $sugarFeedDashlet, Dashlets $dashlets)
    {
        $this->app = $app;
        $this->dashletHandlers = $app->getConfig('dashletHandlers');
        $this->sugarFeedDashlet = $sugarFeedDashlet;
        $this->dashlets = $dashlets;
    }

    /**
     * Receiving data to display the popup for creating a new dashlet
     *
     * @usage http://localhost/index.php?VueAjax=1&method=dashletsDialog
     * @see include/MySugar/MySugar.php :: dashletsDialog()
     * @param $args
     * @return array
     */
    public function dashletsDialog($args): array
    {
        $DashletsDialog = new DashletsDialog();
        $DashletsDialog->getDashlets();
        $result = $DashletsDialog->dashlets;

        foreach ($result['Charts'] as $key => $chart) {
            if ($chart['icon'] === 'CampaignROIChartDashlet') {
                unset($result['Charts'][$key]);
            }
        }

        foreach ($result['Tools'] as $key => $tool) {
            if ($tool['id'] !== 'SugarFeedDashlet_select') {
                unset($result['Tools'][$key]);
            }
        }

        return $result;
    }

    /**
     * Renaming the page with dashlets.
     * There is a similar method in the box, but it cannot provide renaming if there is only one page
     *
     * @usage http://localhost/index.php?VueAjax=1&method=renameDashboardPage&arg[]...
     * @see modules/Home/RenameDashboardPages.php
     * @param $args
     * @return array
     */
    public function renameDashboardPage($args)
    {
        global $current_user;

        $pageId = $args['page_id'];
        $dashName = $args['dashName'];

        $pages = $current_user->getPreference('pages', 'Home');
        $pages[$pageId]['pageTitle'] = $dashName;
        $current_user->setPreference('pages', $pages, 0, 'Home');
        $return_params = array(
            'dashName' => $pages[$pageId]['pageTitle'],
            'page_id' => $pageId,
        );

        return $return_params;
    }

    /**
     * Ajax-handler. Receiving data from the SugarFeed dashlet with a specified offset
     *
     * @usage http://localhost/index.php?VueAjax=1&method=displaySugarFeedDashletWithOffset&arg[]...
     * @param $args
     * @return array
     * @throws Exception
     */
    public function displaySugarFeedDashletWithOffset($args)
    {
        if (empty($args['dashletId'])) {
            throw new Exception("'dashletId' argument was not defined");
        }

        $dashletId = $args['dashletId'];
        $offset = 0;
        if (!empty($args['offset'])) {
            $offset = $args['offset'];
        }
        $dashletTitle = $args['title'];
        $rows = $args['limit'];

        $result = $this->sugarFeedDashlet->display($dashletId, array(), array('offset' => $offset, 'title' => $dashletTitle, 'rows' => $rows,), 'SugarFeedDashlet');

        return $result;
    }

    /**
     * Get data for the dashlet settings page
     *
     * @usage http://localhost/index.php?VueAjax=1&method=configureDashlet&arg[]...
     * @see include/MySugar/MySugar.php :: configureDashlet()
     * @param $args
     * @return array
     * @throws Exception
     */
    public function configureDashlet($args): array
    {
        if (empty($args['id'])) {
            throw new Exception("'id' was not defined");
        }

        global $current_user, $app_strings;
        $dashletId = $args['id'];

        $dashletDefs = $current_user->getPreference('dashlets', 'Home'); // load user's dashlets config
        if (!isset($dashletDefs[$dashletId])) {
            throw new Exception("The dashlet with ID = '$dashletId' does not exist for the current user");
        }
        require_once($dashletDefs[$dashletId]['fileLocation']);

        $dashletOptions = array();
        if ($dashletDefs[$dashletId]['options']) {
            $dashletOptions = $dashletDefs[$dashletId]['options'];
        }

        if (!isset($dashletOptions['myItemsOnly'])) {
            $dashletOptions['myItemsOnly'] = "on";
        }

        $dashlet = new $dashletDefs[$dashletId]['className']($dashletId, $dashletOptions);

        $dashletType = $this->dashlets->getDashletType($dashletDefs[$dashletId]);

        if ($dashletHandlerId = $this->dashletHandlers[$dashletType]) {

            /** @var DashletHandlerInterface $dashletHandler */
            $dashletHandler = $this->app->getService($dashletHandlerId);

            $result = array(
                'header' => $dashlet->title . ' : ' . $app_strings['LBL_OPTIONS'],
                'body' => $dashletHandler->configure($dashlet),
            );

        } else {

            $result = array(
                'header' => '- error retrieve data -',
                'body' => [],
            );

        }

        return $result;
    }
}
