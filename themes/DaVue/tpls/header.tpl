{if $AUTHENTICATED}
{if !$smarty.server.HTTP_X_DA_VUE_AJAX}
{*<script>*}
    {da_rout_redirect}
{*    let frontLink = '{da_rout_redirect}'*}
{*    {literal}*}
{*    if (frontLink) {*}
{*        console.log('REDIRECT TO FRONT')*}
{*        location.replace(frontLink);*}
{*    }*}
{*    {/literal}*}
{*</script>*}
{/if}
{da_app_init}
%%%%%%start themes/DaVue/tpls/header.tpl%%%%%%<br>
{da_add_data dataKey='header' dataSrc='tpl_vars'}
{da_query_params}
%%%%%%end themes/DaVue/tpls/header.tpl%%%%%%
%%%%%%remove_THIS_start modules/AOS_PDF_Templates/formLetter.php line 133-137, 196-200%%%%%%
%%%%%%remove_THIS_start include/MVC/View/SugarView.php getModuleTitle() line 1467%%%%%%
%%%%%%remove_THIS_start Users/views/view.edit.php line 274, 275%%%%%%
{/if}

{****** LOGIN ******}
{if !$AUTHENTICATED}
    {include file="themes/DaVue/tpls/_loginHead.tpl"}
    <body class="hold-transition login-page">
{/if}