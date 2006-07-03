<h2>Login</h2>

    <form action="index.php?mod=account&action=login" method="post">
    <table>
        {if $cfg->login_method == 'email'}
        <tr>
            <td>{translate}Email:{/translate}</td>
            <td><input type="text" name="email" value="{$smarty.post.email|escape:"htmlall"}"></td>
        </tr>
        {/if}
        {if $cfg->login_method == 'nick'}
        <tr>
            <td>{translate}Nickname:{/translate}</td>
            <td><input type="text" name="nickname" value="{$smarty.post.nickname|escape:"htmlall"}"></td>
        </tr>
        {/if}
        <tr>
            <td>{translate}Password:{/translate}</td>
            <td><input type="password" name="password" value=""></td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="checkbox" name="remember_me" value="1" {if $smarty.post.remember_me == 1} checked="checked" {/if}>
                {translate}Remember me{/translate}<br />
                <input type="submit" name="submit" value="{translate}Login{/translate}">
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <a href="index.php?mod=account&action=register">{translate}Not yet registered ?{/translate}</a> <br>
                <a href="index.php?mod=account&action=forgot-password">{translate}Forgot password ?{/translate}</a> <br>
                <a href="index.php?mod=account&action=activation-email">{translate}Did not get an activation email ?{/translate}</a> <br>
            </td>
        </tr>
    </table>
    </form>