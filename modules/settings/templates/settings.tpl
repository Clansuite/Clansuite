{* {$config|@var_dump} *}
{* {modulenavigation} *}
<div class="ModuleHeading">{t}Clansuite Settings{/t}</div>
<div class="ModuleHeadingSmall">{t}Konfiguration des Systems.{/t}</div>

<form action="index.php?mod=settings&amp;sub=admin&amp;action=update" method="post" accept-charset="UTF-8">

    {tabpanel name="Settings"}
       {tabpage name="Standard"}    {include file='tabpage-standard.tpl'}   {/tabpage}
       {tabpage name="Meta Tags"}   {include file='tabpage-metatags.tpl'}   {/tabpage}
       {tabpage name="Language"}    {include file='tabpage-language.tpl'}   {/tabpage}
       {tabpage name="Email"}       {include file='tabpage-email.tpl'}      {/tabpage}
       {tabpage name="Login"}       {include file='tabpage-login.tpl'}      {/tabpage}
       {tabpage name="Developers"}  {include file='tabpage-developer.tpl'}  {/tabpage}
       {tabpage name="Date & Time"} {include file='tabpage-datetime.tpl'}   {/tabpage}
       {tabpage name="Cache"}       {include file='tabpage-cache.tpl'}      {/tabpage}
       {tabpage name="Updates"}     {include file='tabpage-updates.tpl'}    {/tabpage}
       {tabpage name="Minifer"}     {include file='tabpage-minifier.tpl'}    {/tabpage}
    {/tabpanel}

    <br />

    <div style="text-align:center">

        <input type="submit" class="ButtonGreen" value="{t}Save Settings{/t}" name="submit" />

    </div>

</form>