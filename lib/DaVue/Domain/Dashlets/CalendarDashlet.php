<?php

namespace SuiteCRM\DaVue\Domain\Dashlets;

use Dashlet;

class CalendarDashlet implements DashletHandlerInterface
{
    public function configure(Dashlet $focus): array
    {
        $result = array(
            'basicSettings' => array(
                'title' => array(
                    'name' => 'title',
                    'type' => 'varchar',
                    'label' => $focus->dashletStrings['LBL_CONFIGURE_TITLE'],
                    'value' => $focus->title,
                ),
                'view' => array(
                    'name' => 'view',
                    'type' => 'enum',
                    'options' => array(
                        'agendaDay' => $focus->dashletStrings['LBL_VIEW_DAY'],
                        'agendaWeek' => $focus->dashletStrings['LBL_VIEW_WEEK'],
                        'month' => $focus->dashletStrings['LBL_VIEW_MONTH'],
                    ),
                    'label' => $focus->dashletStrings['LBL_CONFIGURE_VIEW'],
                    'value' => $focus->view === 'week' ? 'agendaWeek' : $focus->view,
                )
            )
        );

        return $result;
    }

    public function display($dashletId, $dashletData, $dashletOptions, $dashletType): array
    {
        // The body of the dashlet contains json, wrapped in garbage around the edges
        $indexleft = mb_strpos($dashletData['display'], '{');
        $indexRight = mb_strrpos($dashletData['display'], '}');
        $dashletData['display'] = mb_substr($dashletData['display'], $indexleft, $indexRight - $indexleft + 1);

        $dashletHeaderData = json_decode($dashletData['displayHeader'], true);
        $dashletGenericDisplayData = json_decode($dashletData['display'], true);

        $result = array(
            'id' => $dashletId,  // Probably useless
            'label' => $dashletHeaderData['label'],
            'type' => $dashletType,
            'options' => $dashletOptions,

            'pageData' => array(
                // TODO: The frontend now requires that these properties be present. Rudiment
                'urls' => array(
                    'startPage' => null,
                    'prevPage' => null,
                    'nextPage' => null,
                    'endPage' => null,
                ),
                'bean' => array(
                    'moduleDir' => '',
                    'objectName' => 'sugarfeed',
                ),
                'offsets' => array(
                    'lastOffsetOnPage' => null,
                ),
            ),
            'viewData' => array(
                'displayColumns' => null,
                'data' => $dashletGenericDisplayData,
            ),
        );

        return $result;
    }
}
