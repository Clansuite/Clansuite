<h2>Administration of Permissions</h2>

{* Debuganzeige, wenn DEBUG = 1 | {$permissions_data|@var_dump} *}
{if $smarty.const.DEBUG eq "1"} Debugausgabe des Arrays:   {html_alt_table loop=$permissions_data} {/if}
            
            <h3>{translate}Right-based Usergroups{/translate}</h3>
              
<table cellspacing="0" cellpadding="0" border="0" class="border3d" width="80%">           

<tr class="td_header">
    <td width="10%" align="center" > {translate}Right ID{/translate}        </td>
    <td align="center">             {translate}Right Name{/translate}     </td>
    <td align="center">             {translate}Description{/translate}     </td>
    <td align="center">             {translate}Action{/translate}          </td>
    <td align="center">             {translate}Delete{/translate}          </td>
</tr>


{foreach key=schluessel item=wert from=$permissions_data}

    <tr class="{cycle values="cell1,cell2"}">
        <td align="center" height="50"><input type="hidden" name="ids[]" value="{$wert.right_id}">{$wert.right_id}</td>
        <td align="center">{$wert.name}</td>
        <td align="center">{$wert.description}</td>
        
        <td align="center">
            <a href="index.php?mod=admin&sub=permissions&action=edit&id={$wert.right_id}" class="input_submit" style="position: relative; top: 15px">Edit</a>
            <a href="index.php?mod=admin&sub=permissions&action=lookup&id={$wert.right_id}" class="input_submit" style="position: relative; top: 15px">Look up User</a>
        </td>
        <td align="center"> 
            <form action="index.php?mod=admin&sub=permissions&action=delete" method="POST"> 
            <input type="hidden" name="ids[]" value="{$wert.right_id}">
            <input name="delete[]" type="checkbox" value="{$wert.right_id}">
        </td>
    </tr>   
     
{/foreach}

<tr>
   <td colspan="9" height="40" style="padding: 8px">
        <div>
            <input class="Button" type="submit" name="submit" id="Delete" value="Delete the selected Permissions" tabindex="2" />
            </form>
            <input class="Button" type="reset" tabindex="3" />
            <input class="Button" type="submit" name="xsubmit" id="Submit" value="Scan for new Permissions" tabindex="1" />                
        </div>
    </td>
</tr>
</table>

&nbsp;
            
           
