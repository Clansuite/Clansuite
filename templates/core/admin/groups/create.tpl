{doc_raw}
    {* StyleSheets *}
    <link rel="stylesheet" type="text/css" href="{$www_core_tpl_root}/admin/js_color_picker_v2.css" />
    <link rel="stylesheet" type="text/css" href="{$www_core_tpl_root}/admin/fieldset.css" />  
    
    {* JavaScripts *}
	<script type="text/javascript" src="{$www_core_tpl_root}/javascript/color_functions.js"></script>		
	<script type="text/javascript" src="{$www_core_tpl_root}/javascript/js_color_picker_v2.js"></script>      
    {literal}
    <script type="text/javascript">
        function clip_area(id)
        {
            if( document.getElementById(id).style.display == 'none' )
            {
                document.getElementById(id).style.display = 'block';
            }
            else
            {
                document.getElementById(id).style.display = 'none';
            }
        }
    </script>
    {/literal}
{/doc_raw}

<h2>{translate}Create a group{/translate}</h2>

{if $err.no_special_chars == 1} {error title="Special Chars"}       No special chars except '_' and whitespaces are allowed.{/error}    {/if}
{if $err.fill_form == 1}        {error title="Fill form"}           Please fill all necessary fields.{/error}                                     {/if}
{if $err.name_already == 1}     {error title="Name already exists"} The name you have entered already exists in the database.{/error}   {/if}
 
<form target="_self" method="POST" action="index.php?mod=admin&sub=groups&action=create">

    <table cellpadding="0" cellspacing="0" border="0" align="center" width="600">
    
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
                <input name="info[name]" type="text" value="{$smarty.post.info.name|escape:"htmlall"}" class="input_text"/>
            </td>
        </tr>
        
        <tr class="tr_row2">
            <td>
                {translate}Position{/translate}
            </td>
            <td colspan="2">
                <input name="info[pos]" type="text" value="{$smarty.post.info.pos|escape:"htmlall"}" class="input_text"/>
            </td>
        </tr>
        
        <tr class="tr_row1">
            <td>
                {translate}Description{/translate}
            </td>
            <td colspan="2">
                <input name="info[description]" type="text" value="{$smarty.post.info.description|escape:"htmlall"}" class="input_text"/>
            </td>
        </tr>
        
        <tr class="tr_row2">
            <td>
                {translate}Hex-Code{/translate} ( <a id="color-href" href="javascript: showColorPicker(document.getElementById('color-href'),document.getElementById('color'));">{translate}pick{/translate}</a> )
            </td>
            <td colspan="2">
                <input name="info[name]" type="text" value="{$smarty.post.info.name|escape:"htmlall"}" class="input_text"/>
            </td>
        </tr>
        
        <tr class="tr_row1">
            <td>
                {translate}Icon{/translate}
            </td>
            <td width="1">
               <select class="input_text" name="info[icon]" onChange="document.getElementById('insert_icon').src='{$www_core_tpl_root}/images/groups/icons/'+document.getElementById('icon').options[document.getElementById('icon').options.selectedIndex].text" class="input" id="icon">
                    <option name=""></option>
                    {foreach key=key item=item from=$icons}
                        <option {if $smarty.post.info.icon|escape:"htmlall"==$item}selected{/if} style="background-image:url('{$www_core_tpl_root}/images/groups/icons/{$item}');background-repeat:no-repeat; padding-left:20px; height:16px; width: 135px; line-height:16px;" id="{$item}" name="{$item}">{$item}</option> );
                    {/foreach}
                </select>

            </td>
            <td align="center">
                {if $smarty.post.info.icon==''}
                    <img src="{$www_core_tpl_root}/images/empty.png" id="insert_icon" border="0" width="16" height="16" class="border3d"> 
                {else}
                    <img src="{$www_core_tpl_root}/images/groups/icons/{$smarty.post.info.icon|escape:"htmlall"}" id="insert_icon" border="0" width="16" height="16" class="border3d"> 
                {/if}            
            </td>
        </tr>
        
        <tr class="tr_row2">
            <td>
                {translate}Image{/translate}
            </td>
            <td width="1">
               <select class="input_text" name="info[image]" onChange="document.getElementById('insert_image').src='{$www_core_tpl_root}/images/groups/images/'+document.getElementById('image').options[document.getElementById('image').options.selectedIndex].text" class="input" id="image">
                    <option name=""></option>
                    {foreach key=key item=item from=$images}
                        <option {if $smarty.post.info.image|escape:"htmlall"==$item}selected{/if} style="background-image:url('{$www_core_tpl_root}/images/groups/images/{$item}');background-repeat:no-repeat;padding-left:55px; padding-top: 10px; height:48px; width: 100px; line-height:48px;" id="{$item}" name="{$item}">{$item}</option> );
                    {/foreach}
                </select>
            </td>
            <td align="center">
                {if $smarty.post.info.icon==''}
                    <img src="{$www_core_tpl_root}/images/empty.png" id="insert_image" border="0" width="48" height="48" class="border3d"> 
                {else}
                    <img src="{$www_core_tpl_root}/images/groups/images/{$smarty.post.info.image|escape:"htmlall"}" id="insert_image" border="0" width="48" height="48" class="border3d"> 
                {/if}
            </td>
        </tr>

        <tr class="tr_row1">
            <td>
                {translate}Available Permissions{/translate}<br />
                ( {translate}Areas{/translate} )
            </td>
            <td align="center">
                {foreach key=area_name item=area_array from=$areas}
                    <input type="button" onClick="clip_area('area_{$area_name}')" class="ButtonYellow" value="{$area_name}" />
                {/foreach}
            </td>
            <td align="center" style="padding: 0px;" width="150">
                {foreach key=area_name item=area_array from=$areas}
                    <table style="display: none;" id="area_{$area_name}" cellpadding="0" cellspacing="0" border="0" width="100%">
                        <tr class="tr_row2"><td colspan="2" align="center"><b>{$area_name}</b></td></tr>
                        {foreach key=right_name item=right_array from=$area_array}
                            <tr class="tr_row1">
                                <td align="center" width="20%">
                                    <input type="checkbox" name="info['rights'][]" value="{$right_array.right_id}" {if $smarty.post.info.member_of_group==1}checked{/if} />
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
                

        <tr class="tr_row2">
            <td colspan="3" align="right">
                <input class="ButtonGreen" type="submit" name="submit" value="{translate}Create the group{/translate}" />
                <input class="ButtonGrey" type="reset" value="{translate}Reset{/translate}" />        
            </td>
        </tr>
    </table>

</form>