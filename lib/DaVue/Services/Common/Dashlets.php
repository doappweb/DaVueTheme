<?php

namespace SuiteCRM\DaVue\Services\Common;

class Dashlets
{
    /**
     * Dashlet type recognizer based on its parameters, which are stored in the user settings in the database.
     * Returns "basic" for module dashlets and the name of the handler class for the rest
     */
    public function getDashletType(array $dashletSettings): string
    {
        $toolsDashlets = ['Home', 'SugarFeed', 'Calendar'];
        $chartsDashlets =  [
            'OpportunitiesByLeadSourceDashlet',
            'PipelineBySalesStageDashlet',
            'MyPipelineBySalesStageDashlet',
            'OpportunitiesByLeadSourceByOutcomeDashlet',
            'OutcomeByMonthDashlet',
        ];
        $cstmModDashlets = ['MyClosedOpportunitiesDashlet', 'TopCampaignsDashlet'];

        if (false === mb_strpos($dashletSettings['fileLocation'], "modules/{$dashletSettings['module']}")
            || in_array($dashletSettings['module'], $toolsDashlets)
            || in_array($dashletSettings['className'], $chartsDashlets)
            || in_array($dashletSettings['className'], $cstmModDashlets)
        ) {
            $dashletType = $dashletSettings['className'];
        } else {
            $dashletType = 'basic';
        }

        return $dashletType;
    }
}
