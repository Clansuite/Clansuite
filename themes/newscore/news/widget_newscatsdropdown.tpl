  {* {$widget_newscatsdropdown|@var_dump} *}
  
<form action="">
  <label>
  <select id="newscatsdropdown" size=1 name="Auswahl">
  {foreach item=widget_newscatsdropdown from=$widget_newscatsdropdown}
    <option value="{$www_root}/index.php?mod=news&action=show&page=1&cat={$widget_newscatsdropdown.cat_id}">{$widget_newscatsdropdown.CsCategories.name} ({$widget_newscatsdropdown.sum})</option>
  {/foreach}
  </select>
<input type="submit" name="button" id="button" value="Anzeigen"/>
</label>
</form>