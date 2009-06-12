{move_to target="pre_head_close"}
<script src="{$www_root_themes_core}/javascript/webtoolkit.sha1.js" type="application/javascript"></script>
{/move_to}

{literal}
    <script>

    function hashLoginPassword(theForm)
    {
        if( (theForm.password.value  != '') &&
            (theForm.password2.value != '') &&
            (theForm.password.value  != theForm.password2.value))
            {
                alert('Passwords do not match. Please Re-Enter');
                theForm.password.value  = '';
                theForm.password2.value = '';
                return false;
            }
            else
            {
                theForm.password.value  = SHA1(theForm.password.value);
                theForm.password2.value = SHA1(theForm.password2.value);
                return true;
            }
    }

    function randomPassword(length)
    {
       chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
       pass = "";

       for(x=0;x<length;x++)
       {
          i = Math.floor(Math.random() * 62);
          pass += chars.charAt(i);
       }

       return pass;
    }

    function fillPasswordForm(length)
    {
        document.register_form.password.type = "text";
        document.register_form.password2.type = "text";
        document.register_form.password2.disabled = true;

        document.register_form.password.value=randomPassword(length)
        document.register_form.password2.value=document.register_form.password.value
    }

    function resetPasswordForm()
    {
        document.register_form.password.type = "password";
        document.register_form.password.value = '';

        document.register_form.password2.type = "password";
        document.register_form.password2.disabled = false;
        document.register_form.password2.value = '';
    }
    </script>
{/literal}
<h2>{t}Register{/t}</h2>
{* OLD ERRORS
    {if $err.not_filled == 1}<p class="error">{t}Please fill out all required fields!{/t}</p>{/if}
    {if $err.nick_wrong == 1}<p class="error">{t}The nickname contains violating characters!{/t}</p>{/if}
    {if $err.email_wrong == 1}<p class="error">{t}Please enter a valid email!{/t}</p>{/if}
    {if $err.email_exists == 1}<p class="error">{t}The email address already exists in our database!{/t}</p>{/if}
    {if $err.nick_exists == 1}<p class="error">{t}The nickname already exists in our database!{/t}</p>{/if}
    {if $err.pass_too_short == 1}<p class="error">{t}The password is too short!{/t}</p>{/if}
    {if $err.passes_do_not_fit == 1}<p class="error">{t}The passwords aren't the same!{/t}</p>{/if}
    {if $err.wrong_captcha == 1}<p class="error">{t}The code you entered is wrong!{/t}</p>{/if}
    {if $err.emails_mismatching == 1}<p class="error">{t}The email adresses do not match!{/t}</p>{/if}
*}
    <form action="index.php?mod=account&action=register" method="post" name="register_form" id="register_form" onsubmit="hashLoginPassword(this)">
    <table>
        <tr>
            <td>{t}Nick:{/t}</td>
            <td><input type="text" name="nick" value="{$smarty.post.nick|default|escape:"html"}"></td>
        </tr>
        <tr>
            <td>{t}Email:{/t}</td>
            <td><input onkeyup="javascript:mailTest()" oncopy="javascript:mailTest()" onpaste="javascript:mailTest()" oncut="javascript:mailTest()" type="text" id="email" name="email" id='email' value="{$smarty.post.email|default|escape:"html"}"></td>
        </tr>
        <tr>
            <td>{t}Confirm email:{/t}</td>
            <td><input onkeyup="javascript:mailTest()" oncopy="javascript:mailTest()" onpaste="javascript:mailTest()" oncut="javascript:mailTest()" type="text" id="email2" name="email2" id='email2' value="{$smarty.post.email2|default|escape:"html"}"></td>
        </tr>

        <tr>
            <td valign='top'>{t}Password:{/t}</td>
            <td><input onkeyup="javascript:passTest()" oncopy="javascript:passTest()" onpaste="javascript:passTest()" oncut="javascript:passTest()" type="password" id='password' name="password" value="">
                <br />
                <form name="generatePassword">
                <input type="button" value="Generate Password" onClick="fillPasswordForm(this.form.passwordlength.value)"><br />
                <input type="button" value="Reset" onClick="resetPasswordForm()"><br />
                <b>Password Length:</b>
                <input type="text" name="passwordlength" size=3 value="7">
                </form>
            </td>
        </tr>
        <tr>
            <td valign="top">{t}Confirm Password:{/t}</td>
            <td><input onkeyup="javascript:passTest()" oncopy="javascript:passTest()" onpaste="javascript:passTest()" oncut="javascript:passTest()" type="password" id='password2' name="password2" value=""><br /><span class='font_mini'>{t}Minimum: {/t}{$min_length}</span></td>
        </tr>
        <!--
        <tr>
            <td>{t}Password Security:{/t}</td>
            <td><div id='password_verification' style='width: 1px;height: 15px; background-color: red; border: thin solid black;'>&nbsp;</div></td>
        </tr>
        <tr>
            <td>{t}Captcha:{/t}</td>
            <td>
            {*{if $config.captcha.type == 'recaptcha'} *}
            {* {$cs->loadModule("recaptcha")}
             {$recaptcha->display_recaptcha()} *}
            {* {else} *}
             <img src="{$captcha_url}" style="border:thin solid black;"><br /><input type="text" name="captcha" value="">
            {* {/if} *}
            </td>
        </tr>
        -->
        <tr>
            <td cospan='2'><input type="submit" name="submit" value="{t}Register{/t}"></td>
        </tr>
    </table>
    </form>