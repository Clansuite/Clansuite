<h1>Website Messenger</h1>

<form action="index.php?mod=messaging" method="post">
<div class="message_menu">
   {$menu}
</div>

<br />
  
{if empty($messages)}
    <div align="center">{translate}There are no messages{/translate}</div>
{else}
    <table border="0" width="100%">
    {foreach key=key item=item from=$messages}
        <tr>
            <td width="30px" align="center">
                <input type="checkbox" value="{$item.message_id}" name="infos[message_id][]" />
            </td>
            <td>
                <div class="{if $item.read==0}message_new{else}message_old{/if}">
                    
                    <div class="message_date">{$item.timestamp|date_format:"%A, %B %e, %Y - %H:%M:%S"}</div>
                    <hr width="100%" size="1" class="hrcolor" />
                    
                    <div class="message_headline">
                        {if $item.read==0}
                        <strong>{translate}New{/translate}:&nbsp;</strong>
                        {/if}
                        <a href="index.php?mod=messaging&action=read&id={$item.message_id}"><strong>{$item.headline}</strong></a>
                    </div>                               
                    
                    <div class="message_message">
                        {$item.message|wordwrap:100}
                    </div>

                </div>
            </td>
            <td>
                <div class="message_buttons">
                    <input type="button" class="ButtonGreen" value="{translate}Reply{/translate}" onclick="self.location.href='index.php?mod=messaging&action=create&reply_id={$item.message_id}'" />
                    <br />
                    <input type="button" class="ButtonYellow" value="{if $item.read==0}{translate}Mark as read{/translate}{else}{translate}Mark as unread{/translate}{/if}" onclick="self.location.href='index.php?mod=messaging&action=mark&id={$item.message_id}&read={if $item.read==0}1{else}0{/if}'" />
                    <br />
                    <input type="button" class="ButtonRed" value="{translate}Delete{/translate}" onclick="self.location.href='index.php?mod=messaging&action=delete&id={$item.message_id}'" />
                </div>
            </td>                        
        </tr>

    {/foreach}
        <tr>
            <td class="cell2" colspan="2">
                <div class="message_selections">
                    <select name="action" class="input_text">
                        <option value="delete">{translate}Delete selected messages{/translate}</option>
                        <option value="multiple_mark_read">{translate}Mark messages as read{/translate}</option>
                        <option value="multiple_mark_unread">{translate}Mark messages as unread{/translate}</option>
                    </select>
                    <input type="submit" name="submit" value="{translate}Go!{/translate}" class="ButtonGreen" />
                </div>
            </td>
        </tr>
    </table>
{/if}
</form>