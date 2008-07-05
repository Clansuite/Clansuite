<h1>{t}Forgot Password{/t}</h1>

    <p>{t}Enter your email below and a new password will be generated for your account and sent to you by email.{/t}</p>

    {if $noSuchAccount == 1}<p class="error">{t}There is no account with such email.{/t}</p>{/if}
    {if $accountNotActivated == 1}<p class="error">{t}Account with this email has not been yet activated.{/t}</p>{/if}
    {if $errorWhileSending == 1}<p class="error">{t}There was an error while sending an email. Please, try again later.{/t}</p>{/if}
    
    <form action="index.php?mod=account&action=forgot_password" method="post">
    <table>
    <tr>
        <td>{t}Email:{/t}</td>
        <td><input type="text" name="email" value="{$smarty.post.email|escape:"html"}"></td>
    </tr>
    <tr>
        <td colspan="2">
            <input type="submit" name="submit" value="{t}Send new password{/t}">
        </td>
    </tr>
    </table>
    </form>