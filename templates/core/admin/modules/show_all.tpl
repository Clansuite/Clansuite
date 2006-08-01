<h2> Modulmanagement </h2>

{if isset($content.not_in_whitelist)}
<form action="{$www_root}/index.php?mod=admin&sub=modules&action=add_to_whitelist" method="POST">
<table cellspacing="0" cellpadding="0" border="0" width="100%">
<tr>
    
    <td id="td_header" width="30%">
    {translate}Module folder problem{/translate}
    </td>
    
    <td id="td_header" width="10%">
    {translate}Foldername{/translate}
    </td>
    
    <td id="td_header" width="55%">
    {translate}Additional information{/translate}
    </td>
    
    <td id="td_header" width="5%" align="center">
    {translate}Add?{/translate}
    </td>

</tr>
{foreach key=schluessel item=wert from=$content.not_in_whitelist}
<tr>

    <td id="cell1">
        {translate}There is a module folder that is not stored in the databases whitelist.{/translate}
    </td>
    
    <td id="cell2">
        {$wert.folder}
    </td>
    
    <td id="cell1">
        {if $wert.no_module_config == 1}
            {translate}The modulename.config.php is missing! You have to add this file manually into the modules folder.{/translate}
        {/if}
        <font color="red">{translate}Those values are EXAMPLES! They do not represent the current file settings in any way!{/translate}</font>
        <table border="0" cellpadding="2" cellspacing="2">
            <tr><td><b>{translate}Title:{/translate}</b></td><td><input class="input_text" type="text" name="info[{$wert.folder_name}][title]" value="{$wert.folder_name|ucfirst}"></td></tr>
            <tr><td><b>{translate}Name:<br /><div class="font_mini">?mod=name</div>{/translate}</b></td><td><input class="input_text" type="text" name="info[{$wert.folder_name}][name]" value="{$wert.folder_name}"></td></tr>
            <tr><td><b>{translate}Description:{/translate}</b></td><td><input class="input_text" type="text" name="info[{$wert.folder_name}][description]" value="{$wert.folder_name|ucfirst} module - your description"></td></tr>
            <tr><td><b>{translate}Filename:{/translate}</b></td><td><input class="input_text" type="text" name="info[{$wert.folder_name}][file_name]" value="{$wert.folder_name}.module.php"></td></tr>
            <tr><td><b>{translate}Foldername:{/translate}</b></td><td><input class="input_text" type="text" name="info[{$wert.folder_name}][folder_name]" value="{$wert.folder_name}"></td></tr>
            <tr><td><b>{translate}Classname:{/translate}</b></td><td><input class="input_text" type="text" name="info[{$wert.folder_name}][class_name]" value="module_{$wert.folder_name}"></td></tr>
            <tr><td><b>{translate}Imagename:{/translate}</b></td><td><input class="input_text" type="text" name="info[{$wert.folder_name}][image_name]" value="module_{$wert.folder_name}.jpg"></td></tr>
            <tr><td><b>{translate}Version:{/translate}</b></td><td><input class="input_text" type="text" name="info[{$wert.folder_name}][version]" value="0.1"></td></tr>
            <tr><td><b>{translate}Enabled:{/translate}</b></td><td><input type="checkbox" name="info[{$wert.folder_name}][enabled]" value="1"></td></tr>
        </table>
    </td>
    
    <td id="cell2" align="center">
        <input type="checkbox" name="info[{$wert.folder_name}][add]" value="1">
    </td>    

</tr>
{/foreach}
</table>
<p align="center">
    <input class="input_submit" type="submit" value="{translate}Add the module(s) into the whitelist.{/translate}" name="submit">
</p>
</form>
{/if}
<form action="{$www_root}/index.php?mod=admin&sub=modules&action=update" method="POST">
<table cellspacing="0" cellpadding="0" border="0" width="100%">

<tr>
    <td id="td_header" width="100%" colspan="4">
    {translate}Normal modules{/translate}
    </td>
</tr>
<tr>
    
    <td id="td_header_small" width="120px">
    {translate}Title{/translate}
    </td>
    
    <td id="td_header_small" width="80%">
    {translate}Information{/translate}
    </td>
    
    <td id="td_header_small" width="5%" align="center">
    {translate}Enabled{/translate}
    </td>
    
    <td id="td_header_small" width="5%" align="center">
    {translate}Delete{/translate}
    </td>    

</tr>
{foreach key=schluessel item=wert from=$content.whitelisted.normal}
<tr>
    <input type="hidden" name="ids[]" value="{$wert.module_id}">
    <td id="cell1" align="center">
        <b>{$wert.title}</b><br />
        <img width="100px" height="100px" src="{$www_core_tpl_root}/images/{$wert.image_name}">
    </td>
    
    <td id="cell2">
    <table cellpadding="2" cellspacing="2" border="0">
        <tr><td width="90"><b>{translate}Description:{/translate}</b></td><td>{$wert.description}</td></tr>
        <tr><td><b>{translate}Foldername:{/translate}</b></td><td>{$wert.folder_name}</td></tr>
        <tr><td><b>{translate}Classname:{/translate}</b></td><td>{$wert.class_name}</td></tr>
        <tr><td><b>{translate}Filename:{/translate}</b></td><td>{$wert.file_name}</td></tr>
        <tr><td><b>{translate}URL:{/translate}</b></td><td><a href="/index.php?mod={$wert.name}">index.php?mod={$wert.name}</a></td></tr>
    </table>
    </td>
    
    <td id="cell1" align="center">
        <input name="enabled[]" type="checkbox" value="{$wert.module_id}" {if $wert.enabled == 1} checked{/if}>
    </td>
    
    <td id="cell2" align="center">
        <input type="checkbox" name="delete[]" value="{$wert.module_id}">
    </td>

</tr>
{/foreach}
</table>
<p align="center">
    <input class="input_submit" type="submit" value="{translate}Update modules{/translate}" name="submit">
</p>
</form>
<br /><br />
<center><a href="javascript:clip_core_mods('1')">{translate}Show core modules{/translate}</a></center>
<br /><br />
<script>
function clip_core_mods(id){ldelim}if(document.getElementById("core_span_" + id).style.display == 'none'){ldelim}document.getElementById("core_span_" + id).style.display = "block";{rdelim}else{ldelim}document.getElementById("core_span_" + id).style.display = "none";{rdelim}{rdelim}
</script>
<span id="core_span_1" style="display: none;">
<form action="{$www_root}/index.php?mod=admin&sub=modules&action=update" method="POST">
<table cellspacing="0" cellpadding="0" border="0" width="100%">
<tr>
    <td id="td_header" width="100%" colspan="4">
    {translate}Core modules{/translate}
    </td>
</tr>
<tr>
    
    <td id="td_header_small" width="120px">
    {translate}Title{/translate}
    </td>
    
    <td id="td_header_small" width="80%">
    {translate}Information{/translate}
    </td>
    
    <td id="td_header_small" width="5%" align="center">
    {translate}Enabled{/translate}
    </td>
    
    <td id="td_header_small" width="5%" align="center">
    {translate}Delete{/translate}
    </td>    

</tr>
{foreach key=schluessel item=wert from=$content.whitelisted.core}
<tr>
    <input type="hidden" name="ids[]" value="{$wert.module_id}">
    <td id="cell1" align="center">
        <b>{$wert.title}</b><br />
        <img width="100px" height="100px" src="{$www_core_tpl_root}/images/{$wert.image_name}">
    </td>
    
    <td id="cell2">
    <table cellpadding="2" cellspacing="2" border="0">
        <tr><td colspan="2"><font color="red"><b>{translate}This is a core module! Removing, modifying or deleteing it can cause critical system instability!{/translate}</b></font></td></tr>
        <tr><td width="90"><b>{translate}Description:{/translate}</b></td><td>{$wert.description}</td></tr>
        <tr><td><b>{translate}Foldername:{/translate}</b></td><td>{$wert.folder_name}</td></tr>
        <tr><td><b>{translate}Classname:{/translate}</b></td><td>{$wert.class_name}</td></tr>
        <tr><td><b>{translate}Filename:{/translate}</b></td><td>{$wert.file_name}</td></tr>
        <tr><td><b>{translate}URL:{/translate}</b></td><td><a href="/index.php?mod={$wert.name}">index.php?mod={$wert.name}</a></td></tr>
    </table>
    </td>
    
    <td id="cell1" align="center">
        <input name="enabled[]" type="checkbox" value="{$wert.module_id}" {if $wert.enabled == 1} checked{/if}>
    </td>
    
    <td id="cell2" align="center">
        <input type="checkbox" name="delete[]" value="{$wert.module_id}">
    </td>

</tr>
{/foreach}
</table>
<p align="center">
    <input class="input_submit" type="submit" value="{translate}Update core modules{/translate}" name="submit">
</p>
</form>
</span>