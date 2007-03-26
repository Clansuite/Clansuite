{* Get Debug Informations *}
 {assign_debug_info}

{* Start - Assign the following content to Variable debug_content *}
{capture assign=debug_output}
    {doc_raw}
    {literal}
    <style type="text/css">
    /*<![CDATA[*/
    body            { font-family: Arial, Helvetica, sans-serif; font-size:small; }
    div.one, caption { width: 90%; font-variant: small-caps; text-align:left; font-weight:bold; padding: 8px; border-bottom: 2px solid #BDB76B; border-left:8px solid #BDB76B; background-color: #F0E68C; }
    table           { width: 90%; border-collapse: collapse; border-spacing:0; }
    tr.head, th     { text-align: left; background-color:#EEE8AA; padding:4px; color: blue;}
    td              { padding: 4px; vertical-align:top; }
    col.one         { background-color: #eeeeee; }
    col.two         { background-color: #fafafa; }
    dt              { border: 1px solid #9AA7AE; margin-bottom: 5px;}
    dt.initial      { border-left:8px solid #9AA7AE; padding:3px 10px 3px 5px; background-color: white; }
    div.debug_wrapper { width: 99%;}
    /*]]>*/
    </style>
  {/literal}
  {/doc_raw}

<script src="{$www_core_tpl_root}/javascript/clip.js" type="text/javascript" language="javascript"></script>

<br/><hr/>

<div class="debug_wrapper">

<h2 style="padding: 8px;">Clansuite Debug Console</h2>

<div class="one" onclick="clip('element_1');"><strong>1. Error Log</strong></div>
<div style="display:none; padding: 8px; width: 90%;" id="element_1">
    {foreach key=key item=item from=$debug.error_log}
    <dl>
        <dt style="border-color: {if $key eq "notice"}#FFFF00
                                 {elseif $key eq "user_notice"}#FFCC00
                                 {elseif $key eq "warning"}#FF3333
                                 {/if};"
            class="initial"><strong>{$key}</strong> | {$item|@debug_print_var}
        </dt>
    </dl>
    {/foreach}
</div>

<div class="one" onclick="clip('element_2');"><strong>2. Included templates & config files and their load time in seconds</strong></div>
<table rules="rows" frame="void" style="display: none;" id="element_2" summary="Debugging Table">
<col class="one" /><col class="two" />
<thead><tr><th>Templatename</th></tr></thead>
<tbody>
{section name=templates loop=$_debug_tpls}
<tr style="background-color:{cycle values="#eeeeee,#dddddd"};">
    <td>
        {section name=indent loop=$_debug_tpls[templates].depth}&nbsp;&nbsp;&nbsp;{/section}
        <font color="{if $_debug_tpls[templates].type eq "template"}#a52a2a
                    {elseif $_debug_tpls[templates].type eq "insert"}black
                    {else}green{/if}">
        {$_debug_tpls[templates].filename|escape:html}
        </font>

         {strip}
         <!-- Loadtime -->
         {if isset($_debug_tpls[templates].exec_time)}
         <em>({$_debug_tpls[templates].exec_time|string_format:"%.5f"})
            {if %templates.index% eq 0} (total){/if}</em>
         {/if}{/strip}
    </td>
</tr>
{sectionelse}
<tr><td><em>no templates included</em></td></tr>
{/section}
</tbody>
</table>

<div class="one" onclick="clip('element_3');"><strong>3. Assigned template variables ($tpl->assign)</strong></div>
<table rules="rows" frame="void" style="display: none;" id="element_3" summary="Debugging Table">
<col class="one" /><col class="two" />
<thead><tr><th>Assign</th><th>Data</th></tr></thead>
<tbody>
{section name=vars loop=$_debug_keys}
    {* excluded arrays *}
    {if ($_debug_keys[vars] == "debug")                 or
        ($_debug_keys[vars] == "debug_db")              or
        ($_debug_keys[vars] == "debug_globals")         or
        ($_debug_keys[vars] == "content")               or
        ($_debug_keys[vars] == "copyright")             }
    {* do nothing *}
    {else}
	    <tr style="background-color:{cycle values="#eeeeee,#dddddd"};">
    		<td valign="top"><font color="blue">{ldelim}${$_debug_keys[vars]}{rdelim}</font></td>
    		<td nowrap="nowrap"><font color="green">{$_debug_vals[vars]|@debug_print_var}</font></td>
		</tr>
	{/if}
{sectionelse}
	<tr bgcolor="#E4E0C7">
	<td colspan="2"><em>no template variables assigned</em></td>
	</tr>
{/section}
</tbody>
</table>

<div class="one" onclick="clip('element_4');"><strong>4. Assigned config file variables (outer template scope)</strong></div>
<table rules="rows" frame="void" style="display: none;" id="element_4" summary="Debugging Table">
<col class="one" /><col class="two" />
<thead><tr><th>Assign</th><th>Data</th></tr></thead>
<tbody>
{section name=config_vars loop=$_debug_config_keys}
	 <tr style="background-color:{cycle values="#eeeeee,#dddddd"};">
	  <td valign="top">
	    <font color="maroon">{ldelim}#{$_debug_config_keys[config_vars]}#{rdelim}</font>
	  </td>
	  <td>
	    <font color="green">{$_debug_config_vals[config_vars]|@debug_print_var}</font>
	  </td>
	</tr>
{sectionelse}
	<tr bgcolor="#E4E0C7">
	    <td colspan="2"><em>no config vars assigned</em></td>
	</tr>
{/section}
</tbody>
</table>


<div class="one" onclick="clip('element_5');"><strong>5. Database Stuff (Queries, Prepares, Execs, PDO Attributes)</strong></div>
<table rules="rows" frame="void" style="display: none;" id="element_5" summary="Debugging Table">
<col class="one" /><col class="two" />
<thead><tr><th>Infosection</th><th>Data</th></tr></thead>
<tbody>
{foreach key=outerkey name=outer item=debugouter from=$debug_db}
       <tr>
       <th colspan="2"><strong>{$outerkey}</strong></th>
       </tr>
      {foreach key=key item=item from=$debugouter}
        <tr style="background-color:{cycle values="#eeeeee,#dddddd"};">
            <td>{$key}</td>
            <td>{$item|@debug_print_var}</td>
        </tr>
      {/foreach}

{/foreach}
</tbody>
</table>

<div class="one" onclick="clip('element_6');"><strong>6. Loaded Modules ($modules->loaded) and Loaded Language Files ($lang->loaded)</strong></div>
<table rules="rows" frame="void" style="display: none;" id="element_6" summary="Debugging Table">
<col class="one" /><col class="two" />
<thead><tr><th><strong>Loaded Modules ($modules->loaded)</strong></th></tr></thead>
<tbody>
  {foreach key=key item=item from=$debug.mods_loaded}
   <tr style="background-color:{cycle values="#eeeeee,#dddddd"};">
    <td>{$item|@debug_print_var}</td>
   </tr>
  {/foreach}

  <tr class="head"><td><strong>Loaded Language Files ($lang->loaded)</strong></td></tr>
  {foreach key=key item=item from=$debug.lang_loaded}
  <tr style="background-color: {cycle values="#eeeeee,#dddddd"};">
    <td>{$item|@debug_print_var}</td>
  </tr>
  {/foreach}
</tbody>
</table>

<div class="one" onclick="clip('element_7');"><strong>7. CONFIG ($cfg->setting) </strong></div>
<table rules="rows" frame="void" style="display: none;" id="element_7" summary="Debugging Table">
<col class="one" /><col class="two" />
<tbody>
{foreach key=outerkey name=outer item=debugouter from=$debug.config}
   {if is_array($debugouter)}
        <tr><th colspan="2"><strong>{$outerkey} => array</strong></th></tr>
        {foreach key=key item=item from=$debugouter}
            <tr style="background-color:{cycle values="#eeeeee,#dddddd"};">
                <td>{if is_array($debugouter)} {$key} {else} {* nothing *} {/if} </td>
                <td>{$item|@debug_print_var}</td>
            </tr>
        {/foreach}
   {else}
        <tr><td>{$outerkey}</td><td>{$debugouter}</td></tr>
   {/if}
{/foreach}
</tbody>
</table>

<div class="one" onclick="clip('element_8');"><strong>8. Incoming Vars (COOKIES, GET, POST, REQUEST, SESSION | FILES, SERVER, ENV) </strong></div>
<table rules="rows" frame="void" style="display: none;" id="element_8" summary="Debugging Table">
<col class="one" /><col class="two" />
<tbody>
{foreach key=outerkey name=outer item=debugouter from=$debug_globals}
    <tr>
       <th colspan="2"><strong>{$outerkey}</strong></th>
       </tr>
      {foreach key=key item=item from=$debugouter}
        <tr style="background-color:{cycle values="#eeeeee,#dddddd"};">
            <td>{$key}</td>
            <td>{$item|@debug_print_var}</td>
        </tr>
      {/foreach}
{/foreach}
</tbody>
</table>


</div>

{* Stop - Assign the above content to Variable debug_content *}
{/capture}



{if $debug.debug_popup == 0}
    <!-- Captures Content from above is in $debug_output and moved with doc_raw to body_post -->
    {doc_raw target="body_post"}    {$debug_output}    {/doc_raw}
{else}
        <script type="text/javascript">
         /*<![CDATA[*/
        	if ( self.name == '' )
        	{ldelim}
        		var title = 'Console';
        	{rdelim}
        	else
        	{ldelim}
        		var title = 'Console_' + self.name;
        	{rdelim}
        	_csuite_console = window.open("",title.value,"width=800,height=600,resizable,scrollbars=yes");
        	_csuite_console.document.write('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">');
            _csuite_console.document.write('<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">');
            _csuite_console.document.write('<head><title>ClanSuite Debug Window</title>');
        	_csuite_console.document.write('<link rel="stylesheet" type="text/css" href="{$www_core_tpl_root}/css/debug.css" />');
            _csuite_console.document.write('</head><body>');
        	_csuite_console.document.write('{$debug_output|escape:'javascript'}');
        	_csuite_console.document.write('</body></html>');
        	_csuite_console.document.close();
        /*]]>*/
        </script>
{/if}