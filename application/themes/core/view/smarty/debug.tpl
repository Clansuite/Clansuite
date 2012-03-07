{* Clansuite - Smarty Debug Console *}

{capture name='_smarty_debug_style' assign=debug_style}

<style type="text/css">
    div.debug_one, caption { cursor: pointer; width: auto; font-variant: small-caps; font-family: Verdana; font-size: 11px; height: 8px; text-align:left; font-weight:bold; padding: 8px; background: #ECE9D8; border-top: 1px solid #ffffff; border-bottom: 1px solid #ACA899;  }
    div.debug_inline { padding: 0px; width: 100%;background: #ECE9D8; border-top: 1px solid #ffffff; border-bottom: 1px solid #ACA899; }
    div.smarty-debug { 
        background: none repeat scroll 0 0 #EFEFEF;
        border: 1px solid #333333;
        padding: 20px 0 10px;
        width: 95%;
        margin: 0 auto;
        margin-top: 15px;
        margin-bottom: 15px;
    }
    fieldset.smarty-console {
        background: none repeat scroll 0 0 #CCCCCC;
        border: 1px solid #666666;
        font: 12px tahoma,verdana,arial,sans-serif;
        margin: 10px auto;
        padding: 10px;
        width: 95%;
    }
    fieldset.smarty-console legend {
        background: none repeat scroll 0 0 #FFFFFF;
        border: 1px solid #333333;
        color: #222222;
        float: left;
        font-weight: bold;
        margin-bottom: 10px;
        margin-top: -23px;
        padding: 5px 15px;
    }
    table.table_config_vars {
        background: none repeat scroll 0 0 #FFFFCC;
        border-collapse: collapse;
        border-color: #BF0000;
        border-style: outset;
        border-width: 1px;
        color: #222222;
        font: 12px tahoma,verdana,arial,sans-serif;
        width: 100%;
    }
    .odd { background-color: #eeeeee; }
    .even { background-color: #fafafa; }
    div.config-warning { font-color: FFFFCC; font-weigth: bold; font-size: 1em; }
</style>

<script src="{$www_root_themes_core}javascript/clip.js" type="text/javascript"></script>

{/capture}

{* Start - Assign the following content to Variable debug_content *}
{capture name='_smarty_debug' assign=debug_output}
    
<div class="smarty-debug">
<fieldset class="smarty-console">
<legend>Smarty Debug Console</legend>

<div class="debug_one" onclick="clip('element_1');">1. Included templates & config files and their load time in seconds</div>
<div class="debug_inline" style="display:none;" id="element_1">

Total Time: {$execution_time|string_format:"%.5f"}
<br />
Renderengine: {$smarty.version}
<br />
Template: {$template_name|var_dump}

{if !empty($template_data)}
<table id="table_config_vars">  
{foreach $template_data as $template}
  <font color=brown>{$template.name}</font>
  <span class="exectime">
   (compile {$template['compile_time']|string_format:"%.5f"}) (render {$template['render_time']|string_format:"%.5f"}) (cache {$template['cache_time']|string_format:"%.5f"})
  </span>
  <br>
{/foreach}
{/if}
</table>  
</div>

<div class="debug_one" onclick="clip('element_2');">2. Assigned template variables ($view->assign)</div>
<div class="debug_inline" style="display:none;" id="element_2">
    <table class="table_config_vars">  
    {foreach $assigned_vars as $vars}

       {* Word of Warning first! *}
       {if $vars@key == 'config'}
       <div class="config-warning">Warning! The complete Clansuite Config Array was assigned.
       Advise: assign only the needed config value.</div>
       {/if}

       {* Now print the table *}
       <tr class="{if $vars@iteration % 2 eq 0}odd{else}even{/if}">   
       <th>${$vars@key|escape:'html'}</th>       
       <td>{$vars|debug_print_var}</td></tr>
    {/foreach}   
    </table>
</div>


<div class="debug_one" onclick="clip('element_3');"><strong>3. Assigned config file variables (outer template scope)</strong></div>
<div class="debug_inline" style="display:none;" id="element_3"> 
    <table class="table_config_vars">   
    {foreach $config_vars as $vars}
       <tr class="{if $vars@iteration % 2 eq 0}odd{else}even{/if}">   
       <th>{$vars@key|escape:'html'}</th>
       <td>{$vars|debug_print_var}</td></tr>
    {/foreach}   
    </table>
</div>

</fieldset> {* close fieldset smarty-console *}
</div> {* close smarty-debug div *}

{* Stop - Assign the above content to Variable debug_content *}
{/capture}

{$config = get_object_vars($assigned_vars.config)}

{if $config.value.error.debug_popup == 0}   
    {move_to target="pre_head_close"}
    {$debug_style}   
    {/move_to}

    {* Capture the above content ($debug_output) and append it to the html document *}     
    {$debug_output}    
{else}
    {* Popup Window for Smarty Console *}
    <script type="text/javascript">
    {$id = $template_name|default:''|md5}
    var _csuite_console;
    	_csuite_console = window.open("","Smarty_Console_{$id}","width=800,height=600,resizable,scrollbars=yes");
     _csuite_console.document.write('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">');
     _csuite_console.document.write('<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">');     
  {* @todo figure out why using the <head> tag does not work.. *}
  {* _csuite_console.document.write('<head><title>Clansuite - Smarty Debug Console</title>'); *}     
     _csuite_console.document.write(' {$debug_style|escape:'javascript'} ');     
  {* _csuite_console.document.write(' </head> '); *}
     _csuite_console.document.write(' {$debug_output|escape:'javascript'} ');     
    	_csuite_console.document.write('</body></html>');
    	_csuite_console.document.close();
    </script>
{/if}