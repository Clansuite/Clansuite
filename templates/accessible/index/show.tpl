{* Demo-Assignment von XML-Sprachdaten *}
<strong>{translate}Hello{/translate}</strong><br />
<strong>{translate}Welcome{/translate}</strong><br />
<br />

<em> {translate u=`$smarty.session.user.nick`}How are you, %u ?{/translate}</em>

<br />
{* direktes Ansprechen der Sprachzuweisung *}
{* class language :: function t t(); *} 
<br />
In der LanguageDatei existiert eine &Uuml;bersetzung f&uuml;r "Hello":
{php} print(language::t("Hello")); {/php} 

<br />
In der LanguageDatei existiert eine &Uuml;bersetzung f&uuml;r "You are redirected":
{php} print(language::t("You are redirected")); {/php}
