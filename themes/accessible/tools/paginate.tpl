{* Pagination Placeholders

Links: {$pagination_links}
<br/>
Aktuelle Seite: {$paginate_currentpage}
<br/>
Letzte Seite: {$paginate_lastpage}
<br/>
Anzahl Elemente Total: {$paginate_totalitems}
<br/>
Anzahl Elemente Seite: {$paginate_resultsinpage}
<br/>
Anzahl Elemente max: {$paginate_maxperpage}
*}

<div class="paginate">

    <div class="description">
            <img class="img" src="{$www_root_themes_core}/images/icons/page_edit.png" alt="" />
            {$pagination_links} - Seite {$paginate_currentpage}/{$paginate_lastpage}.

            {if $paginate_currentpage eq $paginate_lastpage}
             {assign var=itemsOnPage value=`$paginate_totalitems`}
             {assign var=itemsFrom value=`$paginate_totalitems+1-$paginate_resultsinpage`}
            {else}
             {assign var=itemsOnPage value=`$paginate_currentpage*$paginate_resultsinpage`}
             {assign var=itemsFrom value=`$itemsOnPage+1-$paginate_resultsinpage`}
            {/if}

            {if $pagination_needed gt 0}
             <span class="inline_text">Displaying Items {$itemsFrom} to {$itemsOnPage} of {$paginate_totalitems} total (with {$paginate_resultsinpage} per page).</span>
            {elseif $paginate_totalitems eq 1}
              1 Item displayed.
            {else}
              Items 1 to {$paginate_totalitems} displayed.
            {/if}
    </div>
    <div class="size">

    </div>
</div>