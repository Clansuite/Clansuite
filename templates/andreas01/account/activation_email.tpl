<h1>{translate}Activation Email{/translate}</h1>
    <p>
        {translate}If you have registered and still didn't get an activation email,
        use the form below to send it again.{/translate}
    </p>

    {if $noSuchAccount == 1}<p class="error">{translate}There is no account with such email.{/translate}</p>{/if}
    {if $alreadyActivated == 1}<p class="error">{translate}Account with this email has already been activated.{/translate}</p>{/if}
    {if $errorWhileSending == 1}<p class="error">{translate}There was an error while sending an email. Please, try again later.{/translate}</p>{/if}

    <form action="index.php?mod=account&action=activation-email" method="post">
    <table>
    <tr>
        <td>{translate}Email:{/translate}</td>
        <td><input type="text" name="email" value="{$smarty.post.email|escape:"htmlall"}"></td>
    </tr>
    <tr>
        <td colspan="2">
            <input type="submit" name="submit" value="{translate}Send activation email{/translate}">
        </td>
    </tr>
    </table>
    </form>