{assign_debug_info}

<span id="container_1" style="{if $debug_popup==0}display: block{else}display: none{/if};">
	<script>
	function clip(id){ldelim}if(document.getElementById("span_" + id).style.display == 'none'){ldelim}document.getElementById("span_" + id).style.display = "block";{rdelim}else{ldelim}document.getElementById("span_" + id).style.display = "none";{rdelim}{rdelim}</script>
	<table border=1px width="100%" bgcolor="#CCCCCC">
	<tr bgcolor=#cccccc><th colspan=2>clansuite Debug Console</th></tr>

	<tr bgcolor=#cccccc><td colspan=2><b><a href="javascript:clip('1')">Included templates & config files (load time in seconds):</a></b></td></tr>
	<tr><td width=100% colspan=2><span style="display:none" id="span_1"><table border=0 width=100%>
	{section name=templates loop=$_debug_tpls}
		<tr bgcolor={if %templates.index% is even}#eeeeee{else}#fafafa{/if}><td colspan=2>{section name=indent loop=$_debug_tpls[templates].depth}&nbsp;&nbsp;&nbsp;{/section}<font color={if $_debug_tpls[templates].type eq "template"}brown{elseif $_debug_tpls[templates].type eq "insert"}black{else}green{/if}>{$_debug_tpls[templates].filename|escape:html}</font>{if isset($_debug_tpls[templates].exec_time)} <font size=-1><i>({$_debug_tpls[templates].exec_time|string_format:"%.5f"}){if %templates.index% eq 0} (total){/if}</i></font>{/if}</td></tr>
	{sectionelse}
		<tr bgcolor=#eeeeee><td colspan=2><i>no templates included</i></td></tr>	
	{/section}
	</table></span></td></tr>

	<tr bgcolor=#cccccc><td colspan=2><b><a href="javascript:clip('2')">$tpl->assign(): Assigned template variables:</a></b></td></tr>
	<tr><td width=100% colspan=2><span style="display:none" id="span_2"><table border=0 width=100%> 
	{section name=vars loop=$_debug_keys}
		<tr bgcolor={if %vars.index% is even}#eeeeee{else}#fafafa{/if}><td valign=top><font color=blue>{ldelim}${$_debug_keys[vars]}{rdelim}</font></td><td nowrap><font color=green>{$_debug_vals[vars]|@debug_print_var}</font></td></tr>
	{sectionelse}
		<tr bgcolor=#eeeeee><td colspan=2><i>no template variables assigned</i></td></tr>	
	{/section}
	</table></span></td></tr>

	<tr bgcolor=#cccccc><td colspan=2><b><a href="javascript:clip('3')">Assigned config file variables (outer template scope):</a></b></td></tr>
	<tr><td width=100% colspan=2><span style="display:none" id="span_3"><table border=0 width=100%>	
	{section name=config_vars loop=$_debug_config_keys}
		<tr bgcolor={if %config_vars.index% is even}#eeeeee{else}#fafafa{/if}><td valign=top width=10%><font color=maroon>{ldelim}#{$_debug_config_keys[config_vars]}#{rdelim}</font></td><td><font color=green>{$_debug_config_vals[config_vars]|@debug_print_var}</font></td></tr>
	{sectionelse}
		<tr bgcolor=#eeeeee><td colspan=2><i>no config vars assigned</i></td></tr>	
	{/section}
	</table></span></td></tr>

	<tr bgcolor=#cccccc><td colspan=2><b><a href="javascript:clip('4')">$Db: all Queries</a></b></td></tr>
	<tr><td width=100% colspan=2><span style="display:none" id="span_4"><table border=0 width=100%>
		{foreach key=schluessel item=wert from=$queries}
			<tr bgcolor=#eeeeee><td width=100 valign=top>
			<font color=blue>{$schluessel}</font></td><td><font color=green>
			{$wert|@debug_print_var}</font>
			</td></tr>
		{/foreach}
	 </table></span></td></tr>

	<tr bgcolor=#cccccc><td colspan=2><b><a href="javascript:clip('4')">$_REQUEST: Incoming Variables</a></b></td></tr>
	<tr><td width=100% colspan=2><span style="display:none" id="span_4"><table border=0 width=100%>
		{foreach key=schluessel item=wert from=$request}
			<tr bgcolor=#eeeeee><td width=100 valign=top>
			<font color=blue>{$schluessel}</font></td><td><font color=green>
			{$wert|@debug_print_var}</font>
			</td></tr>
		{/foreach}
	 </table></span></td></tr>
	 
	<tr bgcolor=#cccccc><td colspan=2><b><a href="javascript:clip('5')">$_SESSION: Session Variables</a></b></td></tr>
	<tr><td width=100% colspan=2><span style="display:none" id="span_5"><table border=0 width=100%>
		{foreach key=schluessel item=wert from=$session}
			<tr bgcolor=#eeeeee><td width=100 valign=top>
			<font color=blue>{$schluessel}</font></td><td><font color=green>
			{$wert|@debug_print_var}</font>
			</td></tr>
		{/foreach}
	 </table></span></td></tr>
	 
	<tr bgcolor=#cccccc><td colspan=2><b><a href="javascript:clip('6')">$_COOKIES: Session Variables</a></b></td></tr>
	<tr><td width=100% colspan=2><span style="display:none" id="span_6"><table border=0 width=100%>
		{foreach key=schluessel item=wert from=$cookies}
			<tr bgcolor=#eeeeee><td width=100 valign=top>
			<font color=blue>{$schluessel}</font></td><td><font color=green>
			{$wert|@debug_print_var}</font>
			</td></tr>
		{/foreach}
	 </table></span></td></tr>
	 
	<tr bgcolor=#cccccc><td colspan=2><b><a href="javascript:clip('7')">$cfg: Configuration (Password hidden)</a></b></td></tr>
	<tr><td width=100% colspan=2><span style="display:none" id="span_7"><table border=0 width=100%> 
		{foreach key=schluessel item=wert from=$config}
			<tr bgcolor=#eeeeee><td width=100 valign=top>
				{if $schluessel == 'db_password'}
					<font color=blue>{$schluessel|@debug_print_var}</font></td><td><font color=green>
					***</font>
				{else}
					<font color=blue>{$schluessel|@debug_print_var}</font></td><td><font color=green>
					{$wert|@debug_print_var}</font>
				{/if}
			</td></tr>
		{/foreach}
	</table></span></td></tr>
	
	<tr bgcolor=#cccccc><td colspan=2><b><a href="javascript:clip('8')">$error->error_log: Advanced Error Logging</a></b></td></tr>
	<tr><td width=100% colspan=2><span style="display:none" id="span_8"><table border=0 width=100%>
		{foreach key=schluessel item=wert from=$error_log}
			<tr bgcolor=#eeeeee><td width=100 valign=top>
			<font color=blue>{$schluessel}</font></td><td><font color=green>
			{$wert|@debug_print_var}</font>
			</td></tr>
		{/foreach}
	</table></span></td></tr>

	<tr bgcolor=#cccccc><td colspan=2><b><a href="javascript:clip('9')">$lang->loaded: Loaded Language Files</a></b></td></tr>
	<tr><td width=100% colspan=2><span style="display:none" id="span_9"><table border=0 width=100%>
		{foreach key=schluessel item=wert from=$lang_loaded}
			<tr bgcolor=#eeeeee><td width=100 valign=top colspan=2>
			<font color=blue>{$wert|@debug_print_var}</font></td></tr>
		{/foreach}
	</table></span></td></tr>			

	<tr bgcolor=#cccccc><td colspan=2><b><a href="javascript:clip('10')">$modules->loaded: Loaded Modules</a></b></td></tr>
	<tr><td width=100% colspan=2><span style="display:none" id="span_10"><table border=0 width=100%>
		{foreach key=schluessel item=wert from=$mods_loaded}
			<tr bgcolor=#eeeeee><td width=100 valign=top colspan=2>
			<font color=blue>{$wert|@debug_print_var}</font></td></tr>
		{/foreach}
	</table></span></td></tr>
	
	</table>
</span>
{if $debug_popup==1}
	<SCRIPT language=javascript>

	if ( self.name == '' )
	{ldelim}
		var title = 'Console';
	{rdelim}
	else
	{ldelim}
		var title = 'Console_' + self.name;
	{rdelim}
		var html = document.getElementById("container_1").innerHTML;
	_csphere_console = window.open("",title.value,"width=800,height=600,resizable,scrollbars=yes");
	_csphere_console.document.write(html);
	_csphere_console.document.close();
	</script>
{/if}