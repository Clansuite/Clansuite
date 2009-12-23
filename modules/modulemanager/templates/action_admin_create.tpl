{*  *}

{$mod|@var_dump}

{modulenavigation}
<div class="ModuleHeading">{t}Modulemanager{/t}</div>
<div class="ModuleHeadingSmall">{t}You are about to create the following module.{/t}</div>

<br />

You created the Module: {$mod.modulename} successfully.

The following Files were written:

{if isset($mod.frontend.checked)}
You can call the <a href="{$www_root}/index.php&mod={$mod.modulename}">Module Frontend</a>
{/if}

{if isset($mod.backend.checked)}
You can call the <a href="{$www_root}/index.php&mod={$mod.modulename}&usb=admin">Module Backend</a>.
{/if}