{doc_raw}
<script src="{$www_root_themes_core}/javascript/php.js" type="application/javascript"></script>
<script src="{$www_root_themes_core}/javascript/mootools/mootools.js" type="application/javascript"></script>
{/doc_raw}
{literal}
<script>
window.addEvent('domready', function() {

    $('send_pass_activation').addEvent('submit', function(){
        $('forgot_password').value = sha1($('forgot_password').value);
        return true;
    });

}, 'javascript');
</script>
{/literal}
<h1>{t}Forgot Password{/t}</h1>

    <p>{t}Enter your email below and a new password will be generated for your account and sent to you by email.{/t}</p>

    {if $err.email_wrong == 1}<p class="error">{t}The mail seems to be not valid.{/t}</p>{/if}
    {if $err.no_such_mail == 1}<p class="error">{t}There is no account with such email.{/t}</p>{/if}
    {if $err.account_not_activated == 1}<p class="error">{t}Account with this email has not been activated yet.{/t}</p>{/if}
    {if $err.form_not_filled == 1}<p class="error">{t}Please fill the form.{/t}</p>{/if}
    {if $err.pass_too_short == 1}<p class="error">{t}The password is too short!{/t}</p>{/if}
    
    <form action="index.php?mod=account&action=forgot_password" method="post" id="send_pass_activation">
    <table>
    <tr>
        <td>{t}Email:{/t}</td>
        <td><input type="text" name="email" value="{$smarty.post.email|escape:"html"}"></td>
    </tr>
    <tr>
        <td>{t}New password:{/t}</td>
        <td><input id="forgot_password" type="password" name="password" value="{$smarty.post.email|escape:"html"}"></td>
    </tr>
    <tr>
        <td colspan="2">
            <input type="submit" name="submit" value="{t}Send password activation mail{/t}">
        </td>
    </tr>
    </table>
    </form>