{doc_raw}
   {* StyleSheets *}
    <link rel="stylesheet" type="text/css" href="{$www_core_tpl_root}/admin/js_color_picker_v2.css" />
    
    {* JavaScripts *}
	<script type="text/javascript" src="{$www_core_tpl_root}/javascript/color_functions.js"></script>		
	<script type="text/javascript" src="{$www_core_tpl_root}/javascript/js_color_picker_v2.js"></script> 
{/doc_raw}

{if $err.no_special_chars == 1} {error title="Special Chars"}       No special chars except '_' and whitespaces are allowed.{/error}    {/if}
{if $err.fill_form == 1}        {error title="Fill form"}           Please fill all necessary fields.{/error}                                     {/if}
{if $err.name_already == 1}     {error title="Name already exists"} The name you have entered already exists in the database.{/error}   {/if}
 
<form target="_self" method="post" action="index.php?mod=admin&sub=categories&action=edit">

    <table cellpadding="0" cellspacing="0" border="0" align="center" width="500">
    
        <tr class="tr_header">
            <td>
                {translate}Description{/translate}
            </td>
            <td colspan="2">
                {translate}Input{/translate}
            </td>
        </tr>
        
        <tr class="tr_row1">
            <td>
                {translate}Name{/translate}
            </td>
            <td colspan="2">
                <input name="info[cat_id]" type="hidden" value="{$info.cat_id}" />
                <input name="info[name]" type="text" value="{$info.name|escape:"html"}" size="30" class="input_text" />
            </td>
        </tr>
        
        <tr class="tr_row1">
            <td>
                {translate}Description{/translate}
            </td>
            <td colspan="2">
                <input name="info[description]" type="text" value="{$info.description|escape:"html"}" size="30" class="input_text"/>
            </td>
        </tr>
                
        <tr class="tr_row2">
            <td>
                {translate}Position{/translate}
            </td>
            <td colspan="2">
                <input name="info[sortorder]" type="text" value="{$info.sortorder|escape:"html"}" size="3" class="input_text"/>
            </td>
        </tr>
        
        <tr class="tr_row1">
            <td>
                {translate}Cat von Modul{/translate}
            </td>
            <td colspan="2"> {* BUG CHECK THIS: name, id and vars*}           
             <select class="input_text" name="info[module_id]" onchange="document.getElementById('insert_icon').src='{$www_core_tpl_root}/images/categories/icons/'+document.getElementById('icon').options[document.getElementById('icon').options.selectedIndex].text" class="input" id="icon">
                    <option name=""></option>
                    {foreach name=outer item=modul from=$modules}                    
                      <option {if $info.module_id|escape:"html"==$modul.module_id}selected{/if} style="background-image:url('{$www_core_tpl_root}/images/categories/icons/{$modules.module_id}');background-repeat:no-repeat; padding-left:20px; height:16px; width: 135px; line-height:16px;" id="{$modul.module_id}" value="{$modul.module_id}" name="{$modul.module_id}">{$modul.module_id} - {$modul.name}</option>
                    {/foreach}
               </select>

            </td>
        </tr>
        
        <tr class="tr_row2">
            <td>
                {translate}Hex-Code{/translate} ( <a id="color_href" href="javascript: showColorPicker(document.getElementById('color_href'),document.getElementById('color'), document.getElementById('color_preview'));">{translate}pick{/translate}</a> )
            </td>
            <td align="center">
                {if $info.color==''}
                    <input name="info[color]" type="text" value="#000000" size="7" id="color" class="input_text"/>
                {else}
                    <input name="info[color]" type="text" value="{$info.color|escape:"html"}" size="7" id="color" class="input_text"/>
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
                {translate}Icon{/translate}
            </td>
            <td width="1">
               <select class="input_text" name="info[icon]" onchange="document.getElementById('insert_icon').src='{$www_core_tpl_root}/images/groups/icons/'+document.getElementById('icon').options[document.getElementById('icon').options.selectedIndex].text" class="input" id="icon">
                    <option name=""></option>
                    {foreach key=key item=item from=$icons}
                        <option {if $info.icon|escape:"html"==$item}selected{/if} style="background-image:url('{$www_core_tpl_root}/images/groups/icons/{$item}');background-repeat:no-repeat; padding-left:20px; height:16px; width: 135px; line-height:16px;" id="{$item}" name="{$item}">{$item}</option> );
                    {/foreach}
                </select>

            </td>
            <td align="center">
                {if $info.icon==''}
                    <img src="{$www_core_tpl_root}/images/empty.png" id="insert_icon" border="0" width="16" height="16" class="border3d"> 
                {else}
                    <img src="{$www_core_tpl_root}/images/groups/icons/{$info.icon|escape:"html"}" id="insert_icon" border="0" width="16" height="16" class="border3d"> 
                {/if}            
            </td>
        </tr>
        
        <tr class="tr_row2">
            <td>
                {translate}Image{/translate}
            </td>
            <td width="1">
               <select class="input_text" name="info[image]" onchange="document.getElementById('insert_image').src='{$www_core_tpl_root}/images/groups/images/'+document.getElementById('image').options[document.getElementById('image').options.selectedIndex].text" class="input" id="image">
                    <option name=""></option>
                    {foreach key=key item=item from=$images}
                        <option {if $info.image|escape:"html"==$item}selected{/if} style="background-image:url('{$www_core_tpl_root}/images/groups/images/{$item}');background-repeat:no-repeat;padding-left:55px; padding-top: 10px; height:48px; width: 100px; line-height:48px;" id="{$item}" name="{$item}">{$item}</option> );
                    {/foreach}
                </select>
            </td>
            <td align="center">
                {if $info.image==''}
                    <img src="{$www_core_tpl_root}/images/empty.png" id="insert_image" border="0" width="48" height="48" class="border3d"> 
                {else}
                    <img src="{$www_core_tpl_root}/images/groups/images/{$info.image|escape:"html"}" id="insert_image" border="0" width="48" height="48" class="border3d"> 
                {/if}
            </td>
        </tr>        
        
        <tr class="tr_row2">
            <td colspan="3" align="right">
                <input class="ButtonGreen" type="submit" name="submit" value="{translate}Edit the category{/translate}" />
                <input class="ButtonGrey" type="reset" value="{translate}Reset{/translate}" />        
            </td>
        </tr>
    </table>

</form>