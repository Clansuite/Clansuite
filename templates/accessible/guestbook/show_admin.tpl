{* Debugausgabe des Arrays:  {$guestbook|@var_dump} {html_alt_table loop=$guestbook} *}

{doc_raw}
{* Windows + Windows CSS Basic + Extra Theme *}
<script type="text/javascript" src="{$www_core_tpl_root}/javascript/windows/window.js"></script>
<link href="{$www_core_tpl_root}/javascript/windows/themes/default.css" rel="stylesheet" type="text/css"/>  
<!-- Add this to have a specific theme-->
<link href="{$www_core_tpl_root}/javascript/windows/darkX.css" rel="stylesheet" type="text/css"/> 
{/doc_raw}

<form action="index.php?mod=guestbook&amp;sub=admin&amp;action=delete" method="post">
    <table cellpadding="0" cellspacing="0" border="0" width="700" style="text-align:center">
      	<tr class="tr_header">
         	<td>{translate}ID{/translate}</td>
       		<td>{translate}Author/Nick{/translate}</td>
       		<td>{translate}Date/Added{/translate}</td>
       		<td>{translate}Email{/translate}</td>
       		<td>{translate}ICQ{/translate}</td>
       		<td>{translate}Website{/translate}</td>
       		<td>{translate}Town{/translate}</td>
       		<td>{translate}Message{/translate}</td>
       		<td>{translate}IP{/translate}</td>
       		<td>{translate}Admin Comment{/translate}</td>       		
       		<td>{translate}Delete{/translate}</td>
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
                <input onclick="self.location.href='index.php?mod=guestbook&amp;sub=admin&amp;action=add_admincomment&amp;id={$entry.gb_id}'" type="button" value="{translate}Add Admin Comment{/translate}" class="ButtonGreen" /><br />
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
        <tr>
            <td colspan="11"align="right" class="cell1">
                {* display pagination info *}
                {paginate_prev text="&lt;&lt;"} {paginate_middle format="page"}  {paginate_next text="&gt;&gt;"}
            </td>        
        </tr>     
    </table>
</form>