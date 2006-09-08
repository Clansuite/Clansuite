{doc_raw}
            {* StyleSheets *}
            <link rel="stylesheet" type="text/css" href="{$www_core_tpl_root}/admin/js_color_picker_v2.css" />
            <link rel="stylesheet" type="text/css" href="{$www_core_tpl_root}/admin/fieldset.css" />  
            
            {* JavaScripts *}
	        <script type="text/javascript" src="{$www_core_tpl_root}/javascript/color_functions.js"></script>		
	        <script type="text/javascript" src="{$www_core_tpl_root}/javascript/js_color_picker_v2.js"></script>      
{/doc_raw}

<h2>{translate}Edit Group{/translate}</h2>

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
 
{* todo : Debugausgabe nur wenn DEBUG = 1 {$editgroup|@var_dump} *}
{if $smarty.const.DEBUG eq "1"} Debugausgabe des Arrays:  {html_alt_table loop=$editgroup}   {/if}
 
 
 <form class="h3sForm" action="index.php?mod=admin&sub=groups&action=edit" method="POST" target="_self">

    <fieldset>

        <input name="info[group_id]" type="hidden" value="{$editgroup.0.group_id}">
                       
       <h3> Edit Usergroup : # {$editgroup.0.group_id} - {$editgroup.0.name} </h3>
	    
       <label for="group_name">
			Groupname
			<input id="group_name" name="info[name]" type="text" value="{$editgroup.0.name}" />
		</label>
		
		<label for="group_position">
			Group Position
			<input id="group_pos" name="info[pos]" type="text" value="{$editgroup.0.pos}" />
		</label>
		
		<label for="group_description">
			Group Description
			<input id="group_description" name="info[description]" type="text" value="{$editgroup.0.description}" />
		</label>
		
		<label for="color">
            {translate}Color{/translate} ( <a id="color-href" href="javascript: showColorPicker(document.getElementById('color-href'),document.getElementById('color'));">hex-codes</a> )
			<input name="info[color]" id="color" type="text" value="Color" />
        </label> 
		
		<label for="icon">
                Icon
                <select name="info[icon]" onChange="document.getElementById('insert_icon').src='{$www_core_tpl_root}/images/groups/'+document.getElementById('icon').options[document.getElementById('icon').options.selectedIndex].text" class="input" id="icon">
                    <option name=""></option>
                    {foreach key=key item=item from=$icons}
                        <option style="background-image:url({$www_core_tpl_root}/images/groups/{$item});background-repeat:no-repeat;padding-left:20px;height:20px;line-height:20px;" id="{$item}" name="{$item}">{$item}</option> );
                    {/foreach}
                </select>
                <img src="" id="insert_icon" border="1">
        </label>
        
        <label for="image">
                Image
                <select name="info[image]" onChange="document.getElementById('insert_image').src='{$www_core_tpl_root}/images/groups/'+document.getElementById('image').options[document.getElementById('image').options.selectedIndex].text" class="input" id="image">
                    <option name=""></option>
                    {foreach key=key item=item from=$images}
                        <option style="background-image:url({$www_core_tpl_root}/images/groups/{$item});background-repeat:no-repeat;padding-left:20px;height:20px;line-height:20px;" id="{$item}" name="{$item}">{$item}</option> );
                    {/foreach}
                </select>
                <img src="" id="insert_image" border="1">
        </label>
        
		<input class="input_submit" type="submit" name="submit" value="{translate}Edit User{/translate}" />
			
	 </fieldset>
</form>