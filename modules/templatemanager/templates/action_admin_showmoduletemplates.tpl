{modulenavigation}
<div class="ModuleHeading">{t}Template - Administration{/t}</div>
<div class="ModuleHeadingSmall">{t}You can write, edit and delete them by using the Templateeditor.{/t}</div>

You have selected the templates of module: {$templateeditor_modulename}

{foreach from=$templates item=template}

{$template.filename}
<br />
<a href="/index.php?mod=templatemanager&amp;sub=admin&amp;action=editor&amp;file=" type="button" class="delete" title="Edit Templates">Edit {$template.filename}</a>

{/foreach}