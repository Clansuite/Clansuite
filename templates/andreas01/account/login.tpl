<h2>{translate}Login{/translate}</h2>

    {if $err.not_filled == 1}<p class="error">{translate}Please fill out all required fields!{/translate}</p>{/if}
    {if $err.mismatch == 1}<p class="error">{translate}This combination is not stored in our database!{/translate}</p>{/if}
    {if $err.login_attempts > 0}<p class="error">{translate}Failed Attempts:{/translate}{$err.login_attempts}</p>{/if}
    <form action="index.php?mod=account&action=login" method="post">
    <table>
        {if $cfg->login_method == 'email'}
        <tr>
            <td>{translate}Email:{/translate}<br /><input class="input_text" type="text" name="email" value="{$smarty.post.email|escape:"htmlall"}"></td>
        </tr>
        {/if}
        {if $cfg->login_method == 'nick'}
        <tr>
            <td>{translate}Nickname:{/translate}<br />
            <input class="input_text" type="text" name="nickname" value="{$smarty.post.nickname|escape:"htmlall"}"></td>
        </tr>
        {/if}
        <tr>
            <td>{translate}Password:{/translate}<br />
            <input class="input_text" type="password" name="password" value=""></td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="checkbox" name="remember_me" value="1" {if $smarty.post.remember_me == 1} checked="checked" {/if}>
                {translate}Remember me ( Cookie ){/translate}
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <input class="input_submit" type="submit" name="submit" value="{translate}Login{/translate}">
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <a href="index.php?mod=account&action=register">{translate}Not yet registered ?{/translate}</a> <br>
                <a href="index.php?mod=account&action=forgot_password">{translate}Forgot password ?{/translate}</a> <br>
                <a href="index.php?mod=account&action=activation_email">{translate}Did not get an activation email ?{/translate}</a> <br>
            </td>
        </tr>
    </table>
    </form>