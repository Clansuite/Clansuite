{* {$widget_newscategories_dropdown|@var_dump} *}

<!-- Start News-Categories Selection Widget from Theme Newscore /-->

<div class="widget_head">
    <span class="widget_title">News Categories</span>
</div>

<form action="">
  <label>
      <select name="newscatsdropdown" id="newscatsdropdown" size=1
              onchange="top.location.href=this.options[this.selectedIndex].value;">

      {* First Item in Options *}
      <option>- {t}Choose{/t} -</option>

      {* Second is all Cats (normal news display) *}
      <option value="{$www_root}/index.php?mod=news&action=show">- {t}All{/t} -</option>

      {foreach item=widget_newscategories_dropdown from=$widget_newscategories_dropdown}

        <option value="{$www_root}/index.php?mod=news&action=show&cat={$widget_newscategories_dropdown.cat_id}">
            {$widget_newscategories_dropdown.CsCategories.name} ({$widget_newscategories_dropdown.sum})
        </option>
      {/foreach}

      </select>
    </label>
</form>

<!-- Ende News-Categories Selection Widget from Theme Newscore /-->