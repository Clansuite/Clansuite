<center>
<table class="tbl_header">
	<tr><td class="htitle">CSS-BUILDER</td></tr>
</table>

<form method="post"  name="builder" action="index.php?mod=toolbox&action=cssbuilder" accept-charset="UTF-8">

<table class="tbl_outer">
	<colgroup><col width="30"/><col width="200"/><col width="560"/></colgroup>
	<tr><td colspan="3" class="tdhead">Soll der Core neu Kompiliert werden?</td></tr>
	<tr><td><input type="checkbox" name="compileCore" value="1" {if $compile.compileCore}checked{/if}></td><td colspan="2">Core neu kompilieren</td></tr>
	<tr><td><input type="checkbox" name="coreImport" value="1" {if $compile.coreImport}checked{/if}></td><td colspan="2">Core in Theme Importieren</td></tr>
	<tr><td colspan="3" class="leer"> </td></tr>
</table>

<table class="tbl_outer">
	<colgroup><col width="30"/><col width="100"/><col width="660"/></colgroup>
	<tr><td colspan="3" class="tdhead">Frontend oder Backend Theme Kompilieren?</td></tr>
	<tr>
		<td><input type="checkbox" name="compileThemeFrontend" value="1" {if $compile.compileThemeFrontend}checked{/if}></td>
		<td>Frontend:</td>
		<td>
			Path: <input type="text" class="path" name="themeFrontendPath" value="{$compile.themeFrontendPath}" style="width: 400px;">&nbsp;&nbsp;
			Theme: / <input type="text" class="path" name="themeFrontend" value="{$compile.themeFrontend}" style="width: 120px;">
		</td>
	</tr>
	<tr>
		<td><input type="checkbox" class="path" name="compileThemeBackend" value="1" {if $compile.compileThemeBackend}checked{/if}></td>
		<td>Backend:</td>
		<td>
			Path: <input type="text" class="path" name="themeBackendPath" value="{$compile.themeBackendPath}" style="width: 400px;">&nbsp;&nbsp;
			Theme: / <input type="text" class="path" name="themeBackend" value="{$compile.themeBackend}" style="width: 120px;">
		</td>
	</tr>
	<tr><td colspan="3" class="leer"> </td></tr>
</table>

<table class="tbl_outer">
	<colgroup><col width="30"/><col width="260"/><col width="500"/></colgroup>
	<tr><td colspan="3" class="tdhead">Für welche(n) Browser Kompilieren?</td></tr>

	{foreach item=row from=$browserinfo}
		<tr>
			<td><input type="checkbox" name="browsers[{$row.short}][active]" value="1" {if $row.active}checked{/if}></td>
			<td>{$row.description}</td>
			<td>
				<input type="hidden" name="browsers[{$row.short}][description]" value="{$row.description}">&nbsp;
				Css-Builder INI + StyleSheets-Postfix:&nbsp;&nbsp;{if $row.postfix !=''}_{else}&nbsp;&nbsp;{/if}<input type="text" name="browsers[{$row.short}][postfix]" value="{$row.postfix}" class="postfix" readonly="readonly">&nbsp;
				e.g. import{if $row.postfix !=''}_{/if}{$row.postfix}.css
			</td>
		</tr>
	{/foreach}

	<tr><td colspan="3" align="center" style="padding-top:30px;"><input type="submit" name="submit" value="Compile">&nbsp;&nbsp;<input type="submit" name="new" value="Reset"></td></tr>
	<tr><td colspan="3" align="center" style="padding-top:10px;">&nbsp;</td></tr>
</table>
</form>

{if $msgcompiled}
<table class="tbl_outer"><tr><td>{$msgcompiled}</td></table>
{/if}
</center>
