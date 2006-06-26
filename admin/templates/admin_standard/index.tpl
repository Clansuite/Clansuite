{include file="header.tpl"}
	
	
{* This calls the method "time" from the redistered module "index" and gives 2 parameters: "english" and "-" seperated by "|" *}
{mod name="index" func="time" params="english|-"}<br>

{$content}

<br><a href="http://www.clansuite.com"><span class="copyright">{$copyright}</span></a>

{include file="footer.tpl"}