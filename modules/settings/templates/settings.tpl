{* {$config|@var_dump} *}

<form action="index.php?mod=controlcenter&amp;sub=settings&amp;action=update" method="post" accept-charset="UTF-8">

    {tabpane name="Settings"}
       {tabpage name="Standard"}    {include file='tabpage-standard.tpl'}   {/tabpage}
       {tabpage name="Meta Tags"}   {include file='tabpage-metatags.tpl'}   {/tabpage}
       {tabpage name="Language"}    {include file='tabpage-language.tpl'}   {/tabpage}
       {tabpage name="Email"}       {include file='tabpage-email.tpl'}      {/tabpage}
       {tabpage name="Login"}       {include file='tabpage-login.tpl'}      {/tabpage}
       {tabpage name="Developers"}  {include file='tabpage-developer.tpl'}  {/tabpage}
       {tabpage name="Date & Time"} {include file='tabpage-datetime.tpl'}   {/tabpage}
       {tabpage name="Cache"}       {include file='tabpage-cache.tpl'}      {/tabpage}
       {tabpage name="Updates"}       {include file='tabpage-updates.tpl'}    {/tabpage}
    {/tabpane}

    <br />

    <div style="text-align:center">
    <input type="submit" class="ButtonGreen" value="{t}Save Settings{/t}" name="submit" />
    </div>

</form>