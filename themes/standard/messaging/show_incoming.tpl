<form action="index.php?mod=messaging" method="post">
<table cellpadding="0" cellspacing="0" border="0" width="100%">
    <tr class="tr_header">
            <td width="140px">
                {t}Options{/t}
            </td>
            <td>
                {t}Results{/t}
            </td>
    </tr>
    <tr>
        <td class="cell1">
            {$menu}
        </td>
        <td class="cell1">
            {if empty($messages)}
                <div align="center">{t}There are no messages{/t}</div>
            {else}
                <table cellpadding="5" cellspacing="0" border="0" width="100%">
                {foreach key=key item=item from=$messages}
                    <tr>
                        <td class="cell2">
                            <div class="{if $item.read==0}message_new{else}message_old{/if}">

                                <div class="message_headline">
                                    {if $item.read==0}<b>{t}New{/t}:&nbsp;</b>{/if}<a href="index.php?mod=messaging&action=read&id={$item.message_id}">{$item.headline}</a>
                                </div>
                                <div class="message_buttons">
                                    <input type="button" class="ButtonGreen" value="{t}Reply{/t}" onclick="self.location.href='index.php?mod=messaging&action=create&reply_id={$item.message_id}'" />
                                    <input type="button" class="ButtonYellow" value="{if $item.read==0}{t}Mark as read{/t}{else}{t}Mark as unread{/t}{/if}" onclick="self.location.href='index.php?mod=messaging&action=mark&id={$item.message_id}&read={if $item.read==0}1{else}0{/if}'" />
                                    <input type="button" class="ButtonRed" value="{t}Delete{/t}" onclick="self.location.href='index.php?mod=messaging&action=delete&id={$item.message_id}'" />
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
                                    <option value="delete">{t}Delete selected messages{/t}</option>
                                    <option value="multiple_mark_read">{t}Mark messages as read{/t}</option>
                                    <option value="multiple_mark_unread">{t}Mark messages as unread{/t}</option>
                                </select>
                                <input type="submit" name="submit" value="{t}Go!{/t}" class="ButtonGreen" />
                            </div>
                        </td>
                    </tr>
                </table>
            {/if}
        </td>
    </tr>
</table>
</form>