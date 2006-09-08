<h2>Administration :: Users </h2>

{doc_raw}
    <script type="text/javascript" src="{$www_core_tpl_root}/javascript/clip.js"></script>
{/doc_raw}
<table cellspacing="0" cellpadding="2" border="0" width="100%">  

<tr>
    <td class="td_header_small" width="10%" align="center"> {translate}User ID{/translate}         </td>
    <td class="td_header_small" align="center">             {translate}eMail{/translate}           </td>
    <td class="td_header_small" align="center">             {translate}Nick{/translate}            </td>  
    <td class="td_header_small" align="center">             {translate}Joined{/translate}          </td>
    <td class="td_header_small" align="center">             {translate}First name{/translate}      </td>
    <td class="td_header_small" align="center">             {translate}Last name{/translate}       </td>
    <td class="td_header_small" align="center">             {translate}Infotext{/translate}       </td>
    <td class="td_header_small" align="center">             {translate}Action{/translate}          </td>
    <td class="td_header_small" align="center">             {translate}Delete{/translate}          </td>
</tr>

<form action="index.php?mod=admin&sub=users&action=update" method="POST">    
{foreach key=schluessel item=wert from=$users}
    <tr class="{cycle values="cell1,cell2"}">

    <input type="hidden" name="ids[]" value="{$wert.group_id}">

    <td class="cell2" height="40" align="center"> {$wert.user_id}     </td>
    <td class="cell1" align="center"> {$wert.email}       </td>
    <td class="cell2" align="center"> {$wert.nick}        </td>
    <td class="cell1" align="center"> {$wert.joined}      </td>
    <td class="cell2" align="center"> {$wert.first_name}  </td>
    <td class="cell1" align="center"> {$wert.last_name}   </td>
    <td class="cell2" align="center"> {$wert.infotext}   </td>
    <td class="cell1" align="center">
        <a class="input_submit" style="position: relative; top: 7px;" href="index.php?mod=admin&sub=users&action=edit&user_id={$wert.user_id}">Edit</a>
    </td>
    <td class="cell2" align="center"> 
        <input type="hidden" name="ids[]" value="{$wert.users_id}">
        <input name="delete[]" type="checkbox" value="{$wert.users_id}">
    </td>
    </tr>
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