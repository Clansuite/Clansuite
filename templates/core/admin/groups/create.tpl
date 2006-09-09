{doc_raw}
            {* StyleSheets *}
            <link rel="stylesheet" type="text/css" href="{$www_core_tpl_root}/admin/js_color_picker_v2.css" />
            <link rel="stylesheet" type="text/css" href="{$www_core_tpl_root}/admin/fieldset.css" />  
            
            {* JavaScripts *}
	        <script type="text/javascript" src="{$www_core_tpl_root}/javascript/color_functions.js"></script>		
	        <script type="text/javascript" src="{$www_core_tpl_root}/javascript/js_color_picker_v2.js"></script>      
{/doc_raw}

<h2>{translate}Create a group{/translate}</h2>

{if $err.no_special_chars == 1} {error title="Special Chars"}       No special chars except '_' and whitespaces are allowed.{/error}    {/if}
{if $err.fill_form == 1}        {error title="Fill form"}           Please fill all necessary fields.{/error}                                     {/if}
{if $err.name_already == 1}     {error title="Name already exists"} The name you have entered already exists in the database.{/error}   {/if}
 
 <form target="_self" method="POST" action="index.php?mod=admin&sub=groups&action=create" class="h3sForm">

    <fieldset> 
    <legend>Create a new group</legend> 

    <br />   
       	    
       <label for="permission_name"><b>Name</b></label>
       <input name="info[name]" type="text" value="{$smarty.post.info.name|escape:"htmlall"}" class="input_text"/>
       
       <label for="permission_name"><b>Position</b></label>
       <input name="info[pos]" type="text" value="{$smarty.post.info.pos|escape:"htmlall"}" class="input_text"/>
       
       <label for="permission_name"><b><b>Description</b></label>
       <input name="info[description]" type="text" value="{$smarty.post.info.description|escape:"htmlall"}" class="input_text"/>
       
       <label for="color">
       <b>Color ( <a id="color-href" href="javascript: showColorPicker(document.getElementById('color-href'),document.getElementById('color'));">hex-codes</a> )</b>
       </label>
       <input name="info[color]" id="color" type="text" value="Hex-Code" class="input_text" />

       <label for="icon"><b>Icon</b></label>
       <select class="input_text" name="info[icon]" onChange="document.getElementById('insert_icon').src='{$www_core_tpl_root}/images/groups/'+document.getElementById('icon').options[document.getElementById('icon').options.selectedIndex].text" class="input" id="icon">
            <option name=""></option>
            {foreach key=key item=item from=$icons}
                <option {if $smarty.post.info.icon|escape:"htmlall"==$item}selected{/if} style="background-image:url({$www_core_tpl_root}/images/groups/{$item});background-repeat:no-repeat;padding-left:20px;height:20px;line-height:20px;" id="{$item}" name="{$item}">{$item}</option> );
            {/foreach}
        </select>
        <img src="{$www_core_tpl_root}/images/groups/{$smarty.post.info.icon|escape:"htmlall"}" id="insert_icon" border="1"> 
       
       <label for="image"><b>Image</b></label>
       <select class="input_text" name="info[image]" onChange="document.getElementById('insert_image').src='{$www_core_tpl_root}/images/groups/'+document.getElementById('image').options[document.getElementById('image').options.selectedIndex].text" class="input" id="image">
            <option name=""></option>
            {foreach key=key item=item from=$images}
                <option {if $smarty.post.info.image|escape:"htmlall"==$item}selected{/if} style="background-image:url({$www_core_tpl_root}/images/groups/{$item});background-repeat:no-repeat;padding-left:20px;height:20px;line-height:20px;" id="{$item}" name="{$item}">{$item}</option> );
            {/foreach}
        </select>
        <img src="{$www_core_tpl_root}/images/groups/{$smarty.post.info.image|escape:"htmlall"}" id="insert_image" border="1">

     
     </fieldset>
   
   
    <fieldset>
    <legend>Save Inputs</legend>
        <div align="right">
        <input class="ButtonGreen" type="submit" name="submit" value="{translate}Create the group{/translate}" />
        <input class="ButtonGrey" type="reset" value="Reset Input Values" tabindex="3" />
        </div>
    </fieldset>     
    
</form>