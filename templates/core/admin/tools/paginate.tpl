<table border="0" cellpadding="0" cellspacing="0" align="center">
    <tr class="tr_header_small">
        <td>
        <div style="float:left;">
            <img src="{$www_core_tpl_root}/images/icons/page_edit.png" style="height:16px;width:16px" alt="" /> 
            {if $paginate.size gt 1}
              Items {$paginate.first}-{$paginate.last} of {$paginate.total} displayed.
            {else}
              Item {$paginate.first} of {$paginate.total} displayed.    
            {/if}  
        </div>
        <span style="float:right;">
            {* display pagination info *}
            {paginate_prev text="&lt;&lt;"} {paginate_middle format="page"}  {paginate_next text="&gt;&gt;"}
        </span>       
        </td>
    </tr>
</table>