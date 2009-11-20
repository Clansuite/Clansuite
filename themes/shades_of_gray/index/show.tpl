{* Demo-Assignment von XML-Sprachdaten *}
<strong>{t}Hello{/t}</strong><br />
<strong>{t}Welcome{/t}</strong><br />
<br />

<em> {translate u=`$smarty.session.user.nick`}How are you, %u ?{/t}</em>

<br />
{* direktes Ansprechen der Sprachzuweisung *}
{* class language :: function t t(); *} 
<br />
In der LanguageDatei existiert eine &Uuml;bersetzung f&uuml;r "Hello":
{php} print(language::t("Hello")); {/php} 

<br />
In der LanguageDatei existiert eine &Uuml;bersetzung f&uuml;r "You are redirected":
{php} print(language::t("You are redirected")); {/php}
