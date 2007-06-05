<form method="post" accept-charset="UTF-8" action="index.php?mod=admin&amp;sub=permissions&amp;action=create_right">
    <table cellpadding="0" cellspacing="0" border="0" align="center" width="280">
        <tr class="tr_header">
            <td width="70">{translate}Description{/translate}</td>
            <td>{translate}Inputs{/translate}</td>
        </tr>
        <tr class="tr_row1">
            <td>{translate}Name{/translate}</td>
            <td><input type="text" name="info[name]" class="input_text" value="{$smarty.post.info.name|escape:"html"}" /></td>
        </tr>
        <tr class="tr_row2">
            <td>{translate}Description{/translate}</td>
            <td><input type="text" name="info[description]" class="input_text" value="{$smarty.post.info.description|escape:"html"}" /></td>
        </tr>
        <tr class="tr_row1">
            <td>{translate}Area{/translate}</td>
            <td>
                <select name="info[area_id]" class="input_text">
                    <option value="0">{translate}- not assigned -{/translate}</option>
                    {foreach key=key item=area_array from=$areas}
                        <option value="{$area_array.area_id}" {if $area_id==$area_array.area_id}selected="selected"{/if}>{$area_array.name|escape:"html"}</option>
                    {/foreach}
                </select>
            </td>
        </tr>
        <tr class="tr_row1">
            <td colspan="2" align="right">
                <input type="button" class="ButtonRed" onclick="Dialog.okCallback()" value="{translate}Abort{/translate}" />
                <input type="submit" name="submit" value="{translate}Create the right{/translate}" class="ButtonGreen" />
                <input type="reset" name="reset" value="{translate}Reset{/translate}" class="ButtonGrey" />
            </td>
        </tr>
    </table>
</form>