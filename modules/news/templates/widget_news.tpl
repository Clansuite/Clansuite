{* {$news_widget|var_dump} *}

<!-- News Widget from Standard Theme /-->

<!-- Start News Widget //-->

<table class="news_widget_info" width="100%" width="100%" cellpadding="0" cellspacing="0">
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
        <td class="cell2" width="70">{$news_widget.news_added}</td>
    </tr>
    {/foreach}
</table>
    
<!-- Ende News Widget //-->