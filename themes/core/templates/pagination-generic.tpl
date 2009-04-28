<!-- Pagination -->
<div>
        <!-- Pagination Icon -->
        <img src="{$www_root_themes_core}/images/icons/page_edit.png" alt="" />

        {* First Section of the Pagination, like: [1][2] Page 1/2 *}
        {$pager_layout->display('',true)} - {t}Page{/t} {$pager->getPage()}/{$pager->getLastPage()}.

        {* Second Section of the Pagination, like: Displaying Items 1 to 3 of 3 total (with 3 per page). *}
        {assign var=resultsInPage value=$pager->getResultsInPage()}
        {assign var=paginate_currentpage value=$pager->getPage()}

        {if $pager->getPage() eq $pager->getLastPage()}
         {assign var=itemsOnPage value=$pager->getNumResults()}
        {else}
         {assign var=itemsOnPage value=`$paginate_currentpage*$resultsInPage`}
        {/if}

        {assign var=itemsFrom value=`$itemsOnPage+1-$resultsInPage`}

        {if $pager->haveToPaginate() gt 0}
         <span>{t 1=`$itemsFrom` 2=`$itemsOnPage` 3=`$itemsOnPage` 4=`$resultsInPage`}Displaying Items %1 to %2 of %3 total (with %4 per page).{/t}</span>
        {elseif $pager->getResultsInPage() eq 1}
          {t}1 Item displayed.{/t}
        {else}
          {t 1=`$pager->getResultsInPage()`}Items 1 to %1 displayed.{/t}
        {/if}
</div>