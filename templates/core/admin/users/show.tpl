<h2>Administration :: Users </h2>

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
    <center>
    <table class="admintable" cellpadding="0" cellspacing="0" border="0" rules="all" width="80%">
    
        <caption>Show Users</caption>
        
        <thead>
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
        </thead>
      
        <tbody>
            {foreach key=schluessel item=wert from=$users}
                
                <tr id={cycle values="nix,cell2"}>
                    <td height="40" align="center"> {$wert.user_id}     </td>
                    <td>                            {$wert.email}       </td>
                    <td>                            {$wert.nick}        </td>
                    <td>                            {$wert.joined}      </td>
                    <td>                            {$wert.first_name}  </td>
                    <td>                            {$wert.last_name}   </td>
                    <td>                            {$wert.infotext}    </td>
                    <td align="center">
                        <a class="ButtonOrange" href="index.php?mod=admin&sub=users&action=edit&id={$wert.user_id}">Edit</a>
                    </td>
                    <td align="center"> 
                        <input type="hidden" name="ids[]" value="{$wert.user_id}">
                        <input name="delete[]" type="checkbox" value="{$wert.user_id}">
                    </td>
                </tr>
            
            {/foreach} 
        </tbody>
        
        <tfoot>
            <tr>
               <td height="20" colspan="9" align="right">

                    <input class="ButtonGreen" type="button" name="xsubmit" id="Submit" onClick="self.location.href='index.php?mod=admin&sub=users&action=create'"  value="Create new User" tabindex="2" />               
                    <input class="Button" type="reset" />
                    <input class="ButtonRed" type="submit" name="Delete" id="Delete" value="Delete Selected Users" tabindex="2" />
                   
                </td>
            </tr>
        </tfoot>
    </table>
    </center>
</form>