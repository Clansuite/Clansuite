{* Pagination Placeholders

// Returns the check if Pager was already executed
$pager->getExecuted();

// Return the total number of itens found on query search
$pager->getNumResults();

// Return the first page (always 1)
$pager->getFirstPage();

// Return the total number of pages
$pager->getLastPage();

// Return the current page
$pager->getPage();

// Defines a new current page (need to call execute again to adjust offsets and values)
$pager->setPage($page);

// Return the next page
$pager->getNextPage();

// Return the previous page
$pager->getPreviousPage();

// Return true if it's necessary to paginate or false if not
$pager->haveToPaginate();

// Return the maximum number of records per page
$pager->getMaxPerPage();

// Defined a new maximum number of records per page (need to call execute again to adjust offset and values)
$pager->setMaxPerPage($maxPerPage);

// Returns the number of itens in current page
$pager->getResultsInPage();

// Returns the Doctrine_Query object that is used to make the count results to pager
$pager->getCountQuery();

// Defines the counter query to be used by pager
$pager->setCountQuery($query, $params = null);

// Returns the params to be used by counter Doctrine_Query (return $defaultParams if no param is defined)
$pager->getCountQueryParams($defaultParams = array());

// Defines the params to be used by counter Doctrine_Query
$pager->setCountQueryParams($params = array(), $append = false);

// Return the Doctrine_Query object
$pager->getQuery();

*}
<div>
    <div>
            <img src="{$www_root_themes_core}/images/icons/page_edit.png" alt="" />
            {$pager_layout->display('',true)} - Seite {$pager->getPage()}/{$pager->getLastPage()}.

            {assign var=resultsInPage value=$pager->getResultsInPage()}
            {assign var=paginate_currentpage value=$pager->getPage()} 

            {if $pager->getPage() eq $pager->getLastPage()}     
             {assign var=itemsOnPage value=$pager->getNumResults()}
            {else}                        
             {assign var=itemsOnPage value=`$paginate_currentpage*$resultsInPage`}             
            {/if}
            
            {assign var=itemsFrom value=`$itemsOnPage+1-$resultsInPage`}

            {if $pager->haveToPaginate() gt 0}
             <span>Displaying Items {$itemsFrom} to {$itemsOnPage} of {$pager->getNumResults()} total (with {$pager->getResultsInPage()} per page).</span>
            {elseif $pager->getResultsInPage() eq 1}
              1 Item displayed.
            {else}
              Items 1 to {$pager->getResultsInPage()} displayed.
            {/if}
    </div>
</div>