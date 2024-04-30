{****** LOGIN ******}
{if !$AUTHENTICATED}
<!-- START LOGIN.TPL -->
<script type='text/javascript'>
    var LBL_LOGIN_SUBMIT = '{sugar_translate module="Users" label="LBL_LOGIN_SUBMIT"}';
    var LBL_REQUEST_SUBMIT = '{sugar_translate module="Users" label="LBL_REQUEST_SUBMIT"}';
    var LBL_SHOWOPTIONS = '{sugar_translate module="Users" label="LBL_SHOWOPTIONS"}';
    var LBL_HIDEOPTIONS = '{sugar_translate module="Users" label="LBL_HIDEOPTIONS"}';
</script>

{* MESSAGES *}
{if $LOGIN_ERROR_MESSAGE}
    <div class="alert alert-danger alert-dismissible">{$LOGIN_ERROR_MESSAGE}</div>
{/if}

<div class="alert alert-danger alert-dismissible" id="browser_warning" style="display:none">
    {sugar_translate label="WARN_BROWSER_VERSION_WARNING"}
</div>
<div class="alert alert-danger alert-dismissible" id="ie_compatibility_mode_warning" style="display:none">
    {sugar_translate label="WARN_BROWSER_IE_COMPATIBILITY_MODE_WARNING"}
</div>

<!-- Start login container -->
<div class="login-box">
    <div class="login-logo">
        {$LOGIN_IMAGE}
    </div>
    <div class="card">
        <div class="card-body login-card-body">

            {* PASSWORD INPUT *}
            <form role="form" action="index.php" method="post" name="DetailView" id="form"
                  onsubmit="return document.getElementById('cant_login').value == ''" autocomplete="off">

                {if $LOGIN_ERROR !=''}
                    <p class="login-box-msg text-danger">{$LOGIN_ERROR}</p>
                    <span class="error"></span>
                    {if $WAITING_ERROR !=''}
                        <p class="login-box-msg text-danger">{$WAITING_ERROR}</p>
                    {/if}
                {/if}


                <input type="hidden" name="module" value="Users">
                <input type="hidden" name="action" value="Authenticate">
                <input type="hidden" name="return_module" value="Users">
                <input type="hidden" name="return_action" value="Login">
                <input type="hidden" id="cant_login" name="cant_login" value="">
                {foreach from=$LOGIN_VARS key=key item=var}
                    <input type="hidden" name="{$key}" value="{$var}">
                {/foreach}


                {if !empty($SELECT_LANGUAGE)}
                    <div class="input-group mb-3">
                        <select class="custom-select" name='login_language' onchange="switchLanguage(this.value)">{$SELECT_LANGUAGE}</select>
                    </div>
                {/if}

                <div class="input-group mb-3">
                    <input type="text" class="form-control"
                           placeholder="{sugar_translate module="Users" label="LBL_USER_NAME"}" required autofocus
                           tabindex="1" id="user_name" name="user_name" value='{$LOGIN_USER_NAME}' autocomplete="off">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" class="form-control"
                           placeholder="{sugar_translate module="Users" label="LBL_PASSWORD"}" tabindex="2"
                           id="username_password" name="username_password" value='{$LOGIN_PASSWORD}' autocomplete="off">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>

                <input id="bigbutton" class="btn btn-primary btn-block mb-2" type="submit"
                       title="{sugar_translate module="Users" label="LBL_LOGIN_BUTTON_TITLE"}" tabindex="3" name="Login"
                       value="{sugar_translate module="Users" label="LBL_LOGIN_BUTTON_LABEL"}">


                <div id="forgotpasslink" style="cursor: pointer; display:{$DISPLAY_FORGOT_PASSWORD_FEATURE};"
                     onclick='toggleDisplay("forgot_password_dialog");'>
                    <p class="mb-1"><a href='javascript:void(0)'>{sugar_translate module="Users" label="LBL_LOGIN_FORGOT_PASSWORD"}</a></p>
                </div>
            </form>

            {* PASSWORD REPAIR *}

            <form role="form" action="index.php" method="post" name="fp_form" id="fp_form" autocomplete="off">

                <div id="forgot_password_dialog" style="display:none">
                    <input type="hidden" name="entryPoint" value="GeneratePassword">
                    <div id="generate_success" class='error' style="display:inline;"></div>
                    <br>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" size='26' id="fp_user_name" name="fp_user_name"
                               value='{$LOGIN_USER_NAME}'
                               placeholder="{sugar_translate module="Users" label="LBL_USER_NAME"}" autocomplete="off">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <input type="text" class="form-control" size='26' id="fp_user_mail" name="fp_user_mail" value=''
                               placeholder="{sugar_translate module="Users" label="LBL_EMAIL"}" autocomplete="off">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>

                    {$CAPTCHA}
                    <div id='wait_pwd_generation'></div>
                    <input title="Email Temp Password" class="btn btn-primary btn-block" type="button" style="display:inline"
                           onclick="validateAndSubmit(); return document.getElementById('cant_login').value == ''"
                           id="generate_pwd_button" name="fp_login"
                           value="{sugar_translate module="Users" label="LBL_LOGIN_SUBMIT"}" autocomplete="off">
                </div>
            </form>

        </div>
    </div>
</div>
<!-- End login container -->
<!-- END LOGIN.TPL -->
{/if}
