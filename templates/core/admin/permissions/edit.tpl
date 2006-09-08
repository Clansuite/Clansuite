<h2>{translate}Edit Permission{/translate}</h2>

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

{* todo : Debugausgabe nur wenn DEBUG = 1 {$permissions|@var_dump} *}
{if $smarty.const.DEBUG eq "1"} Debugausgabe des Arrays:  {html_alt_table loop=$permissions}   {/if}

 
 <form id="h3sForm" action="index.php?mod=admin&sub=permissions&action=edit" method="POST" target="_self">

    <fieldset > 

       <input name="info[id]" type="hidden" value="{$permissions.0.right_id}">
                       
       <h3> Edit Permission : {$permissions.0.right_id} - {$permissions.0.name} </h3>
	    
       <label for="permission_name">
			Permissions Name
			<input id="name" name="info[name]" type="text" value="{$permissions.0.name}" />
		</label>
		
		 <label for="permission_description">
			Permission Description
			<input id="name" name="info[description]" type="text" value="{$permissions.0.description}" />
		</label>
		
		<input class="input_submit" type="submit" name="submit" value="{translate}Edit Permission{/translate}" />
			
	 </fieldset>
	 
 </form>