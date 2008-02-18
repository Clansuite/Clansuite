{* Debugoutout of Arrays:
{if $smarty.const.DEBUG eq "1"} Debug of Newsarchiv {html_alt_table loop=$newsarchiv}   {/if}
{$newscategories|@var_dump}
{$paginate|@var_dump}
*}
    <script type="text/javascript" src="{$www_root}/core/fckeditor/fckeditor.js"></script>
{doc_raw}
    <script type="text/javascript" src="{$www_root_tpl_core}/javascript/prototype/prototype.js"> </script>
  	<script type="text/javascript" src="{$www_root_tpl_core}/javascript/scriptaculous/effects.js"> </script>
  	<script type="text/javascript" src="{$www_root_tpl_core}/javascript/xilinus/window.js"> </script>
  	<script type="text/javascript" src="{$www_root_tpl_core}/javascript/xilinus/window_effects.js"> </script>
  	<link rel="stylesheet" type="text/css" href="{$www_root_tpl_core}/javascript/xilinus/themes/alphacube.css" />
  	<link rel="stylesheet" type="text/css" href="{$www_root_tpl_core}/javascript/xilinus/themes/alert.css" />
  	<link rel="stylesheet" type="text/css" href="{$www_root_tpl_core}/javascript/xilinus/themes/default.css" />
{/doc_raw}
<form method="post" accept-charset="UTF-8" name="news_list" action="index.php?mod=news&amp;sub=admin&amp;action=show">
<table border="0" cellpadding="0" cellspacing="0" width="800" align="center">
    <tr class="tr_header">
        <td colspan="3">{t}News Settings{/t}</td>
    </tr>
    <tr class="tr_row1">
         <td colspan="2"><strong>{t}Category:{/t}</strong>
            <select name="cat_id" class="input_text">
                <option value="0">-- {t}all{/t} --</option>
                {foreach item=cats from=$newscategories}
                <option value="{$cats.cat_id}" {if isset($smarty.post.cat_id) && $smarty.post.cat_id == $cats.cat_id} selected='selected'{/if}>{$cats.name|escape:html}</option>
                {/foreach}
            </select>
            <input class="ButtonYellow" type="submit" name="submit" value="{t}Show{/t}" />
        </td>
    </tr>
</table>
<br/>
</form>
<form method="post" accept-charset="UTF-8" name="news_list" action="index.php?mod=news&amp;sub=admin&amp;action=delete">
<table border="0" cellpadding="0" cellspacing="0" width="800" align="center">
    <tr class="tr_header_small">
        <td>
        <div style="float:left;">
            <img src="{$www_root_tpl_core}/images/icons/page_edit.png" style="height:16px;width:16px" alt="" />
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
        <th width="1%">{t}Edit{/t}</th>
        <th width="1%">{t}Delete{/t}</th>
    </tr>
    {foreach item=news from=$newsarchiv}
    <tr class="tr_row1">
        <td>{t}{$news.news_added|date_format:"%A"}{/t}, {t}{$news.news_added|date_format:"%B"}{/t}{$news.news_added|date_format:" %e, %Y"}</td>
        <td>
        <strong>{$news.news_title}</strong>&nbsp;( <a href="javascript:void(0);" onclick='{literal}Dialog.alert({url: "index.php?mod=news&amp;sub=admin&amp;action=show_single&amp;id={/literal}{$news.news_id}{literal}", options: {method: "get"}}, {className: "alphacube", width:540, okLabel: "{/literal}{t}Close{/t}{literal}"});{/literal}'>fast view</a> )
        </td>
        <td>{$news.cat_name}</td>
        <td><a href='index.php?mod=admin&amp;sub=users&amp;action=edit&amp;id={$news.user_id}'>{$news.nick}</a></td>
        <td>
        {if $news.draft == 1}
            {t}unpublished{/t}
        {else}
            {t}published{/t}
        {/if}
        </td>
        <td align="center">
            <input class="ButtonGreen" type="button" value="{t}Edit{/t}" onclick='{literal}Dialog.info({url: "index.php?mod=news&amp;sub=admin&amp;action=edit&amp;id={/literal}{$news.news_id}{literal}", options: {method: "get"}}, {className: "alphacube", width:900, height: 600});{/literal}' />
        </td>
        <td align="center">
            <input type="checkbox" value="{$news.news_id}" name="delete[]" />
            <input type="hidden" value="{$news.news_id}" name="ids[]" />
        </td>
    </tr>
    {/foreach}
    <tr class="tr_row1">
        <td colspan="9" align="right">
        <input onclick="self.location.href='index.php?mod=news&amp;sub=admin&amp;action=create'" class="ButtonGreen" type="button" value="{t}Create News{/t}" />
        <input class="ButtonGrey" type="reset" name="reset" value="{t}Reset{/t}" />
        <input class="ButtonRed" type="submit" name="submit" value="{t}Delete{/t}" />
        </td>
    </tr>
</table>
</form>
<table border="0" cellpadding="0" cellspacing="0" width="800" align="center">
    <tr class="tr_header_small">
        <td>
        <div style="float:left;">
            <img src="{$www_root_tpl_core}/images/icons/page_edit.png" style="height:16px;width:16px" alt="" />
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