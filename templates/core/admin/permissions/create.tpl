{doc_raw}
           {* StyleSheets *}
           <link rel="stylesheet" type="text/css" href="{$www_core_tpl_root}/admin/fieldset.css" />  
{/doc_raw}

<h2>{translate}Create Permission{/translate}</h2>

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

{* DEBUG
{if $smarty.const.DEBUG eq "1"} Debugausgabe der Var:  {$permission|@var_dump}   {/if}
*}

 
 <form target="_self" method="POST" action="index.php?mod=admin&sub=permissions&action=create" class="h3sForm">

    <fieldset> 
    <legend>Create Permission</legend> 

       <input name="info[id]" type="hidden" value="{$permission.right_id}">
       	    
        <br />   
       	    
       <label for="permission_name">Permissions Name</label>
	   <input id="name" name="info[name]" type="text" value="{$permission.name}" />
		
		
	   <label for="permission_description">Permission Description</label>
	   <input id="name" name="info[description]" type="text" value="{$permission.description}" />
		
		 
	 </fieldset>
	 
	 <fieldset>
	 <legend>{translate}Save Inputs{/translate}</legend>
	    
        <input style="border-color:lightgreen; border-style:groove;" class="Button" type="submit" name="submit" value="{translate}Create Permission{/translate}" />
        <input style="border-color:indianred; border-style:groove;" class="Button" type="reset" value="{translate}Reset Input Values{/translate}" tabindex="3" />  
   
	 </fieldset>
	 
 </form>