{if $errors.fill_form == 1}  {error title="Fill form"}Please fill all necessary fields.{/error}                                     {/if}

{* DEBUG 
{if $smarty.const.DEBUG eq "1"} Debugausgabe der Var: {/if} 
*} {$info|@var_dump}

<form target="_self" method="post" action="index.php?mod=guestbook&sub=admin&action=add_admincomment">

    <input type="hidden" name="info[gb_id]" class="input_text" value="{$info.gb_id}" />

    <table cellpadding="0" cellspacing="0" border="0" align="center" width="400">
    <tr class="tr_header">
        <td width="80">
            {translate}Description{/translate}
        </td>
        <td>
            {translate}Inputs{/translate}
        </td>
    </tr>

    <tr class="tr_row1">
        <td>
            {translate}Admincomment{/translate}
        </td>
        <td>
            <input type="text" name="info[gb_admincomment]" class="input_text" value="{$info.gbadmincomment|escape:"html"}" />
        </td>
    </tr>   

    <tr class="tr_row2">
        <td colspan="2" align="right">
            <input type="button" value="{translate}Abort{/translate}" class="ButtonRed" onclick="self.location.href='index.php?mod=guestbook&amp;sub=admin&amp;action=show'" />
            <input type="submit" name="submit" value="{translate}Add Comment{/translate}" class="ButtonGreen" />
            <input type="reset" name="reset" value="{translate}Reset{/translate}" class="ButtonGrey" />
        </td>
    </tr>
    </table>

</form>