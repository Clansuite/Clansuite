{if $err.no_special_chars == 1} {error title="Special Chars"}       No special chars except '_' and whitespaces are allowed.{/error}    {/if}
{if $err.fill_form == 1}        {error title="Fill form"}           Please fill all necessary fields.{/error}                                     {/if}
{if $err.name_already == 1}     {error title="Name already exists"} The name you have entered already exists in the database.{/error}   {/if}

{* DEBUG
{if $smarty.const.DEBUG eq "1"} Debugausgabe der Var:  {$permission|@var_dump}   {/if}
*}


<form target="_self" method="post" action="index.php?mod=admin&sub=permissions&action=edit_area">

    <input type="hidden" name="info[area_id]" class="input_text" value="{$info.area_id}" />

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
            {translate}Name{/translate}
        </td>
        <td>
            <input type="text" name="info[name]" class="input_text" value="{$info.name|escape:"html"}" />
        </td>
    </tr>

    <tr class="tr_row2">
        <td>
            {translate}Description{/translate}
        </td>
        <td>
            <input type="text" name="info[description]" class="input_text" value="{$info.description|escape:"html"}" />
        </td>
    </tr>

    <tr class="tr_row1">
        <td colspan="2" align="right">
            <input type="Button" value="{translate}Abort{/translate}" class="ButtonRed" onclick="self.location.href='index.php?mod=admin&sub=permissions'" />
            <input type="submit" name="submit" value="{translate}Edit the area{/translate}" class="ButtonGreen" />
            <input type="reset" name="reset" value="{translate}Reset{/translate}" class="ButtonGrey" />
        </td>
    </tr>
    </table>

</form>