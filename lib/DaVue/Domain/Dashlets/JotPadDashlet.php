<?php

namespace SuiteCRM\DaVue\Domain\Dashlets;

use Dashlet;
use JotPadDashlet as JotPadDashletOrigin;

class JotPadDashlet implements DashletHandlerInterface
{
    public function configure(Dashlet $focus): array
    {
        $result = array(
            'title' => array(
                'name' => 'title',
                'type' => 'varchar',
                'label' => $focus->dashletStrings['LBL_CONFIGURE_TITLE'],
                'value' => $focus->title,
            ),
            'height' => array(
                'name' => 'height',
                'type' => 'varchar',
                'label' => $focus->dashletStrings['LBL_CONFIGURE_HEIGHT'],
                'value' => $focus->height,
            )
        );

        return $result;
    }

    public function display($dashletId, $dashletData, $dashletOptions, $dashletType): array
    {
        $dashletHeaderData = json_decode($dashletData['displayHeader'], true);

        if (empty($dashletOptions['height'])) {
            $jotPad = new JotPadDashletOrigin($dashletId, $dashletOptions);
            $dashletOptions['height'] = $jotPad->height;
        }

        $result = array(
            'id' => $dashletId,  // Probably useless
            'label' => $dashletHeaderData['label'],
            'type' => $dashletType,
            'options' => array('height' => $dashletOptions['height']),

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
                'displayColumns' => null,
                'data' => $dashletOptions['savedText'],
            ),
        );

        return $result;
    }
}
