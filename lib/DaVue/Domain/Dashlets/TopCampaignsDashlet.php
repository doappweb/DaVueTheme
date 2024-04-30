<?php

namespace SuiteCRM\DaVue\Domain\Dashlets;

use Dashlet;
use TopCampaignsDashlet as TopCampaignsDashletOrigin;

class TopCampaignsDashlet implements DashletHandlerInterface
{
    /**
     * @param TopCampaignsDashletOrigin $focus
     */
    public function configure(Dashlet $focus): array
    {
        // Get the values that were passed to the template.
        // This is done so as not to hardcode them here again, but only to customize the template file.
        $templateResults = json_decode($focus->displayOptions(), true);

        $result = array(
            'title' => array(
                'name' => 'title',
                'type' => 'varchar',
                'label' => $templateResults['lblTitle'],
                'value' => $focus->title,
            ),
        );

        if ($templateResults['isRefreshable']) {
            if (!array_key_exists($templateResults['autoRefreshSelect'], $templateResults['autoRefreshOptions'])) {
                $templateResults['autoRefreshSelect'] = -1;
            }
            $result['autoRefresh'] = array(
                'label' => $templateResults['lblAutoRefresh'],
                'name' => 'autoRefresh',
                'value' => $templateResults['autoRefreshSelect'],
                'type' => 'enum',
                'options' => $templateResults['autoRefreshOptions'],
            );
        }

        return $result;
    }

    public function display($dashletId, $dashletData, $dashletOptions, $dashletType): array
    {
        $dashletHeaderData = json_decode($dashletData['displayHeader'], true);
        $dashletGenericDisplayData = json_decode($dashletData['display'], true);

        $result = array(
            'id' => $dashletId,  // Probably useless
            'label' => $dashletHeaderData['label'],
            'type' => $dashletType,
            'options' => $dashletOptions,

            // The frontend now requires that these properties be present
            'pageData' => array(
                'urls' => array(
                    'startPage' => null,
                    'prevPage' => null,
                    'nextPage' => null,
                    'endPage' => null,
                ),
                'bean' => array(
                    'moduleDir' => '',
                ),
                'offsets' => array(
                    'lastOffsetOnPage' => null,
                ),
            ),
            'viewData' => array(
                'displayColumns' => array(
                    'lblCampaignName' => $dashletGenericDisplayData['lbl_campaign_name'],
                    'lblRevenue' => $dashletGenericDisplayData['lbl_revenue'],
                ),
                'data' => array(
                    'topCampaigns' => $dashletGenericDisplayData['top_campaigns'],
                ),
            ),
        );

        return $result;
    }
}
