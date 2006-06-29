<h2>Login</h2>

    <form action="index.php?mod=account&action=login" method="post">
    <table>
        <tr>
            <td>Email:</td>
            <td><input type="text" name="email" value="{php} echo $_POST['email']; {/php}"></td>
        </tr>
        <tr>
            <td>Password:</td>
            <td><input type="password" name="password" value=""></td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="submit" name="submit" value="Login">
                <input type="checkbox" name="remember" value="1" {php} echo $_POST['rememberme'] ? 'checked="checked"' : ''; {/php}>
                remember me
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <a href="index.php?mod=account&action=register">Not yet registered ?</a> <br>
                <a href="index.php?mod=account&action=forgot-password">Forgot password ?</a> <br>
                <a href="index.php?mod=account&action=activation-email">Did not get an activation email ?</a> <br>
            </td>
        </tr>
    </table>
    </form>