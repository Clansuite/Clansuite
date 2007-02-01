{doc_raw}
<script src="{$www_core_tpl_root}/javascript/picklist.js" type="text/javascript"></script>
{/doc_raw}

<form action="index.php?mod=admin&sub=groups&action=add_members" method="POST">

    <table cellpadding="0" cellspacing="0" border="0" width="700" align="center">
        <tr class="tr_header">
            <td align="center">{translate}Members not in the group{/translate}</td>
            <td align="center">{translate}Opt.{/translate}</td>
            <td align="center">{translate}Members in the group{/translate}</td>
        </tr>
        <tr>
            <td class="cell1" align="center">
                <select multiple="multiple" name="SelectList" id="SelectList" class="input_textarea" style="width: 200px" size="15">
                    {foreach key=key item=value from=$info.users_not_in_group}
                        <option name="">{$value.nick}</option>
                    {/foreach}
                </select>
            </td>
            <td class="cell2" align="center" width="40px">
                <input style="margin-top: 75px" type="button" value="&lt;" onClick="delIt();" class="ButtonRed" /><br />
                <input type="button" value="&gt;" onClick="addIt();" class="ButtonGreen" />
            </td>
            <td class="cell1" align="center">
                <select multiple="multiple" name="PickList" id="PickList" class="input_textarea" style="width: 200px" name="add" size="15">
                    {foreach key=key item=value from=$info.users_in_group}
                        <option name="">{$value.nick}</option>
                    {/foreach}
                </select>
            </td>
        </tr>
    </table>

</form>
{literal}
<script type="text/javascript">
window.onload = function() {
initIt();
}
</script>
{/literal}