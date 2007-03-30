{* Debugausgabe des Arrays:  {$guestbook|@var_dump} {html_alt_table loop=$guestbook} *}


{* Windows + Windows CSS Basic + Extra Theme *}
<script type="text/javascript" src="{$www_core_tpl_root}/javascript/xilinus/window.js"></script>
<script type="text/javascript" src="{$www_core_tpl_root}/javascript/xilinus/effects.js"> </script>
<script type="text/javascript" src="{$www_core_tpl_root}/javascript/xilinus/debug.js"> </script>
<!-- Set Xilinus default and specific theme-->
<link href="{$www_core_tpl_root}/javascript/xilinus/themes/default.css" rel="stylesheet" type="text/css"/>
<link href="{$www_core_tpl_root}/javascript/xilinus/themes/darkX.css" rel="stylesheet" type="text/css"/> 


<a href="#" onclick="openAlert()">open alert dialog</a><br/>
<a href="#" onclick="openConfirm()">open confirm dialog</a><br/>
<a href="#" onclick="openAjaxConfirm()">open confirm dialog, dialog content is filled by an ajax call</a><br/>

{literal}
<script type="text/javascript">
  function openAlert() {
   Dialog.alert("Add your <b>HTML</b> message here", {windowParameters: {className: "alphacube"}})
  }
  
  function openConfirm() {
    Dialog.confirm("Add your <b>HTML</b> message here<br/>Better than a classic javascript alert?", 
                   {top: 10, width:250, className: "darkX", okLabel: "Yes", cancelLabel:"No"})
  }
  
  function openAjaxConfirm() {
      Dialog.confirm({url: "dialog_ajax.html", options: {method: 'get'}}, 
                     {top: 10, width:250, className: "darkX", okLabel: "Yes", cancelLabel:"No"})    
  }
</script>
{/literal}

{include file="tools/paginate.tpl"}

<form action="index.php?mod=guestbook&amp;sub=admin&amp;action=delete" method="post">
    <table cellpadding="0" cellspacing="0" border="0" width="800px" align="center" style="text-align:center">
        <tr class="tr_header">
         	<td>{columnsort html='ID'}</td>
       		<td>{columnsort html='Author/Nick'}</td>
       		<td>{columnsort selected_class="selected" html='Date/Added'}</td>
       		<td>{columnsort html='Email'}</td>
       		<td>{columnsort html='ICQ'}</td>
       		<td>{columnsort html='Website'}</td>
       		<td>{columnsort html='Town'}</td>
       		<td>{columnsort html='Message'}</td>
       		<td>{columnsort html='IP'}</td>
       		<td width="1%">{columnsort html='Admin Comment'}</td>       		
       		<td width="1%">{translate}Delete{/translate}</td>
       	</tr>
       	       	
        {foreach item=entry from=$guestbook}
        <tr class="{cycle values="tr_row1,tr_row2"}">
            <td>{$entry.gb_id}</td>
            <td style="font-weight:bold">{$entry.gb_nick}</td>
            <td>{$entry.gb_added}</td>
            <td>{$entry.gb_email}</td>
            <td>{$entry.gb_icq}</td>
            <td>{$entry.gb_website}</td>
            <td>{$entry.gb_town}</td>
            <td>{$entry.gb_text}</td>
            <td>{$entry.gb_ip}</td>            
            <td>{if isset($entry.gb_admincomment)} 
                {$entry.gb_admincomment} 
                {else}
                <input onclick="self.location.href='index.php?mod=guestbook&amp;sub=admin&amp;action=add_comment&amp;id={$entry.gb_id}'" type="button" value="{translate}Add Admin Comment{/translate}" class="ButtonGreen" /><br />
                {/if}
                </td>  
            <td align="center">
                        <input type="hidden" name="ids[]" value="{$entry.gb_id}" />
                        <input type="checkbox" name="delete[]" value="{$entry.gb_id}" />
            </td>          
        </tr>
        {/foreach} 
              
        <tr>  
            <td colspan="11" align="right" class="cell1">
                <input class="ButtonGrey" type="reset" name="reset" value="{translate}Reset{/translate}" />
                <input class="ButtonRed" type="submit" name="submit" value="{translate}Delete the selected entries{/translate}" />
            </td>
        </tr>        
</table>
</form>

{include file="tools/paginate.tpl"}