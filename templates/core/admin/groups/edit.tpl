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

<h2>{translate}Edit Group{/translate}</h2>

{if $err.no_special_chars == 1} {error title="Special Chars"}       No special chars except '_' and whitespaces are allowed.{/error}    {/if}
{if $err.fill_form == 1}        {error title="Fill form"}           Please fill all necessary fields.{/error}                                     {/if}
{if $err.name_already == 1}     {error title="Name already exists"} The name you have entered already exists in the database.{/error}   {/if}

{* DEBUG
{if $smarty.const.DEBUG eq "1"} Debugausgabe der Var:  {$editgroup|@var_dump}   {/if}
*}

<form target="_self" method="POST" action="index.php?mod=admin&sub=groups&action=edit" class="h3sForm">

    <fieldset> 
        <h3>{translate}Editing group [ {$editgroup.name} - #{$editgroup.group_id}]{/translate}</h3>

        <table align="center">
            <thead>
            </thead>
            <tfoot>
            </tfoot>
            <tbody>
            <tr>
                <td><b>Name</b></td>
                <td><input name="info[name]" type="text" value="{$editgroup.name}" class="input_text" /><input name="info[group_id]" type="hidden" value="{$editgroup.group_id}"></td>
            </tr>
            <tr>
                <td><b>Description</b></td>
                <td><input name="info[description]" type="text" value="{$editgroup.description}" class="input_text"/></td>
            </tr>
            <tr>
                <td><b>Position</b></td>
                <td><input name="info[pos]" type="text" value="{$editgroup.pos}" class="input_text" /></td>
            </tr>
            <tr>
                <td><b>Color</b>
                    (<a id="color-href" href="javascript: showColorPicker(document.getElementById('color-href'),document.getElementById('color'),document.getElementById('showcolor'));">pick</a>)
                    
                </td>
                <td><input name="info[color]" id="color" type="text" value="{$editgroup.color}" class="input_text" style="width: 210px"/><span id="showcolor" style="background-color:{$editgroup.color}; padding-left: 35px; margin-left: 10px; position: relative; top: -4px;">&nbsp;</span></td>
            </tr>
        </table>

        <table width="100%">
            <thead>
            </thead>
            <tfoot>
            </tfoot>
            <tbody>
            <tr>
                <td align="center">
	                <fieldset class="radio">
	                    <h3>{translate}Icon & Image{/translate}</h3>
                        <table>
                            <thead>
                            </thead>
                            <tfoot>
                            </tfoot>
                            <tbody>
                            <tr>
                                <td>Icon</td>
                                <td>
        		                    <select class="input_text" name="info[icon]" onChange="document.getElementById('insert_icon').src='{$www_core_tpl_root}/images/groups/'+document.getElementById('icon').options[document.getElementById('icon').options.selectedIndex].text" class="input" id="icon">
                                        <option name=""></option>
                                        {foreach key=key item=item from=$icons}
                                            <option {if $editgroup.icon==$item}selected{/if} style="background-image:url({$www_core_tpl_root}/images/groups/{$item});background-repeat:no-repeat;padding-left:20px;height:20px;line-height:20px;" id="{$item}" name="{$item}">{$item}</option> );
                                        {/foreach}
                                        </select> 
                                    <img src="{$www_core_tpl_root}/images/groups/{$editgroup.icon}" id="insert_icon" border="1">                
                                </td>
                            </tr>
                            <tr>
                                <td>Image</td>
                                <td>
        		                    <select class="input_text" name="info[image]" onChange="document.getElementById('insert_image').src='{$www_core_tpl_root}/images/groups/'+document.getElementById('image').options[document.getElementById('image').options.selectedIndex].text" class="input" id="image">
                                        <option name=""></option>
                                        {foreach key=key item=item from=$images}
                                            <option {if $editgroup.image==$item}selected{/if} style="background-image:url({$www_core_tpl_root}/images/groups/{$item});background-repeat:no-repeat;padding-left:20px;height:20px;line-height:20px;" id="{$item}" name="{$item}">{$item}</option> );
                                        {/foreach}
                                    </select>
        		                    <img src="{$www_core_tpl_root}/images/groups/{$editgroup.image}" id="insert_image" border="1">             
                                </td>
                            </tr>
                        </table>            	
	                </fieldset>
	            </td>
                <td align="center">
	                <fieldset class="radio">
                        <h3>{translate}Permissions of this Group{/translate}</h3>
                        <table>
                            <thead>
                            </thead>
                            <tfoot>
                            </tfoot>
                            <tbody>
	                        {foreach item=item key=key from=$editgroup.permissions}
                                <tr>
                                <td>
	                                    <input type="checkbox" name="info['rights'][]" value="{$item.right_id}" {if $smarty.post.info.member_of_group==1}checked{/if} /><a href="index.php?mod=admin&sub=groups&action=edit&id={$item.group_id}" target="_blank">{$item.name}</a>
                                        
                                </td>
                                </tr>
	                        {/foreach}
                        </table>
	                </fieldset>
                </td>
                </tr>
                <tr>
                <td colspan="2" align="center">	    
	                <fieldset class="radio" style="width: 400px" valign="top">
	                    <h3>{translate}Available Permissions (Areas){/translate}</h3>
                        <table>
                            <thead>
                            </thead>
                            <tfoot>
                            </tfoot>
                            <tbody>
                            <tr>
	                        {foreach key=area_name item=area_array from=$editgroup.areas}
                                <td align="center" width="200">
                                    <a href="javascript:void(0);" onClick="clip_area('area_{$area_name}')" class="ButtonYellow">{$area_name}</a>
                            
                                    <div style="display: none;" id="area_{$area_name}" align="left">
                                    {foreach key=right_name item=right_array from=$area_array}
                                        <input type="checkbox" name="info['rights'][]" value="{$right_array.right_id}" {if $smarty.post.info.member_of_group==1}checked{/if} /><b>{$right_name}</b><br />
                                    {/foreach}
                                    </div>
                                 </td>
                            {assign var="x" value=$x+1}
                            {if $x==4}</tr><tr>{/if}
                            {/foreach}
                            </tr>
                        </table>
	                </fieldset>
                </td>
            </tr>
            </table>
	
	</fieldset>
    <br />
    <fieldset>
        <h3>{translate}Options{/translate}</h3>
        <div align="right" style="padding-right: 10px;padding-bottom: 10px;">
        <input class="ButtonGreen" type="submit" name="submit" value="{translate}Edit Group{/translate}" />
        <input class="ButtonGrey" type="reset" value="{translate}Reset Input Values{/translate}" onClick="document.getElementById('insert_image').src='{$www_core_tpl_root}/images/groups/{$editgroup.image}';document.getElementById('insert_icon').src='{$www_core_tpl_root}/images/groups/{$editgroup.icon}';" tabindex="3" />  
        </div>
	</fieldset>		
	 
</form>