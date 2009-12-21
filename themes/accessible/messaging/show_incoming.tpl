{move_to target="pre_head_close"}
    <script src="{$www_root_themes_core}/javascript/prototype/prototype.js" type="text/javascript"></script>
    <script src="{$www_root_themes_core}/javascript/lightbox/lightbox.js" type="text/javascript"></script>
  	<script type="text/javascript" src="{$www_root_themes_core}/javascript/scriptaculous/effects.js"> </script>
  	<script type="text/javascript" src="{$www_root_themes_core}/javascript/xilinus/window.js"> </script>
  	<script type="text/javascript" src="{$www_root_themes_core}/javascript/xilinus/window_effects.js"> </script>
  	<link rel="stylesheet" type="text/css" href="{$www_root_themes_core}/javascript/xilinus/themes/alphacube.css" />
  	<link rel="stylesheet" type="text/css" href="{$www_root_themes_core}/javascript/xilinus/themes/alert.css" />
  	<link rel="stylesheet" type="text/css" href="{$www_root_themes_core}/javascript/xilinus/themes/default.css" />
{/move_to}

<h1>Website Messenger - Incoming</h1>

<form action="index.php?mod=messaging" method="post" accept-charset="UTF-8">

<div class="message_menu">  {$menu} </div>

<br />

{if empty($messages)}   

    <div align="center">{t}There are no messages{/t}</div>

{else}

    <table border="0" width="100%">
    <tr>
        <td class="cell2" colspan="3">
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
    
    {foreach key=key item=item from=$messages}
        <tr>
            
            <td width="30px" align="center" class="cell1">
                <input type="checkbox" value="{$item.message_id}" name="infos[message_id][]" />
            </td>
            
            <td class="cell2">
                <div class="{if $item.read==0}message_new{else}message_old{/if}">
                    
                    {if $item.read==0}
                        <strong>{t}New{/t}:&nbsp;</strong>
                    {/if}
                    From: {$item.from} on {$item.timestamp|date_format:"%A, %B %e, %Y - %H:%M:%S"}
                    
                    <hr width="100%" size="1" class="hrcolor" />

                    <div class="message_headline">
                        <a href="index.php?mod=messaging&action=read&id={$item.message_id}"><strong>{$item.headline}</strong></a>
                    </div>

                    <div class="message_message">
                        {$item.message|wordwrap:100}
                    </div>

                </div>
            </td>
            
            <td class="cell2">
                <div class="message_buttons">
                    <input class="ButtonGreen" type="button" value="{t}Reply{/t}" onclick='Dialog.info({url: "index.php?mod=messaging&action=create&reply_id={$item.message_id}", options: {method: "get"}}, {className: "alphacube", width:420, height: 325});' />
                    <br />
                    <input type="button" class="ButtonYellow" value="{if $item.read==0}{t}Mark as read{/t}{else}{t}Mark as unread{/t}{/if}" onclick="self.location.href='index.php?mod=messaging&action=mark&id={$item.message_id}&read={if $item.read==0}1{else}0{/if}'" />
                    <br />
                    <input type="button" class="ButtonRed" value="{t}Delete{/t}" onclick="self.location.href='index.php?mod=messaging&action=delete&id={$item.message_id}'" />
                </div>
            </td>
        
        </tr>

    {/foreach}  
          
    </table>
    
{/if}

</form>