{* Debugoutout of Arrays:
{if $smarty.const.DEBUG eq "1"} Debug of Newsarchiv {html_alt_table loop=$newsarchiv}   {/if}
{$newscategories|@var_dump}
{$paginate|@var_dump}
*}
<form method="post" name="news_list" action="index.php?mod=news&amp;sub=admin&amp;action=show">
<table border="0" cellpadding="0" cellspacing="0" width="800" align="center">
    <tr class="tr_header">
        <td colspan="3">{translate}News Settings{/translate}</td>
    </tr>
    <tr class="tr_row1">
         <td colspan="2"><strong>{translate}Category:{/translate}</strong>
            <select name="cat_id" class="input_text">
                <option value="0">-- {translate}all{/translate} --</option>
                {foreach item=cats from=$newscategories}
                <option value="{$cats.cat_id}" {if isset($smarty.post.cat_id) && $smarty.post.cat_id == $cats.cat_id} selected='selected'{/if}>{$cats.name|escape:html}</option>
                {/foreach}
            </select>
            <input class="ButtonYellow" type="submit" name="submit" value="{translate}Show{/translate}" />
        </td>
    </tr>
</table>
<br/>
</form>
<form method="post" name="news_list" action="index.php?mod=news&amp;sub=admin&amp;action=delete">
<table border="0" cellpadding="0" cellspacing="0" width="800" align="center">
    <tr class="tr_header_small">
        <td>
        <div style="float:left;">
            <img src="{$www_core_tpl_root}/images/icons/page_edit.png" style="height:16px;width:16px" alt="" />
            {if $paginate.size gt 1}
              Items {$paginate.first}-{$paginate.last} of {$paginate.total} displayed.
            {else}
              Item {$paginate.first} of {$paginate.total} displayed.
            {/if}
        </div>
        <span style="float:right;">
            {* display pagination info *}
            {paginate_prev text="&lt;&lt;"} {paginate_middle format="page"}  {paginate_next text="&gt;&gt;"}
        </span>
        </td>
    </tr>
</table>
<table border="0" cellspacing="0" cellpadding="0" width="800" align="center">
    <tr class="tr_header">
        <th width="150">{columnsort html='Date'}</th>
        <th>{columnsort selected_class="selected" html='Title'}</th>
        <th>{columnsort html='Category'}</th>
        <th>{columnsort html='Author'}</th>
        <th>{columnsort html='Draft'}</th>
        <th width="1%">{translate}Edit{/translate}</th>
        <th width="1%">{translate}Delete{/translate}</th>
    </tr>
    {foreach item=news from=$newsarchiv}
    <tr class="tr_row1">
        <td>{translate}{$news.news_added|date_format:"%A"}{/translate}, {translate}{$news.news_added|date_format:"%B"}{/translate}{$news.news_added|date_format:" %e, %Y"}</td>
        <td><strong>{$news.news_title}</strong></td>
        <td>{$news.cat_name}</td>
        <td><a href='index.php?mod=users&amp;id={$news.user_id}'>{$news.nick}</a></td>
        <td>
        {if $news.draft == 1}
            {translate}unpublished{/translate}
        {else}
            {translate}published{/translate}
        {/if}
        </td>
        <td align="center">
            <input class="ButtonGreen" type="button" value="{translate}Edit{/translate}" onclick="self.location.href='index.php?mod=news&amp;sub=admin&amp;action=edit&amp;id={$news.news_id}'" />
        </td>
        <td align="center">
            <input type="checkbox" value="{$news.news_id}" name="delete[]" />
            <input type="hidden" value="{$news.news_id}" name="ids[]" />
        </td>
    </tr>
    {/foreach}
    <tr class="tr_row1">
        <td colspan="9" align="right">
        <input onclick="self.location.href='index.php?mod=news&amp;sub=admin&amp;action=create'" class="ButtonGreen" type="button" value="{translate}Create News{/translate}" />
        <input class="ButtonGrey" type="reset" name="reset" value="{translate}Reset{/translate}" />
        <input class="ButtonRed" type="submit" name="submit" value="{translate}Delete{/translate}" />
        </td>
    </tr>
</table>
</form>
<table border="0" cellpadding="0" cellspacing="0" width="800" align="center">
    <tr class="tr_header_small">
        <td>
        <div style="float:left;">
            <img src="{$www_core_tpl_root}/images/icons/page_edit.png" style="height:16px;width:16px" alt="" />
            {if $paginate.size gt 1}
              Items {$paginate.first}-{$paginate.last} of {$paginate.total} displayed.
            {else}
              Item {$paginate.first} of {$paginate.total} displayed.
            {/if}
        </div>
        <span style="float:right;">
            {* display pagination info *}
            {paginate_prev text="&lt;&lt;"} {paginate_middle format="page"}  {paginate_next text="&gt;&gt;"}
        </span>
        </td>
    </tr>
</table>