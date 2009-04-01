{* Debuganzeige, wenn DEBUG = 1 | {$users|@var_dump} {$pager|@var_dump}
{if $smarty.const.DEBUG eq "1"} Debugausgabe des Arrays:   {html_alt_table loop=$users} {/if} *}

<div class="ModuleHeading">{t}Users{/t}</div>
<div class="ModuleHeadingSmall">{t}Administrate your Users.{/t}</div>


{move_to}
    <script type="text/javascript" src="{$www_root_themes_core}/javascript/clip.js"></script>
{/move_to}

{*
{if $error.no_users == 1}
   {error title="No users found."}
        Users with ID not found!
    {/error}
{/if}
*}

<form action="index.php?mod=controlcenter&sub=users&amp;action=delete" method="post" accept-charset="UTF-8">

    <table cellpadding="0" cellspacing="0" border="0" width="800" align="center">
            <tr class="tr_row1">
                <td height="20" colspan="8" align="right">
                    {include file="tools/paginate.tpl"}
                </td>
            </tr>
            <tr class="tr_header">
                <td width="1%" align="center">  {columnsort html='ID'}         </td>
                <td align="center">             {columnsort html='eMail'}           </td>
                <td align="center">             {columnsort html='Nick'}            </td>
                <td align="center">             {columnsort html='Joined'}          </td>
                <td align="center">             {t}Action{/t}          </td>
                <td align="center">             {t}Select{/t}          </td>
            </tr>

            {foreach key=schluessel item=wert from=$users}

                <tr class="tr_row1">
                    <td align="center"> {$wert.user_id}     </td>
                    <td>                {$wert.email}       </td>
                    <td>                {$wert.nick}        </td>
                    <td>                {$wert.joined|date_format:"%d.%m.%Y"}      </td>
                    <td align="center">

                        {if isset($smarty.session.user.rights.permission_edit_users) && $smarty.session.user.rights.permission_edit_users == 1} *}
                            <input class="ButtonGreen" type="button" value="{t}User settings{/t}" onclick='{literal}Dialog.info({url: "index.php?mod=controlcenter&amp;sub=users&amp;action=edit_standard&id={/literal}{$wert.user_id}{literal}", options: {method: "get"}}, {className: "alphacube", width:450, height: 400});{/literal}' />
                        {/if}

                        {if isset($smarty.session.user.rights.permission_edit_generals) && $smarty.session.user.rights.permission_edit_generals == 1}
                            <input class="ButtonGreen" type="button" value="{t}General{/t}" onclick='{literal}Dialog.info({url: "index.php?mod=controlcenter&amp;sub=users&amp;action=edit_general", options: {method: "get"}}, {className: "alphacube", width:450, height: 400});{/literal}' />
                        {/if}

                        {if isset($smarty.session.user.rights.permission_edit_computers) && $smarty.session.user.rights.permission_edit_computers == 1}
                            <input class="ButtonGreen" type="button" value="{t}Computers{/t}" onclick='{literal}Dialog.info({url: "index.php?mod=controlcenter&amp;sub=users&amp;action=edit_computer", options: {method: "get"}}, {className: "alphacube", width:450, height: 400});{/literal}' />
                        {/if}

                        {if isset($smarty.session.user.rights.permission_edit_usersguestbook) && $smarty.session.user.rights.permission_edit_usersguestbook == 1}
                            <input class="ButtonGreen" type="button" value="{t}Guestbook{/t}" onclick='{literal}Dialog.info({url: "index.php?mod=controlcenter&amp;sub=users&amp;action=edit_guestbook", options: {method: "get"}}, {className: "alphacube", width:450, height: 400});{/literal}' />
                        {/if}
                    </td>
                    <td align="center" width="1%">
                        <input type="hidden" name="ids[]" value="{$wert.user_id.0}" />
                        <input name="delete[]" type="checkbox" value="{$wert.user_id.0}" />
                    </td>
                </tr>

            {/foreach}

            <tr class="tr_row1">
               <td height="20" colspan="8" align="right">

                    <input class="ButtonGreen" type="button" value="{t}Create new user{/t}" onclick='{literal}Dialog.info({url: "index.php?mod=controlcenter&amp;sub=users&amp;action=create", options: {method: "get"}}, {className: "alphacube", width:370, height: 250});{/literal}' />
                    <input class="Button" name="reset" type="reset" value="{t}Reset{/t}" />
                    <input class="ButtonRed" type="submit" name="delete_text" value="{t}Delete Selected Users{/t}" />

                </td>
            </tr>
            <tr class="tr_row1">
                <td height="20" colspan="8" align="right">
                     {include file="tools/paginate.tpl"}
                </td>
            </tr>
    </table>

</form>