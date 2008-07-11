{doc_raw}
<script src="{$www_root_themes_core}/javascript/php.js" type="application/javascript"></script>
<script src="{$www_root_themes_core}/javascript/mootools/mootools.js" type="application/javascript"></script>
{/doc_raw}
{literal}
<script>
window.addEvent('domready', function() {

    $('register_form').addEvent('submit', function(){
        $('password').value = sha1($('password').value);
        $('password2').value = sha1($('password2').value);
        if( $('password').value != $('password2').value )
        {
            alert('Passwords do not match. Please Re-Enter');
            $('password').value = '';
            $('password2').value = '';
            return false;
        }
        return true;
    });

}, 'javascript');
</script>
{/literal}
<h2>{t}Register{/t}</h2>

    {if $err.not_filled == 1}<p class="error">{t}Please fill out all required fields!{/t}</p>{/if}
    {if $err.nick_wrong == 1}<p class="error">{t}The nickname contains violating characters!{/t}</p>{/if}
    {if $err.email_wrong == 1}<p class="error">{t}Please enter a valid email!{/t}</p>{/if}
    {if $err.email_exists == 1}<p class="error">{t}The email address already exists in our database!{/t}</p>{/if}
    {if $err.nick_exists == 1}<p class="error">{t}The nickname already exists in our database!{/t}</p>{/if}
    {if $err.pass_too_short == 1}<p class="error">{t}The password is too short!{/t}</p>{/if}
    {if $err.passes_do_not_fit == 1}<p class="error">{t}The passwords aren't the same!{/t}</p>{/if}
    {if $err.wrong_captcha == 1}<p class="error">{t}The code you entered is wrong!{/t}</p>{/if}
    {if $err.emails_mismatching == 1}<p class="error">{t}The email adresses do not match!{/t}</p>{/if}
    
    <form action="index.php?mod=account&action=register" method="post" id="register_form">
    <table>
        <tr>
            <td>{t}Nick:{/t}</td>
            <td><input type="text" name="nick" value="{$smarty.post.nick|escape:"html"}"></td>
        </tr>
        <tr>
            <td>{t}Email:{/t}</td>
            <td><input onkeyup="javascript:mailTest()" oncopy="javascript:mailTest()" onpaste="javascript:mailTest()" oncut="javascript:mailTest()" type="text" id="email" name="email" id='email' value="{$smarty.post.email|escape:"html"}"></td>
        </tr>
        <tr>
            <td>{t}Confirm email:{/t}</td>
            <td><input onkeyup="javascript:mailTest()" oncopy="javascript:mailTest()" onpaste="javascript:mailTest()" oncut="javascript:mailTest()" type="text" id="email2" name="email2" id='email2' value="{$smarty.post.email2|escape:"html"}"></td>
        </tr>

        <tr>
            <td valign='top'>{t}Password:{/t}</td>
            <td><input onkeyup="javascript:passTest()" oncopy="javascript:passTest()" onpaste="javascript:passTest()" oncut="javascript:passTest()" type="password" id='password' name="password" value=""></td>
        </tr>
        <tr>
            <td>{t}Confirm Password:{/t}</td>
            <td><input onkeyup="javascript:passTest()" oncopy="javascript:passTest()" onpaste="javascript:passTest()" oncut="javascript:passTest()" type="password" id='password2' name="password2" value=""><br /><span class='font_mini'>{t}Minimum: {/t}{$min_length}</span></td>
        </tr>
        <!--
        <tr>
            <td>{t}Password Security:{/t}</td>
            <td><div id='password_verification' style='width: 1px;height: 15px; background-color: red; border: thin solid black;'>&nbsp;</div></td>
        </tr>
        <tr>
            <td>{t}Enter Code:{/t}</td>
            <td><img src="{$captcha_url}" style="border:thin solid black;"><br /><input type="text" name="captcha" value=""></td>
        </tr>
        -->
        <tr>
            <td cospan='2'><input type="submit" name="submit" value="{t}Register{/t}"></td>
        </tr>
    </table>
    </form>