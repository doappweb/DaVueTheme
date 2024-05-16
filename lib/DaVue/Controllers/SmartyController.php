<?php

namespace SuiteCRM\DaVue\Controllers;

use SuiteCRM\DaVue\App;
use SuiteCRM\DaVue\Services\Smarty\SmartyData;
use SuiteCRM\DaVue\Services\Smarty\SmartyDataBuilder;
use SuiteCRM\DaVue\Services\Smarty\Views\UnifiedSearch;

class SmartyController implements ControllerInterface
{
    /** @var App */
    private $app;

    private $response = [];
    private $tplData = [];
    private $sugarNotice = [];
    private $pageContent;
    private $sysErrors = [];

    public function __construct(App $app)
    {
        if ($_REQUEST['VueAjax']) {
            return;
        }

        $this->app = $app;
        register_shutdown_function(array($this, 'response'));
    }

    private function warningHandler(){
        $warning = error_get_last();
        if (isset($warning)){
            $this->sysWarnings[] = $warning;
        }
    }

    public function response(): void
    {
        $smartyData = $this->app->getService('@SmartyData');

        // Data compatibility with 7.14.x
        $rawData = $smartyData->getCollection();
        foreach ($rawData as $tpl => $data) {
            foreach ($data as $key => $val) {
                if (is_object($val)){
                    $this->tplData[$tpl][$key] = $val->value;
                } else {
                    $this->tplData[$tpl] = $data;
                    break;
                }
            }
        }

        $this->compatibilityHooks();
        $this->setPageContent();
        $this->generateResponse();
        $this->validateResponse();

        $response = json_encode($this->response);
        echo html_entity_decode($response, ENT_NOQUOTES | ENT_SUBSTITUTE | ENT_HTML401, 'UTF-8');
        exit();
    }

    private function generateResponse()
    {
        $builder = new SmartyDataBuilder($this->app, $this->tplData);

        $this->response = [
            'app' => [
                'auth' => $builder->getAuth(),
                'server' => $builder->getServer(),
                'sugarNotice' => $this->sugarNotice
            ],
            'focus' => [
                'get' => $builder->getGet(),
                'actions' => $builder->getActions(),
                'recentRecords' => $builder->getRecentRecords(),
                'favoriteRecords' => $builder->getFavoriteRecords(),
                'data' => $builder->getData(),
                'search' => $builder->getSearch(),
                'subPanelTiles' => $builder->getSubPanelTiles(),
                'smarty_footer_load' => $this->confirmationFullLoad()
            ],
            'popup' => $builder->getPopup(),
            'backend' => [
                'error' => array_merge($builder->getError(), $this->sysErrors),
                'warning' => $builder->getWarning()
            ]
        ];
    }

    private function validateResponse()
    {
        if (!$this->response['focus']['smarty_footer_load']) {
            $this->setFocusHtml();
        }

        if (empty($this->response['focus']['data']) && $this->response['focus']['smarty_footer_load']) {
            $this->setFocusDataSugarViewClassic();
        }

        if (empty($this->response['focus']['data']) && $this->response['focus']['smarty_footer_load']) {
            $this->setFocusHtml();
        }
    }

    private function setPageContent()
    {
        $pageContent = ob_get_contents();
        ob_end_clean();

        preg_match_all('/<p class="error">.*?<\/p>/s', $pageContent, $matches);

        foreach ($matches[0] as $match) {
            $this->sugarNotice[] = strip_tags($match);
            $pageContent = str_replace($match, '', $pageContent);
        }

        $this->pageContent = $pageContent;
    }

    private function setFocusDataSugarViewClassic()
    {
        global $current_view;

        $handler = sprintf('classical.%s.%s', $current_view->module, $current_view->action);
        $classicalViews = $this->app->getConfig('classicalViews');

        if ($serviceId = $classicalViews[$handler]) {
            $service = $this->app->getService($serviceId);
        } else {
            $service = null;
        }

        try {
            $this->response['focus']['data'] = $service->handler();
        } catch (\Throwable $e) {
            $this->sysErrors[] = $e->getMessage();
        } finally {
            $this->warningHandler();
        }
    }

    private function setFocusHtml()
    {
        $pattern = '/^.*%%%%%%.*\n?/m';
        $htmlContent = preg_replace($pattern, '', $this->pageContent);

        $pattern = '/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/i';
        $htmlContent = preg_replace($pattern, '', $htmlContent);

        $pattern = '/(<img\s+[^>]*src=["\'])(?!\/|http:\/\/|https:\/\/)([^"\'\s>]+)/i';
        $htmlContent = preg_replace($pattern, '$1/$2', $htmlContent);

        $content = array(
            'pageData' => [
                'htmlContent' => $htmlContent
            ],
            'viewData' => [],
            'beanData' => [],
        );

        $this->response['focus']['data'] = $content;
    }

    private function compatibilityHooks(){
        if ($_REQUEST['action'] === 'UnifiedSearch' && !isset($this->tplData['lib.Search.Ui.templates.searchResults'])){

            /** @var UnifiedSearch $class */
            $class = $this->app->getService(UnifiedSearch::class);
            $class->unifiedReSearch();
        }
    }

    private function confirmationFullLoad(): bool
    {
        if (strpos($this->pageContent, '==CONFIRMATION=OF=THE=FULL=LOAD==') === false){
            $this->sysErrors[] = 'The page was not fully loaded';
            return false;
        } else {
            return true;
        }
    }
}
