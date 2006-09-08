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
 
<form action="index.php?mod=admin&sub=groups&action=create" method="POST" target="_self">

<table cellpadding="4" cellspacing="0" border="0">
<tr>
    <td colspan="2" class="td_header_small">
        Create a new user
    </td>
</tr>
<tr>
    <td class="cell1"><b>First Name:</b></td>
    <td><input name="info[first_name]" type="text" value="" class="input_text"/></td>
</tr>
<tr>
    <td class="cell1"><b>Last Name:</b></td>
    <td><input name="info[last_name]" type="text" value="" class="input_text"/></td>
</tr>
<tr>
    <td class="cell1"><b>Nick:</b></td>
    <td><input name="info[nick]" type="text" value="" class="input_text"/></td>
</tr>
<tr>
    <td class="cell1"><b>eMail:</b></td>
    <td><input name="info[email]" type="text" value="" class="input_text"/></td>
</tr>
<tr>
    <td class="cell1"><b>Password:</b></td>
    <td><input name="info[password]" type="password" value="" class="input_text"/></td>
</tr>
<tr>
    <td class="cell1"><b>Infotext:</b></td>
    <td><input name="info[infotext]" type="text" value="" class="input_text"/></td>
</tr>
<tr>
    <td class="cell1"><b>Activated:</b></td>
    <td align="left"><input name="info[activated]" type="checkbox" value="1" /></td>
</tr>
<tr>
    <td colspan="2" align="center">
        <input class="input_submit" type="submit" name="submit" value="{translate}Create the user{/translate}" />
    </td>
</tr>
</table>
</form>