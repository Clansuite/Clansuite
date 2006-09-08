{doc_raw}
            {* StyleSheets *}
            <link rel="stylesheet" type="text/css" href="{$www_core_tpl_root}/admin/js_color_picker_v2.css" />
            <link rel="stylesheet" type="text/css" href="{$www_core_tpl_root}/admin/fieldset.css" />  
            
            {* JavaScripts *}
	        <script type="text/javascript" src="{$www_core_tpl_root}/javascript/color_functions.js"></script>		
	        <script type="text/javascript" src="{$www_core_tpl_root}/javascript/js_color_picker_v2.js"></script>      
{/doc_raw}

<h2>{translate}Create a group{/translate}</h2>

{if $err.no_special_chars == 1}
    {error title="Special Chars"}
        No special chars except '_' are allowed.
    {/error}
{/if}

{if $err.fill_form == 1}
    {error title="Fill form"}
        Please fill all fields.
    {/error}
{/if}
 
<form action="index.php?mod=admin&sub=groups&action=create" method="POST" target="_self">

<table cellpadding="4" cellspacing="0" border="0">
<tr>
    <td colspan="2" class="td_header_small">
        Create a new group
    </td>
</tr>
<tr>
    <td class="cell1"><b>Name:</b></td>
    <td><input name="info[name]" type="text" value="" class="input_text"/></td>
</tr>
<tr>
    <td class="cell1"><b>Position:</b></td>
    <td><input name="info[pos]" type="text" value="" class="input_text"/></td>
</tr>
<tr>
    <td class="cell1"><b>Description:</b></td>
    <td><input name="info[description]" type="text" value="" class="input_text"/></td>
</tr>
<tr>
    <td class="cell1"><b>Color ( <a id="color-href" href="javascript: showColorPicker(document.getElementById('color-href'),document.getElementById('color'));">hex-codes</a> )</b></td>
    <td><input name="info[color]" id="color" type="text" value="Hex-Code" class="input_text" /></td>
</tr>
<tr>
    <td class="cell1"><b>Icon:</b></td>
    <td>
        <select class="input_text" name="info[icon]" onChange="document.getElementById('insert_icon').src='{$www_core_tpl_root}/images/groups/'+document.getElementById('icon').options[document.getElementById('icon').options.selectedIndex].text" class="input" id="icon">
            <option name=""></option>
            {foreach key=key item=item from=$icons}
                <option style="background-image:url({$www_core_tpl_root}/images/groups/{$item});background-repeat:no-repeat;padding-left:20px;height:20px;line-height:20px;" id="{$item}" name="{$item}">{$item}</option> );
            {/foreach}
        </select>
        <img src="" id="insert_icon" border="1">    
    </td>
</tr>
<tr>
    <td class="cell1"><b>Image:</b></td>
    <td>
        <select class="input_text" name="info[image]" onChange="document.getElementById('insert_image').src='{$www_core_tpl_root}/images/groups/'+document.getElementById('image').options[document.getElementById('image').options.selectedIndex].text" class="input" id="image">
            <option name=""></option>
            {foreach key=key item=item from=$images}
                <option style="background-image:url({$www_core_tpl_root}/images/groups/{$item});background-repeat:no-repeat;padding-left:20px;height:20px;line-height:20px;" id="{$item}" name="{$item}">{$item}</option> );
            {/foreach}
        </select>
        <img src="" id="insert_image" border="1">    
    </td>
</tr>
<tr>
    <td colspan="2" align="center">
        <input class="input_submit" type="submit" name="submit" value="{translate}Create the group{/translate}" />
    </td>
</tr>
</table>
</form>