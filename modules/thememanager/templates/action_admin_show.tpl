{* {$themes|@var_dump} *}

{*{modulenavigation}*}
<div class="ModuleHeading">{t}Thememanager{/t}</div>
<div class="ModuleHeadingSmall">{t}Themes change the look of our Clansuite website. You can change the default theme, set a new theme for all your members or install a new theme.{/t}</div>

{tabpanel name="tplmanager"}
    {tabpage name="Frontend"}   {include file='tabpage-frontend.tpl'}  {/tabpage}
    {tabpage name="Backend"}    {include file='tabpage-backend.tpl'}   {/tabpage}
{/tabpanel}