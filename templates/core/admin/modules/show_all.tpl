{if isset($content.not_in_whitelist)}
<form action="/index.php?mod=admin&sub=admin_modules&action=add_to_whitelist" method="POST">
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
        {translate}The module.config.php is missing! You have to add this file manually into the modules folder.{/translate}
        {/if}
        <table border="0" cellpadding="2" cellspacing="2">
            <tr><td><b>{translate}Title:{/translate}</b></td><td><input class="input_text" type="text" name="title" value=""></td></tr>
            <tr><td><b>{translate}Name:<br /><div class="font_mini">?mod=name</div>{/translate}</b></td><td><input class="input_text" type="text" name="name" value=""></td></tr>
            <tr><td><b>{translate}Description:{/translate}</b></td><td><input class="input_text" type="text" name="description" value=""></td></tr>
            <tr><td><b>{translate}Filename:{/translate}</b></td><td><input class="input_text" type="text" name="file_name" value=""></td></tr>
            <tr><td><b>{translate}Foldername:{/translate}</b></td><td><input class="input_text" type="text" name="folder_name" value="{$wert.folder_name}"></td></tr>
            <tr><td><b>{translate}Classname:{/translate}</b></td><td><input class="input_text" type="text" name="class_name" value=""></td></tr>
            <tr><td><b>{translate}Imagename:{/translate}</b></td><td><input class="input_text" type="text" name="image_name" value=""></td></tr>
            <tr><td><b>{translate}Enabled:{/translate}</b></td><td><input type="checkbox" name="enabled" value=""></td></tr>
        </table>
    </td>
    
    <td id="cell2" align="center">
        <input type="checkbox" name="add" value="1">
    </td>    

</tr>
{/foreach}
</table>
<p align="center">
    <input class="input_submit" type="submit" value="{translate}Add the module(s) into the whitelist.{/translate}" name="submit">
</p>
</form>
{/if}
<form action="/index.php?mod=admin&sub=admin_modules&action=update" method="POST">
<table cellspacing="0" cellpadding="0" border="0" width="100%">
<tr>
    
    <td id="td_header" width="120px">
    {translate}Title{/translate}
    </td>
    
    <td id="td_header" width="80%">
    {translate}Information{/translate}
    </td>
    
    <td id="td_header" width="5%" align="center">
    {translate}Enabled{/translate}
    </td>
    
    <td id="td_header" width="5%" align="center">
    {translate}Delete{/translate}
    </td>    

</tr>
{foreach key=schluessel item=wert from=$content.whitelisted}
<tr>

    <td id="cell1" align="center">
        <b>{$wert.title}</b><br />
        <img width="100px" height="100px" src="{$www_core_tpl_root}/images/{$wert.image_name}">
    </td>
    
    <td id="cell2">
    <table cellpadding="2" cellspacing="2" border="0">
        <tr><td><b>{translate}Description:{/translate}</b></td><td>{$wert.description}</td></tr>
        <tr><td><b>{translate}Foldername:{/translate}</b></td><td>{$wert.folder_name}</td></tr>
        <tr><td><b>{translate}Classname:{/translate}</b></td><td>{$wert.class_name}</td></tr>
        <tr><td><b>{translate}Filename:{/translate}</b></td><td>{$wert.file_name}</td></tr>
        <tr><td><b>{translate}URL:{/translate}</b></td><td><a href="/index.php?mod={$wert.name}">index.php?mod={$wert.name}</a></td></tr>
    </table>
    </td>
    
    <td id="cell1" align="center">
        <input name="enabled" type="checkbox" value="1" {if $wert.enabled == 1} checked{/if}>
    </td>
    
    <td id="cell1" align="center">
        <input type="checkbox" name="delete" value="1">
    </td>

</tr>
{/foreach}
</table>
<p align="center">
    <input class="input_submit" type="submit" value="{translate}Update whitelist{/translate}" name="submit">
</p>
</form>