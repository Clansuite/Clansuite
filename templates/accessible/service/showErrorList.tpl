{*** 
	Dieses Template wird aufgerufen, sobald
	bei einer Formularvalidierung Fehler aufgetreten sind
	und als Liste angezeigt werden m�ssen
***}

{*** Ist die übergebene ErrorListe nicht leer? ***}
<p class="failed">Es sind folgende Fehler aufgetreten:</p>

<ul class="errorList">
{** Errorliste durchlaufen **}
{foreach from=$_errorList item=actError key=key name=_errorList}				
	
	{*** Aktuellen Fehler ausgeben ***}
	<li id="{$key}"> {$actError} </li>
	
{/foreach}
</ul>