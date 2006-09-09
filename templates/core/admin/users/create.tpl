{doc_raw}
            {* StyleSheets *}
            <link rel="stylesheet" type="text/css" href="{$www_core_tpl_root}/admin/js_color_picker_v2.css" />
            <link rel="stylesheet" type="text/css" href="{$www_core_tpl_root}/admin/fieldset.css" />  
            
            {* JavaScripts *}
	        <script type="text/javascript" src="{$www_core_tpl_root}/javascript/color_functions.js"></script>		
	        <script type="text/javascript" src="{$www_core_tpl_root}/javascript/js_color_picker_v2.js"></script>      
{/doc_raw}

<h2>{translate}Create a user{/translate}</h2>

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

{if $err.nick_already == 1}
    {error title="Nick already stored"}
        The nick you have entered is already in the database.
    {/error}
{/if}

{if $err.email_already == 1}
    {error title="eMail already stored"}
        The eMail you have entered is already in the database.
    {/error}
{/if}

{if $err.email_wrong == 1}
   {error title="Mail wrong!"}
        The email you entered is not valid.
    {/error}
{/if} 

<form target="_self" method="POST" action="index.php?mod=admin&sub=users&action=create" class="h3sForm">

    <fieldset> 
    <legend>Create user</legend>

        <input type="hidden" name="info[user_id]" value="{$user.user_id}">

        <br />

       <label for="first_name"><b>First Name</b></label>
	   <input name="info[first_name]" type="text" value="{$smarty.post.info.first_name|escape:"htmlall"}" class="input_text"/>
	   
	   <label for="last_name"><b>Last Name</b></label>
       <input name="info[last_name]" type="text" value="{$smarty.post.info.last_name|escape:"htmlall"}" class="input_text"/>
	   
	   <label for="nick"><b>Nick</b></label>
	   <input name="info[nick]" type="text" value="{$smarty.post.info.nick|escape:"htmlall"}" class="input_text"/>
 
	   <label for="email"><b>eMail</b></label>
	   <input name="info[email]" type="text" value="{$smarty.post.info.email|escape:"htmlall"}" class="input_text"/>

	   <label for="password"><b>Password</b></label>
	   <input name="info[password]" type="text" value="{$smarty.post.info.password|escape:"htmlall"}" class="input_text"/>

	   
	   <label for="pwexplain"><small>{translate}Leave it blank if you do not want to change the password!{/translate}</small></label>
       
       <label for="infotext"><b>Infotext</b></label>
	   <input name="info[infotext]" type="text" value="{$smarty.post.info.infotext|escape:"htmlall"}" class="input_text"/>

	   	
	   <fieldset class="radio">
       <legend><b>Userstatus: Activated & Banned </b></legend> 
        
            <label for="activated" class="radio"><b>Activated:</b></label>
            <input name="info[activated]" type="checkbox" value="1" {if $user.activated==1}checked{/if} />
            
            <label for="disabled" class="radio"><b>Disabled:</b></label>
            <input name="info[disabled]" type="checkbox" value="1" {if $user.disabled==1}checked{/if} />
           
        </fieldset> 
       
        <fieldset class="radio">
	    <legend><b>Group Memberships</b></legend>
	    
        {foreach item=item key=key from=$all_groups}
	    
	        <label for="member_of_group_{$item.group_id}" class="radio">
	        <a href="index.php?mod=admin&sub=groups&action=edit&id={$item.group_id}" target="_blank">{$item.name}</a>
	        </label>
            <input type="checkbox" name="info[groups][]" value="{$item.group_id}" {if in_array($item.group_id, $groups)}checked{/if} />
        
	    {/foreach}
        
        </fieldset> 
	              
    </fieldset>

    <fieldset>
    <legend>{translate}Save Inputs{/translate}</legend>
        <div align="right">
        <input class="ButtonGreen" type="submit" name="submit" value="{translate}Create the user{/translate}" />
        <input class="ButtonGrey" type="reset" value="{translate}Reset Input Values{/translate}" tabindex="3" />  
        </div>
    </fieldset>


</form>