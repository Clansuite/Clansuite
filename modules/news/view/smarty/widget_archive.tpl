{* {$widget_archive|var_dump} *}

<!-- Start News Archiv Widget from Module News -->

<div class="news_widget" id="widget_newsarchiv" width="100%">

    <h2 class="menu_header"> {t}News Archive{/t}</h2>
    
    <div class="cell1">

    {* Years *}
    {foreach key=year item=year_archiv from=$widget_archive}
    <a href="{$smarty.server.PHP_SELF}?mod=news&action=archive&date={$year}">{$year}</a>
    
        {* Months *}
        {foreach key=month item=month_archiv from=$year_archiv}
        
            <br/>
            <a href="{$smarty.server.PHP_SELF}?mod=news&action=archive&date={$year}-{$month}">{$month}</a>
    
            {* Number of Entries *}
            {foreach item=entry from=$month_archiv name=parent}
                {if $smarty.foreach.parent.last}
                     ({$smarty.foreach.parent.total})
                {/if}
            {/foreach}
        {/foreach}
        <br />
    {/foreach}
    
    </div>

</div>

<!-- End News Archiv Widget from Theme Newscore -->