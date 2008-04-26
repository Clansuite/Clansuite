<div class="paginate">
    
    <div class="description">
            <img class="img" src="{$www_root_themes_core}/images/icons/page_edit.png" alt="" />
            {$pagination_links} - Seite {$paginate_currentpage}/{$paginate_lastpage}.
            
            {* todo else *}
            {if $pagination_needed gt 0}
             <span class="inline_text">Items {$paginate_currentpage} to {$paginate_resultsperpage} of {$paginate_totalitems} displayed.</span>
            {else}
              Item {$paginate.first} of {$paginate_totalitems} displayed.
            {/if}
    </div>    
    <div class="size">
        
    </div>
</div>