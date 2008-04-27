<div class="paginate">
    
    <div class="description">
            <img class="img" src="{$www_root_themes_core}/images/icons/page_edit.png" alt="" />
            {$pagination_links} - Seite {$paginate_currentpage}/{$paginate_lastpage}.
            
           {if $pagination_needed gt 0}
            {assign var=itemsOnPage value=`$paginate_currentpage*$paginate_maxperpage`} 
             <span class="inline_text">Items {$itemsOnPage}-{$paginate_totalitems} (with {$paginate_resultsperpage} per page).</span>
            {else}
              Item {$paginate_first} of {$paginate_totalitems} displayed.
            {/if}
    </div>    
    <div class="size">
        
    </div>
</div>