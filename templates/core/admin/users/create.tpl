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
        The nick you have entred is already in the database.
    {/error}
{/if}

{if $err.email_already == 1}
    {error title="eMail already stored"}
        The eMail you have entered is already in the database.
    {/error}
{/if}
 
<form action="index.php?mod=admin&sub=users&action=create" method="POST" target="_self">

<table cellpadding="4" cellspacing="0" border="0">
<tr>
    <td colspan="2" class="td_header_small">
        Create a new user
    </td>
</tr>
<tr>
    <td class="cell1"><b>First Name:</b></td>
    <td><input name="info[first_name]" type="text" value="{$smarty.post.info.first_name|escape:"htmlall"}" class="input_text"/></td>
</tr>
<tr>
    <td class="cell1"><b>Last Name:</b></td>
    <td><input name="info[last_name]" type="text" value="{$smarty.post.info.last_name|escape:"htmlall"}" class="input_text"/></td>
</tr>
<tr>
    <td class="cell1"><b>Nick:</b></td>
    <td><input name="info[nick]" type="text" value="{$smarty.post.info.nick|escape:"htmlall"}" class="input_text"/></td>
</tr>
<tr>
    <td class="cell1"><b>eMail:</b></td>
    <td><input name="info[email]" type="text" value="{$smarty.post.info.email|escape:"htmlall"}" class="input_text"/></td>
</tr>
<tr>
    <td class="cell1"><b>Password:</b></td>
    <td><input name="info[password]" type="text" value="{$smarty.post.info.password|escape:"htmlall"}" class="input_text"/></td>
</tr>
<tr>
    <td class="cell1"><b>Infotext:</b></td>
    <td><input name="info[infotext]" type="text" value="{$smarty.post.info.infotext|escape:"htmlall"}" class="input_text"/></td>
</tr>
<tr>
    <td class="cell1"><b>Activated:</b></td>
    <td align="left"><input name="info[activated]" type="checkbox" value="1" {if $smarty.post.info.activated==1}checked{/if}/></td>
</tr>
<tr>
    <td class="cell1"><b>Disabled:</b></td>
    <td align="left"><input name="info[disabled]" type="checkbox" value="1" {if $smarty.post.info.disabled==1}checked{/if}/></td>
</tr>
<tr>
    <td colspan="2" align="center">
        <input class="input_submit" type="submit" name="submit" value="{translate}Create the user{/translate}" />
    </td>
</tr>
</table>
</form>