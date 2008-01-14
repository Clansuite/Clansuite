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
        <td class="cell1" align="center">
            {if empty($message)}
                <div align="center">{t}There are no messages{/t}</div>
            {else}
                <table cellpadding="5" cellspacing="0" border="0" width="100%">
                    <tr>
                        <td class="cell1">
                            <table cellpadding="5" cellspacing="0" border="0" width="100%">
                                <tr>
                                    <td class="{if $message.read==0}message_new{else}message_old{/if}">

                                        <div class="message_headline">
                                            {if $message.read==0}<b>{t}New{/t}:&nbsp;</b>{/if}<a href="index.php?mod=messaging&action=read&id={$message.message_id}">{$message.headline}</a>
                                        </div>
                                        <div class="message_buttons">
                                            <input type="button" class="ButtonRed" value="{t}Delete{/t}" onclick="self.location.href='index.php?mod=messaging&action=delete&id={$message.message_id}'" />
                                        </div>
                                        <div class="message_date">{$message.timestamp|date_format:"%A, %B %e, %Y - %H:%M:%S"}</div>
                                        <hr class="message_divider"></hr>
                                        <div class="message_message">
                                            {$message.message}
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                <br /><br />
                <form action="index.php?mod=messaging&action=create" method="post">
                    <table cellpadding="5" cellspacing="0" border="0" width="400px">
                        <tr>
                            <td class="td_header" colspan="2">
                                {t}Reply{/t}
                            </td>
                        </tr>
                        <tr>
                            <td class="cell2">
                                {t}To{/t}:
                            </td>
                            <td class="cell1">
                                <input type="text" value="{$message.from_user}" class="input_text" name="info[to]" />
                            </td>
                        </tr>
                        <tr>
                            <td class="cell2">
                                {t}Headline{/t}:
                            </td>
                            <td class="cell1">
                                <input type="text" value="Re: {$message.headline}" class="input_text" name="info[headline]" />
                            </td>
                        </tr>
                        <tr>
                            <td class="cell2">
                                {t}Message{/t}:
                            </td>
                            <td class="cell1">
                                <textarea cols="50" rows="15" class="input_textarea" name="info[message]">


[quote]
{$message.bb_message}
[/quote]</textarea>
                            </td>
                        </tr>
                        <tr>
                            <td align="right" colspan="2" class="cell2">
                                <input type="submit" name="submit" class="ButtonGreen" value="{t}Send message{/t}" />
                            </td>
                        </tr>
                    </table>
                </form>
            {/if}
        </td>
    </tr>
</table>