{* {$widget_newscategories_dropdown|@var_dump} *}

<!-- Start Start Widget NewsCategoriesDropDown from Module News -->

<div class="news_widget" id="widget_newscategoriesdropdown" width="100%">

    <h2 class="td_header"> {t}News Categories{/t}</h2>
    
    <div class="cell1">
    
        <form action="">
          <label>
              <select name="newscatsdropdown" id="newscatsdropdown" size=1
                      onchange="top.location.href=this.options[this.selectedIndex].value;">

              {* First Item in Options *}
              <option>- {t}Choose{/t} -</option>

              {* Second is all Cats (normal news display) *}
              <option value="{$www_root}/index.php?mod=news&action=show">- {t}All{/t} -</option>

              {foreach item=newscategory from=$widget_newscategories_dropdown}

                <option value="{$www_root}/index.php?mod=news&action=show&cat={$newscategory.cat_id}">
                    {$newscategory.CsCategories.name} ({$newscategory.sum_news})
                </option>
              {/foreach}

              </select>
            </label>
        </form>
      
      </div>
            
</div>

<!-- End Start Widget NewsCategoriesDropDown from Module News -->