{assign_debug_info}

<span id="container_1" style="{if $debug_popup==0}display: block{else}display: none{/if};">
	<script>
	function clip(id){ldelim}if(document.getElementById("span_" + id).style.display == 'none'){ldelim}document.getElementById("span_" + id).style.display = "block";{rdelim}else{ldelim}document.getElementById("span_" + id).style.display = "none";{rdelim}{rdelim}</script>
	<div class="header">ClanSuite Debug Console</div>
    <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#ECE9D8" style="border: thin solid black; border-color: #ffffff #ACA899 #ACA899 #ffffff;">
	
	<tr style="border-color: #ffffff #ACA899 #ACA899 #ffffff;"><td colspan=2 class="header"><b><a style="text-decoration: none;" href="javascript:clip('8')">Error Logs ($error->error_log)</a></b></td></tr>
	<tr><td width=100% colspan=2><span style="display:none" id="span_8"><table border=0 width=100%>
		{foreach key=schluessel item=wert from=$debug.error_log}
			<tr bgcolor=#E4E0C7><td width=100 valign=top>
			<font color=red>{$schluessel}</font></td><td><font color=blue>
			{$wert|@debug_print_var}</font>
			</td></tr>
		{/foreach}
	</table></span></td></tr>
	
	<tr style="border-color: #ffffff #ACA899 #ACA899 #ffffff;"><td colspan=2 class="header"><b><a style="text-decoration: none;" href="javascript:clip('1')">Included templates & config files (load time in seconds):</a></b></td></tr>
	<tr><td width=100% colspan=2><span style="display:none" id="span_1"><table border=0 width=100%>
	{section name=templates loop=$_debug_tpls}
		<tr bgcolor={if %templates.index% is even}#E4E0C7{else}#fafafa{/if}><td colspan=2>{section name=indent loop=$_debug_tpls[templates].depth}&nbsp;&nbsp;&nbsp;{/section}<font color={if $_debug_tpls[templates].type eq "template"}brown{elseif $_debug_tpls[templates].type eq "insert"}black{else}green{/if}>{$_debug_tpls[templates].filename|escape:html}</font>{if isset($_debug_tpls[templates].exec_time)} <font size=-1><i>({$_debug_tpls[templates].exec_time|string_format:"%.5f"}){if %templates.index% eq 0} (total){/if}</i></font>{/if}</td></tr>
	{sectionelse}
		<tr bgcolor=#E4E0C7><td colspan=2><i>no templates included</i></td></tr>	
	{/section}
	</table></span></td></tr>

	<tr style="border-color: #ffffff #ACA899 #ACA899 #ffffff;"><td colspan=2 class="header"><b><a style="text-decoration: none;" href="javascript:clip('2')">Assigned template variables ($tpl->assign)</a></b></td></tr>
	<tr><td width=100% colspan=2><span style="display:none" id="span_2"><table border=0 width=100%> 
	{section name=vars loop=$_debug_keys}
		<tr bgcolor={if %vars.index% is even}#E4E0C7{else}#fafafa{/if}><td valign=top><font color=blue>{ldelim}${$_debug_keys[vars]}{rdelim}</font></td><td nowrap><font color=green>{$_debug_vals[vars]|@debug_print_var}</font></td></tr>
	{sectionelse}
		<tr bgcolor=#E4E0C7><td colspan=2><i>no template variables assigned</i></td></tr>	
	{/section}
	</table></span></td></tr>

	<tr style="border-color: #ffffff #ACA899 #ACA899 #ffffff;"><td colspan=2 class="header"><b><a style="text-decoration: none;" href="javascript:clip('3')">Assigned config file variables (outer template scope):</a></b></td></tr>
	<tr><td width=100% colspan=2><span style="display:none" id="span_3"><table border=0 width=100%>	
	{section name=config_vars loop=$_debug_config_keys}
		<tr bgcolor={if %config_vars.index% is even}#E4E0C7{else}#fafafa{/if}><td valign=top width=10%><font color=maroon>{ldelim}#{$_debug_config_keys[config_vars]}#{rdelim}</font></td><td><font color=green>{$_debug_config_vals[config_vars]|@debug_print_var}</font></td></tr>
	{sectionelse}
		<tr bgcolor=#E4E0C7><td colspan=2><i>no config vars assigned</i></td></tr>	
	{/section}
	</table></span></td></tr>

	<tr style="border-color: #ffffff #ACA899 #ACA899 #ffffff;"><td colspan=2 class="header"><b><a style="text-decoration: none;" href="javascript:clip('11')">Database Stuff (Queries, Prepares, Execs, PDO Attributes)</a></b></td></tr>
	<tr><td width=100% colspan=2><span style="display:none" id="span_11"><table border=0 width=100%>
			<tr bgcolor=#E4E0C7><td width=100 valign=top>
			<font color=blue>Queries:</font></td><td><font color=green>
			{foreach key=schluessel item=wert from=$debug.queries}
            <font color=brown> {$wert|@debug_print_var} </font> <br />
            {/foreach}
			</font>
			</td></tr>
		
			<tr bgcolor=#fafafa><td width=100 valign=top>
			<font color=blue>Prepares:</font></td><td><font color=green>
			{foreach key=schluessel item=wert from=$debug.prepares}
            <font color=brown> {$wert|@debug_print_var} </font> <br />
            {/foreach}
			</font>
			</td></tr>
			
			<tr bgcolor=#E4E0C7><td width=100 valign=top>
			<font color=blue>Execs:</font></td><td><font color=green>
			{foreach key=schluessel item=wert from=$debug.execs}
            <font color=brown> {$wert|@debug_print_var} </font> <br />
            {/foreach}
			</font>
			</td></tr>
			
			<tr bgcolor=#fafafa><td width=100 valign=top>
			<font color=blue>PDO Attributes:</font></td><td><font color=green>
			{foreach key=schluessel item=wert from=$debug.attributes}
            {$schluessel}: <font color=brown> {$wert|@debug_print_var} </font> <br />
            {/foreach}
			</font>
			</td></tr>
			
					
	 </table></span></td></tr>

	<tr style="border-color: #ffffff #ACA899 #ACA899 #ffffff;"><td colspan=2 class="header"><b><a style="text-decoration: none;" href="javascript:clip('4')">Incoming Vars (COOKIES, GET, POST, REQUEST, SESSION)</a></b></td></tr>
	<tr><td width=100% colspan=2><span style="display:none" id="span_4"><table border=0 width=100%>
			<tr bgcolor=#E4E0C7><td width=100 valign=top>
			<font color=blue>GET:</font></td><td><font color=green>
			{foreach key=schluessel item=wert from=$debug.get}
            {$schluessel}: <font color=brown> {$wert|@debug_print_var} </font> <br />
            {/foreach}
			</font>
			</td></tr>
		
			<tr bgcolor=#fafafa><td width=100 valign=top>
			<font color=blue>POST:</font></td><td><font color=green>
			{foreach key=schluessel item=wert from=$debug.post}
            {$schluessel}: <font color=brown> {$wert|@debug_print_var} </font> <br />
            {/foreach}
			</font>
			</td></tr>
			
			<tr bgcolor=#E4E0C7><td width=100 valign=top>
			<font color=blue>COOKIES:</font></td><td><font color=green>
			{foreach key=schluessel item=wert from=$debug.cookies}
            {$schluessel}: <font color=brown> {$wert|@debug_print_var} </font> <br />
            {/foreach}
			</font>
			</td></tr>
			
			<tr bgcolor=#fafafa><td width=100 valign=top>
			<font color=blue>REQUEST:</font></td><td><font color=green>
			{foreach key=schluessel item=wert from=$debug.request}
            {$schluessel}: <font color=brown> {$wert|@debug_print_var} </font> <br />
            {/foreach}
			</font>
			</td></tr>
			
			<tr bgcolor=#fafafa><td width=100 valign=top>
			<font color=blue>SESSION:</font></td><td><font color=green>
			{foreach key=schluessel item=wert from=$debug.session}
            {$schluessel}: <font color=brown> {$wert|@debug_print_var} </font> <br />
            {/foreach}
			</font>
			</td></tr>
					
	 </table></span></td></tr>
	 
	<tr style="border-color: #ffffff #ACA899 #ACA899 #ffffff;"><td colspan=2 class="header"><b><a style="text-decoration: none;" href="javascript:clip('7')">Configurations ($cfg - Password hidden)</a></b></td></tr>
	<tr><td width=100% colspan=2><span style="display:none" id="span_7"><table border=0 width=100%> 
		{foreach key=schluessel item=wert from=$debug.configs}
			<tr bgcolor=#E4E0C7><td width=100 valign=top>
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

	<tr style="border-color: #ffffff #ACA899 #ACA899 #ffffff;"><td colspan=2 class="header"><b><a style="text-decoration: none;" href="javascript:clip('9')">Loaded Language Files ($lang->loaded)</a></b></td></tr>
	<tr><td width=100% colspan=2><span style="display:none" id="span_9"><table border=0 width=100%>
		{foreach key=schluessel item=wert from=$debug.lang_loaded}
			<tr bgcolor=#E4E0C7><td width=100 valign=top colspan=2>
			<font color=brown>&nbsp;{$wert|@debug_print_var}</font></td></tr>
		{/foreach}
	</table></span></td></tr>			

	<tr style="border-color: #ffffff #ACA899 #ACA899 #ffffff;"><td colspan=2 class="header"><b><a style="text-decoration: none;" href="javascript:clip('10')">Loaded Modules ($modules->loaded)</a></b></td></tr>
	<tr><td width=100% colspan=2><span style="display:none" id="span_10"><table border=0 width=100%>
		{foreach key=schluessel item=wert from=$debug.mods_loaded}
			<tr bgcolor=#E4E0C7><td width=100 valign=top colspan=2>
			<font color=brown>&nbsp;{$wert|@debug_print_var}</font></td></tr>
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