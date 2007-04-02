<from action="index.php?mod=account&amp;sub=general&amp;action=edit" method="post">
<table cellpadding="0" cellspacing="0" border="0">
        <tr class="tr_row1">
            <td>{translate}General{/translate}</td>
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