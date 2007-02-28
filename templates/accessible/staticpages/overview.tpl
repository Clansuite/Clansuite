{* Debugausgabe des Arrays: {$overview|@var_dump} *}
<ol>
{foreach from=$overview item=staticpage}
	<li><a href='index.php?mod=staticpages&amp;page={$staticpage.title}'>{$staticpage.title}</a> {$staticpage.description}</li>
{/foreach}
</ol>