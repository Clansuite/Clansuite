<h2>{t}Login{/t}</h2>

    {if $err.not_filled == 1}<p class="error">{t}Please fill out all required fields!{/t}</p>{/if}
    {if $err.mismatch == 1}<p class="error">{t}This combination is not stored in our database!{/t}</p>{/if}
    {if $err.login_attempts > 0}<p class="error">{t}Failed Attempts:{/t}{$err.login_attempts}</p>{/if}
    <form action="index.php?mod=account&action=login" method="post">
    <table>
        {if $cfg->login_method == 'email'}
        <tr>
            <td>{t}Email:{/t}<br /><input class="input_text" type="text" name="email" value="{$smarty.post.email|escape:"html"}"></td>
        </tr>
        {/if}
        {if $cfg->login_method == 'nick'}
        <tr>
            <td>{t}Nickname:{/t}<br />
            <input class="input_text" type="text" name="nickname" value="{$smarty.post.nickname|escape:"html"}"></td>
        </tr>
        {/if}
        <tr>
            <td>{t}Password:{/t}<br />
            <input class="input_text" type="password" name="password" value=""></td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="checkbox" name="remember_me" value="1" {if $smarty.post.remember_me == 1} checked="checked" {/if}>
                {t}Remember me ( Cookie ){/t}
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <input class="ButtonGrey" type="submit" name="submit" value="{t}Login{/t}">
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <a href="index.php?mod=account&action=register">{t}Not yet registered ?{/t}</a> <br>
                <a href="index.php?mod=account&action=forgot_password">{t}Forgot password ?{/t}</a> <br>
                <a href="index.php?mod=account&action=activation_email">{t}Did not get an activation email ?{/t}</a> <br>
            </td>
        </tr>
    </table>
    </form>