{* Debug of Array
                    {$info|@var_dump}
                    {$info.users_not_in_group|@var_dump}
                    {$info.users_in_group|@var_dump}
                    {$info.group|@var_dump}
*}


{move_to target="pre_head_close"}
<script src="{$www_root_themes_core}/javascript/picklist.js" type="text/javascript"></script>
{/move_to}

<form action="index.php?mod=controlcenter&amp;sub=groups&amp;action=add_members&amp;id={$info.group_id}" method="post" accept-charset="UTF-8" onsubmit="return selIt();">
    <table class="admintable" cellpadding="0" cellspacing="0" border="0" style="width:700px;margin:0 auto;text-align:center">
        <tr class="tr_header">
            <td colspan="3" style="text-align: center;"><img src="{$info.group.icon}" alt="Icon" /><font color="{$info.group.color}">{$info.group.name}</font> - <small>{$info.group.description}</small></td>
        </tr>
        <tr class="tr_header_small">
            <td>{t}Members not in the group{/t}</td>
            <td>{t}Opt.{/t}</td>
            <td>{t}Members in the group{/t}</td>
        </tr>
        <tr>
            <td class="cell1">
                <select multiple="multiple" name="SelectList" id="SelectList" class="input_textarea" style="width: 200px" size="15">
                    {foreach key=key item=value from=$info.users_not_in_group}
                        <option value="{$value.user_id}">{$value.nick}</option>
                    {/foreach}
                </select>
            </td>
            <td class="cell2" width="40px">
                <input style="margin-top: 75px" type="button" value="&lt;" onclick="delIt();" class="ButtonRed" /><br />
                <input type="button" value="&gt;" onclick="addIt();" class="ButtonGreen" />
            </td>
            <td class="cell1">
                <select multiple="multiple" name="PickList[]" id="PickList[]" class="input_textarea" style="width: 200px" size="15">
                    {foreach key=key item=value from=$info.users_in_group}
                        <option value="{$value.user_id}">{$value.nick}</option>
                    {/foreach}
                </select>
            </td>
        </tr>
        <tr>
            <td class="cell2" align="right" colspan="3">
                <input type="button" value="{t}Abort{/t}" class="ButtonRed" onclick="self.location.href='index.php?mod=controlcenter&amp;sub=groups'" />
                <input type="button" value="{t}Reset{/t}" class="ButtonGrey" onclick="self.location.href='index.php?mod=controlcenter&amp;sub=groups&amp;action=add_members&amp;id={$info.group_id}'" />
                <input type="submit" name="submit" value="{t}Set Members{/t}" class="ButtonGreen" />
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