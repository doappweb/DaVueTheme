<?php

namespace SuiteCRM\DaVue\Domain\Dashlets;

use ACLAction;
use BeanFactory;
use Dashlet;
use SugarFeed;
use SugarFeedDashlet as SugarFeedDashletOrigin;

class SugarFeedDashlet implements DashletHandlerInterface
{
    /**
     * @param SugarFeedDashletOrigin $focus
     */
    public function configure(Dashlet $focus): array
    {
        // Get values that were passed to the template.
        // This is done so as not to hardcode them here again, but only to customize the template file.
        $templateResults = json_decode($focus->displayOptions(), true);

        $result = array(
            'title' => array(
                'name' => 'title',
                'type' => 'varchar',
                'label' => $templateResults['titleLBL'],
                'value' => $focus->title,
            ),
            'rows' => array(
                'name' => 'rows',
                'type' => 'varchar',
                'label' => $templateResults['rowsLBL'],
                'value' => $focus->displayRows,
            ),
            'categories' => array(
                'name' => 'categories',
                'type' => 'multienum',
                'label' => $templateResults['categoriesLBL'],
                'options' => $templateResults['categories'],
                'value' => $templateResults['selectedCategories'],
            ),
        );

        if ($templateResults['isRefreshable']) {
            if (!array_key_exists($templateResults['autoRefreshSelect'], $templateResults['autoRefreshOptions'])) {
                $templateResults['autoRefreshSelect'] = -1;
            }
            $result['autoRefresh'] = array(
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
        global $current_user, $db, $timedate;;

        if (empty($dashletData)) {
            // The method was called by Ajax
            $offset = $dashletOptions['offset'];
            $dashletTitle = $dashletOptions['title'];
        } else {
            // The method is called when the page is loaded
            $offset = 0;

            // Remove garbage from the dashlet so that json_decode works without errors
            $superfluousIndex = mb_strpos($dashletData['displayHeader'], '_MyActivityStream');
            $dashletData['displayHeader'] = mb_substr($dashletData['displayHeader'], 0, $superfluousIndex);
            $dashletHeaderData = json_decode($dashletData['displayHeader'], true);
//            $dashletGenericDisplayData = json_decode($dashletData['display'], true);
            $dashletTitle = $dashletHeaderData['title'];
        }

        $userDashlets = $current_user->getPreference('dashlets', 'Home');
        $dashlet = new $userDashlets[$dashletId]['className']($dashletId, array());

        // link types options
        $linkTypesIn = SugarFeed::getLinkTypes();
        $linkTypes = array();
        foreach ($linkTypesIn as $key => $value) {
            $label = translate('LBL_LINK_TYPE_'.$value, 'SugarFeed');
            $linkTypes[$key] = str_ireplace('&#153;', '&trade;', $label);
        }

        if (!$dashlet->seedBean->ACLAccess('ListView')) {
            $result = array(
                'id' => $dashletId,  // Probably useless
                'label' => $dashletTitle,
                'type' => $dashletType,
                'options' => $dashletOptions,

                'pageData' => array(
                    // The frontend now requires that these properties be present
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
                    'linkTypesOptions' => $linkTypes,
                ),
                'viewData' => array(
                    'displayColumns' => array(),
                    'data' => array(),
                ),
            );

            return $result;
        }

        // Get a list of modules used in the event feed that the user has access to
        // @see modules/SugarFeed/Dashlets/SugarFeedDashlet/SugarFeedDashlet::process()
        $regular_modules = array();
        $owner_modules = array();
        if (empty($dashlet->selectedCategories)) {
            $modList = $dashlet->categories;
        } else {
            $modList = array_flip($dashlet->selectedCategories);//27949, here the key of $this->selectedCategories is not module name, the value is module name, so array_flip it.
        }
        foreach (array_keys($modList) as $moduleName) {
            if ($moduleName === 'UserFeed' || $moduleName === 'Facebook' || $moduleName === 'Twitter') {
                $regular_modules[] = $moduleName;
                continue;
            }
            // The box also defined $external_modules, but it seems that this functionality is not fully implemented
            // if (in_array($moduleName, $dashlet->externalAPIList)) {
            //     $external_modules[] = $moduleName;
            // }
            if (ACLAction::getUserAccessLevel($current_user->id, $moduleName, 'view') <= ACL_ALLOW_NONE) {
                // Not enough access to view any records, don't add it to any lists
                continue;
            }
            if (ACLAction::getUserAccessLevel($current_user->id, $moduleName, 'view') == ACL_ALLOW_OWNER) {
                $owner_modules[] = $moduleName;
            } else {
                $regular_modules[] = $moduleName;
            }
        }

        // TODO: The box will further discard posts from social networks for which the current user is not responsible
        // for the post. It is necessary to rewrite the sql query and discard them even before receiving them from
        // the database, so that it is easier to calculate pagination
        if (is_admin($current_user)) {
            $where = " sugarfeed.related_module IN ('" . implode("','", array_merge($regular_modules, $owner_modules)) . "')";
        } else {
            if (count($owner_modules) > 0) {
                $where = " ((sugarfeed.related_module IN ('".implode("','", $regular_modules)."') " .") ";
                $where .= "OR (sugarfeed.related_module IN('".implode("','", $owner_modules)."') AND sugarfeed.assigned_user_id = '".$current_user->id."' "
                    .") ";
                $where .= ")";
            }
        }

        if (!empty($where)) {
            $where .= ' AND ';
        }

        // Condition for basic modules
        $module_limiter = " sugarfeed.related_module in ('" . implode("','", $regular_modules) . "')";

        $where .= $module_limiter;

        // Request to receive basic sugarFeed records (response records will be received separately later)
        // @see ListViewData::getListViewData()
        // TODO: Delete this comment. 'UserFeed', 'Leads', 'Cases', 'Opportunities', 'Contacts'
        $mainQuery = "
            SELECT sugarfeed.id,
               sugarfeed.modified_user_id,
               sugarfeed.name,
               sugarfeed.description,
               sugarfeed.date_entered,
               sugarfeed.created_by,
               sugarfeed.related_module,
               sugarfeed.related_id,
               sugarfeed.link_url,
               sugarfeed.link_type,
               sugarfeed.assigned_user_id
            FROM sugarfeed
            WHERE $where
                AND sugarfeed.deleted = 0
            ORDER BY sugarfeed.date_entered DESC
        ";

        // Number of records displayed on dashlet
        if (isset($dashletOptions['rows'])) {
            $limit = $dashletOptions['rows'];
        } else {
            $limit = $dashlet->displayRows;
        }

        $result = $db->limitQuery($mainQuery, $offset, $limit + 1);

        $rows = array();
        $countRecords = 0;

        while (($row = $db->fetchByAssoc($result)) != null) {

            // In this way, records of modules that cannot be accessed were discarded in the box, but before the main
            // sql request, the extra modules were already discarded. Is this check a rudiment?
            $rowBean = BeanFactory::getBean($row['related_module'], $row['related_id']);
            if (!empty($rowBean->id) && $rowBean->ACLAccess('ListView') === false) {
                continue;
            }

            // TODO: Temporarily copied from the box. How can we have records > than limit if we used LIMIT in sql?
            if ($countRecords < $limit) {
                // Add a photo of the user who created the post, if available.
                // if not, it will be false and the default photo will appear on the frontend
                $user = BeanFactory::getBean('Users', $row['created_by']);
                $userPhotoUrl = false;
                if (!empty($user) && !empty($user->photo)) {
                    $userPhotoUrl = "entryPoint=download&id={$user->id}_photo&type=Users";
                }

                $linkType = false;
                $linkUrl = false;
                if($row['link_type']){
                    $linkType = $row['link_type'];
                    $linkUrl  = $row['link_url'];
                }
                if('Image' === $linkType){
                    $linkUrl = unserialize(base64_decode($row['link_url']));
                }

                $rows[$countRecords] = array(
                    'id' => $row['id'],
                    'dateEntered' => SugarFeed::getTimeLapse($timedate->to_display_date_time($row['date_entered'])),
                    'createdById' => $row['created_by'],
                    'createdByName' => get_assigned_user_name($row['created_by']),
                    'relatedModule' => $row['related_module'],
                    'relatedId' => $row['related_id'],
                    'relatedName' => null,
                    'isRelatedLink' => false,
                    'value' => $row['name'],
                    'photo' => $userPhotoUrl,
                    'type' => $linkType,
                    'linkUrl' => $linkUrl,
                    'replies' => array(),
                );

                // TODO: For custom posts (publications), the related_module field in the database contains the author id.
                // In the original theme, after rendering, such posts have the SugarFeed id, but where does it come from?
//                if ($row['related_module'] !== 'UserFeed') {
//                    $rows[$countRecords]['relatedId'] = /*sugarFeedId*/;
//                }

                // Parsing SugarFeed labels (except for the first one - it is always there). If yes, then the entry is not custom
                preg_match('/\{SugarFeed\.([^\}]+)\}/', $rows[$countRecords]['value'], $modStringMatches);
                if (count($modStringMatches) == 2 && !empty($rows[$countRecords]['relatedModule'])) {
                    $rows[$countRecords]['relatedLabel'] = $modStringMatches[1];

                    // Parsing the title of the linked post. There may be a label indicating that the post should not be displayed as a link.
                    // @see SugarFeedDashlet::display()
                    if (preg_match('/\[(\w+)\:([\w\-\d]*)\:([^\]]*)\]\[HIDELINK\]/', $rows[$countRecords]['value'], $relatedNameMatches)) {
                        $rows[$countRecords]['relatedName'] = $relatedNameMatches[3];
                    } elseif (preg_match('/\[(\w+)\:([\w\-\d]*)\:([^\]]*)\]/', $rows[$countRecords]['value'], $relatedNameMatches)) {
                        $rows[$countRecords]['relatedName'] = $relatedNameMatches[3];
                        $rows[$countRecords]['isRelatedLink'] = true;
                    }

                    // Translation of SugarFeed labels, if there are any in the current post
                    // @see SugarFeedDashlet::process()
                    $rows[$countRecords]['value'] = null;
                    $modKey = $modStringMatches[1];
                    $modString = translate($modKey, 'SugarFeed');
                    if (strpos($modString, '{0}') !== false || isset($GLOBALS['app_list_strings']['moduleListSingular'][$rows[$countRecords]['relatedModule']])) {
                        $modStringSingular = $GLOBALS['app_list_strings']['moduleListSingular'][$rows[$countRecords]['relatedModule']];
                        $modString = string_format($modString, array($modStringSingular));
                        $rows[$countRecords]['value'] = strtolower($modString);
                    }
                } else {
                    // The user wrote this post manually
                    $rows[$countRecords]['value'] = mb_substr($rows[$countRecords]['value'], 37);
                }

                // Now we get response records to the current record
                // @see SugarFeed::fetchReplies()
                $replies = $dashlet->seedBean->get_list('date_entered', "related_module = 'SugarFeed' AND related_id = '{$rows[$countRecords]['id']}'");
                if (count($replies['list']) > 0) {
                    foreach ($replies['list'] as $reply) {
                        // Add a photo of the user who created the post, if it exists, if not, it will be false
                        // and a default photo will appear on the frontend
                        $user = BeanFactory::getBean('Users', $reply->created_by);
                        $userPhotoUrl = false;
                        if (!empty($user) && !empty($user->photo)) {
                            $userPhotoUrl = "entryPoint=download&id={$user->id}_photo&type=Users";
                        }

                        $rows[$countRecords]['replies'][] = array(
                            'id' => $reply->id,
                            'dateEntered' => SugarFeed::getTimeLapse($reply->date_entered),
                            'createdById' => $reply->created_by,
                            'createdByName' => get_assigned_user_name($reply->created_by),
                            'relatedModule' => $reply->related_module,  // Always SugarFeed
                            'relatedId' => $reply->related_id,
                            'relatedName' => null,
                            'isRelatedLink' => false,
                            'value' => mb_substr($reply->name, 37),
                            'photo' => $userPhotoUrl,
                        );
                    }
                }
            }
            $countRecords++;
        }

        // Total number of records.
        // In the box, some records may be removed from the output after executing the sql, but getTotalCount is
        // executed separately and does not take this into account. Will the number of posts in the pagination not match the number in the search results?
        $countMainQuery = "
            SELECT COUNT(*) AS count
            FROM sugarfeed
            WHERE $where 
                AND sugarfeed.deleted = 0
        ";
        $totalRecordsCount = $db->fetchOne($countMainQuery);
        $totalRecordsCount = (int)$totalRecordsCount['count'];

        // Calculating pagination
        $paginationUrl = "index.php?VueAjax=1&method=displaySugarFeedDashletWithOffset&arg[dashletId]=$dashletId&arg[offset]=%d&arg[title]=MyActivityStream&arg[limit]=$limit";
        $startPageUrl = $prevPageUrl = $nextPageUrl = $endPageUrl = null;

        if (0 == $totalRecordsCount) {
            $amountPages = 0;
        } else {
            $amountPages = (int)floor(($totalRecordsCount - 1) / $limit) + 1;
        }

        $currentOffset = $offset;

        $nextOffset = $currentOffset + $limit;
        if ($nextOffset > $totalRecordsCount) {
            $nextOffset = -1;
        }

        $prevOffset = $currentOffset + $limit;
        if ($prevOffset < 0) {
            $prevOffset = -1;
        }

        $lastOffset = ($amountPages - 1) * $limit;

        $lastOffsetOnPage = $nextOffset;
        if (-1 === $nextOffset) {
            $lastOffsetOnPage = $totalRecordsCount;
        }

        if ($offset > 0) {
            $startPageUrl = sprintf($paginationUrl, 0);
            if ($offset - $limit < 0) {
                $prevPageUrl = sprintf($paginationUrl, 0);
            } else {
                $prevPageUrl = sprintf($paginationUrl, $offset - $limit);
            }
        }
        if ($offset + $limit < $totalRecordsCount) {
            $nextPageUrl = sprintf($paginationUrl, $offset + $limit);
            if ($totalRecordsCount - $limit > $offset) {
                $endPageUrl = sprintf($paginationUrl, $totalRecordsCount - $limit);
            }
        }

        $result = array(
            'id' => $dashletId,  // Probably useless
            'label' => $dashletTitle,
            'type' => $dashletType,
            'options' => $dashletOptions,

            'pageData' => array(
                // The frontend now requires that these properties be present
                'urls' => array(
                    'startPage' => $startPageUrl,
                    'prevPage' => $prevPageUrl,
                    'nextPage' => $nextPageUrl,
                    'endPage' => $endPageUrl,
                ),
                'bean' => array(
                    'moduleDir' => '',
                    'objectName' => 'sugarfeed',
                ),
                'offsets' => array(

                    'current' => $currentOffset,
                    'next' => $nextOffset,  // Could be -1
                    'prev' => $prevOffset,  // Could be -1
                    'end' => $lastOffset,

                    'totalCounted' => true,
                    'total' => $totalRecordsCount,
                    'lastOffsetOnPage' => $lastOffsetOnPage,
                ),
                'linkTypesOptions' => $linkTypes,
            ),
            'viewData' => array(
                'displayColumns' => array(),
                'data' => $rows,
            ),
        );

        return $result;
    }
}
