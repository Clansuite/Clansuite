<table cellspacing="0" cellpadding="0" border="0" width="100%" align="center">

    {* /---------------------------------------------------
       |
       |     Tab: Email >> Mail Server Configuration
       |
       \--------------------------------------------------- *}

    <tr>
        <td class="td_header_small"  colspan="2">
            {t}Mail Server Configuration{/t}
        </td>
    </tr>
    <tr>
        <td class="cell2" width="15%">
            {t}Mail method{/t}
        </td>
        <td class="cell1" style="padding: 3px">
            <select name="config[email][mailmethod]" class="input_text">
                <option value="mail"     {if isset($config.email.mailmethod) && $config.email.mailmethod == 'mail'}selected="selected"{/if}>{t}Normal{/t}</option>
                <option value="smtp"     {if isset($config.email.mailmethod) && $config.email.mailmethod == 'smtp'}selected="selected"{/if}>{t}SMTP{/t}</option>
                <option value="sendmail" {if isset($config.email.mailmethod) && $config.email.mailmethod == 'sendmail'}selected="selected"{/if}>{t}Sendmail{/t}</option>
                <option value="exim"     {if isset($config.email.mailmethod) && $config.email.mailmethod == 'exim'}selected="selected"{/if}>{t}Exim{/t}</option>
                <option value="qmail"    {if isset($config.email.mailmethod) && $config.email.mailmethod == 'qmail'}selected="selected"{/if}>{t}Qmail{/t}</option>
                <option value="postfix"  {if isset($config.email.mailmethod) && $config.email.mailmethod == 'postfix'}selected="selected"{/if}>{t}PostFix{/t}</option>
            </select>
        </td>
    </tr>
    <tr>
        <td class="cell2" width="15%">
            {t}Mailerhost{/t}
        </td>
        <td class="cell1" style="padding: 3px">
            <input class="input_text" type="text" value="{if isset($config.email.mailerhost)}{$config.email.mailerhost}{/if}" name="config[email][mailerhost]" />
        </td>
    </tr>
    <tr>
        <td class="cell2" width="15%">
            {t}Mailerport{/t}
        </td>
        <td class="cell1" style="padding: 3px">
            <input class="input_text" type="text" value="{if isset($config.email.mailerport)}{$config.email.mailerport}{/if}" name="config[email][mailerport]" />
        </td>
    </tr>
    <tr>
        <td class="cell2" width="15%">
            {t}Mail encryption{/t}
        </td>
        <td class="cell1" style="padding: 3px">
            <select name="config[email][mailencryption]" class="input_text">
                <option value="SWIFT_OPEN" {if isset($config.email.mailencryption) && $config.email.mailencryption == 'SWIFT_OPEN'}selected="selected"{/if}>{t}No encryption{/t}</option>
                <option value="SWIFT_SSL"  {if isset($config.email.mailencryption) && $config.email.mailencryption == 'SWIFT_SSL'}selected="selected"{/if}>{t}SSL encryption{/t}</option>
                <option value="SWIFT_TLS"  {if isset($config.email.mailencryption) && $config.email.mailencryption == 'SWIFT_TLS'}selected="selected"{/if}>{t}TLS/SSL encryption{/t}</option>
            </select>
        </td>
    </tr>

    {* /---------------------------------------------------
       |
       |     Tab: Email >> SMTP Authentication
       |
       \--------------------------------------------------- *}


    <tr>
        <td class="td_header_small"  colspan="2">
            {t}SMTP authentication{/t}
        </td>
    </tr>
    <tr>
        <td class="cell2" width="15%">
            {t}SMTP username{/t}
        </td>
        <td class="cell1" style="padding: 3px">
            <input class="input_text" type="text" value="{if isset($config.email.smtp_username)}{$config.email.smtp_username}{/if}" name="config[email][smtp_username]" />
        </td>
    </tr>
    <tr>
        <td class="cell2" width="15%">
            {t}SMTP password{/t}
        </td>
        <td class="cell1" style="padding: 3px">
            <input class="input_text" type="text" value="{if isset($config.email.smtp_password)}{$config.email.smtp_password}{/if}" name="config[email][smtp_password]" />
        </td>
    </tr>

    {* /---------------------------------------------------
       |
       |     Tab: Email >> Email sender address
       |
       \--------------------------------------------------- *}

    <tr>
        <td class="td_header_small"  colspan="2">
            {t}Email sender address{/t}
        </td>
    </tr>
    <tr>
        <td class="cell2" width="15%">
            {t}From (eMail){/t}
        </td>
        <td class="cell1" style="padding: 3px">
            <small>{t}Bitte geben Sie die Emailadresse des Systems bzw. des Systemadministrators ein;{/t}<br /></small>
            <input class="input_text" type="text" value="{if isset($config.email.from)}{$config.email.from}{/if}" name="config[email][from]" />
        </td>
    </tr>
    <tr>
        <td class="cell2" width="15%">
            {t}From (name){/t}
        </td>
        <td class="cell1" style="padding: 3px">
            <small>{t}Bitte geben Sie den vollen Namen des Absenders des Systems bzw. des Systemadministrators ein;{/t}<br /></small>
            <input class="input_text" type="text" value="{if isset($config.email.from_name)}{$config.email.from_name}{/if}" name="config[email][from_name]" />
        </td>
    </tr>


    {* /---------------------------------------------------
       |
       |     Tab: Email >> Send Test Mail
       |
       \--------------------------------------------------- *}

    <tr>
        <td class="td_header_small"  colspan="2">
            {t}Send Test Mail{/t}
        </td>
    </tr>
    <tr>
        <td class="cell2" width="15%">
            {t}Recipient (email){/t}
        </td>
        <td class="cell1" style="padding: 3px">
            <input class="input_text" type="text" value="{$config.email.from}" name="config[email][from]" />
            <input type="button" class="ButtonOrange" value="Send Mail" />
        </td>
    </tr>
</table>