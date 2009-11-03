<!-- Start Pagination -->
<div id="pagination">
        <!-- Pagination Icon -->
        <img src="{$www_root_themes_core}/images/icons/page_edit.png" alt="" />

        {assign var=resultsInPage value=$pager->getResultsInPage()}

        {if $pager->haveToPaginate() gt 0}

            {assign var=numResults value=$pager->getNumResults()}
            {assign var=firstIndice value=$pager->getFirstIndice()}
            {assign var=lastIndice value=$pager->getLastIndice()}

            {* First Section of the Pagination, like: [1][2] Page 1/2 *}
            {$pager_layout->display('',true)} - {t}Page{/t} {$pager->getPage()}/{$pager->getLastPage()}.

            {* Second Section of the Pagination, like: Displaying Items 1 to 3 of 3 total (with 3 per page). *}
            <span>{t 1=`$firstIndice` 2=`$lastIndice` 3=`$numResults` 4=`$resultsInPage`}Displaying Items %1 to %2 of %3 total (with %4 per page).{/t}</span>

        {elseif $pager->getResultsInPage() eq 1}

          {t}Displaying 1 Item.{/t}

        {elseif $pager->getNumResults() gt 1}

          {t 1=`$resultsInPage`}Items 1 to %1 displayed.{/t}
 
        {else}

          {* No Pagination Display *}

        {/if}
</div>
<!-- End Pagination  -->