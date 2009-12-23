{* DEBUG OUTPUT of assigned Arrays:
   {$smarty.session|@var_dump}
   <hr>
    {$quotes|@var_dump}
   <hr>
   {$pager|@var_dump}
*}
{modulenavigation}
<div class="ModuleHeading">{t}Quotes - Administration{/t}</div>
<div class="ModuleHeadingSmall">{t}You can write Quotes, edit and delete them.{/t}</div>

<table cellpadding="0" cellspacing="0" border="0" width="800" align="center">

    <!-- Pagination -->
    <tr class="tr_row1">
        <td height="20" colspan="8" align="right">
            {pagination}
        </td>
    </tr>

    <!-- Table Headings -->
    <tr class="tr_header">
        <td align="center">             {columnsort html='Body'}      </td>
        <td align="center">             {columnsort html='Author'}    </td>
        <td align="center">             {columnsort html='Source'}          </td>
        <td align="center">             {t}Action{/t}                       </td>
        <td align="center">             {t}Select{/t}                       </td>
    </tr>

    <!-- Start the Form -->
    <form action="index.php?mod=quotes&amp;sub=admin&amp;action=delete" method="post">

{foreach item=quote from=$quotes}

    <!-- Anchor for Quote ID {$quote.quote_id}-->
    <a name="news-{$quote.quote_id}"></a>

    <!-- Table Content -->
    <tr class="tr_row1">
        <td width="55%" valign="top">{$quote.quote_body}</td>
        <td>{$quote.quote_author}</td>
        <td>{$quote.quote_source}</td>

        <td><input class="ButtonOrange" type="button" value="{t}Edit{/t}" /></td>
        <td align="center" width="1%">
            <input type="hidden" name="ids[]" value="{$quote.quote_id}" />
            <input name="delete[]" type="checkbox" value="{$quote.quote_id}" />
        </td>
    </tr>

{/foreach}

    <tr class="tr_row1">
    	<td height="20" colspan="5" align="right">

        {* {if isset($smarty.session.user.rights.permission_edit_news) AND isset($smarty.session.user.rights.permission_access) } *}

                <!-- Form Hidden Data -->
                <input type="hidden" value="{$quote.quote_id}" name="delete[]" />
                <input type="hidden" value="{$quote.quote_id}" name="ids[]" />

                <!-- Form Buttons/Commands -->
                <input class="ButtonGreen" type="button" value="{t}Add Quote{/t}" />
                <input class="ButtonGreen" type="button" value="{t}Edit Selected Quotes{/t}" onclick='Dialog.info({url: "index.php?mod=quotes&amp;sub=admin&amp;action=edit&amp;id={$quote.quote_id}&amp;front=1", options: {method: "get"}}, {className: "alphacube", width:900, height: 600});' />
                <input class="Button" name="reset" type="reset" value="{t}Reset{/t}" />
                <input class="ButtonRed" type="submit" name="submit" value="{t}Delete Selected Quotes{/t}" />

            <!-- Form Close -->
            </form>
        {* {/if} *}

        </td>
    </tr>
</table>