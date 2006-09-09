
{doc_raw}
<link rel="stylesheet" type="text/css" href="{$www_core_tpl_root}/admin/adminmenu/DynamicTree.css" />

<link rel="stylesheet" type="text/css" href="{$www_core_tpl_root}/admin/luna.css" />
<script type="text/javascript" src="{$www_core_tpl_root}/javascript/tabpane.js"></script>
{literal}
<script type="text/javascript">

function str_replace (search, replace, subject)
{
  var result = "";
  var  oldi = 0;
  for (i = subject.indexOf (search)
     ; i > -1
     ; i = subject.indexOf (search, i))
  {
    result += subject.substring (oldi, i);
    result += replace;
    i += search.length;
    oldi = i;
  }
  return result + subject.substring (oldi, subject.length);
}

function clip_core_mods(id)
{
    if(document.getElementById("core_span_" + id).style.display == 'none')
    {
        document.getElementById("core_span_" + id).style.display = "block";
    }
    else
    {
        document.getElementById("core_span_" + id).style.display = "none";
    }
}



function sub_delete(tr)
{
    document.getElementById(tr + "_tr1").innerHTML = "";
    document.getElementById(tr + "_tr2").innerHTML = "";
    document.getElementById(tr + "_tr3").innerHTML = "";
    document.getElementById(tr + "_tr4").innerHTML = "";
    document.getElementById(tr + "_tr5").innerHTML = "";
}

/***********************************************
* Drop Down/ Overlapping Content- © Dynamic Drive (www.dynamicdrive.com)
* This notice must stay intact for legal use.
* Visit http://www.dynamicdrive.com/ for full source code
*
* Enhanced by x!sign.dll
***********************************************/
function getposOffset(overlay, offsettype)
{
    var totaloffset=(offsettype=="left")? overlay.offsetLeft : overlay.offsetTop;
    var parentEl=overlay.offsetParent;
    while (parentEl!=null)
    {
        totaloffset=(offsettype=="left")? totaloffset+parentEl.offsetLeft : totaloffset+parentEl.offsetTop;
        parentEl=parentEl.offsetParent;
    }
    return totaloffset;
}

function sub_add(table,mod_id,name,object)
{
    if ( name == '' )
    {
        var new_obj=document.getElementById('enter_sub_name')
        new_obj.style.display=(new_obj.style.display!="block")? "block" : "none"
        var xpos=getposOffset(object, "left")+((typeof opt_position!="undefined" && opt_position.indexOf("right")!=-1)? -(new_obj.offsetWidth-object.offsetWidth) : 0) 
        var ypos=getposOffset(object, "top")+((typeof opt_position!="undefined" && opt_position.indexOf("bottom")!=-1)? object.offsetHeight : 0) - 30
        new_obj.style.left=xpos+"px"
        new_obj.style.top=ypos+"px"
        c1 = str_replace('{$mod_id}', mod_id, new_obj.innerHTML);
        c2 = str_replace("{$table}", table, c1);
        new_obj.innerHTML = c2;
    }
    else
    {

        document.getElementById('enter_sub_name').style.display = 'none';
        content = document.getElementById('subs_container').innerHTML;
        if ( document.getElementById('create_sub_file').checked == true )
        {
            new_content = content + document.getElementById('hidden_input_container').innerHTML;
        }
        else
        {
            new_content = content;
        }
        c1 = str_replace('{$wert.module_id}', mod_id, new_content);
        c2 = str_replace("{$key}", document.getElementById('subs_name').value, c1);
        new_table = document.getElementById(table).innerHTML+c2;

        document.getElementById(table).innerHTML = new_table;
        window.location.hash=mod_id+'_'+document.getElementById('subs_name').value;
        if( document.getElementById(mod_id+'_no_subs') )
        {
            document.getElementById(mod_id+'_no_subs').innerHTML = '';
            document.getElementById(mod_id+'_no_subs').outerHTML = '';
        }
        

    }
}

function checker(checkboxen, caller)
{
    if ( !this.loaded )
    {
        this.loaded = new Array;
    }
    
    checkbox = checkboxen.split(",");

    for( x=0; x<checkbox.length; x++ )
    {
        if( document.getElementById(caller).checked )
        {
            document.getElementById(checkbox[x]).checked=1;
            
            if ( this.loaded[checkbox[x]] >= 1 )
            {
                this.loaded[checkbox[x]]++;
            }
            else
            {
                this.loaded[checkbox[x]] = 1;
            }
        }
        else
        {
            //document.write(this.loaded[checkbox[x]]);
            this.loaded[checkbox[x]]--;
            if( this.loaded[checkbox[x]] == 0 )
                document.getElementById(checkbox[x]).checked=0;
        }
    }
}
{/literal}
</script>
{/doc_raw}

{literal}
<div id="hidden_input_container" style="display: none;">
<input type="hidden" name="info[{$wert.module_id}][subs][{$key}][create_sub]" value="1">
</div>

<div id="enter_sub_name" style="position:absolute; border: 1px solid orange; background-color: white; width: 300px; padding: 8px; display:none">
    <center>
    <b>{/literal}{translate}{literal}Name:{/literal}{/translate}{literal}</b>&nbsp;<input class="input_text" type="text" value="" id="subs_name" size="40"><br />
    <input type="checkbox" value="1" name="create_sub_file" id="create_sub_file">Create the submodule file?<br />
    <a href="javascript:sub_add('{$table}', '{$mod_id}', 'check_text_field')">Confirm</a>&nbsp;|&nbsp;<a href="javascript:void(document.getElementById('enter_sub_name').style.display = 'none');">Abort</a><br />
    </center>
</div>

<table id="subs_container" style="display: none">
    
    <tr id="{$wert.module_id}_sub_{$key}_tr1">
        <td width="40" height="20">
            <a name="{$wert.module_id}_{$key}">
            <b>{/literal}{translate}Name:{/translate}{literal}</b>
        </td>
        <td width="165">
            <span onDblClick="javascript:clip_edit('{$wert.module_id}','_subs_{$key}_name');" id="{$wert.module_id}_subs_{$key}_name_text" style="display: none"></span>
            <input onBlur="javascript:clip_edit('{$wert.module_id}','_subs_{$key}_name');" class="input_text" type="textarea" id="{$wert.module_id}_subs_{$key}_name" style="display: block" name="info[{$wert.module_id}][subs][{$key}][name]" value="{$key}" size="30"></td>
    </tr>
    <tr id="{$wert.module_id}_sub_{$key}_tr2">
        <td height="20">
            <b>{/literal}{translate}File:{/translate}{literal}</b>
        </td>
        <td>
            <span onDblClick="javascript:clip_edit('{$wert.module_id}','_subs_{$key}_file');" id="{$wert.module_id}_subs_{$key}_file_text" style="display: none"></span>
            <input onBlur="javascript:clip_edit('{$wert.module_id}','_subs_{$key}_file');" class="input_text" type="textarea" id="{$wert.module_id}_subs_{$key}_file" style="display: block" name="info[{$wert.module_id}][subs][{$key}][file]" value="" size="30"></td>
    </tr>
    <tr id="{$wert.module_id}_sub_{$key}_tr3">
        <td height="20">
            <b>Class:</b>
        </td>
        <td>
            <span onDblClick="javascript:clip_edit('{$wert.module_id}','_subs_{$key}_class');" id="{$wert.module_id}_subs_{$key}_class_text" style="display: none"></span>
            <input onBlur="javascript:clip_edit('{$wert.module_id}','_subs_{$key}_class');" class="input_text" type="textarea" id="{$wert.module_id}_subs_{$key}_class" style="display: block" name="info[{$wert.module_id}][subs][{$key}][class]" value="" size="30"></td>
    </tr>
    <tr id="{$wert.module_id}_sub_{$key}_tr4">
        <td colspan="2" height="20">
        <a href="javascript:sub_delete('{$wert.module_id}_sub_{$key}');">{/literal}{translate}Delete submodule{/translate}{literal} '{$key}' {/literal}{translate}from whitelist{/translate}{literal}</a>
        </td>
    </tr>
    <tr id="{$wert.module_id}_sub_{$key}_tr5">
        <td colspan="3" height="20">
        &nbsp;
        </td>
    </tr>
    
</table>
{/literal}

<h2>{translate}Modulemanagement{/translate}</h2>

{if isset($content.not_in_whitelist)}
<form action="index.php?mod=admin&sub=modules&action=add_to_whitelist" method="POST">
<table cellspacing="0" cellpadding="0" border="0" width="100%">
<tr>
    
    <td class="td_header" width="150">
    {translate}Module folder problem{/translate}
    </td>
    
    <td class="td_header" width="100">
    {translate}Folder{/translate}
    </td>
    
    <td class="td_header">
    {translate}Additional information{/translate}
    </td>
</tr>
{foreach key=schluessel item=wert from=$content.not_in_whitelist}
<tr>

    <td class="cell1">
        <b>{translate}There is a module folder that is not stored in the databases whitelist.{/translate}</b>
    </td>
    
    <td class="cell2">
        {$wert.folder}
    </td>
    
    <td class="cell1">
        {if $wert.no_module_config == 1}
            <font color="red">{translate}The modulename.config.php is missing! You have to add this file manually into the modules folder.{/translate}</font>
            <font color="red">{translate}Those values are EXAMPLES! They do not represent the current file settings in any way!{/translate}</font>
        {/if}
        <table border="0" cellpadding="2" cellspacing="2">
        {if $wert.no_module_config == 1}
            <tr><td><b>{translate}Title:{/translate}</b></td><td><input class="input_text" type="text" name="info[{$wert.folder_name}][title]" value="{$wert.folder_name|ucfirst}"></td></tr>
            <tr><td><b>{translate}Name:<br /><div class="font_mini">?mod=name</div>{/translate}</b></td><td><input class="input_text" type="text" name="info[{$wert.folder_name}][name]" value="{$wert.folder_name}"></td></tr>
            <tr><td><b>{translate}Author:{/translate}</b></td><td><input class="input_text" type="text" name="info[{$wert.folder_name}][author]" value="{translate}Your Name{/translate}"></td></tr>
            <tr><td><b>{translate}Homepage:{/translate}</b></td><td><input class="input_text" type="text" name="info[{$wert.folder_name}][homepage]" value="http://www.{$wert.folder_name}.com"></td></tr>
            <tr><td><b>{translate}License:{/translate}</b></td><td><input class="input_text" type="text" name="info[{$wert.folder_name}][license]" value="GPL v2"></td></tr>
            <tr><td><b>{translate}Copyright:{/translate}</b></td><td><input class="input_text" type="text" name="info[{$wert.folder_name}][copyright]" value="{translate}Your Name{/translate}"></td></tr>
            <tr><td><b>{translate}Description:{/translate}</b></td><td><input class="input_text" type="text" name="info[{$wert.folder_name}][description]" value="{$wert.folder_name|ucfirst} module - your description"></td></tr>
            <tr><td><b>{translate}Filename:{/translate}</b></td><td><input class="input_text" type="text" name="info[{$wert.folder_name}][file_name]" value="{$wert.folder_name}.module.php"></td></tr>
            <tr><td><b>{translate}Foldername:{/translate}</b></td><td><input class="input_text" type="text" name="info[{$wert.folder_name}][folder_name]" value="{$wert.folder_name}"></td></tr>
            <tr><td><b>{translate}Classname:{/translate}</b></td><td><input class="input_text" type="text" name="info[{$wert.folder_name}][class_name]" value="module_{$wert.folder_name}"></td></tr>
            <tr><td><b>{translate}Imagename:{/translate}</b></td><td><input class="input_text" type="text" name="info[{$wert.folder_name}][image_name]" value="module_{$wert.folder_name}.jpg"></td></tr>
            <tr><td><b>{translate}Version:{/translate}</b></td><td><input class="input_text" type="text" name="info[{$wert.folder_name}][version]" value="0.1"></td></tr>
            <input type="hidden" name="info[{$wert.name}][subs]" value=""></td></tr>
            <tr><td><b>{translate}Enabled?{/translate}</b></td><td><input type="checkbox" name="info[{$wert.foldername}][enabled]" value="1"></td></tr>
            <tr><td><b>{translate}Core?{/translate}</b></td><td><input type="checkbox" name="info[{$wert.foldername}][core]" value="1"></td></tr>
            <tr><td><b>{translate}Add?{/translate}</b></td><td><input type="checkbox" name="info[{$wert.foldername}][add]" value="1" checked></td></tr>
        {else}
            <tr><td><b>{translate}Title:{/translate}</b></td><td><input class="input_text" type="text" name="info[{$wert.name}][title]" value="{$wert.title|escape:"html"}"></td></tr>
            <tr><td><b>{translate}Name:<br /><div class="font_mini">?mod=name</div>{/translate}</b></td><td><input class="input_text" type="text" name="info[{$wert.name}][name]" value="{$wert.name|escape:"html"}"></td></tr>
            <tr><td><b>{translate}Author:{/translate}</b></td><td><input class="input_text" type="text" name="info[{$wert.folder_name}][author]" value="{$wert.author|escape:"html"}"></td></tr>
            <tr><td><b>{translate}Homepage:{/translate}</b></td><td><input class="input_text" type="text" name="info[{$wert.folder_name}][homepage]" value="{$wert.homepage|escape:"html"}"></td></tr>
            <tr><td><b>{translate}License:{/translate}</b></td><td><input class="input_text" type="text" name="info[{$wert.folder_name}][license]" value="{$wert.license|escape:"html"}"></td></tr>
            <tr><td><b>{translate}Copyright:{/translate}</b></td><td><input class="input_text" type="text" name="info[{$wert.folder_name}][copyright]" value="{$wert.copyright|escape:"html"}"></td></tr>
            <tr><td><b>{translate}Description:{/translate}</b></td><td><input class="input_text" type="text" name="info[{$wert.name}][description]" value="{$wert.description|escape:"html"}"></td></tr>
            <tr><td><b>{translate}Filename:{/translate}</b></td><td><input class="input_text" type="text" name="info[{$wert.name}][file_name]" value="{$wert.file_name|escape:"html"}"></td></tr>
            <tr><td><b>{translate}Foldername:{/translate}</b></td><td><input class="input_text" type="text" name="info[{$wert.name}][folder_name]" value="{$wert.folder_name|escape:"html"}"></td></tr>
            <tr><td><b>{translate}Classname:{/translate}</b></td><td><input class="input_text" type="text" name="info[{$wert.name}][class_name]" value="{$wert.class_name|escape:"html"}"></td></tr>
            <tr><td><b>{translate}Imagename:{/translate}</b></td><td><input class="input_text" type="text" name="info[{$wert.name}][image_name]" value="{$wert.image_name|escape:"html"}"></td></tr>
            <tr><td><b>{translate}Version:{/translate}</b></td><td><input class="input_text" type="text" name="info[{$wert.name}][version]" value="{$wert.version|escape:"html"}"></td></tr>
            <tr><td><b>{translate}Subs:{/translate}</b></td><td><input class="input_text" type="text" name="info[{$wert.name}][subs]" value="{$wert.subs|escape:"html"}"></td></tr>
            <tr><td><b>{translate}CS Version:{/translate}</b></td><td><input class="input_text" type="text" name="info[{$wert.name}][cs_version]" value="{$wert.cs_version|escape:"html"}"></td></tr>
            <tr><td><b>{translate}Enabled?{/translate}</b></td><td><input type="checkbox" name="info[{$wert.name}][enabled]" value="1"></td></tr>
            <tr><td><b>{translate}Core?{/translate}</b></td><td><input type="checkbox" name="info[{$wert.name}][core]" value="1"></td></tr>
            <tr><td><b>{translate}Add?{/translate}</b></td><td><input type="checkbox" name="info[{$wert.name}][add]" value="1" checked></td></tr>
        {/if}
        </table>
    </td>
</tr>
{/foreach}
</table>
<p align="center">
    <input class="ButtonGrey" type="submit" value="{translate}Add the module(s) into the whitelist.{/translate}" name="submit">
</p>
</form>
{/if}


<form action="index.php?mod=admin&sub=modules&action=update" method="POST">

<table cellspacing="0" cellpadding="0" border="0" width="100%">

<tr>
    <td class="td_header" width="100%" colspan="4">
    {translate}Normal modules{/translate}
    </td>
</tr>
<tr>
    
    <td class="td_header_small" width="120px">
    {translate}Title{/translate}
    </td>
    
    <td class="td_header_small" width="80%">
    {translate}Information{/translate}
    </td>
    
    <td class="td_header_small" width="5%" align="center">
    {translate}Enabled{/translate}
    </td>
    
    <td class="td_header_small" width="5%" align="center">
    {translate}Delete{/translate}
    </td>    

</tr>
{foreach key=schluessel item=wert from=$content.whitelisted.normal}
<tr>
    <input type="hidden" name="ids[]" value="{$wert.module_id}">
    <td class="cell1" align="center">
        <b>{$wert.title}</b><br />
        <img width="100px" height="100px" src="{$www_core_tpl_root}/images/modules/{$wert.image_name}">
    </td>
    
    <td class="cell2">
        <div id="{$wert.module_id}_remember_to_update" style="display: none; padding: 10px;"><b><font color="red">{translate}Remember to press the update button below!{/translate}</font></b></div>
        <div class="tab-pane" id="{$wert.name}">
    
        <script type="text/javascript">
        tp1 = new WebFXTabPane( document.getElementById( "{$wert.name}" ) );
        </script>
    	<div class="tab-page" id="{$wert.name}_generals">
    	   <h2 class="tab">General</h2>
    	   <script type="text/javascript">tp1.addTabPage( document.getElementById( "{$wert.name}_generals" ) );</script>
            <table cellpadding="2" cellspacing="2" border="0">
            
            {foreach key=key item=item from=$content.generals}
                <tr>
                <td width="90"><b>{translate}{$key}:{/translate}</b></td>
                <td width="250" height="25"><span onDblClick="javascript:clip_edit('{$wert.module_id}','_{$wert.name}_{$item}');" id="{$wert.module_id}_{$wert.name}_{$item}_text">{$wert.$item}</span>
                <input onBlur="javascript:clip_edit('{$wert.module_id}','_{$wert.name}_{$item}');" class="input_text" type="textarea" id="{$wert.module_id}_{$wert.name}_{$item}" style="display: none" name="info[{$wert.module_id}][{$item}]" value="{$wert.$item}" size="40"></td>
                </tr>
            {/foreach}
                      
            </table>
        </div>
    
    	<div class="tab-page" id="{$wert.name}_more">
    	   <h2 class="tab">Moduldetails</h2>
    	   <script type="text/javascript">tp1.addTabPage( document.getElementById( "{$wert.name}_more" ) );</script>    
    
            <table cellpadding="2" cellspacing="2" border="0">
    
            {foreach key=key item=item from=$content.more}
                <tr>
                <td width="90"><b>{translate}{$key}:{/translate}</b></td>
                <td width="250" height="25"><span onDblClick="javascript:clip_edit('{$wert.module_id}','_{$wert.name}_{$item}');" id="{$wert.module_id}_{$wert.name}_{$item}_text">{$wert.$item}</span>
                <input onBlur="javascript:clip_edit('{$wert.module_id}','_{$wert.name}_{$item}');" class="input_text" type="textarea" id="{$wert.module_id}_{$wert.name}_{$item}" style="display: none" name="info[{$wert.module_id}][{$item}]" value="{$wert.$item}" size="40"></td>
                </tr>
            {/foreach}
                    
            </table>
        </div>
    
    	<div class="tab-page" id="{$wert.name}_subs">
    	   <h2 class="tab">Submodules</h2>
    	   <script type="text/javascript">tp1.addTabPage( document.getElementById( "{$wert.name}_subs" ) );</script>
           
           <table cellpadding="2" cellspacing="2" border="0">    
            <tr>
                <td><b>{translate}Submodules:{/translate}</b>{if is_array($wert.subs)}<br /><a href="javascript:void(0)" onClick="return sub_add('{$wert.module_id}_subs', '{$wert.module_id}', '', this);">Add a submodule</a>{/if}</td>
                <td>
                    <table cellspacing="0" cellpadding="0" border="0" width="100%" id="{$wert.module_id}_subs">
                    {if is_array($wert.subs)}
                    {foreach key=key item=item from=$wert.subs}
                    <tr id="{$wert.module_id}_sub_{$key}_tr1">
                        <td width="40" height="20">
                            <b>Name:</b>
                        </td>
                        <td width="165">
                            <span onDblClick="javascript:clip_edit('{$wert.module_id}','_subs_{$key}_name');" id="{$wert.module_id}_subs_{$key}_name_text">{$key}</span>
                            <input onBlur="javascript:clip_edit('{$wert.module_id}','_subs_{$key}_name');" class="input_text" type="textarea" id="{$wert.module_id}_subs_{$key}_name" style="display: none" name="info[{$wert.module_id}][subs][{$key}][name]" value="{$key}" size="30"></td>
                    </tr>
                    <tr id="{$wert.module_id}_sub_{$key}_tr2">
                        <td height="20">
                            <b>File:</b>
                        </td>
                        <td>
                            <span onDblClick="javascript:clip_edit('{$wert.module_id}','_subs_{$key}_file');" id="{$wert.module_id}_subs_{$key}_file_text">{$item[0]}</span>
                            <input onBlur="javascript:clip_edit('{$wert.module_id}','_subs_{$key}_file');" class="input_text" type="textarea" id="{$wert.module_id}_subs_{$key}_file" style="display: none" name="info[{$wert.module_id}][subs][{$key}][file]" value="{$item[0]}" size="30"></td>
                    </tr>
                    <tr id="{$wert.module_id}_sub_{$key}_tr3">
                        <td height="20">
                            <b>Class:</b>
                        </td>
                        <td>
                            <span onDblClick="javascript:clip_edit('{$wert.module_id}','_subs_{$key}_file');" id="{$wert.module_id}_subs_{$key}_class_text">{$item[1]}</span>
                            <input onBlur="javascript:clip_edit('{$wert.module_id}','_subs_{$key}_file');" class="input_text" type="textarea" id="{$wert.module_id}_subs_{$key}_class" style="display: none" name="info[{$wert.module_id}][subs][{$key}][class]" value="{$item[1]}" size="30"></td>
                    </tr>
                    <tr id="{$wert.module_id}_sub_{$key}_tr4">
                        <td colspan="2" height="20">
                        <a href="javascript:sub_delete('{$wert.module_id}_sub_{$key}');">Delete submodule '{$key}' from whitelist</a>
                        </td>
                    </tr>
                    <tr id="{$wert.module_id}_sub_{$key}_tr5">
                        <td colspan="3" height="20">
                        &nbsp;
                        </td>
                    </tr>
                    {/foreach}
                    {else}
                    <tr id="{$wert.module_id}_no_subs">
                    <td>
                    {translate}No submodules{/translate}
                    </td>
                    </tr>
                    {/if}
                    </table>
                    <table cellspacing="0" cellpadding="0" border="0" width="100%">
                    <tr>
                        <td colspan="3" height="20">
                        &nbsp;
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" height="20">
                        <a href="javascript:void(0)" onClick="return sub_add('{$wert.module_id}_subs', '{$wert.module_id}', '', this);">Add a submodule</a>
                        </td>
                    </tr>
                    </table>
                </td>
            </tr>
            </table>
        </div>

    </td>
    
    <td class="cell1" align="center">
        <input name="enabled[]" type="checkbox" value="{$wert.module_id}" {if $wert.enabled == 1} checked{/if}>
    </td>
    
    <td class="cell2" align="center">
        <input name="delete[]" type="checkbox" value="{$wert.module_id}">
    </td>

</tr>
{/foreach}
</table>

<p align="center">
    <input class="ButtonGrey" type="submit" value="{translate}Update modules{/translate}" name="submit">
</p>
</form>
<br /><br />
<center><a href="javascript:clip_core_mods('1')">{translate}Show core modules{/translate}</a></center>
<br /><br />

<span id="core_span_1" style="display: none;">
<form action="index.php?mod=admin&sub=modules&action=update" method="POST">
<table cellspacing="0" cellpadding="0" border="0" width="100%">
<tr>
    <td class="td_header" width="100%" colspan="4">
    {translate}Core modules{/translate}
    </td>
</tr>
<tr>
    
    <td class="td_header_small" width="120px">
    {translate}Title{/translate}
    </td>
    
    <td class="td_header_small" width="80%">
    {translate}Information{/translate}
    </td>
    
    <td class="td_header_small" width="5%" align="center">
    {translate}Enabled{/translate}
    </td>
    
    <td class="td_header_small" width="5%" align="center">
    {translate}Delete{/translate}
    </td>    

</tr>
{foreach key=schluessel item=wert from=$content.whitelisted.core}
<tr>
    <input type="hidden" name="ids[]" value="{$wert.module_id}">
    <td class="cell1" align="center">
        <b>{$wert.title}</b><br />
        <img width="100px" height="100px" src="{$www_core_tpl_root}/images/{$wert.image_name}">
    </td>
    
    <td class="cell2">
        <div id="{$wert.module_id}_remember_to_update" style="display: none; padding: 10px;"><b><font color="red">{translate}Remember to press the update button below!{/translate}</font></b></div>
        <div class="tab-pane" id="{$wert.name}">
    
        <script type="text/javascript">
        tp1 = new WebFXTabPane( document.getElementById( "{$wert.name}" ) );
        </script>
    	<div class="tab-page" id="{$wert.name}_generals">
    	   <h2 class="tab">General</h2>
    	   <script type="text/javascript">tp1.addTabPage( document.getElementById( "{$wert.name}_generals" ) );</script>
            <table cellpadding="2" cellspacing="2" border="0">
            
            {foreach key=key item=item from=$content.generals}
                <tr>
                <td width="90"><b>{translate}{$key}:{/translate}</b></td>
                <td width="250" height="25"><span onDblClick="javascript:clip_edit('{$wert.module_id}','_{$wert.name}_{$item}');" id="{$wert.module_id}_{$wert.name}_{$item}_text">{$wert.$item}</span>
                <input onBlur="javascript:clip_edit('{$wert.module_id}','_{$wert.name}_{$item}');" class="input_text" type="textarea" id="{$wert.module_id}_{$wert.name}_{$item}" style="display: none" name="info[{$wert.module_id}][{$item}]" value="{$wert.$item}" size="40"></td>
                </tr>
            {/foreach}
                      
            </table>
        </div>
    
    	<div class="tab-page" id="{$wert.name}_more">
    	   <h2 class="tab">Moduldetails</h2>
    	   <script type="text/javascript">tp1.addTabPage( document.getElementById( "{$wert.name}_more" ) );</script>    
    
            <table cellpadding="2" cellspacing="2" border="0">
    
            {foreach key=key item=item from=$content.more}
                <tr>
                <td width="90"><b>{translate}{$key}:{/translate}</b></td>
                <td width="250" height="25"><span onDblClick="javascript:clip_edit('{$wert.module_id}','_{$wert.name}_{$item}');" id="{$wert.module_id}_{$wert.name}_{$item}_text">{$wert.$item}</span>
                <input onBlur="javascript:clip_edit('{$wert.module_id}','_{$wert.name}_{$item}');" class="input_text" type="textarea" id="{$wert.module_id}_{$wert.name}_{$item}" style="display: none" name="info[{$wert.module_id}][{$item}]" value="{$wert.$item}" size="40"></td>
                </tr>
            {/foreach}
                    
            </table>
        </div>
    
    	<div class="tab-page" id="{$wert.name}_subs">
    	   <h2 class="tab">Submodules</h2>
    	   <script type="text/javascript">tp1.addTabPage( document.getElementById( "{$wert.name}_subs" ) );</script>
           
           <table cellpadding="2" cellspacing="2" border="0">    
            <tr>
                <td><b>{translate}Submodules:{/translate}</b>{if is_array($wert.subs)}<br /><a href="javascript:void(0)" onClick="return sub_add('{$wert.module_id}_subs', '{$wert.module_id}', '', this);">Add a submodule</a>{/if}</td>
                <td>
                    <table cellspacing="0" cellpadding="0" border="0" width="100%" id="{$wert.module_id}_subs">
                    {if is_array($wert.subs)}
                    {foreach key=key item=item from=$wert.subs}
                    <tr id="{$wert.module_id}_sub_{$key}_tr1">
                        <td width="40" height="20">
                            <b>Name:</b>
                        </td>
                        <td width="165">
                            <span onDblClick="javascript:clip_edit('{$wert.module_id}','_subs_{$key}_name');" id="{$wert.module_id}_subs_{$key}_name_text">{$key}</span>
                            <input onBlur="javascript:clip_edit('{$wert.module_id}','_subs_{$key}_name');" class="input_text" type="textarea" id="{$wert.module_id}_subs_{$key}_name" style="display: none" name="info[{$wert.module_id}][subs][{$key}][name]" value="{$key}" size="30"></td>
                    </tr>
                    <tr id="{$wert.module_id}_sub_{$key}_tr2">
                        <td height="20">
                            <b>File:</b>
                        </td>
                        <td>
                            <span onDblClick="javascript:clip_edit('{$wert.module_id}','_subs_{$key}_file');" id="{$wert.module_id}_subs_{$key}_file_text">{$item[0]}</span>
                            <input onBlur="javascript:clip_edit('{$wert.module_id}','_subs_{$key}_file');" class="input_text" type="textarea" id="{$wert.module_id}_subs_{$key}_file" style="display: none" name="info[{$wert.module_id}][subs][{$key}][file]" value="{$item[0]}" size="30"></td>
                    </tr>
                    <tr id="{$wert.module_id}_sub_{$key}_tr3">
                        <td height="20">
                            <b>Class:</b>
                        </td>
                        <td>
                            <span onDblClick="javascript:clip_edit('{$wert.module_id}','_subs_{$key}_class');" id="{$wert.module_id}_subs_{$key}_class_text">{$item[1]}</span>
                            <input onBlur="javascript:clip_edit('{$wert.module_id}','_subs_{$key}_class');" class="input_text" type="textarea" id="{$wert.module_id}_subs_{$key}_class" style="display: none" name="info[{$wert.module_id}][subs][{$key}][class]" value="{$item[1]}" size="30"></td>
                    </tr>
                    <tr id="{$wert.module_id}_sub_{$key}_tr4">
                        <td colspan="2" height="20">
                        <a href="javascript:sub_delete('{$wert.module_id}_sub_{$key}');">Delete submodule '{$key}' from whitelist</a>
                        </td>
                    </tr>
                    <tr id="{$wert.module_id}_sub_{$key}_tr5">
                        <td colspan="3" height="20">
                        &nbsp;
                        </td>
                    </tr>
                    {/foreach}
                    {else}
                    <tr id="{$wert.module_id}_no_subs">
                    <td>
                    {translate}No submodules{/translate}
                    </td>
                    </tr>
                    {/if}
                    </table>
                    <table cellspacing="0" cellpadding="0" border="0" width="100%">
                    <tr>
                        <td colspan="3" height="20">
                        &nbsp;
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" height="20">
                        <a href="javascript:void(0)" onClick="return sub_add('{$wert.module_id}_subs', '{$wert.module_id}', '', this);">Add a submodule</a>
                        </td>
                    </tr>
                    </table>
                </td>
            </tr>
            </table>
        </div>

    </td>
    
    <td class="cell1" align="center">
        <input name="enabled[]" type="checkbox" value="{$wert.module_id}" {if $wert.enabled == 1} checked{/if}>
    </td>
    
    <td class="cell2" align="center">
        <input type="checkbox" name="delete[]" value="{$wert.module_id}">
    </td>

</tr>
{/foreach}
</table>
<p align="center">
    <input class="ButtonGrey" type="submit" value="{translate}Update core modules{/translate}" name="submit">
</p>
</form>
</span>

<script type="text/javascript">
setupAllTabs();
{literal}
function clip_edit(id,edit)
{
    self.focus();
    if(document.getElementById(id + edit).style.display == 'none')
    {
        document.getElementById(id + edit).style.display = "block";
        document.getElementById(id + edit + "_text").style.display = "none";
        document.getElementById(id + edit).focus();
    }
    else
    {
        document.getElementById(id + edit).style.display = "none";
        document.getElementById(id + edit + "_text").innerHTML = document.getElementById(id + edit).value;
        document.getElementById(id + edit + "_text").style.display = "block";
        document.getElementById(id + "_remember_to_update").style.display = "block";
    }
}
{/literal}
</script>