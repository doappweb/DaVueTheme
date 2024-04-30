<?php

namespace SuiteCRM\DaVue\Domain\Dashlets;

use Dashlet;
use iFrameDashlet as iFrameDashletOrigin;

class iFrameDashlet implements DashletHandlerInterface
{
    /**
     * @param iFrameDashletOrigin $focus
     */
    public function configure(Dashlet $focus): array
    {
        // Get values that were passed to the template.
        // This is done so as not to hardcode them here again, but only to customize the template file.
        $templateResults = json_decode($focus->displayOptions(), true);

        $result = array(
            'basicSettings' => array(
                'title' => array(
                    'name' => 'title',
                    'type' => 'varchar',
                    'label' => $templateResults['titleLBL'],
                    'value' => $focus->title,
                ),
                'height' => array(
                    'name' => 'height',
                    'type' => 'varchar',
                    'label' => $templateResults['heightLBL'],
                    'value' => $focus->height,
                ),
                'url' => array(
                    'name' => 'url',
                    'type' => 'varchar',
                    'label' => $templateResults['urlLBL'],
                    'value' => $focus->url,
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

    /**
     * @inheritDoc
     */
    public function display($dashletId, $dashletData, $dashletOptions, $dashletType): array
    {
        $dashletHeaderData = json_decode($dashletData['displayHeader'], true);

        // Instead of a template, the data for this dashlet is displayed inside the class, so you have to repeat the logic for getting it here
        $dashlet = new iFrameDashletOrigin($dashletId, $dashletOptions);

        $scheme = parse_url($dashlet->url, PHP_URL_SCHEME);

        // iFrameDashlet has a similar property, but it is protected. I hardcoded it so as not to expand the entire class because of it.
        $allowed_schemes = array("http", "https");

        if (!in_array($scheme, $allowed_schemes)) {
            $outUrl = false;
        } else {
            $outUrl = str_replace(
                array('@@LANG@@', '@@VER@@', '@@EDITION@@'),
                array($GLOBALS['current_language'], $GLOBALS['sugar_config']['sugar_version'], 'COM'),
                $dashlet->url
            );
        }

        $result = array(
            'id' => $dashletId,  // Probably useless
            'label' => $dashletHeaderData['label'],
            'type' => $dashletType,
            'options' => array(
                'height' => $dashlet->height,
            ),

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
                'displayColumns' => array(),
                'data' => array(
                    'url' => $outUrl,
                ),
            ),
        );

        return $result;
    }
}
