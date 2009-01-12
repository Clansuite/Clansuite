<from action="index.php?mod=account&amp;sub=general&amp;action=edit" method="post" accept-charset="UTF-8">
<table cellpadding="0" cellspacing="0" border="0">
        <tr class="tr_row1">
            <td>{t}General{/t}</td>
            <td>
                <table cellpadding="0" cellspacing="0" border="0" width="100%">
                {foreach item=item key=key from=$profile}
                    <tr class="tr_row1">
                        <td width="80px">
                            {if $key == custom_text}
                                {t}Custom Text{/t}
                            {/if}
                            {if $key == mobile}
                                {t}Mobile{/t}
                            {/if}
                            {if $key == phone}
                                {t}Phone{/t}
                            {/if}
                            {if $key == icq}
                                {t}ICQ{/t}
                            {/if}
                            {if $key == msn}
                                {t}MSN{/t}
                            {/if}
                            {if $key == homepage}
                                {t}Homepage{/t}
                            {/if}
                            {if $key == skype}
                                {t}Skype{/t}
                            {/if}
                            {if $key == country}
                                {t}Country{/t}
                            {/if}
                            {if $key == city}
                                {t}City{/t}
                            {/if}
                            {if $key == zipcode}
                                {t}ZIP Code{/t}
                            {/if}
                            {if $key == address}
                                {t}Address{/t}
                            {/if}
                            {if $key == height}
                                {t}Height{/t}
                            {/if}
                            {if $key == gender}
                                {t}Gender{/t}
                            {/if}
                            {if $key == birthday}
                                {t}Birthday{/t}
                            {/if}
                            {if $key == last_name}
                                {t}Last name{/t}
                            {/if}
                            {if $key == first_name}
                                {t}First name{/t}
                            {/if}
                            {if $key == timestamp}
                                {t}Last changed{/t}
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
                <input type="button" value="{t}Abort{/t}" class="ButtonRed" onclick="self.location.href='index.php?mod=controlcenter&amp;sub=users'" />
                <input class="ButtonGreen" type="submit" name="submit" value="{t}Edit the user{/t}" />
                <input class="ButtonGrey" type="reset" value="{t}Reset{/t}" />
            </td>
        </tr>
    </table>

</form>