{* Demo-Assignment von XML-Sprachdaten *}
<b>{t}Hello{/t}</b><br />
<b>{t}Welcome{/t}</b><br />
<i>{translate u="Benutzernamen_variable"}How are you, %u ?{/t}</i>
{$base_url}
{* direktes Ansprechen der Sprachzuweisung *}
{* class language :: function t t(); *} 
<br />
In der LanguageDatei existiert eine Übersetzung für "Hello":
{php} print(language::t("Hello")); {/php} 
<form action="index.php" method="post">
<input type="submit" name="set" value="1">
</form>
<br />
In der LanguageDatei existiert eine Übersetzung für "You are redirected":
{php} print(language::t("You are redirected")); {/php}
