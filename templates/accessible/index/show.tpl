{* Demo-Assignment von XML-Sprachdaten *}
<strong>{translate}Hello{/translate}</strong><br />
<strong>{translate}Welcome{/translate}</strong><br />
<br />



Ersetzung mit einfacher $www_root ( {$www_root} ) an %u (dynamischer Platzhalter) im Languagestring. [ok]
<br />
<em>{translate u="$www_root"}How are you, %u ?{/translate}</em>


<br />



Ersetzung mit $session.user.nick ( {$session.user.nick} {$session.client_browser} ) an %u (dynamischer Platzhalter) im Languagestring.
[fails]
<br />
<em>{translate u="$session.user.nick"}How are you, %u ?{/translate}</em>



<br /><br />
{* direktes Ansprechen der Sprachzuweisung *}
{* class language :: function t t(); *} 
<br />
In der LanguageDatei existiert eine &Uuml;bersetzung f&uuml;r "Hello":
{php} print(language::t("Hello")); {/php} 

<br />
In der LanguageDatei existiert eine &Uuml;bersetzung f&uuml;r "You are redirected":
{php} print(language::t("You are redirected")); {/php}
