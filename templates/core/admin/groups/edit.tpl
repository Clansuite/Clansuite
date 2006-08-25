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
 
 
 <form id="h3sForm"
       action="index.php?mod=admin&sub=groups&action=edit" method="POST" target="_self">

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