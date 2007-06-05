{if $errors.fill_form == 1}
    {error title="Fill form"}Please fill all necessary fields.{/error}
{/if}

<form target="_self" method="post" accept-charset="UTF-8" action="index.php?mod=guestbook&amp;action=create">

    <input type="hidden" name="infos[gb_id]" value="{$infos.gb_id}" />
    <input type="hidden" name="infos[front]" value="{$front}" />

    <table cellpadding="5" cellspacing="0" border="0" align="center" style="margin: auto" width="450" height="400" >
    <tr class="tr_header">
        <td width="100">
            {translate}Description{/translate}
        </td>
        <td>
            {translate}Inputs{/translate}
        </td>
    </tr>

    <tr class="tr_row1">
        <td style="padding: 3px">
            <b>{translate}Nick:{/translate}</b>
        </td>
        <td align="left">
            <input type="text" name="infos[gb_nick]" class="input_text" value="{$infos.gb_nick|escape:"html"}" />
        </td>
    </tr>
    <tr class="tr_row1">
        <td style="padding: 3px">
            <b>{translate}eMail:{/translate}</b>
        </td>
        <td align="left">
            <input type="text" name="infos[gb_email]" class="input_text" value="{$infos.gb_email|escape:"html"}" />
        </td>
    </tr>
    <tr class="tr_row1">
        <td style="padding: 3px">
            <b>{translate}ICQ:{/translate}</b>
        </td>
        <td align="left">
            <input type="text" name="infos[gb_icq]" class="input_text" value="{$infos.gb_icq|escape:"html"}" />
        </td>
    </tr>
    <tr class="tr_row1">
        <td style="padding: 3px">
            <b>{translate}Website:{/translate}</b>
        </td>
        <td align="left">
            <input type="text" name="infos[gb_website]" class="input_text" value="{$infos.gb_website|escape:"html"}" />
        </td>
    </tr>
    <tr class="tr_row1">
        <td style="padding: 3px">
            <b>{translate}Town:{/translate}</b>
        </td>
        <td align="left">
            <input type="text" name="infos[gb_town]" class="input_text" value="{$infos.gb_town|escape:"html"}" />
        </td>
    </tr>
    <tr class="tr_row1">
        <td style="padding: 3px">
            <b>{translate}Text:{/translate}</b>
        </td>
        <td align="left">
            <textarea class="input_textarea" name="infos[gb_text]" cols="55" rows="5">{$infos.gb_text|escape:"html"}</textarea>
        </td>
    </tr>
    <tr class="tr_row2">
        <td colspan="2" align="right">
            <input class="ButtonRed" type="button" onclick="Dialog.okCallback()" value="{translate}Abort{/translate}"/>
            <input type="submit" name="submit" value="{translate}Add GB Entry{/translate}" class="ButtonGreen" />
            <input type="reset" name="reset" value="{translate}Reset{/translate}" class="ButtonGrey" />
        </td>
    </tr>
    </table>

</form>