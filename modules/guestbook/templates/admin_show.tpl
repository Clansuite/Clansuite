{* Debugausgabe des Arrays: {html_alt_table loop=$guestbook} {$guestbook|@var_dump} *}
{modulenavigation}
<div class="ModuleHeading">{t}Guestbook - Administration{/t}</div>
<div class="ModuleHeadingSmall">{t}You can manage your Guestbook.{/t}</div>

<form action="index.php?mod=controlcenter&sub=users&amp;action=delete" method="post" accept-charset="UTF-8">

<table cellpadding="0" cellspacing="0" border="0" width="800" align="center">
    <tr class="tr_row1">
        <td height="20" colspan="11" align="right">
            {pagination}
        </td>
    </tr>
    <tr class="tr_header">
        <td width="1%" align="center"> {columnsort html="ID"}</td>
        <td align="center">{columnsort html="Added"}</td>
        <td align="center">{columnsort html="Author/Nick"}</td>        
        <td align="center">{columnsort html="eMail"}</td>
        <td align="center">{columnsort html="ICQ"}</td>
        <td align="center">{columnsort html="Website"}</td>
        <td align="center">{columnsort html="Town"}</td>
        <td align="center">{columnsort html="Message"}</td>
        <td align="center">{columnsort html="IP"}</td>

        <td align="center">{t}Edit Action{/t}</td>
        <td align="center">{t}Del{/t}</td>
    </tr>
    </tr>
    {foreach item=entry from=$guestbook}
        <tr class="tr_row1">
            <td>{$entry.gb_id}</td>
            <td>{$entry.gb_added}</td>
            <td>{$entry.gb_nick} <a href='index.php?mod=users&show'>{$entry.gb_nick}</a></td>
            <td>{$entry.gb_email}</td>
            <td>{$entry.gb_icq}</td>
            <td>{$entry.gb_website}</td>
            <td>{$entry.gb_town}</td>
            <td>{$entry.gb_text}</td>
            <td>{$entry.gb_ip}</td>
            <td>Edit</td>
            <td align="center" width="1%">
                        <input type="hidden" name="ids[]" value="{$wert.user_id.0}" />
                        <input name="delete[]" type="checkbox" value="{$wert.user_id.0}" />
                    </td>
        </tr>
    {/foreach}

    <tr class="tr_row1">
       <td height="20" colspan="11" align="right">
            <input class="ButtonGreen" type="button" value="{t}Accept New Entries{/t}" onclick='{literal}Dialog.info({url: "index.php?mod=guestbook&amp;sub=admin&amp;action=accept", options: {method: "get"}}, {className: "alphacube", width:370, height: 250});{/literal}' />
            <input class="Button" name="reset" type="reset" value="{t}Reset{/t}" />
            <input class="ButtonRed" type="submit" name="delete_text" value="{t}Delete Selected Entries{/t}" />
        </td>
    </tr>
    <tr class="tr_row1">
        <td height="20" colspan="11" align="right">
             {pagination}
        </td>
    </tr>
</table>