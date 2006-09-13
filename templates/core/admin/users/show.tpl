{* Debuganzeige, wenn DEBUG = 1 | {$users|@var_dump} 
{if $smarty.const.DEBUG eq "1"} Debugausgabe des Arrays:   {html_alt_table loop=$users} {/if} *}

{doc_raw}
    <script type="text/javascript" src="{$www_core_tpl_root}/javascript/clip.js"></script>
{/doc_raw}


{if $err.no_users == 1}
   {error title="No users found."}
        Users with ID not found!
    {/error}
{/if}

<form action="index.php?mod=admin&sub=users&action=delete" method="POST">

    <table cellpadding="0" cellspacing="0" border="0" width="700" align="center">

            <tr class="tr_header">
                <td width="10%" align="center"> {translate}User ID{/translate}         </td>
                <td align="center">             {translate}eMail{/translate}           </td>
                <td align="center">             {translate}Nick{/translate}            </td>  
                <td align="center">             {translate}Joined{/translate}          </td>
                <td align="center">             {translate}First name{/translate}      </td>
                <td align="center">             {translate}Last name{/translate}       </td>
                <td align="center">             {translate}Infotext{/translate}       </td>
                <td align="center">             {translate}Action{/translate}          </td>
                <td align="center">             {translate}Delete{/translate}          </td>
            </tr>

            {foreach key=schluessel item=wert from=$users}
                
                <tr class="tr_row1">
                    <td align="center"> {$wert.user_id}     </td>
                    <td>                {$wert.email}       </td>
                    <td>                {$wert.nick}        </td>
                    <td>                {$wert.joined}      </td>
                    <td>                {$wert.first_name}  </td>
                    <td>                {$wert.last_name}   </td>
                    <td>                {$wert.infotext}    </td>
                    <td align="center">
                        <input type="button" class="ButtonGreen" onClick="self.location.href='index.php?mod=admin&sub=users&action=edit&id={$wert.user_id}'" value="{translate}Edit{/translate}" />
                    </td>
                    <td align="center"> 
                        <input type="hidden" name="ids[]" value="{$wert.user_id}">
                        <input name="delete[]" type="checkbox" value="{$wert.user_id}">
                    </td>
                </tr>
            
            {/foreach}
            
            <tr class="tr_row1">
               <td height="20" colspan="9" align="right">

                    <input class="ButtonGreen" type="button" name="submit" onClick="self.location.href='index.php?mod=admin&sub=users&action=create'"  value="{translate}Create new user{/translate}" />
                    <input class="Button" name="reset" type="reset" value="{translate}Reset{/translate}"/>
                    <input class="ButtonRed" type="submit" name="delete_text" value="{translate}Delete Selected Users{/translate}" />
                   
                </td>
            </tr>
    </table>
    </center>
</form>