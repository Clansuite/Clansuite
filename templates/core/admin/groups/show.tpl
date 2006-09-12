{* Debuganzeige, wenn DEBUG = 1 |  {$groups|@var_dump} 
{if $smarty.const.DEBUG eq "1"} Debugausgabe des Arrays:   {html_alt_table loop=$groups} {/if} *}
 
<form action="index.php?mod=admin&sub=groups&action=delete" method="POST">
    
    <table cellpadding="0" cellspacing="0" border="0" width="700" align="center">      
        <thead>
        	<tr class="tr_header">
        		<td align="center">{translate}ID{/translate}</td>
        		<td align="center">{translate}Name{/translate}</td>                
        		<td align="center">{translate}Pos{/translate}</td>
        		<td align="center">{translate}Icon{/translate}</td>
        		<td align="center">{translate}Image{/translate}</td>
        		<td align="center">{translate}Description{/translate}</td>
        		<td align="center">{translate}Members{/translate}</td>		
        		<td align="center">{translate}Edit{/translate}</td>
        		<td align="center">{translate}Delete{/translate}</td>
        	</tr>
            
            {foreach key=key item=group from=$groups}
                <tr class="{cycle values="tr_row1,tr_row2"}">
                   <input type="hidden" name="ids[]" value="{$group.group_id}" />
                    <td align="center">{$group.group_id}</td>
                    <td style="color: {$group.color}; font-weight: bold;" align="center">{$group.name}</td>
                    <td align="center">{$group.pos}</td>
                    <td align="center">
                        {if $group.icon==''}
                            <img src="{$www_core_tpl_root}/images/empty.png" width="16" height="16" class="border3d">
                        {else}
                            <img src="{$www_core_tpl_root}/images/groups/icons/{$group.icon}" class="border3d">
                        {/if}
                    </td>
                    <td align="center">
                        {if $group.image==''}
                            <img src="{$www_core_tpl_root}/images/empty.png" width="48" height="48" class="border3d" alt="48x48" title="50x50">
                        {else}
                            <img src="{$www_core_tpl_root}/images/groups/images/{$group.image}" class="border3d" alt="48x48">
                        {/if}
                    </td>
                    <td>{$group.description}</td>
                    <td>
                        {foreach name=usersarray key=schluessel item=userswert from=$group.users}
                        <a href="index.php?mod=admin&sub=users&action=edit&user_id={$userswert.user_id}">{$userswert.nick}</a>
                        {if !$smarty.foreach.usersarray.last},{/if} 
                        {/foreach}
                    </td>      
                    <td align="center"><a href="index.php?mod=admin&sub=groups&action=edit&id={$group.group_id}"><input type="button" value="{translate}Edit{/translate}" class="ButtonGreen" /></a></td>
                    <td align="center"><input type="checkbox" name="delete[]" value="{$group.group_id}"></td>
                
                </tr>
            {/foreach}
            <tr>
                <td colspan="9" align="right" class="cell1">
                    <a href="index.php?mod=admin&sub=groups&action=create"><input class="ButtonGreen" type="button" value="{translate}Create a new Group{/translate}" />
                    <input class="ButtonGrey" type="reset" name="reset" value="{translate}Reset{/translate}"/>
                    <input class="ButtonRed" type="submit" name="submit" value="{translate}Delete the selected groups{/translate}" />
                </td>
            </tr>
    </table>
    
</form>