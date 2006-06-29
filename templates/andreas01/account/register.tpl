<h2>Register</h2>

    {php}
    global $err;
    #var_dump($err);
    if ( $err['email_wrong'] 	== 1 ) { echo '<p class="error">The email address is wrong!</p>';}
    if ( $err['nick_wrong'] 	== 1 ) { echo '<p class="error">The nick is wrong!</p>';}
    if ( $err['email_exists'] 	== 1 ) { echo '<p class="error">There is already an account with such email. <br /> Choose another!</p>';}
    if ( $err['nick_exists'] 	== 1 ) { echo '<p class="error">There is already an account with such nick. <br /> Choose another!</p>';}
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