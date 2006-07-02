<h2>{translate}Register{/translate}</h2>

    {if $err.not_filled == 1}{translate}<p class="error">Please fill out all required fields!{/translate}</p>{/if}
    {if $err.nick_wrong == 1}{translate}<p class="error">The nickname contains violating characters!{/translate}</p>{/if}
    {if $err.email_wrong == 1}{translate}<p class="error">The email address is wrong!{/translate}</p>{/if}
    {if $err.email_exists == 1}{translate}<p class="error">The email address already exists in our database!{/translate}</p>{/if}
    {if $err.nick_exists == 1}{translate}<p class="error">The nickname already exists in our database!{/translate}</p>{/if}
    {if $err.pass_too_short == 1}{translate}<p class="error">The password is too short!{/translate}</p>{/if}
    {if $err.passes_do_not_fit == 1}{translate}<p class="error">The passwords aren't the same!{/translate}</p>{/if}
    
    <form action="index.php?mod=account&action=register" method="post">
    <table>
        <tr>
            <td>{translate}Email:{/translate}</td>
            <td><input type="text" name="email" value="{$smarty.post.email|escape:"htmlall"}"></td>
        </tr>
        <tr>
            <td>{translate}Confirm email:{/translate}</td>
            <td><input type="text" name="email2" value="{$smarty.post.email2|escape:"htmlall"}"></td>
        </tr>
        <tr>
            <td>{translate}Nick:{/translate}</td>
            <td><input type="text" name="nick" value="{$smarty.post.nick|escape:"htmlall"}"></td>
        </tr>
        <tr>
            <td valign='top'>{translate}Password:{/translate}</td>
            <td><input type="password" name="password" value=""><br /><span class='font_mini'>{translate}Minimum: {/translate}{$min_length}</span></td>
        </tr>
        <tr>
            <td>{translate}Password again:{/translate}</td>
            <td><input type="password" name="password2" value=""></td>
        </tr>
        <tr>
            <td colspan="2">
                <span id='password_verification' style='width: 1px; height: 20px; background: red;'></span>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="submit" name="submit" value="{translate}Register{/translate}">
            </td>
        </tr>
    </table>
    </form>