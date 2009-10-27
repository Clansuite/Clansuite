{* {$widget_newscategories_dropdown|@var_dump} *}

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
            {$newscategory.CsCategories.name} ({$newscategory.sum})
        </option>
      {/foreach}

      </select>
    </label>
</form>