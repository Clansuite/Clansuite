{if $err.no_special_chars == 1}
    {error title="Special Chars"}
        No special chars except '_' are allowed.
    {/error}
{/if}

{if $err.fill_form == 1}
    {error title="Fill form"}
        Please fill all fields.
    {/error}
{/if}

{if $err.nick_already == 1}
    {error title="Nick already stored"}
        The nick you have entred is already in the database.
    {/error}
{/if}

{if $err.email_already == 1}
    {error title="eMail already stored"}
        The eMail you have entered is already in the database.
    {/error}
{/if}

{if $err.email_wrong == 1}
   {error title="Mail wrong!"}
        The email you entered is not valid.
    {/error}
{/if}

<form method="post" accept-charset="UTF-8" action="index.php?mod=controlcenter&amp;sub=users&amp;action=create">

    <table cellpadding="0" cellspacing="0" border="0" align="center" width="350">
        <tr class="tr_header">
            <td align="center" width="20%">{t}Description{/t}</td>
            <td align="center" width="80%">{t}Input{/t}</td>
        </tr>
        <tr class="tr_row1">
            <td>{t}Nick{/t}</td>
            <td><input type="text" value="{$smarty.post.info.nick|escape:"html"}" class="input_text" name="info[nick]" /></td>
        </tr>
        <tr class="tr_row1">
            <td>{t}eMail{/t}</td>
            <td><input type="text" value="{$smarty.post.info.email|escape:"html"}" class="input_text" name="info[email]" /></td>
        </tr>
        <tr class="tr_row1">
            <td>{t}Password{/t}</td>
            <td>
                <input type="text" value="{$smarty.post.info.password|escape:"html"}" class="input_text" name="info[password]" />
            </td>
        </tr>
        <tr class="tr_row1">
            <td>{t}Activated{/t}</td>
            <td><input type="checkbox" value="1" class="input_text" name="info[activated]" {if $smarty.post.info.activated==1}checked="checked"{/if} /></td>
        </tr>
        <tr class="tr_row1">
            <td>{t}Disabled/Banned{/t}</td>
            <td><input type="checkbox" value="1" class="input_text" name="info[disabled]" {if $smarty.post.info.disabled==1}checked="checked"{/if} /></td>
        </tr>
        <tr class="tr_row1">
            <td>{t}Groups{/t}</td>
            <td>
                <table cellpadding="0" cellspacing="0" border="0" width="100%">
                {foreach item=item key=key from=$all_groups}
                    <tr class="tr_row1">
                        <td width="1%">
                            <input type="checkbox" value="{$item.group_id}" class="input_text" name="info[groups][]" {if in_array($item.group_id, $smarty.post.info.groups)}checked="checked"{/if}/>
                        </td>
                        <td>
                            <a href="index.php?mod=controlcenter&sub=groups&action=edit&id={$item.group_id}" target="_blank">{$item.name|escape:"html"}</a>
                        </td>
                    </tr>
                {/foreach}
                </table>
            </td>
        </tr>
        <tr class="tr_row1">
            <td colspan="2" align="right">
                <input type="button" value="{t}Abort{/t}" class="ButtonRed" onclick="Dialog.okCallback()" />
                <input class="ButtonGreen" type="submit" name="submit" value="{t}Create the user{/t}" />
                <input class="ButtonGrey" type="reset" value="{t}Reset{/t}" />
            </td>
    </table>
</form>