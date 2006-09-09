{doc_raw}
           {* StyleSheets *}
           <link rel="stylesheet" type="text/css" href="{$www_core_tpl_root}/admin/fieldset.css" />  
{/doc_raw}

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

{* DEBUG
{if $smarty.const.DEBUG eq "1"} Debugausgabe der Var:  {$permission|@var_dump}   {/if}
*}

 
 <form target="_self" method="POST" action="index.php?mod=admin&sub=permissions&action=edit" class="h3sForm">

    <fieldset> 
    <legend>Editing group [<b> {$permission.name} - #{$permission.right_id}</b>]</legend> 

       <input name="info[id]" type="hidden" value="{$permission.right_id}">
       	    
        <br />   
       	    
       <label for="permission_name">Permissions Name</label>
	   <input id="name" name="info[name]" type="text" value="{$permission.name}" />
		
		
	   <label for="permission_description">Permission Description</label>
	   <input id="name" name="info[description]" type="text" value="{$permission.description}" />
		
		 
	 </fieldset>
	 
	 <fieldset>
	 <legend>{translate}Save Inputs{/translate}</legend>
	    <div align="right">
        <input class="ButtonGreen" type="submit" name="submit" value="{translate}Edit Permission{/translate}" />
        <input class="ButtonGrey" type="reset" value="{translate}Reset Input Values{/translate}" tabindex="3" />  
        </div>
	 </fieldset>
	 
 </form>