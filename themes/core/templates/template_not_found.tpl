{literal}
<style type="text/css">
.error {
    background:#FFDDDD url({/literal}{$www_root_themes_core}{literal}/images/icons/error.png) no-repeat scroll 15px 12px;
    border:1px solid #FFBBBB;
    color:#BB0000;
    font-weight:bold;
    margin:0.5em 0 1em;
    padding:15px 20px 15px 50px;
}

.create {
    background:#DDFFDD url({/literal}{$www_root_themes_core}{literal}/images/icons/page_edit.png) no-repeat scroll 15px 12px;
    border:1px solid #BBFFBB;
    color:#00BB00;
    font-weight:bold;
    margin:0.5em 0 1em;
    padding:15px 20px 15px 50px;
}
</style>
{/literal}

<div class="error">
    <strong>{t}The Template for the module & action you requested was{/t} <u> {t}not found{/t}</u> !</strong>
</div>

{if $smarty.const.DEBUG AND $smarty.const.DEVELOPMENT}
<div class="create">
        {t}You can create this template directly in the{/t}
        <a href="{$www_root}/index.php?mod=templatemanager&amp;sub=admin&amp;action=editor&amp;tpl={$template_to_render}">Templateeditor</a>
        {t} now.{/t}
</div>
{/if}