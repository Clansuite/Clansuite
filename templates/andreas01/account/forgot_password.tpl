<h1>{translate}Forgot Password{/translate}</h1>

    <p>{translate}Enter your email below and a new password will be generated for your account and sent to you by email.{/translate}</p>

    {if $noSuchAccount == 1}<p class="error">{translate}There is no account with such email.{/translate}</p>{/if}
    {if $accountNotActivated == 1}<p class="error">{translate}Account with this email has not been yet activated.{/translate}</p>{/if}
    {if $errorWhileSending == 1}<p class="error">{translate}There was an error while sending an email. Please, try again later.{/translate}</p>{/if}
    
    <form action="index.php?mod=account&action=forgot_password" method="post">
    <table>
    <tr>
        <td>{translate}Email:{/translate}</td>
        <td><input type="text" name="email" value="{$smarty.post.email|escape:"htmlall"}"></td>
    </tr>
    <tr>
        <td colspan="2">
            <input type="submit" name="submit" value="{translate}Send new password{/translate}">
        </td>
    </tr>
    </table>
    </form>