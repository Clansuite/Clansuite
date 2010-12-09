<!-- BEGIN: main -->

{modulenavigation}
<div class="ModuleHeading">{t}{$modulename} - Settings{/t}</div>
<div class="ModuleHeadingSmall">{t}You can control and configure the behaviour of the {$modulename} Module by the settings provided here.{/t}</div>

{if $error.mod_config_not_writeable == 1}<p class="error">{t}File{/t}: ($modulename}.config.php {t}ist not writeable. Please make it writeable!{/t}</p>{/if}
{if $success.mod_config_success == 1}<p class="info">($modulename}.config.php {t}changed succesfully{/t}</p>{/if}

<form action="index.php?mod=modulesettings&amp;sub=admin&amp;action=show&modulename={$modulename}" method="post" accept-charset="UTF-8">

    {tabpanel name="Settings"}
       {tabpage name="Configuration"}    {include file='tabpages/config.tpl'}   {/tabpage}
       {tabpage name="Information"}    {include file='tabpages/info.tpl'}   {/tabpage}
       {tabpage name="Routes"}    {include file='tabpages/routes.tpl'}   {/tabpage}
       {tabpage name="Permission"}    {include file='tabpages/permission.tpl'}   {/tabpage}
       {tabpage name="Language"}    {include file='tabpages/language.tpl'}   {/tabpage}
       {tabpage name="Templates"}    {include file='tabpages/templates.tpl'}   {/tabpage}
    {/tabpanel}

    <br />

    <div style="text-align:center">

        <input type="submit" class="ButtonGreen" value="{t}Save Settings{/t}" name="submit" />

    </div>

</form>

<!-- END: main -->