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

    <table cellpadding="0" cellspacing="0" border="0">
    
        <tr>
        <td>
            Name
        </td>
        <td>
            <input name="info[name]" type="text" value="{$smarty.post.info.name|escape:"htmlall"}" class="input_text"/>
        </td>
        </tr>
        
        <tr>
        <td>
            Position
        </td>
        <td>
            <input name="info[pos]" type="text" value="{$smarty.post.info.pos|escape:"htmlall"}" class="input_text"/>
        </td>
        </tr>
        
        <tr>
        <td>
            Description
        </td>
        <td>
            <input name="info[description]" type="text" value="{$smarty.post.info.description|escape:"htmlall"}" class="input_text"/>
        </td>
        </tr>
        
        <tr>
        <td>
            Hex-Code ( <a id="color-href" href="javascript: showColorPicker(document.getElementById('color-href'),document.getElementById('color'));">pick</a> )
        </td>
        <td>
            <input name="info[name]" type="text" value="{$smarty.post.info.name|escape:"htmlall"}" class="input_text"/>
        </td>
        </tr>
        
        <tr>
        <td>
            Icon
        </td>
        <td>
           <select class="input_text" name="info[icon]" onChange="document.getElementById('insert_icon').src='{$www_core_tpl_root}/images/groups/'+document.getElementById('icon').options[document.getElementById('icon').options.selectedIndex].text" class="input" id="icon">
                <option name=""></option>
                {foreach key=key item=item from=$icons}
                    <option {if $smarty.post.info.icon|escape:"htmlall"==$item}selected{/if} style="background-image:url({$www_core_tpl_root}/images/groups/{$item});background-repeat:no-repeat;padding-left:20px;height:20px;line-height:20px;" id="{$item}" name="{$item}">{$item}</option> );
                {/foreach}
            </select>
            <img src="{$www_core_tpl_root}/images/groups/{$smarty.post.info.icon|escape:"htmlall"}" id="insert_icon" border="1"> 
        </td>
        </tr>
        
        <tr>
        <td>
            Image
        </td>
        <td>
           <select class="input_text" name="info[image]" onChange="document.getElementById('insert_image').src='{$www_core_tpl_root}/images/groups/'+document.getElementById('image').options[document.getElementById('image').options.selectedIndex].text" class="input" id="image">
                <option name=""></option>
                {foreach key=key item=item from=$images}
                    <option {if $smarty.post.info.image|escape:"htmlall"==$item}selected{/if} style="background-image:url({$www_core_tpl_root}/images/groups/{$item});background-repeat:no-repeat;padding-left:20px;height:20px;line-height:20px;" id="{$item}" name="{$item}">{$item}</option> );
                {/foreach}
            </select>
            <img src="{$www_core_tpl_root}/images/groups/{$smarty.post.info.image|escape:"htmlall"}" id="insert_image" border="1">
        </td>
        </tr>

        <tr>
        <td>
            Available Permissions<br />
            ( Areas )
        </td>
        <td>
            {foreach key=area_name item=area_array from=$areas}
                <input type="button" onClick="clip_area('area_{$area_name}')" class="ButtonYellow" value="{$area_name}" />
                <div style="display: none;" id="area_{$area_name}" align="left">
                {foreach key=right_name item=right_array from=$area_array}
                    <br />                
                    <input type="checkbox" name="info['rights'][]" value="{$right_array.right_id}" {if $smarty.post.info.member_of_group==1}checked{/if} /><b>{$right_name}</b>
                {/foreach}
                </div>
                <br />
            {/foreach}
        </td>
        </tr>
                

        <tr>
        <td colspan="2">
            <input class="ButtonGreen" type="submit" name="submit" value="{translate}Create the group{/translate}" />
            <input class="ButtonGrey" type="reset" value="{translate}Reset Input Values{/translate}" tabindex="3" />        
        </td>
        </tr>
    </table>

</form>