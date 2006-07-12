{$chmod_tpl}

{if $err.no_special_chars == 1}
<div id="cell1" align="center">
    <b>{translate}No special chars except '_' are allowed, because of php and file relating sourcecode. Whitespaces are allowed, except for the name.{/translate}</b>
</div>
{/if}

{if $err.give_correct_url == 1}
<div id="cell1" align="center">
    <b>{translate}Please enter a valid URL or leave the field blank.{/translate}</b>
</div>
{/if}

{if $err.fill_form == 1}
<div id="cell1" align="center">
    <b>{translate}Please fill all fields.{/translate}</b>
</div>
{/if}

{if $err.mod_already_exist == 1}
<div id="cell1" align="center">
    <b>{translate}We are sorry but a module with this name already exists as folder or database setting.{/translate}</b>
</div>
{/if}

<form action="/index.php?mod=admin&sub=admin_modules&action=create_new" method="POST">
<table cellspacing="0" cellpadding="0" border="0" width="100%">
<tr>
    
    <td id="td_header" width="10%">
        {translate}Description{/translate}
    </td>
    
    <td id="td_header" width="55%">
    {translate}Needed information{/translate}
    </td>
    
</tr>
<tr>
   
    <td id="cell1">
        {translate}This will add a fresh, new and clean module with all necessary functions, globals etc.{/translate}
    </td>
    
    <td id="cell2">
        <table border="0" cellpadding="2" cellspacing="2">
            <tr><td><b>{translate}Title:{/translate}</b></td><td><input class="input_text" type="text" name="title" value="{$smarty.post.title|escape:"htmlall"}"></td></tr>
            <tr><td><b>{translate}Name:<br /><div class="font_mini">?mod=name</div>{/translate}</b></td><td><input class="input_text" type="text" name="name" value="{$smarty.post.name|escape:"htmlall"}"></td></tr>
            <tr><td><b>{translate}Description:{/translate}</b></td><td><input class="input_text" type="text" name="description" value="{$smarty.post.description|escape:"htmlall"}"></td></tr>
            <tr><td><b>{translate}Author:{/translate}</b></td><td><input class="input_text" type="text" name="author" value="{$smarty.post.author|escape:"htmlall"}"></td></tr>
            <tr><td><b>{translate}Copyright:{/translate}</b></td><td><input class="input_text" type="text" name="copyright" value="{$smarty.post.copyright|escape:"htmlall"}"></td></tr>
            <tr><td><b>{translate}Homepage:{/translate}</b></td><td><input class="input_text" type="text" name="homepage" value="{$smarty.post.homepage|escape:"htmlall"}"></td></tr>
            <tr><td><b>{translate}License:{/translate}</b></td><td><input class="input_text" type="text" name="license" value="{$smarty.post.license|escape:"htmlall"}"></td></tr>
            <tr><td><b>{translate}Enabled:{/translate}</b></td><td><input type="checkbox" name="enabled" value="1"></td></tr>
        </table>
    </td>   

</tr>
</table>
<p align="center">
    <input class="input_submit" type="submit" value="{translate}Create the new module.{/translate}" name="submit">
</p>
</form>