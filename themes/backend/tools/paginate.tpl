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

<table border="0" cellpadding="0" cellspacing="0" align="center">
    <tr class="tr_header_small">
        <td>
        <div style="float:left;">
            <img class="img" src="{$www_root_themes_core}/images/icons/page_edit.png" alt="" />            

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
        <span style="float:right;">
        {* display pagination info *}
        {$pagination_links} - Seite {$paginate_currentpage}/{$paginate_lastpage}.
        </span>    
        </td>
    </tr>
</table>