{* {$themes|@var_dump} *}

{*{modulenavigation}*}
<div class="ModuleHeading">{t}Thememanager{/t}</div>
<div class="ModuleHeadingSmall">{t}Themes ver�ndern das Aussehen Ihrer Clansuite Webseite. Sie k�nnen hier das Standard-Theme �ndern, ein Theme f�r alle Mitglieder festlegen oder neue Themes installieren.{/t}</div>

{tabpanel name="tplmanager"}
	{tabpage name="Frontend"}   {include file='tabpage-frontend.tpl'}  {/tabpage}
	{tabpage name="Backend"}    {include file='tabpage-backend.tpl'}   {/tabpage}
{/tabpanel}