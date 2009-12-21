{* {$config|@var_dump} *}

{move_to target="pre_head_close"}
<script src="{$www_root_themes_core}/javascript/webtoolkit.sha1.js" type="application/javascript"></script>
{/move_to}


    <script>
    function hashLoginPassword(theForm)
    {
        theForm.password.value = SHA1(theForm.password.value);
    }
    </script>


{* OLD ERRORS
    {if $error.not_filled == 1}<p class="error">{t}Please fill out all required fields!{/t}</p>{/if}
    {if $error.mismatch == 1}<p class="error">{t}This combination is not stored in our database!{/t}</p>{/if}
    {if $error.login_attempts > 0}<p class="error">{t}Failed Attempts:{/t}{$error.login_attempts}</p>{/if}
*}

<!-- Start Login Widget from Module Account /-->

<div class="widget_head">
	<span class="widget_title">Login</span>
</div>
<form action="index.php?mod=account&action=login"
                      method="post"
                      id="block_login_form"
                      accept-charset="UTF-8"
                      onsubmit="hashLoginPassword(this);"
                />
{if isset($config.login.login_method) && $config.login.login_method == 'email'}
<div class="text">E-Mail:</div>
<div class="field"><input class="input_text" type="text" name="email"
                                     onblur="if(this.value=='')this.value=this.defaultValue;"
                                     onfocus="if(this.value==this.defaultValue)this.value='';"
                                     value="email" />
</div>
{/if}
{if isset($config.login.login_method) && $config.login.login_method == 'nick'}
<div class="text">Nickname:</div>
<div class="field"><input class="input_text" type="text" name="nickname"
                                     onblur="if(this.value=='')this.value=this.defaultValue;"
                                     onfocus="if(this.value==this.defaultValue)this.value='';"
                                     value="nickname" />
</div>
{/if}
<div class="text">Passwort:</div>
<div class="field"><input class="input_text" type="password" name="password" id="block_password"
                                     onblur="if(this.value=='')this.value=this.defaultValue;"
                                     onfocus="if(this.value==this.defaultValue)this.value='';"
                                     value="******" autocomplete="off" />
</div>
<div class="extrafield">
	<input type="checkbox" name="remember_me" value="1" {if isset($smarty.post.remember_me)} checked="checked" {/if} /> Passwort merken (Cookie)
</div>
<div class="submitbutton">
	<input type="submit" name="submit" id="login_button" value="{t}Login{/t}" class="ButtonGreen" />
</div>
<div class="helplinks">
	<a href="index.php?mod=account&action=register">{t}Not yet registered ?{/t}</a> <br />
    <a href="index.php?mod=account&action=forgot_password">{t}Forgot password ?{/t}</a> <br />
    <a href="index.php?mod=account&action=activation_email">{t}Did not get an activation email ?{/t}</a>
</div>
</form>

<!-- Ende Login Widget -->