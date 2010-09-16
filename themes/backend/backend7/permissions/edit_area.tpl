<form method="post" accept-charset="UTF-8" action="index.php?mod=controlcenter&amp;sub=permissions&amp;action=edit_area">
    <input type="hidden" name="info[area_id]" class="input_text" value="{$info.area_id}" />
    <table cellpadding="0" cellspacing="0" border="0" align="center" width="280">
        <tr class="tr_header">
            <td width="80">{t}Description{/t} </td>
            <td>{t}Inputs{/t}</td>
        </tr>
        <tr class="tr_row1">
            <td>{t}Name{/t} </td>
            <td><input type="text" name="info[name]" class="input_text" value="{$info.name|escape:"html"}" /></td>
        </tr>
        <tr class="tr_row2">
            <td>{t}Description{/t}</td>
            <td><input type="text" name="info[description]" class="input_text" value="{$info.description|escape:"html"}" /></td>
        </tr>
        <tr class="tr_row1">
            <td colspan="2" align="right">
                <input type="button" value="{t}Abort{/t}" class="ButtonRed" onclick="Dialog.okCallback()" />
                <input type="submit" name="submit" value="{t}Edit the area{/t}" class="ButtonGreen" />
                <input type="reset" name="reset" value="{t}Reset{/t}" class="ButtonGrey" />
            </td>
        </tr>
    </table>
</form>