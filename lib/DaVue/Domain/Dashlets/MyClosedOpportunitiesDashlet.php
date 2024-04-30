<?php

namespace SuiteCRM\DaVue\Domain\Dashlets;

use Dashlet;
use MyClosedOpportunitiesDashlet as MyClosedOpportunitiesDashletOrigin;

class MyClosedOpportunitiesDashlet implements DashletHandlerInterface
{
    /**
     * @param MyClosedOpportunitiesDashletOrigin $focus
     */
    public function configure(Dashlet $focus): array
    {
        // We get the values that were passed to the template.
        // It was done so as not to hardcode them here again, but only to customize the template file.
        $templateResults = json_decode($focus->displayOptions(), true);

        $result = array(
            'basicSettings' => array(
                'title' => array(
                    'name' => 'title',
                    'type' => 'varchar',
                    'label' => $templateResults['titleLBL'],
                    'value' => $focus->title,
                ),
            ),
            'filters' => array(),
        );

        if ($templateResults['isRefreshable']) {
            if (!array_key_exists($templateResults['autoRefreshSelect'], $templateResults['autoRefreshOptions'])) {
                $templateResults['autoRefreshSelect'] = -1;
            }
            $result['basicSettings']['autoRefresh'] = array(
                'label' => $templateResults['autoRefresh'],
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
            'id' => $dashletId,  // It may not be useful
            'label' => $dashletHeaderData['label'],
            'type' => $dashletType,
            'options' => $dashletOptions,

            // On the frontend, it is now required that these properties must be
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
                    'lblTotalOpportunities' => $dashletGenericDisplayData['lblTotalOpportunities'],
                    'lblClosedWonOpportunities' => $dashletGenericDisplayData['lblClosedWonOpportunities'],
                ),
                'data' => array(
                    'totalOpportunities' => $dashletGenericDisplayData['total_opportunities'],
                    'totalOpportunitiesWon' => $dashletGenericDisplayData['total_opportunities_won'],
                ),
            ),
        );

        return $result;
    }
}