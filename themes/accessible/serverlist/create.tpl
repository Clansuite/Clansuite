{move_to target="pre_head_close"}
    {* StyleSheets *}
    <link rel="stylesheet" type="text/css" href="{$www_root_themes_core}/admin/js_color_picker_v2.css" />
    <link rel="stylesheet" type="text/css" href="{$www_root_themes_core}/admin/fieldset.css" />  
    
    {* JavaScripts *}
	<script type="text/javascript" src="{$www_root_themes_core}/javascript/color_functions.js"></script>		
	<script type="text/javascript" src="{$www_root_themes_core}/javascript/js_color_picker_v2.js"></script>      
    {literal}
    <script type="text/javascript">
        function clip_area(id)
        {
            if( document.getElementById(id).style.display == 'none' )
            {
                document.getElementById(id).style.display = 'block';
            }
            else
            {
                document.getElementById(id).style.display = 'none';
            }
        }
    </script>
    {/literal}
{/move_to}

{if $err.no_special_chars == 1} {error title="Special Chars"}       No special chars except '_' and whitespaces are allowed.{/error}    {/if}
{if $err.fill_form == 1}        {error title="Fill form"}           Please fill all necessary fields.{/error}                                     {/if}
{if $err.name_already == 1}     {error title="Name already exists"} The name you have entered already exists in the database.{/error}   {/if}
 
<form target="_self" method="post" accept-charset="UTF-8" action="index.php?mod=controlcenter&sub=serverlist&action=create">

    <table cellpadding="0" cellspacing="0" border="0" align="center" width="500">
    
        <tr class="tr_header">
            <td>
                {t}Description{/t}
            </td>
            <td colspan="2">
                {t}Input{/t}
            </td>
        </tr>
        
        <tr class="tr_row1">
            <td>
                {t}IP{/t}
            </td>
            <td colspan="2">
                <input name="info[ip]" type="text" value="{$smarty.post.info.ip|escape:"htmlall"}" size="40" class="input_text"/>
            </td>
        </tr>
        
        <tr class="tr_row1">
            <td>
                {t}PORT{/t}
            </td>
            <td colspan="2">
                <input name="info[port]" type="text" value="{$smarty.post.info.port|escape:"htmlall"}" size="10" class="input_text"/>
            </td>
        </tr>
                
        <tr class="tr_row2">
            <td>
                {t}Servername{/t}
            </td>
            <td colspan="2">
                <input name="info[name]" type="text" value="{$smarty.post.info.name|escape:"htmlall"}" size="200" class="input_text"/>
            </td>
        </tr>
     
        <tr class="tr_row2">
            <td colspan="3" align="right">
                <input class="ButtonGreen" type="submit" name="submit" value="{t}Add Server{/t}" />
                <input class="ButtonGrey" type="reset" value="{t}Reset{/t}" />        
            </td>
        </tr>
    </table>

</form>