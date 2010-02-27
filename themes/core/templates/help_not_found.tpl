<!-- Start Template help_not_found.tpl -->

{move_to target="pre_head_close"}
<link rel="stylesheet" type="text/css" href="{$www_root_themes_core}/css/error.css" />
{/move_to}

<div class="error_helpbox">
    <strong>{t}A helptext for this module is {/t} <u> {t}not existing{/t} </u> !</strong>
</div>

{if $smarty.const.DEBUG AND $smarty.const.DEVELOPMENT}
<div class="create_helpbox">
        {t}You can create a helptext in the{/t}
        <a href="{$www_root}/index.php?mod=templatemanager&amp;sub=admin&amp;action=edit&amp;file={$modulename}/templates/help.tpl&amp;tplmod={$modulename}">Templateeditor</a>
        {t} now.{/t}
</div>
{/if}
<!-- End Template help_not_found.tpl -->