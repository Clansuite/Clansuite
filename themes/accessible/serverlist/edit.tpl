{move_to target="pre_head_close"}
    {* StyleSheets *}
    <link rel="stylesheet" type="text/css" href="{$www_root_themes_core}/admin/js_color_picker_v2.css" />
    <link rel="stylesheet" type="text/css" href="{$www_root_themes_core}/admin/fieldset.css" />  
    
    {* JavaScripts *}
	<script type="text/javascript" src="{$www_root_themes_core}/javascript/color_functions.js"></script>		
	<script type="text/javascript" src="{$www_root_themes_core}/javascript/js_color_picker_v2.js"></script>
	<script type="text/javascript" src="{$www_root_themes_core}/javascript/clip.js"></script>      
{/move_to}

{if $err.no_special_chars == 1} {error title="Special Chars"}       No special chars except '_' and whitespaces are allowed.{/error}    {/if}
{if $err.fill_form == 1}        {error title="Fill form"}           Please fill all necessary fields.{/error}                           {/if}
{if $err.name_already == 1}     {error title="Name already exists"} The name you have entered already exists in the database.{/error}   {/if}
 
<form target="_self" method="post" accept-charset="UTF-8" action="index.php?mod=controlcenter&sub=groups&action=edit">

    <table cellpadding="0" cellspacing="0" border="0" align="center" width="500">
    
        <tr class="tr_header">
            <td>
                {t}Description{/t}
            </td>
            <td colspan="2">
                {t}Input{/t}
            </td>
        </tr>
        
        <tr class="tr_row1">
            <td>
                {t}Name{/t}
            </td>
            <td colspan="2">
                <input name="info[group_id]" type="hidden" value="{$info.group_id}" />
                <input name="info[name]" type="text" value="{$info.name|escape:"htmlall"}" size="30" class="input_text" />
            </td>
        </tr>
        
        <tr class="tr_row1">
            <td>
                {t}Description{/t}
            </td>
            <td colspan="2">
                <input name="info[description]" type="text" value="{$info.description|escape:"htmlall"}" size="30" class="input_text"/>
            </td>
        </tr>
                
        <tr class="tr_row2">
            <td>
                {t}Position{/t}
            </td>
            <td colspan="2">
                <input name="info[sortorder]" type="text" value="{$info.sortorder|escape:"htmlall"}" size="3" class="input_text"/>
            </td>
        </tr>
        
        <tr class="tr_row2">
            <td>
                {t}Hex-Code{/t} ( <a id="color_href" href="javascript: showColorPicker(document.getElementById('color_href'),document.getElementById('color'), document.getElementById('color_preview'));">{t}pick{/t}</a> )
            </td>
            <td align="center">
                {if $info.color==''}
                    <input name="info[color]" type="text" value="#000000" size="7" id="color" class="input_text"/>
                {else}
                    <input name="info[color]" type="text" value="{$info.color|escape:"htmlall"}" size="7" id="color" class="input_text"/>
                {/if}
            </td>
            <td align="center">
                {if $info.color==''}
                    <div id="color_preview" style="background-color: #000000; height: 20px; width: 30px;" class="border3d"></div>
                {else}
                    <div id="color_preview" style="background-color: {$info.color}; height: 20px; width: 30px;" class="border3d"></div>
                {/if}
            </td>
        </tr>
        
        <tr class="tr_row1">
            <td>
                {t}Icon{/t}
            </td>
            <td width="1">
               <select class="input_text" name="info[icon]" onchange="document.getElementById('insert_icon').src='{$www_root_themes_core}/images/groups/icons/'+document.getElementById('icon').options[document.getElementById('icon').options.selectedIndex].text" class="input" id="icon">
                    <option name=""></option>
                    {foreach key=key item=item from=$icons}
                        <option {if $info.icon|escape:"htmlall"==$item}selected="selected"{/if} style="background-image:url('{$www_root_themes_core}/images/groups/icons/{$item}');background-repeat:no-repeat; padding-left:20px; height:16px; width: 135px; line-height:16px;" id="{$item}" name="{$item}">{$item}</option> );
                    {/foreach}
                </select>

            </td>
            <td align="center">
                {if $info.icon==''}
                    <img src="{$www_root_themes_core}/images/empty.png" id="insert_icon" border="0" width="16" height="16" class="border3d"> 
                {else}
                    <img src="{$www_root_themes_core}/images/groups/icons/{$info.icon|escape:"htmlall"}" id="insert_icon" border="0" width="16" height="16" class="border3d"> 
                {/if}            
            </td>
        </tr>
        
        <tr class="tr_row2">
            <td>
                {t}Image{/t}
            </td>
            <td width="1">
               <select class="input_text" name="info[image]" onchange="document.getElementById('insert_image').src='{$www_root_themes_core}/images/groups/images/'+document.getElementById('image').options[document.getElementById('image').options.selectedIndex].text" class="input" id="image">
                    <option name=""></option>
                    {foreach key=key item=item from=$images}
                        <option {if $info.image|escape:"htmlall"==$item}selected="selected"{/if} style="background-image:url('{$www_root_themes_core}/images/groups/images/{$item}');background-repeat:no-repeat;padding-left:55px; padding-top: 10px; height:48px; width: 100px; line-height:48px;" id="{$item}" name="{$item}">{$item}</option> );
                    {/foreach}
                </select>
            </td>
            <td align="center">
                {if $info.image==''}
                    <img src="{$www_root_themes_core}/images/empty.png" id="insert_image" border="0" width="48" height="48" class="border3d"> 
                {else}
                    <img src="{$www_root_themes_core}/images/groups/images/{$info.image|escape:"htmlall"}" id="insert_image" border="0" width="48" height="48" class="border3d"> 
                {/if}
            </td>
        </tr>
        
        <tr class="tr_row1">
            <td>
                {t}Available Rights{/t}<br />
                ( {t}Areas{/t} )
            </td>
            <td align="center">
                {foreach key=area_name item=area_array from=$info.areas}
                    <input type="button" onclick="clip('area_{$area_name}')" class="ButtonYellow" value="{$area_name}" />
                {/foreach}
            </td>
            <td align="center" style="padding: 0px;" width="150">
                {foreach key=area_name item=area_array from=$info.areas}
                    <table style="display: none;" id="area_{$area_name}" cellpadding="0" cellspacing="0" border="0" width="100%">
                        <tr class="tr_row2"><td colspan="2" align="center"><strong>{$area_name}</strong></td></tr>
                        {foreach key=right_name item=right_array from=$area_array}
                            <tr class="tr_row1">
                                <td align="center" width="20%">
                                    <input type="checkbox" name="info[rights][]" value="{$right_array.right_id}" {if array_key_exists($right_array.right_id,$info.rights)}checked="checked"{/if} />
                                </td>
                                <td align="left" width="90%">
                                    {$right_name}
                                </td>

                            </tr>
                        {/foreach}
                    </table>
                {/foreach}
            </td>
        </tr>

        <tr class="tr_row1">
            <td>
                {t}Assigned Rights{/t}
            </td>
            <td colspan="2">
                <table cellpadding="0" cellspacing="0" border="0" width="100%">
                    {foreach key=right_id item=right_array from=$info.rights}
                        <tr class="tr_row1">
                            <td align="center" width="5%" style="padding: 0px">
                                <input type="checkbox" name="info[rights][]" value="{$right_id}" checked="checked" />
                            </td>
                            <td align="left" width="98%" style="padding: 2px; padding-left: 5px">
                                {$right_array.name}
                            </td>

                        </tr>               
                    {/foreach}
                </table>
            </td>
        </tr>
        
        <tr class="tr_row2">
            <td colspan="3" align="right">
                <input class="ButtonGreen" type="submit" name="submit" value="{t}Edit the group{/t}" />
                <input class="ButtonGrey" type="reset" value="{t}Reset{/t}" />        
            </td>
        </tr>
    </table>

</form>