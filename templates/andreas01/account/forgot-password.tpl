<h1>Forgot Password</h1>

    <p>Enter your email below and a new password will be generated for your account and sent to you by email.</p>

    {php} if ($noSuchAccount) { echo '<p class="error">There is no account with such email.</p>'; } {/php}
    {php} if ($accountNotActivated) { echo '<p class="error">Account with this email has not been yet activated.</p>'; } {/php}
    {php} if ($errorWhileSending) { echo '<p class="error">There was an error while sending an email. Please, try again later.</p>'; } {/php}
    
    <form action="index.php?mod=account&action=forgot-password" method="post">
    <table>
    <tr>
        <td>Email:</td>
        <td><input type="text" name="email" value="{php} echo $email; {/php}"></td>
    </tr>
    <tr>
        <td colspan="2">
            <input type="submit" name="submit" value="Send new password">
        </td>
    </tr>
    </table>
    </form>