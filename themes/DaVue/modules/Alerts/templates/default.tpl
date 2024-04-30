{ldelim}
    "alerts":[
        {foreach from=$Results item=result name=alertsForeach}
            {ldelim}
                "id":{$result->id|@json_encode},
                "type":{$result->type|@json_encode},
                "url_redirect":{$result->url_redirect|@json_encode},
                "target_module":{$result->target_module|@json_encode},
                "name":{$result->name|@json_encode},
                "description":{$result->description|@json_encode}
            {rdelim}
            {if !$smarty.foreach.alertsForeach.last},{/if}
        {/foreach}
    ]
{rdelim}