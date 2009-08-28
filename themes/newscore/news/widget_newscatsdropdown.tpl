  {* {$widget_newscatsdropdown|@var_dump} *}

<form action="">
  <label>
      <select name="newscatsdropdown" id="newscatsdropdown" size=1
              onchange="top.location.href=this.options[this.selectedIndex].value;">
    
      {* First Item in Options *}
      <option value="{$www_root}/index.php?mod=news&action=show">- {t}All{/t} -</option>

      {foreach item=widget_newscatsdropdown from=$widget_newscatsdropdown}
        
        <option value="{$www_root}/index.php?mod=news&action=show&cat={$widget_newscatsdropdown.cat_id}">
            {$widget_newscatsdropdown.CsCategories.name} ({$widget_newscatsdropdown.sum})
        </option>
      {/foreach}

      </select>
    </label>
</form>