{modulenavigation}
<div class="ModuleHeading">{t}Languages - Show{/t}</div>
<div class="ModuleHeadingSmall">{t}You can edit, delete, search and add language items.{/t}</div>

{move_to target="pre_head_close"}
<link rel="stylesheet" href="{$www_root_core}javascript/thickbox/thickbox.css" type="text/css" media="screen"/>
<link rel="stylesheet" href="{$www_root_mod}languages.css" type="text/css" media="screen"/>
{/move_to}

{tabpanel name="Languages"}
    {tabpage name="Core"}     {include file='languages-core.tpl'}   {/tabpage}
    {tabpage name="Modules"}  {include file='languages-modules.tpl'}   {/tabpage}
    {tabpage name="Themes"}   {include file='languages-themes.tpl'}   {/tabpage}
{/tabpanel}