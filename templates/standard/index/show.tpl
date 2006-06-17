{* Demo-Assignment von XML-Sprachdaten *}
<b>{translate}Hello{/translate}</b><br />
<b>{translate}Welcome{/translate}</b><br />
<i>{translate u="Benutzernamen_variable"}How are you, %u ?{/translate}</i>

{* direktes Ansprechen der Sprachzuweisung *}
{* class language :: function t t(); *} 
<br />
In der LanguageDatei existiert eine Übersetzung für "Hello":
{php} print(language::t("Hello")); {/php} 

<br />
In der LanguageDatei existiert eine Übersetzung für "You are redirected":
{php} print(language::t("You are redirected")); {/php}
