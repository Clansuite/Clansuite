{literal}
<script>

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

function clip_edit(edit)
{
    if(document.getElementById(edit).style.display == 'none')
    {
        document.getElementById(edit).style.display = "block";
        document.getElementById(edit + "_text").style.display = "none";
        document.getElementById(edit + "_href").innerHTML = "update";
    }
    else
    {
        document.getElementById(edit).style.display = "none";
        document.getElementById(edit + "_text").innerHTML = document.getElementById(edit).value;
        document.getElementById(edit + "_text").style.display = "block";
        document.getElementById(edit + "_href").innerHTML = "edit";
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
        var ypos=getposOffset(object, "top")+((typeof opt_position!="undefined" && opt_position.indexOf("bottom")!=-1)? object.offsetHeight : 0)
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

function clip_more(more)
{
    if(document.getElementById(more+"_more").style.display == 'none')
    {
        document.getElementById(more+"_more").style.display = "block";
    }
    else
    {
        document.getElementById(more+"_more").style.display = "none";
    }
}
</script>
<div id="hidden_input_container" style="display: none;">
<input type="hidden" name="info[{$wert.module_id}][subs][{$key}][create_sub]" value="1">
</div>

<div id="enter_sub_name" style="position:absolute; border: 1px solid orange; background-color: white; width: 300px; padding: 8px; display:none">
    <center>
    <input type="text" value="" id="subs_name" size="40"><br />
    <input type="checkbox" value="1" name="create_sub_file" id="create_sub_file">Create the submodule file?<br />
    <a href="javascript:sub_add('{$table}', '{$mod_id}', 'check_text_field')">Confirm...</a><br />
    </center>
</div>

<table id="subs_container" style="display: none">
    
    <tr id="{$wert.module_id}_sub_{$key}_tr1">
        <td width="40" height="20">
            <a name="{$wert.module_id}_{$key}">
            <b>{/literal}{translate}Name:{/translate}{literal}</b>
        </td>
        <td width="165">
            <span id="{$wert.module_id}_subs_{$key}_name_text" style="display: none"></span>
            <input class="input_text" type="textarea" id="{$wert.module_id}_subs_{$key}_name" style="display: block" name="info[{$wert.module_id}][subs][{$key}][name]" value="{$key}" size="30"></td>
        <td width="80" align="left"><a id="{$wert.module_id}_subs_{$key}_name_href" href="javascript:clip_edit('{$wert.module_id}_subs_{$key}_name');">{/literal}{translate}update{/translate}{literal}</a></td>
    </tr>
    <tr id="{$wert.module_id}_sub_{$key}_tr2">
        <td height="20">
            <b>{/literal}{translate}File:{/translate}{literal}</b>
        </td>
        <td>
            <span id="{$wert.module_id}_subs_{$key}_file_text" style="display: none"></span>
            <input class="input_text" type="textarea" id="{$wert.module_id}_subs_{$key}_file" style="display: block" name="info[{$wert.module_id}][subs][{$key}][file]" value="" size="30"></td>
        <td><a id="{$wert.module_id}_subs_{$key}_file_href" href="javascript:clip_edit('{$wert.module_id}_subs_{$key}_file');">{/literal}{translate}update{/translate}{literal}</a></td>
    </tr>
    <tr id="{$wert.module_id}_sub_{$key}_tr3">
        <td height="20">
            <b>Class:</b>
        </td>
        <td>
            <span id="{$wert.module_id}_subs_{$key}_class_text" style="display: none"></span>
            <input class="input_text" type="textarea" id="{$wert.module_id}_subs_{$key}_class" style="display: block" name="info[{$wert.module_id}][subs][{$key}][class]" value="" size="30"></td>
        <td><a id="{$wert.module_id}_subs_{$key}_class_href" href="javascript:clip_edit('{$wert.module_id}_subs_{$key}_class');">{/literal}{translate}update{/translate}{literal}</a></td>
    </tr>
    <tr id="{$wert.module_id}_sub_{$key}_tr4">
        <td colspan="3" height="20">
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
<form action="{$www_root}/index.php?mod=admin&sub=modules&action=add_to_whitelist" method="POST">
<table cellspacing="0" cellpadding="0" border="0" width="100%">
<tr>
    
    <td id="td_header" width="30%">
    {translate}Module folder problem{/translate}
    </td>
    
    <td id="td_header" width="10%">
    {translate}Foldername{/translate}
    </td>
    
    <td id="td_header" width="55%">
    {translate}Additional information{/translate}
    </td>
    
    <td id="td_header" width="5%" align="center">
    {translate}Add?{/translate}
    </td>

</tr>
{foreach key=schluessel item=wert from=$content.not_in_whitelist}
<tr>

    <td id="cell1">
        {translate}There is a module folder that is not stored in the databases whitelist.{/translate}
    </td>
    
    <td id="cell2">
        {$wert.folder}
    </td>
    
    <td id="cell1">
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
            <tr><td><b>{translate}Enabled:{/translate}</b></td><td><input type="checkbox" name="info[{$wert.folder_name}][enabled]" value="1"></td></tr>
        {else}
            <tr><td><b>{translate}Title:{/translate}</b></td><td><input class="input_text" type="text" name="info[{$wert.name}][title]" value="{$wert.title}"></td></tr>
            <tr><td><b>{translate}Name:<br /><div class="font_mini">?mod=name</div>{/translate}</b></td><td><input class="input_text" type="text" name="info[{$wert.name}][name]" value="{$wert.name}"></td></tr>
            <tr><td><b>{translate}Author:{/translate}</b></td><td><input class="input_text" type="text" name="info[{$wert.folder_name}][author]" value="{$wert.author}"></td></tr>
            <tr><td><b>{translate}Homepage:{/translate}</b></td><td><input class="input_text" type="text" name="info[{$wert.folder_name}][homepage]" value="{$wert.homepage}"></td></tr>
            <tr><td><b>{translate}License:{/translate}</b></td><td><input class="input_text" type="text" name="info[{$wert.folder_name}][license]" value="{$wert.license}"></td></tr>
            <tr><td><b>{translate}Copyright:{/translate}</b></td><td><input class="input_text" type="text" name="info[{$wert.folder_name}][copyright]" value="{$wert.copyright}"></td></tr>
            <tr><td><b>{translate}Description:{/translate}</b></td><td><input class="input_text" type="text" name="info[{$wert.name}][description]" value="{$wert.description}"></td></tr>
            <tr><td><b>{translate}Filename:{/translate}</b></td><td><input class="input_text" type="text" name="info[{$wert.name}][file_name]" value="{$wert.file_name}"></td></tr>
            <tr><td><b>{translate}Foldername:{/translate}</b></td><td><input class="input_text" type="text" name="info[{$wert.name}][folder_name]" value="{$wert.folder_name}"></td></tr>
            <tr><td><b>{translate}Classname:{/translate}</b></td><td><input class="input_text" type="text" name="info[{$wert.name}][class_name]" value="{$wert.class_name}"></td></tr>
            <tr><td><b>{translate}Imagename:{/translate}</b></td><td><input class="input_text" type="text" name="info[{$wert.name}][image_name]" value="{$wert.image_name}"></td></tr>
            <tr><td><b>{translate}Version:{/translate}</b></td><td><input class="input_text" type="text" name="info[{$wert.name}][version]" value="{$wert.version}"></td></tr>
            <tr><td><b>{translate}CS Version:{/translate}</b></td><td><input class="input_text" type="text" name="info[{$wert.name}][cs_version]" value="{$wert.cs_version}"></td></tr>
            <tr><td><b>{translate}Enabled:{/translate}</b></td><td><input type="checkbox" name="info[{$wert.name}][enabled]" value="1"></td></tr>        
        {/if}
        </table>
    </td>
    
    <td id="cell2" align="center">
        <input type="checkbox" name="info[{$wert.folder_name}][add]" value="1">
    </td>    

</tr>
{/foreach}
</table>
<p align="center">
    <input class="input_submit" type="submit" value="{translate}Add the module(s) into the whitelist.{/translate}" name="submit">
</p>
</form>
{/if}
<form action="{$www_root}/index.php?mod=admin&sub=modules&action=update" method="POST">
<table cellspacing="0" cellpadding="0" border="0" width="100%">

<tr>
    <td id="td_header" width="100%" colspan="4">
    {translate}Normal modules{/translate}
    </td>
</tr>
<tr>
    
    <td id="td_header_small" width="120px">
    {translate}Title{/translate}
    </td>
    
    <td id="td_header_small" width="80%">
    {translate}Information{/translate}
    </td>
    
    <td id="td_header_small" width="5%" align="center">
    {translate}Enabled{/translate}
    </td>
    
    <td id="td_header_small" width="5%" align="center">
    {translate}Delete{/translate}
    </td>    

</tr>
{foreach key=schluessel item=wert from=$content.whitelisted.normal}
<tr>
    <input type="hidden" name="ids[]" value="{$wert.module_id}">
    <td id="cell1" align="center">
        <b>{$wert.title}</b><br />
        <img width="100px" height="100px" src="{$www_core_tpl_root}/images/{$wert.image_name}">
    </td>
    
    <td id="cell2">
    <table cellpadding="2" cellspacing="2" border="0">
        
        <tr><td width="90"><b>{translate}Title:{/translate}</b></td>
        <td width="250" height="25"><span id="{$wert.module_id}_{$wert.name}_title_text">{$wert.title}</span>
        <input class="input_text" type="textarea" id="{$wert.module_id}_{$wert.name}_title" style="display: none" name="info[{$wert.module_id}][title]" value="{$wert.title}" size="40"></td>
        <td><a id="{$wert.module_id}_{$wert.name}_title_href" href="javascript:clip_edit('{$wert.module_id}_{$wert.name}_title');">edit</a></td></tr>
        
        <tr><td width="90"><b>{translate}Description:{/translate}</b></td>
        <td width="250" height="25"><span id="{$wert.module_id}_{$wert.name}_description_text">{$wert.description}</span>
        <input class="input_text" type="textarea" id="{$wert.module_id}_{$wert.name}_description" style="display: none" name="info[{$wert.module_id}][description]" value="{$wert.description}" size="40"></td>
        <td><a id="{$wert.module_id}_{$wert.name}_description_href" href="javascript:clip_edit('{$wert.module_id}_{$wert.name}_description');">edit</a></td></tr>
        
        <tr><td><b>{translate}Author:{/translate}</b></td>
        <td width="250" height="25"><span id="{$wert.module_id}_{$wert.name}_author_text">{$wert.author}</span>
        <input class="input_text" type="textarea" id="{$wert.module_id}_{$wert.name}_author" style="display: none" name="info[{$wert.module_id}][author]" value="{$wert.author}" size="40"></td>
        <td><a id="{$wert.module_id}_{$wert.name}_author_href" href="javascript:clip_edit('{$wert.module_id}_{$wert.name}_author');">edit</a></td></tr>
        
        <tr><td><b>{translate}Homepage:{/translate}</b></td>
        <td width="250" height="25"><span id="{$wert.module_id}_{$wert.name}_homepage_text"><a href="{$wert.homepage}" target="_blank">{$wert.homepage}</a></span>
        <input class="input_text" type="textarea" id="{$wert.module_id}_{$wert.name}_homepage" style="display: none" name="info[{$wert.module_id}][homepage]" value="{$wert.homepage}" size="40"></td>
        <td><a id="{$wert.module_id}_{$wert.name}_homepage_href" href="javascript:clip_edit('{$wert.module_id}_{$wert.name}_homepage');">edit</a></td></tr>
        
        <tr><td><b>{translate}URL:{/translate}</b></td><td><a href="{$www_root}/index.php?mod={$wert.name}" target="_blank">/index.php?mod={$wert.name}</a></td></tr>

        <tr><td colspan="3" style="padding-top: 10px; padding-left: 10px;"><a href="javascript:clip_more('{$wert.name}');"><img src="{$www_core_tpl_root}/images/expand.gif" border="0" width="9" height="9">&nbsp;{translate}more...{/translate}</a></td></tr>
        
        <tr>
        <td colspan="3">
        <span id="{$wert.name}_more" style="display: none">
        <table cellpadding="2" cellspacing="2" border="0">

        <tr><td width="90"><b>{translate}Name:{/translate}</b></td>
        <td width="250" height="25"><span id="{$wert.module_id}_{$wert.name}_name_text">{$wert.name}</span>
        <input class="input_text" type="textarea" id="{$wert.module_id}_{$wert.name}_name" style="display: none" name="info[{$wert.module_id}][name]" value="{$wert.name}" size="40"></td>
        <td><a id="{$wert.module_id}_{$wert.name}_name_href" href="javascript:clip_edit('{$wert.module_id}_{$wert.name}_name');">edit</a></td></tr>
                
        <tr><td width="90"><b>{translate}Version:{/translate}</b></td>
        <td width="250" height="25"><span id="{$wert.module_id}_{$wert.name}_version_text">{$wert.version}</span>
        <input class="input_text" type="textarea" id="{$wert.module_id}_{$wert.name}_version" style="display: none" name="info[{$wert.module_id}][version]" value="{$wert.version}" size="40"></td>
        <td><a id="{$wert.module_id}_{$wert.name}_version_href" href="javascript:clip_edit('{$wert.module_id}_{$wert.name}_version');">edit</a></td></tr>     
           
        <tr><td width="90"><b>{translate}License:{/translate}</b></td>
        <td width="250" height="25"><span id="{$wert.module_id}_{$wert.name}_license_text">{$wert.license}</span>
        <input class="input_text" type="textarea" id="{$wert.module_id}_{$wert.name}_license" style="display: none" name="info[{$wert.module_id}][license]" value="{$wert.license}" size="40"></td>
        <td><a id="{$wert.module_id}_{$wert.name}_license_href" href="javascript:clip_edit('{$wert.module_id}_{$wert.name}_license');">edit</a></td></tr>
        
        <tr><td><b>{translate}Copyright:{/translate}</b></td>
        <td width="250" height="25"><span id="{$wert.module_id}_{$wert.name}_copyright_text">{$wert.copyright}</span>
        <input class="input_text" type="textarea" id="{$wert.module_id}_{$wert.name}_copyright" style="display: none" name="info[{$wert.module_id}][copyright]" value="{$wert.copyright}" size="40"></td>
        <td><a id="{$wert.module_id}_{$wert.name}_copyright_href" href="javascript:clip_edit('{$wert.module_id}_{$wert.name}_copyright');">edit</a></td></tr>
        
        <tr><td><b>{translate}Foldername:{/translate}</b></td>
        <td width="250" height="25"><span id="{$wert.module_id}_{$wert.name}_folder_name_text">{$wert.folder_name}</span>
        <input class="input_text" type="textarea" id="{$wert.module_id}_{$wert.name}_folder_name" style="display: none" name="info[{$wert.module_id}][folder_name]" value="{$wert.folder_name}" size="40"></td>
        <td><a id="{$wert.module_id}_{$wert.name}_folder_name_href" href="javascript:clip_edit('{$wert.module_id}_{$wert.name}_folder_name');">edit</a></td></tr>

        <tr><td><b>{translate}Imagename:{/translate}</b></td>
        <td width="250" height="25"><span id="{$wert.module_id}_{$wert.name}_image_name_text">{$wert.image_name}</span>
        <input class="input_text" type="textarea" id="{$wert.module_id}_{$wert.name}_image_name" style="display: none" name="info[{$wert.module_id}][image_name]" value="{$wert.image_name}" size="40"></td>
        <td><a id="{$wert.module_id}_{$wert.name}_image_name_href" href="javascript:clip_edit('{$wert.module_id}_{$wert.name}_image_name');">edit</a></td></tr>
                
        <tr><td><b>{translate}Classname:{/translate}</b></td>
        <td width="250" height="25"><span id="{$wert.module_id}_{$wert.name}_class_name_text">{$wert.class_name}</span>
        <input class="input_text" type="textarea" id="{$wert.module_id}_{$wert.name}_class_name" style="display: none" name="info[{$wert.module_id}][class_name]" value="{$wert.class_name}" size="40"></td>
        <td><a id="{$wert.module_id}_{$wert.name}_class_name_href" href="javascript:clip_edit('{$wert.module_id}_{$wert.name}_class_name');">edit</a></td></tr>
        
        <tr><td><b>{translate}Filename:{/translate}</b></td>
        <td width="250" height="25"><span id="{$wert.module_id}_{$wert.name}_file_name_text">{$wert.file_name}</span>
        <input class="input_text" type="textarea" id="{$wert.module_id}_{$wert.name}_file_name" style="display: none" name="info[{$wert.module_id}][file_name]" value="{$wert.file_name}" size="40"></td>
        <td><a id="{$wert.module_id}_{$wert.name}_file_name_href" href="javascript:clip_edit('{$wert.module_id}_{$wert.name}_file_name');">edit</a></td></tr>
                    
        </table>
        </td>
        </tr>
        
    </table>
    </td>
    
    <td id="cell1" align="center">
        <input name="enabled[]" type="checkbox" value="{$wert.module_id}" {if $wert.enabled == 1} checked{/if}>
    </td>
    
    <td id="cell2" align="center">
        <input type="checkbox" name="delete[]" value="{$wert.module_id}">
    </td>

</tr>
{/foreach}
</table>
<p align="center">
    <input class="input_submit" type="submit" value="{translate}Update modules{/translate}" name="submit">
</p>
</form>
<br /><br />
<center><a href="javascript:clip_core_mods('1')">{translate}Show core modules{/translate}</a></center>
<br /><br />

<span id="core_span_1" style="display: none;">
<form action="{$www_root}/index.php?mod=admin&sub=modules&action=update" method="POST">
<table cellspacing="0" cellpadding="0" border="0" width="100%">
<tr>
    <td id="td_header" width="100%" colspan="4">
    {translate}Core modules{/translate}
    </td>
</tr>
<tr>
    
    <td id="td_header_small" width="120px">
    {translate}Title{/translate}
    </td>
    
    <td id="td_header_small" width="80%">
    {translate}Information{/translate}
    </td>
    
    <td id="td_header_small" width="5%" align="center">
    {translate}Enabled{/translate}
    </td>
    
    <td id="td_header_small" width="5%" align="center">
    {translate}Delete{/translate}
    </td>    

</tr>
{foreach key=schluessel item=wert from=$content.whitelisted.core}
<tr>
    <input type="hidden" name="ids[]" value="{$wert.module_id}">
    <td id="cell1" align="center">
        <b>{$wert.title}</b><br />
        <img width="100px" height="100px" src="{$www_core_tpl_root}/images/{$wert.image_name}">
    </td>
    
    <td id="cell2">
    <table cellpadding="2" cellspacing="2" border="0" width="500">
        <tr><td colspan="3"><font color="red"><b>{translate}This is a core module! Removing, modifying or deleting it can cause critical system instability!{/translate}</b></font></td></tr>
        
        <tr><td width="90"><b>{translate}Title:{/translate}</b></td>
        <td width="250" height="25"><span id="{$wert.module_id}_{$wert.name}_title_text">{$wert.title}</span>
        <input class="input_text" type="textarea" id="{$wert.module_id}_{$wert.name}_title" style="display: none" name="info[{$wert.module_id}][title]" value="{$wert.title}" size="40"></td>
        <td width="100" align="left"><a id="{$wert.module_id}_{$wert.name}_title_href" href="javascript:clip_edit('{$wert.module_id}_{$wert.name}_title');">edit</a></td></tr>
        
        <tr><td width="90"><b>{translate}Description:{/translate}</b></td>
        <td width="250" height="25"><span id="{$wert.module_id}_{$wert.name}_description_text">{$wert.description}</span>
        <input class="input_text" type="textarea" id="{$wert.module_id}_{$wert.name}_description" style="display: none" name="info[{$wert.module_id}][description]" value="{$wert.description}" size="40"></td>
        <td><a id="{$wert.module_id}_{$wert.name}_description_href" href="javascript:clip_edit('{$wert.module_id}_{$wert.name}_description');">edit</a></td></tr>
        
        <tr><td><b>{translate}Author:{/translate}</b></td>
        <td width="250" height="25"><span id="{$wert.module_id}_{$wert.name}_author_text">{$wert.author}</span>
        <input class="input_text" type="textarea" id="{$wert.module_id}_{$wert.name}_author" style="display: none" name="info[{$wert.module_id}][author]" value="{$wert.author}" size="40"></td>
        <td><a id="{$wert.module_id}_{$wert.name}_author_href" href="javascript:clip_edit('{$wert.module_id}_{$wert.name}_author');">edit</a></td></tr>
        
        <tr><td><b>{translate}Homepage:{/translate}</b></td>
        <td width="250" height="25"><span id="{$wert.module_id}_{$wert.name}_homepage_text"><a href="{$wert.homepage}" target="_blank">{$wert.homepage}</a></span>
        <input class="input_text" type="textarea" id="{$wert.module_id}_{$wert.name}_homepage" style="display: none" name="info[{$wert.module_id}][homepage]" value="{$wert.homepage}" size="40"></td>
        <td><a id="{$wert.module_id}_{$wert.name}_homepage_href" href="javascript:clip_edit('{$wert.module_id}_{$wert.name}_homepage');">edit</a></td></tr>
        
        <tr><td><b>{translate}URL:{/translate}</b></td><td colspan="2"><a href="{$www_root}/index.php?mod={$wert.name}" target="_blank">/index.php?mod={$wert.name}</a></td></tr>

        <tr><td colspan="3" style="padding-top: 10px; padding-left: 10px;"><a href="javascript:clip_more('{$wert.name}');"><img src="{$www_core_tpl_root}/images/expand.gif" border="0" width="9" height="9">&nbsp;{translate}more...{/translate}</a></td></tr>
        
        <tr>
        <td colspan="3">
        <span id="{$wert.name}_more" style="display: none">
        <table cellpadding="2" cellspacing="2" border="0" width="100%">

        <tr><td width="90"><b>{translate}Name:{/translate}</b></td>
        <td width="250" height="25"><span id="{$wert.module_id}_{$wert.name}_name_text">{$wert.name}</span>
        <input class="input_text" type="textarea" id="{$wert.module_id}_{$wert.name}_name" style="display: none" name="info[{$wert.module_id}][name]" value="{$wert.name}" size="40"></td>
        <td width="100" align="left"><a id="{$wert.module_id}_{$wert.name}_name_href" href="javascript:clip_edit('{$wert.module_id}_{$wert.name}_name');">edit</a></td></tr>
                
        <tr><td width="90"><b>{translate}Version:{/translate}</b></td>
        <td width="250" height="25"><span id="{$wert.module_id}_{$wert.name}_version_text">{$wert.version}</span>
        <input class="input_text" type="textarea" id="{$wert.module_id}_{$wert.name}_version" style="display: none" name="info[{$wert.module_id}][version]" value="{$wert.version}" size="40"></td>
        <td><a id="{$wert.module_id}_{$wert.name}_version_href" href="javascript:clip_edit('{$wert.module_id}_{$wert.name}_version');">edit</a></td></tr>     
           
        <tr><td width="90"><b>{translate}License:{/translate}</b></td>
        <td width="250" height="25"><span id="{$wert.module_id}_{$wert.name}_license_text">{$wert.license}</span>
        <input class="input_text" type="textarea" id="{$wert.module_id}_{$wert.name}_license" style="display: none" name="info[{$wert.module_id}][license]" value="{$wert.license}" size="40"></td>
        <td><a id="{$wert.module_id}_{$wert.name}_license_href" href="javascript:clip_edit('{$wert.module_id}_{$wert.name}_license');">edit</a></td></tr>
        
        <tr><td><b>{translate}Copyright:{/translate}</b></td>
        <td width="250" height="25"><span id="{$wert.module_id}_{$wert.name}_copyright_text">{$wert.copyright}</span>
        <input class="input_text" type="textarea" id="{$wert.module_id}_{$wert.name}_copyright" style="display: none" name="info[{$wert.module_id}][copyright]" value="{$wert.copyright}" size="40"></td>
        <td><a id="{$wert.module_id}_{$wert.name}_copyright_href" href="javascript:clip_edit('{$wert.module_id}_{$wert.name}_copyright');">edit</a></td></tr>
        
        <tr><td><b>{translate}Foldername:{/translate}</b></td>
        <td width="250" height="25"><span id="{$wert.module_id}_{$wert.name}_folder_name_text">{$wert.folder_name}</span>
        <input class="input_text" type="textarea" id="{$wert.module_id}_{$wert.name}_folder_name" style="display: none" name="info[{$wert.module_id}][folder_name]" value="{$wert.folder_name}" size="40"></td>
        <td><a id="{$wert.module_id}_{$wert.name}_folder_name_href" href="javascript:clip_edit('{$wert.module_id}_{$wert.name}_folder_name');">edit</a></td></tr>

        <tr><td><b>{translate}Imagename:{/translate}</b></td>
        <td width="250" height="25"><span id="{$wert.module_id}_{$wert.name}_image_name_text">{$wert.image_name}</span>
        <input class="input_text" type="textarea" id="{$wert.module_id}_{$wert.name}_image_name" style="display: none" name="info[{$wert.module_id}][image_name]" value="{$wert.image_name}" size="40"></td>
        <td><a id="{$wert.module_id}_{$wert.name}_image_name_href" href="javascript:clip_edit('{$wert.module_id}_{$wert.name}_image_name');">edit</a></td></tr>
                
        <tr><td><b>{translate}Classname:{/translate}</b></td>
        <td width="250" height="25"><span id="{$wert.module_id}_{$wert.name}_class_name_text">{$wert.class_name}</span>
        <input class="input_text" type="textarea" id="{$wert.module_id}_{$wert.name}_class_name" style="display: none" name="info[{$wert.module_id}][class_name]" value="{$wert.class_name}" size="40"></td>
        <td><a id="{$wert.module_id}_{$wert.name}_class_name_href" href="javascript:clip_edit('{$wert.module_id}_{$wert.name}_class_name');">edit</a></td></tr>
        
        <tr><td><b>{translate}Filename:{/translate}</b></td>
        <td width="250" height="25"><span id="{$wert.module_id}_{$wert.name}_file_name_text">{$wert.file_name}</span>
        <input class="input_text" type="textarea" id="{$wert.module_id}_{$wert.name}_file_name" style="display: none" name="info[{$wert.module_id}][file_name]" value="{$wert.file_name}" size="40"></td>
        <td><a id="{$wert.module_id}_{$wert.name}_file_name_href" href="javascript:clip_edit('{$wert.module_id}_{$wert.name}_file_name');">edit</a></td></tr>

        <tr>
            <td><b>{translate}Submodules:{/translate}</b>{if is_array($wert.subs)}<br /><a href="javascript:void(0)" onClick="return sub_add('{$wert.module_id}_subs', '{$wert.module_id}', '', this);">Add a submodule</a>{/if}</td>
            <td colspan="2">
                <table cellspacing="0" cellpadding="0" border="0" width="100%" id="{$wert.module_id}_subs">
                {if is_array($wert.subs)}
                {foreach key=key item=item from=$wert.subs}
                <tr id="{$wert.module_id}_sub_{$key}_tr1">
                    <td width="40" height="20">
                        <b>Name:</b>
                    </td>
                    <td width="165">
                        <span id="{$wert.module_id}_subs_{$key}_name_text">{$key}</span>
                        <input class="input_text" type="textarea" id="{$wert.module_id}_subs_{$key}_name" style="display: none" name="info[{$wert.module_id}][subs][{$key}][name]" value="{$key}" size="30"></td>
                    <td width="80" align="left"><a id="{$wert.module_id}_subs_{$key}_name_href" href="javascript:clip_edit('{$wert.module_id}_subs_{$key}_name');">edit</a></td>
                </tr>
                <tr id="{$wert.module_id}_sub_{$key}_tr2">
                    <td height="20">
                        <b>File:</b>
                    </td>
                    <td>
                        <span id="{$wert.module_id}_subs_{$key}_file_text">{$item[0]}</span>
                        <input class="input_text" type="textarea" id="{$wert.module_id}_subs_{$key}_file" style="display: none" name="info[{$wert.module_id}][subs][{$key}][file]" value="{$item[0]}" size="30"></td>
                    <td><a id="{$wert.module_id}_subs_{$key}_file_href" href="javascript:clip_edit('{$wert.module_id}_subs_{$key}_file');">edit</a></td>
                </tr>
                <tr id="{$wert.module_id}_sub_{$key}_tr3">
                    <td height="20">
                        <b>Class:</b>
                    </td>
                    <td>
                        <span id="{$wert.module_id}_subs_{$key}_class_text">{$item[1]}</span>
                        <input class="input_text" type="textarea" id="{$wert.module_id}_subs_{$key}_class" style="display: none" name="info[{$wert.module_id}][subs][{$key}][class]" value="{$item[1]}" size="30"></td>
                    <td><a id="{$wert.module_id}_subs_{$key}_class_href" href="javascript:clip_edit('{$wert.module_id}_subs_{$key}_class');">edit</a></td>
                </tr>
                <tr id="{$wert.module_id}_sub_{$key}_tr4">
                    <td colspan="3" height="20">
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
        </td>
        </tr>
        
    </table>
    </td>
    
    <td id="cell1" align="center">
        <input name="enabled[]" type="checkbox" value="{$wert.module_id}" {if $wert.enabled == 1} checked{/if}>
    </td>
    
    <td id="cell2" align="center">
        <input type="checkbox" name="delete[]" value="{$wert.module_id}">
    </td>

</tr>
{/foreach}
</table>
<p align="center">
    <input class="input_submit" type="submit" value="{translate}Update core modules{/translate}" name="submit">
</p>
</form>
</span>