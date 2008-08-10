; <?php die( 'Access forbidden.' ); /* DO NOT MODIFY THIS LINE! ?>
; 
; {$mod.meta.title} configuration file
;
[{$mod.module_name}]
{foreach from=$mod.config.config_keys item=item key=key}
{$item} = {$mod.config.config_values.$key}
{/foreach}


; DO NOT REMOVE THIS LINE */ ?>