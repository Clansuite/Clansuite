{* Debuganzeige, wenn DEBUG = 1 |  {$groups|@var_dump}
{if $smarty.const.DEBUG eq "1"} Debugausgabe des Arrays:   {html_alt_table loop=$groups} {/if} *}

<form action="index.php?mod=admin&sub=groups&action=delete" method="POST">

    <table cellpadding="0" cellspacing="0" border="0" width="700" align="center">
        <tr class="tr_header">
            <td align="center">{translate}Members not in the group{/translate}</td>
            <td align="center">{translate}Opt.{/translate}</td>
            <td align="center">{translate}Members in the group{/translate}</td>
        </tr>
        <tr>
            <td class="cell1" align="center">
                <select class="input_textarea" style="width: 200px" name="old" size="15">
                    {foreach key=key item=value from=$info.users_not_in_group}
                        <option name="">{$value.nick}</option>
                    {/foreach}
                </select>
            </td>
            <td class="cell2" align="center" width="40px">
                <input style="margin-top: 75px" type="button" value="&lt;" class="ButtonRed" /><br />
                <input type="button" value="&gt;" class="ButtonGreen" />
            </td>
            <td class="cell1" align="center">
                <select class="input_textarea" style="width: 200px" name="add" size="15">
                    {foreach key=key item=value from=$info.users_in_group}
                        <option name="">{$value.nick}</option>
                    {/foreach}
                </select>
            </td>
        </tr>
    </table>

</form>