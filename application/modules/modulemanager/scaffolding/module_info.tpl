; <?php die( 'Access forbidden.' ); /* DO NOT MODIFY THIS LINE! ?>
;
; Clansuite Informations File for the Module {$mod.module_name|capitalize}
;

[{$mod.modulename}_info]
name = {$mod.module_name|capitalize}
description = {$mod.meta.description}
author = {$mod.meta.author}
license = {$mod.meta.license}
link =  {$mod.meta.website}

[{$mod.modulename}_package]
name = {$mod.module_name|capitalize}
tye = {$mod.meta.type}
version = {$mod.meta.initialversion}
created = {$startDate|date_format:"%d.%m.%Y"}
uniqueid =
updateurl =

[{$mod.modulename}_dependencies]
dependencies = {$mod.dependencies.website}

; DO NOT REMOVE THIS LINE */ ?>