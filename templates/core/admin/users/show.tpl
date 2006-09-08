<h2>Administration :: Users </h2>

{* Debuganzeige, wenn DEBUG = 1 | {$users|@var_dump} *}
{if $smarty.const.DEBUG eq "1"} Debugausgabe des Arrays:   {html_alt_table loop=$users} {/if}


{doc_raw}
    <script type="text/javascript" src="{$www_core_tpl_root}/javascript/clip.js"></script>
{/doc_raw}
<table cellspacing="0" cellpadding="2" border="0" width="100%">  

<tr>
    <td class="td_header" width="10%" align="center"> {translate}User ID{/translate}         </td>
    <td class="td_header" align="center">             {translate}eMail{/translate}           </td>
    <td class="td_header" align="center">             {translate}Nick{/translate}            </td>  
    <td class="td_header" align="center">             {translate}Joined{/translate}          </td>
    <td class="td_header" align="center">             {translate}First name{/translate}      </td>
    <td class="td_header" align="center">             {translate}Last name{/translate}       </td>
    <td class="td_header" align="center">             {translate}Infotext{/translate}       </td>
    <td class="td_header" align="center">             {translate}Action{/translate}          </td>
    <td class="td_header" align="center">             {translate}Delete{/translate}          </td>
</tr>

<form action="index.php?mod=admin&sub=users&action=delete" method="POST">
{assign var="td_class" value="cell1"}
{foreach key=schluessel item=wert from=$users}
    <tr>

    <td class="{$td_class}" height="40" align="center"> {$wert.user_id}     </td>
    <td class="{$td_class}" align="center"> {$wert.email}       </td>
    <td class="{$td_class}" align="center"> {$wert.nick}        </td>
    <td class="{$td_class}" align="center"> {$wert.joined}      </td>
    <td class="{$td_class}" align="center"> {$wert.first_name}  </td>
    <td class="{$td_class}" align="center"> {$wert.last_name}   </td>
    <td class="{$td_class}" align="center"> {$wert.infotext}   </td>
    <td class="{$td_class}" align="center">
        <a class="input_submit" style="position: relative; top: 7px;" href="index.php?mod=admin&sub=users&action=edit&user_id={$wert.user_id}">Edit</a>
    </td>
    <td class="{$td_class}" align="center"> 
        <input type="hidden" name="ids[]" value="{$wert.user_id}">
        <input name="delete[]" type="checkbox" value="{$wert.user_id}">
    </td>
    </tr>
{if $td_class=='cell1'}{assign var="td_class" value="cell2"}{else}{assign var="td_class" value="cell1"}{/if}    
{/foreach} 
    
{* Actions - Buttons *}

<tr>
   <td height="20" colspan="9" align="right">
        <input class="input_submit" type="reset" tabindex="3" />
        <input class="input_submit" type="submit" name="Delete" id="Delete" value="Delete Selected Users" tabindex="2" />
    </td>
</tr>
</form>
</table>