<h1>Activation Email</h1>
    <p>
        If you have registered and still didn't get an activation email,
        use the form below to send it again.
    </p>

    {php} if ($noSuchAccount) echo '<p class="error">There is no account with such email.</p>'; {/php}
    {php} if ($alreadyActivated) echo '<p class="error">Account with this email has already been activated.</p>'; {/php}
    {php} if ($errorWhileSending) { echo '<p class="error">There was an error while sending an email. Please, try again later.</p>'; } {/php}

    <form action="index.php?mod=account&action=activation-email" method="post">
    <table>
    <tr>
        <td>Email:</td>
        <td><input type="text" name="email" value="{php} echo $email; {/php}"></td>
    </tr>
    <tr>
        <td colspan="2">
            <input type="submit" name="submit" value="Send activation email">
        </td>
    </tr>
    </table>
    </form>