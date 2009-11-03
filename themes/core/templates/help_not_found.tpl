<!-- Start Template help_not_found.tpl -->

{literal}
<style type="text/css">
/* this defines the look of the red error box, for example: when an template is missing */
.error_help {
    background:#FFDDDD url({/literal}{$www_root_themes_core}{literal}/images/icons/error.png) no-repeat scroll 15px 12px;
    border:1px solid #FFBBBB;
    color:#BB0000;
    font-weight:bold;
    margin:0.5em 15px 1em -20px;
    padding:15px 20px 15px 50px;
}

/* this defines the look of box, providing the link to the editor, if an template is missing */
.create_help {
    background:#DDFFDD url({/literal}{$www_root_themes_core}{literal}/images/icons/page_edit.png) no-repeat scroll 15px 12px;
    border:1px solid #BBFFBB;
    color:#00BB00;
    font-weight:bold;
    margin:0.5em 15px 1em -20px;
    padding:15px 20px 15px 50px;
}
</style>
{/literal}

<div class="error_help">
    <strong>{t}A helptext for this module is {/t} <u> {t}not existing{/t} </u> !</strong>
</div>

{if $smarty.const.DEBUG AND $smarty.const.DEVELOPMENT}
<div class="create_help">
        {t}You can create a helptext in the{/t}
        <a href="{$www_root}/index.php?mod=templatemanager&amp;sub=admin&amp;action=editor&amp;file={$template_of_module}/templates/help.tpl&amp;tplmod={$template_of_module}">Templateeditor</a>
        {t} now.{/t}
</div>
{/if}
<!-- End Template help_not_found.tpl -->