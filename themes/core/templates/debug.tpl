{* Get Debug Informations *}
{assign_debug_info}

{* Start - Assign the following content to Variable debug_content *}
{capture assign=debug_output}
    {move_to target="pre_head_close"}

    
    <style type="text/css">
        div.debug_one, caption { cursor: pointer; width: auto; font-variant: small-caps; font-family: Verdana; font-size: 11px; height: 8px; text-align:left; font-weight:bold; padding: 8px; background: #ECE9D8; border-top: 1px solid #ffffff; border-bottom: 1px solid #ACA899;  }
        col.debug_one         { background-color: #eeeeee; }
        col.debug_two         { background-color: #fafafa; }
        dl.debug_dl { padding: 0; margin: 0; }
        dt.debug_initial      { border-left:8px solid #cc9966; padding: 3px 10px 3px 5px; background-color: white; font-family: Verdana; font-size: 11px; }
        dt.debug_second      { border-left:8px solid #cc9966; padding: 3px 10px 3px 5px; background-color: #cccccc; font-weight: bold; font-family: Verdana; font-size: 11px; }
        div.debug_wrapper { margin: auto; width: 80%; border: padding: 8px; background: #ECE9D8; border: 1px solid; border-color: #ffffff #ACA899 #ACA899 #ffffff; font-family: Verdana; font-size: 11px; }
        h2.debug_heading { text-align: center; color: grey; }
        div.debug_inline { padding: 0px; width: 100%;background: #ECE9D8; border-top: 1px solid #ffffff; border-bottom: 1px solid #ACA899; }
    </style>
  

  {/move_to}

<script src="{$www_root_themes_core}/javascript/clip.js" type="text/javascript"></script>

<br />
<h2 class="debug_heading">Clansuite - Smarty Debug Console</h2>
<div class="debug_wrapper">

<div class="debug_one" onclick="clip('element_1');">1. Error Log</div>
<div class="debug_inline" style="display: none;" id="element_1">
    {foreach key=key item=item from=$debug.error_log}
        <dl class="debug_dl">
    	    <dt style="border-color: {if $key eq "notice"}#FFFF00
                                     {elseif $key eq "user_notice"}#FFCC00
                                     {elseif $key eq "warning"}#FF3333
                                     {/if};"
            class="debug_second">
                {$key|ucfirst}
       		</dt>
            <dt style="border-color: {if $key eq "notice"}#FFFF00
                                     {elseif $key eq "user_notice"}#FFCC00
                                     {elseif $key eq "warning"}#FF3333
                                     {/if};"
                class="debug_initial">{$item|@debug_print_var}
            </dt>
        </dl>
    {/foreach}
</div>

<div class="debug_one" onclick="clip('element_2');">2. Included templates & config files and their load time in seconds</div>
<div class="debug_inline" style="display:none;" id="element_2">
    <dl class="debug_dl">
       {section name=templates loop=$_debug_tpls}
            <dt class="debug_initial">
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
            </dt>
        {sectionelse}
            <dt class="debug_initial">
                        No templates included.
            </dt>
        {/section}
    </dl>
</div>


<div class="debug_one" onclick="clip('element_3');">3. Assigned template variables ($tpl->assign)</div>
<div class="debug_inline" style="display:none;" id="element_3">
    <dl class="debug_dl">
        {section name=vars loop=$_debug_keys}
            {* excluded arrays *}
            {if ($_debug_keys[vars] == "debug")                 or
                ($_debug_keys[vars] == "debug_db")              or
                ($_debug_keys[vars] == "debug_globals")         or
                ($_debug_keys[vars] == "content")               or
                ($_debug_keys[vars] == "copyright")             }
            {* do nothing *}
            {else}
        	    <dt class="debug_second">
                    <font color="blue">{ldelim}${$_debug_keys[vars]}{rdelim}</font>
           		</dt>
        		<dt class="debug_initial">
                    <font color="green">{$_debug_vals[vars]|@debug_print_var}</font>
                </dt>
        	{/if}
        {sectionelse}
        	<dt class="debug_initial">
                No template variables assigned.
        	</dt>
        {/section}
    </dl>
</div>


<div class="debug_one" onclick="clip('element_4');"><strong>4. Assigned config file variables (outer template scope)</strong></div>
<div class="debug_inline" style="display:none;" id="element_4">
    <dl class="debug_dl">
        {section name=config_vars loop=$_debug_config_keys}
            <dt class="debug_second">
                <font color="maroon">{ldelim}#{$_debug_config_keys[config_vars]}#{rdelim}</font>
            </dt>
            <dt class="debug_initial">
                <font color="green">{$_debug_config_vals[config_vars]|@debug_print_var}</font>
            </dt>
        {sectionelse}
            <dt class="debug_initial">
                No config vars assigned
            </dt>
        {/section}
    </dl>
</div>

<div class="debug_one" onclick="clip('element_5');"><strong>5. Database Stuff (Queries, Prepares, Execs, PDO Attributes)</strong></div>
<div class="debug_inline" style="display:none;" id="element_5">
    <dl class="debug_dl">
        {foreach key=outerkey name=outer item=debugouter from=$debug_db}
            <dt class="debug_second">
                {$outerkey|ucfirst}
            </dt>
            {foreach key=key item=item from=$debugouter}
            <dt class="debug_initial">
                <b>{$key}:</b> {$item|@debug_print_var}
            </dt>
            {/foreach}
        {/foreach}
    </dl>
</div>

<div class="debug_one" onclick="clip('element_6');"><strong>6. Loaded Modules ($modules->loaded) and Loaded Language Files ($lang->loaded)</strong></div>
<div class="debug_inline" style="display:none;" id="element_6">
    <dl class="debug_dl">
        <dt class="debug_second">
            Loaded Modules ($modules->loaded)
        </dt>
        {foreach key=key item=item from=$debug.mods_loaded}
            <dt class="debug_initial">
                {$item|@debug_print_var}
            </dt>
        {/foreach}
        <dt class="debug_second">
            Loaded Language Files ($lang->loaded)
        </dt>
        {foreach key=key item=item from=$debug.lang_loaded}
            <dt class="debug_initial">
                {$item|@debug_print_var}
            </dt>
        {/foreach}
    </dl>
</div>


<div class="debug_one" onclick="clip('element_7');"><strong>7. Config ($cfg->setting) </strong></div>
<div class="debug_inline" style="display:none;" id="element_7">
    <dl class="debug_dl">
        {foreach key=outerkey name=outer item=debugouter from=$debug.config}
            {if is_array($debugouter)}
                <dt class="debug_second">
                    {$outerkey|ucfirst}
                </dt>
                {foreach key=key item=item from=$debugouter}
                    <dt class="debug_initial">
                        {if is_array($debugouter)} <b>{$key}</b> {else} {* nothing *} {/if}
                        {$item|@debug_print_var}
                    </dt>
                {/foreach}
            {else}
            <dt class="debug_initial">
                {if $outerkey == 'db_password' OR $outerkey == 'smtp_password'}
                <b>{$outerkey}</b>: ******** {else} <b> {$outerkey}</b>: {$debugouter} {/if}
             </dt>
            {/if}
        {/foreach}
    </dl>
</div>

<div class="debug_one" onclick="clip('element_8');"><strong>8. Incoming Vars (COOKIES, GET, POST, REQUEST, SESSION | FILES, SERVER, ENV) </strong></div>
<div class="debug_inline" style="display:none;" id="element_8">
    <dl class="debug_dl">
        {foreach key=outerkey name=outer item=debugouter from=$debug_globals}
            <dt class="debug_second">
                {$outerkey|ucfirst}
            </dt>
            {foreach key=key item=item from=$debugouter}
                <dt class="debug_initial">
                    <b>{$key}</b>: {$item|@debug_print_var}
                </dt>
            {/foreach}
        {/foreach}
    </dl>
</div>
</div>
<p>&nbsp;</p><p>&nbsp;</p>
{* Stop - Assign the above content to Variable debug_content *}
{/capture}

{if $debug.debug_popup == 0}
    {* Captures Content from above is in $debug_output and moved with doc_raw to body_post *}
    {move_to target="pre_head_close"}   {$debug_output}    {/move_to}
{else}
    <script type="text/javascript">
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
    	_csuite_console.document.write('<link rel="stylesheet" type="text/css" href="{$www_root_themes_core}/css/debug.css" />');
        _csuite_console.document.write('</head><body>');
    	_csuite_console.document.write('{$debug_output|escape:'javascript'}');
    	_csuite_console.document.write('</body></html>');
    	_csuite_console.document.close();
    </script>
{/if}


<script type="text/javascript">
    window.onload = function ()
    {
        // check to see if firebug is installed and enabled
        if (window.console && console.firebug)
        {
            var firebug = document.getElementById('firebug');
            firebug.style.display = 'block';
            firebug.innerHTML = 'It appears that <strong>you have firebug enabled</strong>.' +
            'Using firebug with Clansuite will cause a <strong>significant performance degradation</strong>.';
        }
    }
</script>
