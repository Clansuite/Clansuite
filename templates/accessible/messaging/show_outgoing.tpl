<h1>Website Messenger - Outgoing</h1>

<form action="index.php?mod=messaging" method="post">
<div class="message_menu">
   {$menu}
</div>

<br />

{if empty($messages)}

    <div align="center">{translate}There are no messages{/translate}</div>

{else}

    <table border="0" width="100%">
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
        
    {foreach key=key item=item from=$messages}
        
        <tr>
            
            <td width="30px" align="center" class="cell1">
                <input type="checkbox" value="{$item.message_id}" name="infos[message_id][]" />
            </td>
            
            <td class="cell2">
                <div class="{if $item.read==0}message_new{else}message_old{/if}">
                                        
                    Recipient: {$item.to} on {$item.timestamp|date_format:"%A, %B %e, %Y - %H:%M:%S"}
                    
                    <hr width="100%" size="1" class="hrcolor" />
                     
                    <div class="message_headline">
                        {if $item.read==0}<strong>{translate}New{/translate}:&nbsp;</strong>{/if}<a href="index.php?mod=messaging&action=read&id={$item.message_id}">{$item.headline}</a>
                    </div>
                    <div class="message_buttons">
                        {if $item.read==0}<input type="button" class="ButtonRed" value="{translate}Get meesage back{/translate}" onclick="self.location.href='index.php?mod=messaging&action=get_back&id={$item.message_id}'" />{/if}
                    </div>

                    <div class="message_message">
                        {$item.message|wordwrap:100}
                    </div>

                </div>
            </td>
                        
        </tr>

    {/foreach}            
           
    </table>

{/if}       

</form>