{* {$config|var_dump} *}

{move_to target="pre_head_close"}
<script src="{$www_root_themes_core}javascript/webtoolkit.sha1.js" type="application/javascript"></script>
<script type="text/javascript">
function hashLoginPassword(theForm)
{
    theForm.password.value = SHA1(theForm.password.value);
}
</script>
{/move_to}

<form action="{$www_root}index.php?mod=account&action=login"
      method="post"
      id="block_login_form"
      accept-charset="UTF-8"
      onsubmit="hashLoginPassword(this);">

<h2 class="menu_header">{t}Login{/t}</h2>
<div class="cell1">
<table cellpadding="0" cellspacing="0" border="0" width="100%">
    {if isset($config.login.login_method) && $config.login.login_method == 'email'}
    <tr>
        <td class="cell1">{t}Email:{/t}</td>
        <td class="cell2"><input class="input_text" type="text" name="email"
                   onblur="if(this.value=='')this.value=this.defaultValue;"
                   onfocus="if(this.value==this.defaultValue)this.value='';"
                   value="email" /></td>
    </tr>
    {elseif isset($config.login.login_method) && $config.login.login_method == 'nick'}
    <tr>
        <td class="cell1">{t}Nickname:{/t}</td>
        <td class="cell2"><input class="input_text" type="text" name="nickname"
                   onblur="if(this.value=='')this.value=this.defaultValue;"
                   onfocus="if(this.value==this.defaultValue)this.value='';"
                   value="nickname" /></td>
    </tr>
    {/if}
    <tr>
        <td class="cell1">{t}Password:{/t}</td>
        <td class="cell2"><input class="input_text" type="password" name="password" id="block_password"
                   onblur="if(this.value=='')this.value=this.defaultValue;"
                   onfocus="if(this.value==this.defaultValue)this.value='';"
                   value="******" /></td>
    </tr>
    <tr>
        <td colspan="2" class="cell1">
            <input type="checkbox" name="remember_me" value="1" {if isset($smarty.post.remember_me)} checked="checked" {/if} />
            {t}Remember me ( Cookie ){/t}
        </td>
    </tr>
    <tr>
        <td colspan="2" align="center" class="cell1">
            <input type="submit" name="submit" id="login_button" value="{t}Login{/t}" class="ButtonGreen" />
        </td>
    </tr>
    <tr>
        <td colspan="2" class="cell1" width="70%">
            <font size="10">
                <a href="{$www_root}index.php?mod=account&action=register">{t}Not yet registered?{/t}</a> <br />
                <a href="{$www_root}index.php?mod=account&action=forgot_password">{t}Forgot password?{/t}</a> <br />
                <a href="{$www_root}index.php?mod=account&action=activation_email">{t}Did not get an activation email?{/t}</a>
            </font>

        </td>
    </tr>
</table>
</div>
</form>