<h2>Administration of Permissions</h2>

{* Debuganzeige, wenn DEBUG = 1 | {$permissions_data|@var_dump} 
{if $smarty.const.DEBUG eq "1"} Debugausgabe des Arrays:   {html_alt_table loop=$permissions_data} {/if}*}
            
<form action="index.php?mod=admin&sub=permissions&action=delete" method="POST">

    <table class="admintable" cellpadding="0" cellspacing="0" border="0" rules="all" width="80%">           
    <caption>Show Permissions</caption>
    
        <thead>
            <tr class="td_header">
                <td width="10%"> {translate}Right ID{/translate}        </td>
                <td>             {translate}Right Name{/translate}     </td>
                <td>             {translate}Description{/translate}     </td>
                <td>             {translate}Action{/translate}          </td>
                <td>             {translate}Delete{/translate}          </td>
            </tr>
        </thead>
    
        <tbody>
            {foreach key=schluessel item=wert from=$permissions_data}
            
                <tr class="{cycle values="cell1,cell2"}">
                    <td height="50"><input type="hidden" name="ids[]" value="{$wert.right_id}">{$wert.right_id}</td>
                    <td>{$wert.name}</td>
                    <td>{$wert.description}</td>
                    
                    <td>
                        <a href="index.php?mod=admin&sub=permissions&action=edit&id={$wert.right_id}" class="ButtonGrey">Edit</a>
                        <a href="index.php?mod=admin&sub=permissions&action=lookup&id={$wert.right_id}" class="ButtonGrey">Look up User</a>
                    </td>
                    <td style="text-align:center;">
                        <input type="hidden" name="ids[]" value="{$wert.right_id}">
                        <input name="delete[]" type="checkbox" value="{$wert.right_id}">
                    </td>
                </tr>   
                 
            {/foreach}
        </tbody>
        
        <tfoot>
            <tr>
               <td colspan="9" height="40">
                   
                   <input style="border-top-color:darkslategray; border-style:groove;" class="Button" type="submit" name="submit" id="Delete" value="Delete the selected Permissions" tabindex="4" />
                   <input style="border-top-color:indianred; border-style:groove;" class="Button" type="reset" tabindex="3" />
                   
                   <input style="border-top-color:lightgreen; border-style:groove;" class="Button" type="button" name="xsubmit" id="Submit" 
                   onClick="self.location.href='index.php?mod=admin&sub=permissions&action=create'"  value="Create new Permission" tabindex="2" />
                   
                   <input style="border-top-color:yellow; border-style:groove;" class="Button" type="button" name="xsubmit" id="Scan" 
                   onClick="self.location.href='index.php?mod=admin&sub=permissions&action=scan'" value="Scan for new Permissions" tabindex="1" />                
                
                </td>
            </tr>
        </tfoot>
    </table>
</form>