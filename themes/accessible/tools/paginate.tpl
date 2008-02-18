<div class="paginate">
        <div class="description">
            <img class="img" src="{$www_root_tpl_core}/images/icons/page_edit.png" alt="" />
            {if $paginate.size gt 0}
              <span class="inline_text">Items {$paginate.first}-{$paginate.last} of {$paginate.total} displayed.</span>
            {else}
              Item {$paginate.first} of {$paginate.total} displayed.
            {/if}
        </div>
        <div class="size">
            {* display pagination info *}
            <span class="inline_text">{paginate_prev text="&lt;&lt;"} {paginate_middle format="page"}  {paginate_next text="&gt;&gt;"}</span>
        </div>
</div>