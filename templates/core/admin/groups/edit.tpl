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
 
 
 <form id="h3sForm" action="index.php?mod=admin&sub=groups&action=edit" method="POST" target="_self">

    <fieldset > 

        <input name="group_id" type="hidden" value="{$groupedit.group_id}">
                       
       <h3> Edit Usergroup </h3>
	    
       <label for="right_group_name">
			Groupname
			<input id="group_name" name="group_name" type="text" value="{$groupedit.name}" />
		</label>
		            				
		
		<label for="icon">
            Icon
			{* <input id="icon" name="icon" type="text" value="iconname?" /> *}
				<input type="text" id="icon" class="selectFile" name="icon" />
                <input type="button" name="select" onclick="ImageSelector.select('icon');" />
		</label>
		
		<input class="input_tsubmit" type="submit" name="submit" value="{translate}Edit User{/translate}" />
			
	 </fieldset>
</form>

                            <select name="icon" onChange="document.getElementById('insert_icon').src='{$www_core_tpl_root}/images/groups/'+document.getElementById('tree-insert-custom_icon').options[document.getElementById('icon').options.selectedIndex].text" class="input" id="icon">
                                <option name=""></option>
                                {foreach key=key item=item from=$icons}
                                    <option style="background-image:url({$www_core_tpl_root}/images/groups/{$item});background-repeat:no-repeat;padding-left:20px;height:20px;line-height:20px;" id="{$item}" name="{$item}">{$item}</option> );
                                {/foreach}
                            </select>
                            <img src="" id="insert_icon" border="1">