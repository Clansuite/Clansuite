{* {$widget_archiv|@var_dump} *}

<!-- Start News Archiv Widget from Theme Newscore /-->

<div class="widget_head">
    <span class="widget_title">Newsarchiv</span>
</div>

{* Years *}
{foreach key=year item=year_archiv from=$widget_archiv}
<a href="{$smarty.server.PHP_SELF}?mod=news&action=action_archiv&date={$year}">{$year}</a>

    {* Months *}
    {foreach key=month item=month_archiv from=$year_archiv}
    
        <br/>
        <a href="{$smarty.server.PHP_SELF}?mod=news&action=archiv&date={$year}-{$month}">{$month}</a>

        {* Number of Entries *}
        {foreach item=entry from=$month_archiv name=parent}
            {if $smarty.foreach.parent.last}
                 ({$smarty.foreach.parent.total})
            {/if}
        {/foreach}
    {/foreach}
    <br />
{/foreach}

<!-- Ende News Archiv Widget from Theme Newscore /-->