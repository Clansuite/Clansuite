Accessible Theme - Module Index - Show

<br />

<strong>{t}Hello{/t}</strong><br />
<strong>{t}Welcome{/t}</strong><br />
<br />
{* {$smarty|var_dump} *}
<em> {t 1=$smarty.session.user.nick}How are you, %1 ?{/t}</em>

<p>
<br />
<strong>This demonstrates gettext-Support with Locales</strong>
<br />
{t}Hello World{/t}
<br />
{t name=$smarty.session.user.nick}How are you, %1 ?{/t}
<br />
{t 1='one' 2='two' 3='three'}The 1st parameter is %1, the 2nd is %2 and the 3rd %3.{/t}
</p>
