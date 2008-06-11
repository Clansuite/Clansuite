{* Debuganzeige, wenn DEBUG = 1 | {$users|@var_dump}
{if $smarty.const.DEBUG eq "1"} Debugausgabe des Arrays:   {html_alt_table loop=$users} {/if} *}

{doc_raw}
    <script type="text/javascript" src="{$www_root_themes_core}/javascript/clip.js"></script>

    {* Prototype + Scriptaculous + Smarty_Ajax + Xilinus*}
    <script src="{$www_root_themes_core}/javascript/prototype/prototype.js" type="text/javascript"></script>
    <script type="text/javascript" src="{$www_root_themes_core}/javascript/scriptaculous/effects.js"></script>
    <script type="text/javascript" src="{$www_root_themes_core}/javascript/xilinus/window.js"></script>
    <script type="text/javascript" src="{$www_root_themes_core}/javascript/xilinus/window_effects.js"></script>
    <script type="text/javascript" src="{$www_root_themes_core}/javascript/smarty_ajax.js"></script>

    <link rel="stylesheet" type="text/css" href="{$www_root_themes_core}/javascript/xilinus/themes/alphacube.css" />
    <link rel="stylesheet" type="text/css" href="{$www_root_themes_core}/javascript/xilinus/themes/alert.css" />
    <link rel="stylesheet" type="text/css" href="{$www_root_themes_core}/javascript/xilinus/themes/default.css" />
{/doc_raw}

{if isset($additional_head)} {$additional_head} {/if}
{if $error.no_users == 1}
   {error title="No users found."}
        Users with ID not found!
    {/error}
{/if}

<form action="index.php?mod=admin&sub=users&amp;action=delete" method="post" accept-charset="UTF-8">

    <table cellpadding="0" cellspacing="0" border="0" width="800" align="center">
            <tr class="tr_row1">
                <td height="20" colspan="8" align="right">
                    {include file="admin/tools/paginate.tpl"}
                </td>
            </tr>
            <tr class="tr_header">
                <td width="1%" align="center">  {columnsort html="ID"}         </td>
                <td align="center">             {columnsort html="eMail"}           </td>
                <td align="center">             {columnsort html="Nick"}            </td>
                <td align="center">             {columnsort html="Joined"}          </td>
                <td align="center">             {t}Edit Action{/t}          </td>
                <td align="center">             {t}Del{/t}          </td>
            </tr>

            {foreach key=schluessel item=wert from=$users}

                <tr class="tr_row1">
                    <td align="center"> {$wert.user_id}     </td>
                    <td>                {$wert.email}       </td>
                    <td>                {$wert.nick}        </td>
                    <td>                {$wert.joined|date_format:"%d.%m.%Y"}      </td>
                    <td align="center">

                        {if $smarty.session.user.rights.cc_edit_users == 1}
                            <input class="ButtonGreen" type="button" value="{t}User settings{/t}" onclick='{literal}Dialog.info({url: "index.php?mod=admin&amp;sub=users&amp;action=edit_standard&id={/literal}{$wert.user_id}{literal}", options: {method: "get"}}, {className: "alphacube", width:450, height: 400});{/literal}' />
                        {/if}
                        {if $smarty.session.user.rights.cc_edit_generals == 1}
                            <input class="ButtonGreen" type="button" value="{t}General{/t}" onclick='{literal}Dialog.info({url: "index.php?mod=admin&amp;sub=users&amp;action=edit_general", options: {method: "get"}}, {className: "alphacube", width:450, height: 400});{/literal}' />
                        {/if}
                        {if $smarty.session.user.rights.cc_edit_computers == 1}
                            <input class="ButtonGreen" type="button" value="{t}Computers{/t}" onclick='{literal}Dialog.info({url: "index.php?mod=admin&amp;sub=users&amp;action=edit_computer", options: {method: "get"}}, {className: "alphacube", width:450, height: 400});{/literal}' />
                        {/if}
                        {if $smarty.session.user.rights.cc_edit_users == 1}
                            <input class="ButtonGreen" type="button" value="{t}Guestbook{/t}" onclick='{literal}Dialog.info({url: "index.php?mod=admin&amp;sub=users&amp;action=edit_guestbook", options: {method: "get"}}, {className: "alphacube", width:450, height: 400});{/literal}' />
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

                    <input class="ButtonGreen" type="button" value="{t}Create new user{/t}" onclick='{literal}Dialog.info({url: "index.php?mod=admin&amp;sub=users&amp;action=create", options: {method: "get"}}, {className: "alphacube", width:370, height: 250});{/literal}' />
                    <input class="Button" name="reset" type="reset" value="{t}Reset{/t}" />
                    <input class="ButtonRed" type="submit" name="delete_text" value="{t}Delete Selected Users{/t}" />

                </td>
            </tr>
            <tr class="tr_row1">
                <td height="20" colspan="8" align="right">
                    {include file="admin/tools/paginate.tpl"}
                </td>
            </tr>
    </table>

</form> 