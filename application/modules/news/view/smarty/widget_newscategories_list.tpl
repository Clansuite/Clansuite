{* {$widget_newscategories_list|var_dump} *}

<!-- Start Widget NewsCategoriesList from Module News -->

<div class="news_widget" id="widget_topmatch">

    <h2 class="menu_header"> {t}News Categories{/t}</h2>

    <div class="cell1">

        <ul>
        {foreach item=newscategory from=$widget_newscategories_list}
        <li>
            <a href="{$www_root}index.php?mod=news&action=show&page=1&cat={$newscategory.cat_id}"> {$newscategory.name} ({$newscategory.sum_news})</a>
        </li>
        {/foreach}
        </ul>

    </div>

</div>

<!-- End NewsCategoriesList Widget from Module News -->