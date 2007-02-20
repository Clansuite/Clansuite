{assign_debug_info}

<span id="container_1" style="{if $debug.debug_popup==0}display: block{else}display: none{/if};">
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
	  {* exclude printf of the whole debugarray *}
    	{if $_debug_keys[vars] == "debug"} {* do nothing *}
    	{else}
    	    <tr bgcolor={if %vars.index% is even}#E4E0C7{else}#fafafa{/if}>
    		<td valign=top><font color=blue>{ldelim}${$_debug_keys[vars]}{rdelim}</font></td>
    		<td nowrap><font color=green>{$_debug_vals[vars]|@debug_print_var}</font></td></tr>
    	{/if}
	{sectionelse}
		<tr bgcolor=#E4E0C7><td colspan=2><i>no template variables assigned</i></td></tr>
	{/section}
	</table></span></td></tr>

	{*
    <tr style="border-color: #ffffff #ACA899 #ACA899 #ffffff;"><td colspan=2 class="header"><b><a style="text-decoration: none;" href="javascript:clip('3')">Assigned config file variables (outer template scope):</a></b></td></tr>
	<tr><td width=100% colspan=2><span style="display:none" id="span_3"><table border=0 width=100%>
	{section name=config_vars loop=$_debug_config_keys}
		<tr bgcolor={if %config_vars.index% is even}#E4E0C7{else}#fafafa{/if}><td valign=top width=10%><font color=maroon>{ldelim}#{$_debug_config_keys[config_vars]}#{rdelim}</font></td><td><font color=green>{$_debug_config_vals[config_vars]|@debug_print_var}</font></td></tr>
	{sectionelse}
		<tr bgcolor=#E4E0C7><td colspan=2><i>no config vars assigned</i></td></tr>
	{/section}
	</table></span></td></tr>
	*}

	<tr style="border-color: #ffffff #ACA899 #ACA899 #ffffff;"><td colspan=2 class="header"><b><a style="text-decoration: none;" href="javascript:clip('11')">Database Stuff (Queries, Prepares, Execs, PDO Attributes)</a></b></td></tr>
	<tr><td width=100% colspan=2><span style="display:none" id="span_11">
	
	<ul style="list-style-image:url(list_style_image.gif)">
    <li>&#160; <font color=blue>Queries:</font> 
                <font color=green>
                {foreach key=schluessel item=wert from=$debug.queries}
                    <font color=brown> {$wert|@debug_print_var} </font> <br />
                {/foreach}
                </font>
    </li> </ul>	
    </td>
    </tr>
			
    <table border=0 width=100%>
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

	<tr style="border-color: #ffffff #ACA899 #ACA899 #ffffff;">
	<td colspan=2 class="header"><b>
	<a style="text-decoration: none;" href="javascript:clip('10')">Loaded Modules ($modules->loaded)</a></b></td></tr>
	<tr><td width=100% colspan=2><span style="display:none" id="span_10"><table border=0 width=100%>
		{foreach key=schluessel item=wert from=$debug.mods_loaded}
			<tr bgcolor=#E4E0C7><td width=100 valign=top colspan=2>
			<font color=brown>&nbsp;{$wert|@debug_print_var}</font></td></tr>
		{/foreach}
	</table></span></td></tr>

	</table>
</span>

{if $debug.debug_popup==1}
	<script type="text/javascript">

	if ( self.name == '' )
	{ldelim}
		var title = 'Console';
	{rdelim}
	else
	{ldelim}
		var title = 'Console_' + self.name;
	{rdelim}
		var html = document.getElementById("container_1").innerHTML;
	_csuite_console = window.open("",'',"width=800,height=600,resizable,scrollbars=yes");
	_csuite_console.document.write('<html><head><title>ClanSuite Debug Window</title>');
	_csuite_console.document.write('<link rel="stylesheet" type="text/css" href="{$www_core_tpl_root}/admin/admin.css" />');
    _csuite_console.document.write('<style type="text/css">td{ldelim}font-size: 11px;{rdelim} a,a:link,a:visited,a:hover{ldelim}font-size: 11px{rdelim}</style>');
	_csuite_console.document.write('</head><body><span>');
	_csuite_console.document.write(html);
	_csuite_console.document.write('</span></body></html>');
	_csuite_console.document.close();
	document.getElementById("container_1").innerHTML = '';
	</script>
{/if}


{* testing 
<hr />

{literal}
<style>
dd {
  margin:0; 
  padding:0; 
  text-align:left; 
  border-top:1px solid #fff; 
  }

dt {
  margin:0; 
  padding:0.4em; 
  text-align:left; 
  font-size: 1.4em; 
  font-weight:bold; 
  background: #69c;
  }

dl {
  margin: 0; 
  padding: 0; 
  border-left:1px solid #fff; 
  border-right:1px solid #fff;
  }
  
</style>
{/literal}

{foreach key=outerkey name=outer item=debugouter from=$debug_superglobals}
  <dl>
  <dt><strong>{$outerkey}</strong></dt>  
  {foreach key=key item=item from=$debugouter}
    <dd>
        <font color=brown>{$key}</font>  : <dt>{$item|@debug_print_var}</dt>
    </dd>
  {/foreach}  
  </dl>
{/foreach}

{foreach key=outerkey name=outer item=debugouter from=$debug_db}
  <dl>
  <dt><strong>{$outerkey}</strong></dt>  
  {foreach key=key item=item from=$debugouter}
    <dd>
        <font color=brown>{$key}</font>  : <dt>{$item|@debug_print_var}</dt>
    </dd>
  {/foreach}  
  </dl>
{/foreach}

*}