<?php

use SuiteCRM\DaVue\Domain\Dashlets;

return [
    'basic' => Dashlets\Basic::class,
    'CalendarDashlet' => Dashlets\CalendarDashlet::class,
    'SugarFeedDashlet' => Dashlets\SugarFeedDashlet::class,
    'MyPipelineBySalesStageDashlet' => Dashlets\Charts\MyPipelineBySalesStageDashlet::class,
    'OpportunitiesByLeadSourceByOutcomeDashlet' => Dashlets\Charts\OpportunitiesByLeadSourceByOutcomeDashlet::class,
    'OpportunitiesByLeadSourceDashlet' => Dashlets\Charts\OpportunitiesByLeadSourceDashlet::class,
    'OutcomeByMonthDashlet' => Dashlets\Charts\OutcomeByMonthDashlet::class,
    'PipelineBySalesStageDashlet' => Dashlets\Charts\PipelineBySalesStageDashlet::class,
    'MyClosedOpportunitiesDashlet' => Dashlets\MyClosedOpportunitiesDashlet::class,
    'iFrameDashlet' => Dashlets\iFrameDashlet::class,
    'TopCampaignsDashlet' => Dashlets\TopCampaignsDashlet::class,
    'JotPadDashlet' => Dashlets\JotPadDashlet::class
];
