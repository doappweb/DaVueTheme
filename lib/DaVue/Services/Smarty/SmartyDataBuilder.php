<?php

namespace SuiteCRM\DaVue\Services\Smarty;

use SuiteCRM\DaVue\App;
use SuiteCRM\DaVue\Services\Common\SubPanel;
use SuiteCRM\DaVue\Services\Smarty\Views\CalendarView;
use SuiteCRM\DaVue\Services\Smarty\Views\DetailView;
use SuiteCRM\DaVue\Services\Smarty\Views\EditView;
use SuiteCRM\DaVue\Services\Smarty\Views\ListView;
use SuiteCRM\DaVue\Services\Smarty\Views\DashBoard;
use SuiteCRM\DaVue\Services\Smarty\Views\Popups;
use SuiteCRM\DaVue\Services\Smarty\Views\SearchForm;
use SuiteCRM\DaVue\Services\Smarty\Views\UnifiedSearch;
use SuiteCRM\DaVue\Services\Smarty\Views\ViewHandlerInterface;

class SmartyDataBuilder
{
    /** @var App */
    private $app;

    /** @var array */
    private $tplData;

    private $auth;
    private $server;
    private $sugarNotice;
    private $get;
    private $actions;
    private $recentRecords;
    private $favoriteRecords;
    private $data;
    private $search;
    private $subPanelTiles;
    private $smarty_footer_load;
    private $popup;
    private $error = [];
    private $warning = [];


    public function __construct(App $app, array $tplData)
    {
        $this->app = $app;
        $this->tplData = $tplData;
    }

    /**
     * @return mixed
     */
    public function getAuth()
    {
        return $this->app->auth;
    }

    /**
     * @return mixed
     */
    public function getServer()
    {
        return $this->app->getServerInfo();
    }

    /**
     * @return mixed
     */
    public function getSugarNotice()
    {
        return $this->sugarNotice;
    }

    /**
     * @return mixed
     */
    public function getGet()
    {
        if (!empty($this->tplData['smartyGet'])){
            $getParams = $this->tplData['smartyGet'];
        } else {
            $getParams = [
                'module' => $this->tplData['smartyRequest']['module'],
                'action' => $this->tplData['smartyRequest']['action'],
                'record' => $this->tplData['smartyRequest']['record'],
            ];
        }

        if (!isset($getParams['action'])) {
            $getParams['action'] = 'index';
        }

        return $getParams;
    }

    /**
     * @return mixed
     */
    public function getActions()
    {
        $moduleMenu = [];

        global $currentModule;
        $shortcutTopMenu = $this->tplData['header']['shortcutTopMenu'];

        if (isset($shortcutTopMenu[$currentModule])
            && is_array($shortcutTopMenu[$currentModule])
            && count($shortcutTopMenu[$currentModule]) > 0) {

            foreach ($shortcutTopMenu[$currentModule] as $item) {

                if ($item['URL'] !== '-') {
                    $moduleMenu[$item['MODULE_NAME']] = [
                        'key' => strtolower(str_replace('_', '-', $item['MODULE_NAME'])),
                        'lbl' => $item['LABEL'],
                        'link' => str_replace('action=index', 'action=ListView', str_replace('index.php?', '', $item['URL'])),
                    ];
                }

                if ($item['MODULE_NAME'] === 'Today') {
                    unset($moduleMenu['Today']);
                }
            }
        }

        return $moduleMenu;
    }

    /**
     * @return mixed
     */
    public function getRecentRecords()
    {
        return $this->tplData['header']['recentRecords'];
    }

    /**
     * @return mixed
     */
    public function getFavoriteRecords()
    {
        return $this->tplData['header']['favoriteRecords'];
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        $map = [
            'include.DetailView'                    => DetailView::class,
            'modules.Users.DetailView'              => DetailView::class,
            'include.EditView'                      => EditView::class,
            'include.ListView.ListViewGeneric'      => ListView::class,
            'include.ListView.ListViewNoMassUpdate' => ListView::class,
            'include.MySugar'                       => DashBoard::class,
            'include.MySugar2'                      => DashBoard::class,
            'lib.Search.Ui.templates.searchResults' => UnifiedSearch::class,
            'modules.Calendar.main'                 => CalendarView::class,
        ];

        $intersection = array_intersect_key($map, $this->tplData);

        if (count($intersection) === 1) {
            /** @var ViewHandlerInterface $class */
            $class = $this->app->getService(current($intersection));
            return $this->handle($class, $this->tplData[key($intersection)]);
        } else {
            $this->error[] = 'It is not possible to define a view data handler';
        }

        return [];
    }

    /**
     * @return mixed
     */
    public function getSearch()
    {
        if (isset($this->tplData['include.SearchForm.header'])) {

            /** @var SearchForm $class */
            $class = $this->app->getService(SearchForm::class);
            return $this->handle($class, $this->tplData['include.SearchForm.header']);
        }

        return [];
    }

    /**
     * @return mixed
     */
    public function getSubPanelTiles()
    {
        try {
            if (isset($this->tplData['include.SubPanel.SubPanelTiles'])) {
                /** @var SubPanel $class */
                $class = $this->app->getService(SubPanel::class);
                return $class->subPanelTiles($this->tplData['include.SubPanel.SubPanelTiles']);
            }
        } catch (\Throwable $e) {
            $this->error[] = $e->getMessage();
        } finally {
            $this->warningHandler();
        }

        return [];
    }

    /**
     * @return mixed
     */
    public function getSmartyFooterLoad()
    {
        return $this->smarty_footer_load;
    }

    /**
     * @return mixed
     */
    public function getPopup()
    {
        if (isset($this->tplData['include.Popups.PopupGeneric'])) {

            /** @var Popups $class */
            $class = $this->app->getService(Popups::class);
            return $this->handle($class, $this->tplData['include.Popups.PopupGeneric']);
        }

        return [];
    }

    /**
     * @return mixed
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @return mixed
     */
    public function getWarning()
    {
        return $this->warning;
    }

    private function handle(ViewHandlerInterface $handler, array $params): array
    {
        try {
            return $handler->handle($params);
        } catch (\Throwable $e) {
            $this->error[] = $e->getMessage();
        } finally {
            $this->warningHandler();
        }

        return [];
    }

    private function warningHandler(){
        $warning = error_get_last();
        if (isset($warning)){
            $this->warning[] = $warning;
        }
    }
}
