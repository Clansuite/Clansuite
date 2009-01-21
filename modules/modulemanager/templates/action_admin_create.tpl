{* {$mod|@var_dump} *}

You created the Module: {$mod.module_name} successfully.

The following Files were written:

{if $mod.frontend.checked}
You can call the <a href="{$www_root}/index.php&mod={$mod.module_name}">Module Frontend</a>
{/if}

{if $mod.backend.checked}
You can call the <a href="{$www_root}/index.php&mod={$mod.module_name}&usb=admin">Module Backend</a>.
{/if}