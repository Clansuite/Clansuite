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

<form action="index.php?mod=admin&sub=users&amp;action=delete" method="post">

    <table cellpadding="0" cellspacing="0" border="0" width="700" align="center">

            <tr class="tr_header">
                <td width="10%" align="center"> {columnsort html="User ID"}         </td>
                <td align="center">             {columnsort html="eMail"}           </td>
                <td align="center">             {columnsort html="Nick"}            </td>
                <td align="center">             {columnsort html="Joined"}          </td>
                <td align="center">             {columnsort html="First name"}      </td>
                <td align="center">             {columnsort html="Last name"}       </td>
                <td align="center">             {translate}Action{/translate}          </td>
                <td align="center">             {translate}Delete{/translate}          </td>
            </tr>

            {foreach key=schluessel item=wert from=$users}

                <tr class="tr_row1">
                    <td align="center"> {$wert.user_id.0}     </td>
                    <td>                {$wert.email}       </td>
                    <td>                {$wert.nick}        </td>
                    <td>                {$wert.joined}      </td>
                    <td>                {$wert.first_name}  </td>
                    <td>                {$wert.last_name}   </td>
                    <td align="center">
                        <input type="button" class="ButtonGreen" onclick="self.location.href='index.php?mod=admin&amp;sub=users&amp;action=edit&amp;id={$wert.user_id.0}'" value="{translate}Edit{/translate}" />
                    </td>
                    <td align="center">
                        <input type="hidden" name="ids[]" value="{$wert.user_id.0}" />
                        <input name="delete[]" type="checkbox" value="{$wert.user_id.0}" />
                    </td>
                </tr>

            {/foreach}

            <tr class="tr_row1">
               <td height="20" colspan="8" align="right">

                    <input class="ButtonGreen" type="button" name="submit" onclick="self.location.href='index.php?mod=admin&amp;sub=users&amp;action=create'"  value="{translate}Create new user{/translate}" />
                    <input class="Button" name="reset" type="reset" value="{translate}Reset{/translate}" />
                    <input class="ButtonRed" type="submit" name="delete_text" value="{translate}Delete Selected Users{/translate}" />

                </td>
            </tr>
            <tr class="tr_row1">
                <td height="20" colspan="8" align="right">
                    {* display pagination info *}
                    {paginate_prev text="&lt;&lt;"} {paginate_middle format="page"}  {paginate_next text="&gt;&gt;"}
                </td>
            </tr>
    </table>

</form>