<?php

use SuiteCRM\DaVue\Services\Api;

return [
    'getApp' => Api\AppService::class,
    'getUserThemeSettings' => Api\AppService::class,
    'setUserThemeSettings' => Api\AppService::class,
    'getAdditionalDetails' => Api\AppService::class,
    'getAuditPopupPicker' => Api\AppService::class,
    'getVCalFreeBusyByIds' => Api\AppService::class,
    'getSubpanelDynamic' => Api\SubPanelService::class,
    'setSubpanelExpand' => Api\SubPanelService::class,
    'getTimeline' => Api\TimeLineService::class,
    'dashletsDialog' => Api\HomeService::class,
    'displaySugarFeedDashletWithOffset' => Api\HomeService::class,
    'configureDashlet' => Api\HomeService::class,
    'renameDashboardPage' => Api\HomeService::class,
    'getKanbanSearchForm' => Api\KanbanService::class,
    'getKanbanData' => Api\KanbanService::class,
    'saveKanban' => Api\KanbanService::class,
    'getKbArticles' => Api\KnowledgeBaseService::class,
    'getKbArticle' => Api\KnowledgeBaseService::class,
    'getMassUpdateForm' => Api\MassUpdateService::class,
    'mergeRecords' => Api\MergeRecordsService::class,
    'getSendEmailForm' => Api\EmailsService::class,
    'quickCreateView' => Api\QuickCreateViewService::class,
    'checkForDuplicates' => Api\DuplicateCheckService::class,
    'getEditField' => Api\InlineEditService::class,
    'saveActivity' => Api\CalendarService::class,
];
