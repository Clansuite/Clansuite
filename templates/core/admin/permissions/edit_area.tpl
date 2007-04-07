<form method="post" action="index.php?mod=admin&amp;sub=permissions&amp;action=edit_area">
    <input type="hidden" name="info[area_id]" class="input_text" value="{$info.area_id}" />
    <table cellpadding="0" cellspacing="0" border="0" align="center" width="280">
        <tr class="tr_header">
            <td width="80">{translate}Description{/translate} </td>
            <td>{translate}Inputs{/translate}</td>
        </tr>
        <tr class="tr_row1">
            <td>{translate}Name{/translate} </td>
            <td><input type="text" name="info[name]" class="input_text" value="{$info.name|escape:"html"}" /></td>
        </tr>
        <tr class="tr_row2">
            <td>{translate}Description{/translate}</td>
            <td><input type="text" name="info[description]" class="input_text" value="{$info.description|escape:"html"}" /></td>
        </tr>
        <tr class="tr_row1">
            <td colspan="2" align="right">
                <input type="button" value="{translate}Abort{/translate}" class="ButtonRed" onclick="Dialog.okCallback()" />
                <input type="submit" name="submit" value="{translate}Edit the area{/translate}" class="ButtonGreen" />
                <input type="reset" name="reset" value="{translate}Reset{/translate}" class="ButtonGrey" />
            </td>
        </tr>
    </table>
</form>