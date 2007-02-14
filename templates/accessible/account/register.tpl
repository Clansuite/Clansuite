<h2>{translate}Register{/translate}</h2>

    {if $err.not_filled == 1}<p class="error">{translate}Please fill out all required fields!{/translate}</p>{/if}
    {if $err.nick_wrong == 1}<p class="error">{translate}The nickname contains violating characters!{/translate}</p>{/if}
    {if $err.email_wrong == 1}<p class="error">{translate}The email address is wrong!{/translate}</p>{/if}
    {if $err.email_exists == 1}<p class="error">{translate}The email address already exists in our database!{/translate}</p>{/if}
    {if $err.nick_exists == 1}<p class="error">{translate}The nickname already exists in our database!{/translate}</p>{/if}
    {if $err.pass_too_short == 1}<p class="error">{translate}The password is too short!{/translate}</p>{/if}
    {if $err.passes_do_not_fit == 1}<p class="error">{translate}The passwords aren't the same!{/translate}</p>{/if}
    {if $err.wrong_captcha == 1}<p class="error">{translate}The code you entered is wrong!{/translate}</p>{/if}
    
    <form action="index.php?mod=account&action=register" method="post">
    <table>
        <tr>
            <td>{translate}Nick:{/translate}</td>
            <td><input class='input_text' type="text" name="nick" value="{$smarty.post.nick|escape:"html"}"></td>
        </tr>
        <tr>
            <td>{translate}Email:{/translate}</td>
            <td><input class='input_text' onkeyup="javascript:mailTest()" oncopy="javascript:mailTest()" onpaste="javascript:mailTest()" oncut="javascript:mailTest()" type="text" id="email" name="email" id='email' value="{$smarty.post.email|escape:"html"}"></td>
        </tr>
        <tr>
            <td>{translate}Confirm email:{/translate}</td>
            <td><input class='input_text' onkeyup="javascript:mailTest()" oncopy="javascript:mailTest()" onpaste="javascript:mailTest()" oncut="javascript:mailTest()" type="text" id="email2" name="email2" id='email2' value="{$smarty.post.email2|escape:"html"}"></td>
        </tr>

        <tr>
            <td valign='top'>{translate}Password:{/translate}</td>
            <td><input class='input_text' onkeyup="javascript:passTest()" oncopy="javascript:passTest()" onpaste="javascript:passTest()" oncut="javascript:passTest()" type="password" id='password' name="password" value=""></td>
        </tr>
        <tr>
            <td>{translate}Confirm Password:{/translate}</td>
            <td><input class='input_text' onkeyup="javascript:passTest()" oncopy="javascript:passTest()" onpaste="javascript:passTest()" oncut="javascript:passTest()" type="password" id='password2' name="password2" value=""><br /><span class='font_mini'>{translate}Minimum: {/translate}{$min_length}</span></td>
        </tr>
        <tr>
            <td>{translate}Password Security:{/translate}</td>
            <td><div id='password_verification' style='width: 1px;height: 15px; background-color: red; border: thin solid black;'>&nbsp;</div></td>
        </tr>
        <tr>
            <td>{translate}Enter Code:{/translate}</td>
            <td><img src="{$captcha_url}" style="border:thin solid black;"><br /><input class='input_text' type="text" name="captcha" value=""></td>
        </tr>
        <tr>
            <td cospan='2'><input class='ButtonGrey' type="submit" name="submit" value="{translate}Register{/translate}"></td>
        </tr>
    </table>
    </form>