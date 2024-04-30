<?php

namespace SuiteCRM\DaVue\Services\Api;

use BeanFactory;
use DBManagerFactory;

class KnowledgeBaseService
{
    /**
     * Get article list based on search (module Cases field Name)
     *
     * @usage http://localhost/index.php?VueAjax=1&method=getKbArticles&arg[]...
     * @param $args
     * @return array
     */
    public function getKbArticles($args)
    {
        global $app_list_strings;
        $search = trim($args['search']);

        $relevanceCalculation = "CASE WHEN name LIKE '$search' THEN 10 
                                ELSE 0 END + CASE WHEN name LIKE '%$search%' THEN 5 
                                ELSE 0 END + CASE WHEN description LIKE '%$search%' THEN 2 ELSE 0 END";

        $query = "SELECT id, $relevanceCalculation AS relevance FROM aok_knowledgebase 
                  WHERE deleted = '0' AND $relevanceCalculation > 0 ORDER BY relevance DESC";

        $offset = 0;
        $limit = 30;
        $ret = [];
        $result = DBManagerFactory::getInstance()->limitQuery($query, $offset, $limit);

        while ($row = DBManagerFactory::getInstance()->fetchByAssoc($result)) {
            $kb = BeanFactory::getBean('AOK_KnowledgeBase', $row['id']);
            $ret[] = array(
                'id' => $kb->id,
                'name' => $kb->name,
                'status' => $app_list_strings['aok_status_list'][$kb->status],
            );
        }
        return $ret;
    }

    /**
     * Get article data
     *
     * @usage http://localhost/index.php?VueAjax=1&method=getKbArticle&arg[]...
     * @param $args
     * @return array
     */
    public function getKbArticle($args)
    {
        $article_id = $args['article'];
        $article = BeanFactory::newBean('AOK_KnowledgeBase');
        $article->retrieve($article_id);
        $result = array(
            'type' => 'casePopover',
            'title' => $article->name,
            'description' => html_entity_decode($article->description)
        );

        if (isset($article->additional_info) && trim($article->additional_info) !== '') {
            $result['additional_info'] = $article->additional_info;
        }

        return $result;
    }
}
