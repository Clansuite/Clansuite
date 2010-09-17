<h1>{t}Activation Email{/t}</h1>


    {if isset($err.no_such_mail) and $err.no_such_mail == 1}<p class="error">{t}There is no account with such email.{/t}</p>{/if}
    {if isset($err.form_not_filled) and $err.form_not_filled == 1}<p class="error">{t}Please fill out the form below.{/t}</p>{/if}
    {if isset($err.already_activated) and $err.already_activated == 1}<p class="error">{t}There was an error while sending an email. Please, try again later.{/t}</p>{/if}
    {if isset($err.email_wrong) and $err.email_wrong == 1}<p class="error">{t}The email address is wrong!{/t}</p>{/if}
    <p>
        {t}If you have registered and still haven't got an activation email, use the form below to send it again.{/t}
    </p>
    <form action="index.php?mod=account&action=activation_email" method="post">
    <table>
    <tr>
        <td>{t}Email:{/t}</td>
        <td><input class="input_text" type="text" name="email" value="{if isset($smarty.post.email)}{$smarty.post.email|escape:"html"}{/if}"></td>
    </tr>
    <tr>
        <td colspan="2">
            <input class="ButtonGrey" type="submit" name="submit" value="{t}Send activation email{/t}">
        </td>
    </tr>
    </table>
    </form>