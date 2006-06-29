<h2>Register</h2>

    {php}
        if (isset($err['email_exists'])) { echo '<p class="error">There is already an account with such email.</p>'; }
        if (isset($err['nick_exists'])) { echo '<p class="error">There is already an account with such nick.</p>'; }
    {/php}


    <form action="index.php?mod=account&action=register" method="post">
    <table>
        <tr>
            <td>Email:</td>
            <td><input type="text" name="email" value="{php} echo $_POST['email']; {/php}"></td>
        </tr>
        <tr>
            <td>Confirm email:</td>
            <td><input type="text" name="email2" value="{php} echo $_POST['email2']; {/php}"></td>
        </tr>
        <tr>
            <td>Nick:</td>
            <td><input type="text" name="nick" value="{php} echo $_POST['nick']; {/php}"></td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="submit" name="submit" value="Register">
            </td>
        </tr>
    </table>
    </form>