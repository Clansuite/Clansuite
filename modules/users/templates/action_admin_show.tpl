{* Debuganzeige, wenn DEBUG = 1 | {$users|@var_dump} {$pager|@var_dump}
{if $smarty.const.DEBUG eq "1"} Debugausgabe des Arrays:   {html_alt_table loop=$users} {/if} *}

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

{modulenavigation}
<div class="ModuleHeading">{t}Users - Administration{/t}</div>
<div class="ModuleHeadingSmall">{t}You can create Users, edit and delete them.{/t}</div>

<form action="index.php?mod=controlcenter&sub=users&amp;action=delete" method="post" accept-charset="UTF-8">

    <table cellpadding="0" cellspacing="0" border="0" align="center">

            <tr class="tr_row1">
                <td height="20" colspan="8" align="right">

                    {pagination type="alphabet"}
                    {pagination}

                </td>
            </tr>

            <tr class="tr_header">
                <td width="1%" align="center">  {columnsort html='#'}           </td>
                <td align="center">             {columnsort html='Nick'}         </td>
                <td align="center">             {columnsort html='Email'}        </td>
                <td align="center">             {columnsort html='Last Visit'}   </td>
                <td align="center">             {columnsort html='Joined'}       </td>
                <td align="center">             {t}Action{/t}                    </td>
                <td align="center">             {t}Select{/t}                    </td>
            </tr>

            {foreach key=schluessel item=wert from=$users}

                <tr class="tr_row1">
                    <td align="center">         {$wert.user_id}                          </td>
                    <td>                        {$wert.nick}                             </td>
                    <td>                        {$wert.email}                            </td>
                    <td>                        {$wert.timestamp|date_format:"%d.%m.%Y"} </td>
                    <td>                        {$wert.joined|date_format:"%d.%m.%Y"}    </td>
                    <td align="center">

                       <input class="ButtonOrange" type="button" value="{t}Edit Profile{/t}" />

                       {*

                       <input class="ButtonOrange" type"button" value="{t}Send Warning{/t}" />

                       <input class="ButtonOrange" type"button" value="{t}Log Actions{/t}" />

                       <input class="ButtonRed" type"button" value="{t}Ban{/t}" />

                       *}

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

                    {pagination type="alphabet"}
                    {pagination}

                </td>
            </tr>
    </table>

</form>