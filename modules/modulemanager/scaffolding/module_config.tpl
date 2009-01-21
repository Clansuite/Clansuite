; <?php die( 'Access forbidden.' ); /* DO NOT MODIFY THIS LINE! ?>
;
; {$mod.module_name|capitalize} - Module Configuration File
;
[{$mod.module_name}]
{foreach from=$mod.config.config_keys item=item key=key}
{if isset($item) AND $item != '' AND isset($mod.config.config_values.$key) AND $mod.config.config_values.$key != ''}
{$item} = {$mod.config.config_values.$key}
{/if}
{/foreach}

; DO NOT REMOVE THIS LINE */ ?>