{* {$widget_latestnews|var_dump} *}

<!-- Start News Widget from Module News /-->

<table class="latestnews_widget" width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td class="td_header" colspan="2">
            {t}Recent news{/t}
        </td>
    </tr>
    <tr>
        <td class="td_header_small">Titel</td>
        <td class="td_header_small" width="70">Datum</td>
    </tr>
    {foreach item=news_item from=$widget_latestnews}
    <tr>
        <td class="cell1" ><a href="index.php?mod=news&action=showone&id={$news_item.news_id}">{$news_item.news_title}</a></td>
        <td class="cell2" width="70">{$news_item.created_at|date_format}</td>
    </tr>
    {/foreach}
</table>

<!-- Ende News Widget //-->
