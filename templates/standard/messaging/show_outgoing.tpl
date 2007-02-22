<form action="index.php?mod=messaging" method="post">
<table cellpadding="0" cellspacing="0" border="0" width="100%">
    <tr class="tr_header">
            <td width="140px">
                {translate}Options{/translate}
            </td>
            <td>
                {translate}Results{/translate}
            </td>
    </tr>
    <tr>
        <td class="cell1">
            {$menu}
        </td>
        <td class="cell1">
            {if empty($messages)}
                <div align="center">{translate}There are no messages{/translate}</div>
            {else}
                <table cellpadding="5" cellspacing="0" border="0" width="100%">
                {foreach key=key item=item from=$messages}
                    <tr>
                        <td class="cell2">
                            <div class="{if $item.read==0}message_new{else}message_old{/if}">

                                <div class="message_headline">
                                    {if $item.read==0}<b>{translate}New{/translate}:&nbsp;</b>{/if}<a href="index.php?mod=messaging&action=read&id={$item.message_id}">{$item.headline}</a>
                                </div>
                                <div class="message_buttons">
                                    {if $item.read==0}<input type="Button" class="ButtonRed" value="{translate}Get meesage back{/translate}" onclick="self.location.href='index.php?mod=messaging&action=get_back&id={$item.message_id}'" />{/if}
                                </div>
                                <div class="message_date">{$item.timestamp|date_format:"%A, %B %e, %Y - %H:%M:%S"}</div>
                                <hr class="message_divider"></hr>
                                <div class="message_message">
                                    {$item.message|wordwrap:100}
                                </div>

                            </div>
                        </td>
                        <td width="30px" align="center" class="cell1">
                            <input type="checkbox" value="{$item.message_id}" name="infos[message_id][]" />
                        </td>
                    </tr>

                {/foreach}
                    <tr>
                        <td class="cell2" colspan="2">
                            <div class="message_selections">
                                <select name="action" class="input_text">
                                    <option value="get_back">{translate}Get messages back (if possible){/translate}</option>
                                </select>
                                <input type="submit" name="submit" value="{translate}Go!{/translate}" class="ButtonGreen" />
                            </div>
                        </td>
                    </tr>
                </table>
            {/if}
        </td>
    </tr>
</table>
</form>