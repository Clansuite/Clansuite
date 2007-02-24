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

<form target="_self" method="post" action="index.php?mod=admin&amp;sub=users&amp;action=edit">

    <input type="hidden" name="info[user_id]" value="{$user.user_id}" />
    <table cellpadding="0" cellspacing="0" border="0" align="center" width="500">
        <tr class="tr_header">
            <td align="center" width="20%">{translate}Description{/translate}</td>
            <td align="center" width="80%">{translate}Input{/translate}</td>
        </tr>
        <tr class="tr_row1">
            <td>{translate}Nick{/translate}</td>
            <td><input type="text" value="{$user.nick|escape:"html"}" class="input_text" name="info[nick]" /></td>
        </tr>
        <tr class="tr_row1">
            <td>{translate}eMail{/translate}</td>
            <td><input type="text" value="{$user.email|escape:"html"}" class="input_text" name="info[email]" /></td>
        </tr>
        <tr class="tr_row1">
            <td>{translate}Password{/translate}</td>
            <td>
                <input type="text" value="" class="input_text" name="info[password]" /><br />
                {translate}Leave it blank if you do not want to change the password!{/translate}
            </td>
        </tr>
        <tr class="tr_row1">
            <td>{translate}Activated{/translate}</td>
            <td><input type="checkbox" value="1" class="input_text" name="info[activated]" {if $user.activated==1}checked="checked"{/if} /></td>
        </tr>
        <tr class="tr_row1">
            <td>{translate}Disabled/Banned{/translate}</td>
            <td><input type="checkbox" value="1" class="input_text" name="info[disabled]" {if $user.disabled==1}checked="checked"{/if} /></td>
        </tr>
        <tr class="tr_row1">
            <td>{translate}Groups{/translate}</td>
            <td>
                <table cellpadding="0" cellspacing="0" border="0" width="100%">
                {foreach item=item key=key from=$all_groups}
                    <tr class="tr_row1">
                        <td width="1%">
                            <input type="checkbox" value="{$item.group_id}" class="input_text" name="info[groups][]" {if in_array($item.group_id, $groups)}checked="checked"{/if}/>
                        </td>
                        <td>
                            <a href="index.php?mod=admin&amp;sub=groups&amp;action=edit&amp;id={$item.group_id}" target="_blank">{$item.name|escape:"html"}</a>
                        </td>
                    </tr>
                {/foreach}
                </table>
            </td>
        </tr>

        <tr class="tr_row1">
            <td>{translate}Profile{/translate}</td>
            <td>
                <table cellpadding="0" cellspacing="0" border="0" width="100%">
                {foreach item=item key=key from=$profile}
                    <tr class="tr_row1">
                        <td width="80px">
                            {if $key == custom_text}
                                {translate}Custom Text{/translate}
                            {/if}
                            {if $key == mobile}
                                {translate}Mobile{/translate}
                            {/if}
                            {if $key == phone}
                                {translate}Phone{/translate}
                            {/if}
                            {if $key == icq}
                                {translate}ICQ{/translate}
                            {/if}
                            {if $key == msn}
                                {translate}MSN{/translate}
                            {/if}
                            {if $key == homepage}
                                {translate}Homepage{/translate}
                            {/if}
                            {if $key == skype}
                                {translate}Skype{/translate}
                            {/if}
                            {if $key == country}
                                {translate}Country{/translate}
                            {/if}
                            {if $key == city}
                                {translate}City{/translate}
                            {/if}
                            {if $key == zipcode}
                                {translate}ZIP Code{/translate}
                            {/if}
                            {if $key == address}
                                {translate}Address{/translate}
                            {/if}
                            {if $key == height}
                                {translate}Height{/translate}
                            {/if}
                            {if $key == gender}
                                {translate}Gender{/translate}
                            {/if}
                            {if $key == birthday}
                                {translate}Birthday{/translate}
                            {/if}
                            {if $key == last_name}
                                {translate}Last name{/translate}
                            {/if}
                            {if $key == first_name}
                                {translate}First name{/translate}
                            {/if}
                            {if $key == timestamp}
                                {translate}Last changed{/translate}
                            {/if}
                        </td>
                        <td>
                            {if $key == custom_text}
                                <textarea name="profile[:{$key}]" rows="10" cols="45" class="input_textarea">{$item}</textarea>
                            {elseif $key == birthday}
                                {$item|date_format:"<input type='text' size='2' name='profile[birthday][day]' class='input_text' value='%d' /><input type='text' size='2' name='profile[birthday][month]' class='input_text' value='%m' /><input type='text' size='4' name='profile[birthday][year]' class='input_text' value='%Y' />"}
                            {elseif $key == timestamp}
                                {$item|date_format:'<input type="text" size="2" name="profile[timestamp][day]" class="input_text" value="%d" /><input type="text" size="2" name="profile[timestamp][month]" class="input_text" value="%m" /><input type="text" size="4" name="profile[timestamp][year]" class="input_text" value="%Y" />'}
                            {else}
                                <input name="profile[:{$key}]" type="text" value="{$item}" class="input_text" />
                            {/if}
                        </td>
                    </tr>
                {/foreach}
                </table>
            </td>
        </tr>
        <tr class="tr_row1">
            <td colspan="2" align="right">
                <input type="button" value="{translate}Abort{/translate}" class="ButtonRed" onclick="self.location.href='index.php?mod=admin&amp;sub=users'" />
                <input class="ButtonGreen" type="submit" name="submit" value="{translate}Edit the user{/translate}" />
                <input class="ButtonGrey" type="reset" value="{translate}Reset{/translate}" />
            </td>
        </tr>
    </table>

</form>