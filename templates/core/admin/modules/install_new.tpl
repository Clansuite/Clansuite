{$chmod_tpl}
<form action="/index.php?mod=admin&sub=modules&action=add_to_whitelist" method="POST">
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
<tr>

    <td id="cell1">
        {translate}There is a module folder that is not stored in the databases whitelist.{/translate}
    </td>
    
    <td id="cell2">
        {$wert.folder_name}
    </td>
    
    <td id="cell2">
        {if $wert.no_module_config == 1}
        {translate}The module.config.php is missing! You have to add this file manually into the modules folder.{/translate}
        {/if}
        <table border="0" cellpadding="2" cellspacing="2">
            <tr><td><b>{translate}Title:{/translate}</b></td><td><input class="input_text" type="text" name="title" value=""></td></tr>
            <tr><td><b>{translate}Name:<br /><div class="font_mini">?mod=name</div>{/translate}</b></td><td><input class="input_text" type="text" name="name" value=""></td></tr>
            <tr><td><b>{translate}Description:{/translate}</b></td><td><input class="input_text" type="text" name="description" value=""></td></tr>
            <tr><td><b>{translate}Filename:{/translate}</b></td><td><input class="input_text" type="text" name="file_name" value=""></td></tr>
            <tr><td><b>{translate}Foldername:{/translate}</b></td><td><input class="input_text" type="text" name="folder_name" value=""></td></tr>
            <tr><td><b>{translate}Classname:{/translate}</b></td><td><input class="input_text" type="text" name="class_name" value=""></td></tr>
            <tr><td><b>{translate}Imagename:{/translate}</b></td><td><input class="input_text" type="text" name="image_name" value=""></td></tr>
            <tr><td><b>{translate}Enabled:{/translate}</b></td><td><input type="checkbox" name="enabled" value=""></td></tr>
        </table>
    </td>
    
    <td id="cell1" align="center">
        <input type="checkbox" name="add" value="1">
    </td>    

</tr>
</table>
<p align="center">
    <input class="input_submit" type="submit" value="{translate}Add the module into the whitelist.{/translate}" name="submit">
</p>
</form>