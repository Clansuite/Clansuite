{* {$news_widget|var_dump} *}

<!-- Start News Widget from Module News /-->

<table class="news_widget" width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td class="td_header" colspan="2">
            {t}Recent news{/t}
        </td>
    </tr>
    <tr>
        <td class="td_header_small">Titel</td>
        <td class="td_header_small" width="70">Datum</td>
    </tr>
    {foreach item=news_widget from=$news_widget}
    <tr>
        <td class="cell1" ><a href="index.php?mod=news&action=showone&id={$news_widget.news_id}">{$news_widget.news_title}</a></td>
        <td class="cell2" width="70">{$news_widget.created_at|date_format}</td>
    </tr>
    {/foreach}
</table>

<!-- Ende News Widget //-->