{*** 
	Dieses Template wird aufgerufen, sobald
	bei einer Formularvalidierung Fehler aufgetreten sind
	und als Liste angezeigt werden müssen
***}

{*** Ist die übergebene ErrorListe nicht leer? ***}
<p class="failed">Es sind folgende Fehler aufgetreten:</p>

<ul class="errorList">
{** Errorliste durchlaufen **}
{foreach from=$_errorList item=actError name=_errorList}				
	
	{*** Aktuellen Fehler ausgeben ***}
	{strip}
		{assign var="i" value=$smarty.foreach._errorList.iteration}
		<li id="{$i}">
			{$actError}
		</li>
	{/strip}
{/foreach}
</ul>