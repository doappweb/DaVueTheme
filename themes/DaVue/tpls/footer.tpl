{if $AUTHENTICATED}
%%%%%%remove_THIS_end include/javascript/jsAlerts.php line 81%%%%%%
%%%%%%remove_THIS_end include/MassUpdate.php getMassUpdateForm() line 446%%%%%%
%%%%%%remove_THIS_end modules/SecurityGroups/AssignGroups.php line 293%%%%%%
%%%%%%start themes/DaVue/tpls/footer.tpl%%%%%%
%%%%%%end themes/DaVue/tpls/footer.tpl%%%%%%
%%%%%%==CONFIRMATION=OF=THE=FULL=LOAD==%%%%%%
{/if}


{****** LOGIN ******}
{if !$AUTHENTICATED}
<script src="/themes/DaVue/assets/style.js"></script>

<div class="d-none">
%%%%%%
=data=
{ldelim}
     "app_auth": false,
     "config" :{ldelim}"site_url": {$APP_CONFIG.site_url|@json_encode}{rdelim},
     {if !empty($smarty.get)}
          "focus": {ldelim}"get":{$smarty.get|@json_encode}{rdelim}
     {else}
          "focus": {ldelim}"get": {ldelim}"module":"Users","action":"Login"{rdelim}{rdelim}
     {/if}
{rdelim}
=data=
%%%%%%
==NO=AUTH=LOGIN_PAGE==
==CONFIRMATION=OF=THE=FULL=LOAD==
</div>

</body>
</html>
{/if}


