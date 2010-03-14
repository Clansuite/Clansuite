{* Debugausgabe des Arrays:          {$guestbook|@var_dump}
{html_alt_table loop=$guestbook}*}

{*
{foreach item=entry from=$guestbook}
    {$entry.gb_id}
    {$entry.gb_added}
    {$entry.gb_nick} <a href='index.php?mod=users&show'>{$entry.gb_nick}</a>
    {$entry.gb_email}
    {$entry.gb_icq}
    {$entry.gb_website}
    {$entry.gb_town}
    {$entry.gb_text}
    {$entry.gb_ip}
    {$entry.CsImage|@var_dump}
    {$entry.CsImage.type}
{/foreach}
*}

<!-- start jq confirm dialog -->
{jqconfirm}
<!-- end jq confirm dialog -->

{modulenavigation}
<div class="ModuleHeading">{t}Guestbook{/t}</div>
<div class="ModuleHeadingSmall">{t}You can edit, delete and comment guestbook entries.{/t}</div>


<table border="0" cellspacing="1" cellpadding="3" style="width:99%">

    <caption class="td_header">Guestbook</caption>

    <tr class="tr_row1">
        <!-- Table-Head Pagination -->
        <td height="20" colspan="9" align="right">
            {pagination}
        </td>
    </tr>

    <!-- Header of Table -->
    <tr class="td_header">
        <th>(Gr)Avatar</th>
        <th>{columnsort html='Nickname'}</th>
        <th>{columnsort selected_class="selected" html='Message'}</th>
        <th>{columnsort html='Date'}</th>
        <th>{columnsort html='Website'}</th>
        <th>Contact</th>
        <th>Draft</th>
        <th>Action</th>
        <th>Select</th>
    </tr>

    <!-- Open Form -->
    <form id="deleteForm" name="deleteForm" action="index.php?mod=news&sub=admin&amp;action=delete" method="post" accept-charset="UTF-8">
        <!-- Content of Table -->

        {foreach $guestbook  as entry}

        <tr class="tr_row1" id="guestbook-entry-{$entry.gb_id}">
                
                <td>{gravatar email="`$entry.gb_email`"}</td>
                
                <td><a href='index.php?mod=users&amp;id={$entry.user_id}'>{$entry.gb_nick}</a></td>
                
                <td>{$entry.gb_text}</td>
                
                <td>{* date_format:"%d.%m.%y" *}
                <span>{t}Date{/t}</span>: {t}{$entry.gb_added|date_format:"%A"}{/t}, {t}{$entry.gb_added|date_format:"%B"}{/t}{$entry.gb_added|date_format:" %e, %Y"}
                </td>
                
                <td><a href="{$entry.gb_website}" target="_blank">{$entry.gb_website}</a></td>
                
                <td>Mail <a href="mailto:{$entry.gb_email}">{$entry.gb_email} - ICQ {$entry.gb_icq}</a></td>
                
                <td>published (Set hide)</td>
                
                <td>
                    <a class="ui-button ui-button-check ui-widget ui-state-default ui-corner-all ui-button-size-small ui-button-orientation-l" 
                       href="index.php?mod=guestbook&amp;sub=admin&amp;action=edit&amp;id={$entry.gb_id}" tabindex="0">
                        <span class="ui-button-icon">
                            <span class="ui-icon ui-icon-pencil"></span>
                        </span>
                        <span class="ui-button-label" unselectable="on" style="-moz-user-select: none;">Edit</span>
                    </a>
                </td>
                
                <td align="center" width="1%">                    
                    <input name="delete[]" type="checkbox" value="{$entry.gb_id}" />
                </td>
        </tr>

        <!-- Show Guestbook Comment if one exists -->
        {if !empty($entry.gb_comment)}
            <tr class="tr_row2">
                <td colspan="9">
                        <legend>{t}Comment{/t}</legend>
                        {$entry.gb_comment}
                </td>
            </tr>
        {/if}

        {/foreach}

        <!-- Table-Footer Pagination -->
        <tr class="tr_row1">
            <td height="20" colspan="9" align="right">
                {pagination}
            </td>
        </tr>

        <!-- Form Buttons -->
        <tr class="tr_row1">
            <td height="20" colspan="9" align="right">
                <a class="ButtonGreen" href="index.php?mod=news&amp;sub=admin&amp;action=create" />{t}Create News{/t}</a>
                <input class="Button" name="reset" type="reset" value="{t}Reset{/t}" />
                <input class="ButtonRed" type="submit" name="delete_text" value="{t}Delete Selected News{/t}" />
            </td>
        </tr>

    </form>
    <!-- Close Form -->

</table>