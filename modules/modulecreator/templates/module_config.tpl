; <?php die( 'Access forbidden.' ); /* DO NOT MODIFY THIS LINE! ?>
; 
; {$m.module_name|capitalize} configuration file
;
[{$m.module_name}]
{foreach from=$m.config.config_keys item=item key=key}
{$item} = {$m.config.config_values.$key}
{/foreach}


; DO NOT REMOVE THIS LINE */ ?>