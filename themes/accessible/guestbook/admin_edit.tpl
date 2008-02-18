<div class="admin_guestbook">

{if $errors.fill_form == 1}
    {error title="Fill form"}Please fill all necessary fields.{/error}
{/if}

<form target="_self" method="post" accept-charset="UTF-8" action="index.php?mod=guestbook&amp;sub=admin&amp;action=edit">

    <input type="hidden" name="infos[gb_id]" value="{$infos.gb_id}" />
    <input type="hidden" name="infos[front]" value="{$front}" />

    <table cellpadding="5" cellspacing="0" border="0" align="center" style="margin: auto" width="450" height="400" >
    <tr class="tr_header">
        <td width="100">
            {t}Description{/t}
        </td>
        <td>
            {t}Inputs{/t}
        </td>
    </tr>

    <tr class="tr_row1">
        <td style="padding: 3px">
            <b>{t}Nick:{/t}</b>
        </td>
        <td align="left">
            <input type="text" name="infos[gb_nick]" class="input_text" value="{$infos.gb_nick|escape:"html"}" />
        </td>
    </tr>
    <tr class="tr_row1">
        <td style="padding: 3px">
            <b>{t}Date added:{/t}</b>
        </td>
        <td align="left" style="padding: 3px">
            {t}{$infos.gb_added|date_format:"%A"}{/t}, {t}{$infos.gb_added|date_format:"%B"}{/t}{$infos.gb_added|date_format:" %e, %Y"}
        </td>
    </tr>
    <tr class="tr_row1">
        <td style="padding: 3px">
            <b>{t}eMail:{/t}</b>
        </td>
        <td align="left">
            <input type="text" name="infos[gb_email]" class="input_text" value="{$infos.gb_email|escape:"html"}" />
        </td>
    </tr>
    <tr class="tr_row1">
        <td style="padding: 3px">
            <b>{t}ICQ:{/t}</b>
        </td>
        <td align="left">
            <input type="text" name="infos[gb_icq]" class="input_text" value="{$infos.gb_icq|escape:"html"}" />
        </td>
    </tr>
    <tr class="tr_row1">
        <td style="padding: 3px">
            <b>{t}Website:{/t}</b>
        </td>
        <td align="left">
            <input type="text" name="infos[gb_website]" class="input_text" value="{$infos.gb_website|escape:"html"}" />
        </td>
    </tr>
    <tr class="tr_row1">
        <td style="padding: 3px">
            <b>{t}Town:{/t}</b>
        </td>
        <td align="left">
            <input type="text" name="infos[gb_town]" class="input_text" value="{$infos.gb_town|escape:"html"}" />
        </td>
    </tr>
    <tr class="tr_row1">
        <td style="padding: 3px">
            <b>{t}IP:{/t}</b>
        </td>
        <td align="left">
            <input type="text" name="infos[gb_ip]" class="input_text" value="{$infos.gb_ip|escape:"html"}" />
        </td>
    </tr>
    <tr class="tr_row1">
        <td style="padding: 3px">
            <b>{t}Text:{/t}</b>
        </td>
        <td align="left">
            <textarea class="input_textarea" name="infos[gb_text]" cols="55" rows="5">{$infos.gb_text|escape:"html"}</textarea>
        </td>
    </tr>
    <tr class="tr_row1">
        <td style="padding: 3px">
            <b>{t}Comment:{/t}</b>
        </td>
        <td align="left">
            <textarea class="input_textarea" name="infos[gb_comment]" cols="55" rows="5">{$infos.gb_comment|escape:"html"}</textarea>
        </td>
    </tr>
    <tr class="tr_row2">
        <td colspan="2" align="right">
            <input class="ButtonRed" type="button" onclick="Dialog.okCallback()" value="{t}Abort{/t}"/>
            <input type="submit" name="submit" value="{t}Edit GB Entry{/t}" class="ButtonGreen" />
            <input type="reset" name="reset" value="{t}Reset{/t}" class="ButtonGrey" />
        </td>
    </tr>
    </table>

</form>
</div>