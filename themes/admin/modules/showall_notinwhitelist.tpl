

    <form action="index.php?mod=controlcenter&sub=modules&action=add_to_db" method="post" accept-charset="UTF-8">
    <table cellspacing="0" cellpadding="0" border="0" width="100%">
    <tr>
        <td class="td_header" width="150">    {t}Module folder problem{/t}     </td>
        <td class="td_header" width="100">    {t}Folder{/t}                    </td>
        <td class="td_header">                {t}Additional information{/t}    </td>
    </tr>

{foreach key=schluessel item=wert from=$content.not_in_whitelist}
<tr>

    <td class="cell1">
        <strong>{t}There is a module folder that is not stored in the databases whitelist.{/t}</strong>
    </td>

    <td class="cell2">
        {$wert.folder}
    </td>

    <td class="cell1">
        {if $wert.no_module_config == 1}
            <font color="red">{t}The modulename.config.php is missing! You have to add this file manually into the modules folder.{/t}</font>
            <font color="red">{t}Those values are EXAMPLES! They do not represent the current file settings in any way!{/t}</font>
        {/if}
        <table border="0" cellpadding="2" cellspacing="2">
        {if $wert.no_module_config == 1}
            <tr><td><strong>{t}Title:{/t}</strong></td><td><input class="input_text" type="text" name="info[{$wert.folder_name}][title]" value="{$wert.folder_name|ucfirst}"></td></tr>
            <tr><td><strong>{t}Name:<br /><div class="font_mini">?mod=name</div>{/t}</strong></td><td><input class="input_text" type="text" name="info[{$wert.folder_name}][name]" value="{$wert.folder_name}"></td></tr>
            <tr><td><strong>{t}Author:{/t}</strong></td><td><input class="input_text" type="text" name="info[{$wert.folder_name}][author]" value="{t}Your Name{/t}"></td></tr>
            <tr><td><strong>{t}Homepage:{/t}</strong></td><td><input class="input_text" type="text" name="info[{$wert.folder_name}][homepage]" value="http://www.{$wert.folder_name}.com"></td></tr>
            <tr><td><strong>{t}License:{/t}</strong></td><td><input class="input_text" type="text" name="info[{$wert.folder_name}][license]" value="GPL v2"></td></tr>
            <tr><td><strong>{t}Copyright:{/t}</strong></td><td><input class="input_text" type="text" name="info[{$wert.folder_name}][copyright]" value="{t}Your Name{/t}"></td></tr>
            <tr><td><strong>{t}Description:{/t}</strong></td><td><input class="input_text" type="text" name="info[{$wert.folder_name}][description]" value="{$wert.folder_name|ucfirst} module - your description"></td></tr>
            <tr><td><strong>{t}Filename:{/t}</strong></td><td><input class="input_text" type="text" name="info[{$wert.folder_name}][file_name]" value="{$wert.folder_name}.module.php"></td></tr>
            <tr><td><strong>{t}Foldername:{/t}</strong></td><td><input class="input_text" type="text" name="info[{$wert.folder_name}][folder_name]" value="{$wert.folder_name}"></td></tr>
            <tr><td><strong>{t}Classname:{/t}</strong></td><td><input class="input_text" type="text" name="info[{$wert.folder_name}][class_name]" value="module_{$wert.folder_name}"></td></tr>
            <tr><td><strong>{t}Imagename:{/t}</strong></td><td><input class="input_text" type="text" name="info[{$wert.folder_name}][image_name]" value="module_{$wert.folder_name}.jpg"></td></tr>
            <tr><td><strong>{t}Version:{/t}</strong></td><td><input class="input_text" type="text" name="info[{$wert.folder_name}][version]" value="0.1"></td></tr>
            <input type="hidden" name="info[{$wert.folder_name}][subs]" value=""></td></tr>
            <tr><td><strong>{t}Enabled?{/t}</strong></td><td><input type="checkbox" name="info[{$wert.folder_name}][enabled]" value="1"></td></tr>
            <tr><td><strong>{t}Core?{/t}</strong></td><td><input type="checkbox" name="info[{$wert.folder_name}][core]" value="1"></td></tr>
            <tr><td><strong>{t}Add?{/t}</strong></td><td><input type="checkbox" name="info[{$wert.folder_name}][add]" value="1" checked></td></tr>
        {else}
            <tr><td><strong>{t}Title:{/t}</strong></td><td><input class="input_text" type="text" name="info[{$wert.name}][title]" value="{$wert.title|escape:"html"}"></td></tr>
            <tr><td><strong>{t}Name:<br /><div class="font_mini">?mod=name</div>{/t}</strong></td><td><input class="input_text" type="text" name="info[{$wert.name}][name]" value="{$wert.name|escape:"html"}"></td></tr>
            <tr><td><strong>{t}Author:{/t}</strong></td><td><input class="input_text" type="text" name="info[{$wert.name}][author]" value="{$wert.author|escape:"html"}"></td></tr>
            <tr><td><strong>{t}Homepage:{/t}</strong></td><td><input class="input_text" type="text" name="info[{$wert.name}][homepage]" value="{$wert.homepage|escape:"html"}"></td></tr>
            <tr><td><strong>{t}License:{/t}</strong></td><td><input class="input_text" type="text" name="info[{$wert.name}][license]" value="{$wert.license|escape:"html"}"></td></tr>
            <tr><td><strong>{t}Copyright:{/t}</strong></td><td><input class="input_text" type="text" name="info[{$wert.name}][copyright]" value="{$wert.copyright|escape:"html"}"></td></tr>
            <tr><td><strong>{t}Description:{/t}</strong></td><td><input class="input_text" type="text" name="info[{$wert.name}][description]" value="{$wert.description|escape:"html"}"></td></tr>
            <tr><td><strong>{t}Filename:{/t}</strong></td><td><input class="input_text" type="text" name="info[{$wert.name}][file_name]" value="{$wert.file_name|escape:"html"}"></td></tr>
            <tr><td><strong>{t}Foldername:{/t}</strong></td><td><input class="input_text" type="text" name="info[{$wert.name}][folder_name]" value="{$wert.folder_name|escape:"html"}"></td></tr>
            <tr><td><strong>{t}Classname:{/t}</strong></td><td><input class="input_text" type="text" name="info[{$wert.name}][class_name]" value="{$wert.class_name|escape:"html"}"></td></tr>
            <tr><td><strong>{t}Imagename:{/t}</strong></td><td><input class="input_text" type="text" name="info[{$wert.name}][image_name]" value="{$wert.image_name|escape:"html"}"></td></tr>
            <tr><td><strong>{t}Module Version:{/t}</strong></td><td><input class="input_text" type="text" name="info[{$wert.name}][module_version]" value="{$wert.module_version|escape:"html"}"></td></tr>
            <tr><td><strong>{t}Subs:{/t}</strong></td><td><input class="input_text" type="text" name="info[{$wert.name}][subs]" value="{$wert.subs|escape:"html"}"></td></tr>
            <tr><td><strong>{t}CLansuite Version:{/t}</strong></td><td><input class="input_text" type="text" name="info[{$wert.name}][clansuite_version]" value="{$wert.clansuite_version|escape:"html"}"></td></tr>
            <tr><td><strong>{t}Enabled?{/t}</strong></td><td><input type="checkbox" name="info[{$wert.name}][enabled]" value="1"></td></tr>
            <tr><td><strong>{t}Core?{/t}</strong></td><td><input type="checkbox" name="info[{$wert.name}][core]" value="1"></td></tr>
            <tr><td><strong>{t}Add?{/t}</strong></td><td><input type="checkbox" name="info[{$wert.name}][add]" value="1" checked></td></tr>
        {/if}
        </table>
    </td>
</tr>
{/foreach}
</table>
<p align="center">
    <input class="ButtonGrey" type="submit" value="{t}Add the module(s) into the whitelist.{/t}" name="submit">
</p>
</form>
