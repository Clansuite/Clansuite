<h2>Login</h2>

    <form action="index.php?mod=account&action=login" method="post">
    <table>
        <tr>
            <td>{translate}Email:{/translate}</td>
            <td><input type="text" name="email" value="{$smarty.post.email|escape:"htmlall"}"></td>
        </tr>
        <tr>
            <td>{translate}Password:{/translate}</td>
            <td><input type="password" name="password" value=""></td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="submit" name="submit" value="{translate}Login{/translate}">
                <input type="checkbox" name="remember" value="1" {if $smarty.post.rememberme == 1} checked="checked" {/if}>
                {translate}remember me{/translate}
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