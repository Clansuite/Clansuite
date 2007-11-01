{* Demo-Assignment von XML-Sprachdaten *}
<strong>{translate}Hello{/translate}</strong><br />
<strong>{translate}Welcome{/translate}</strong><br />
<br />
{* {$smarty|@var_dump} *}
<em> {translate u=`$smarty.session.user.nick`}How are you, %u ?{/translate}</em>

<br />
{* direktes Ansprechen der Sprachzuweisung *}
{* class language :: function t t(); *} 
<br />
In der Language-Datei existiert eine &Uuml;bersetzung f&uuml;r "Hello":
<br /> 
1. indirekt mit php language::t == {php} print(language::t("Hello")); {/php} 
<br />
2. direkt mit smarty block function (translate) == {translate}Hello{/translate}

<br /><br />
In der Language-Datei existiert eine &Uuml;bersetzung f&uuml;r "You are redirected":
{php} print(language::t("You are redirected")); {/php}

<p>
<br />
<strong>This demonstrates gettext-Support with Locales</strong>
<br />
{t}Hello World{/t}
<br />
{t name=`$smarty.session.user.nick`}How are you, %1 ?{/t}
<br />
{t 1='one' 2='two' 3='three'}The 1st parameter is %1, the 2nd is %2 and the 3rd %3.{/t}
</p>
