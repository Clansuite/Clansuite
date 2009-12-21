{move_to target="pre_head_close"}
    <link rel="stylesheet" type="text/css" href="{$www_root_themes_core}/admin/adminmenu/DynamicTree.css" />

    <link rel="stylesheet" type="text/css" href="{$www_root_themes_core}/admin/luna.css" />
    <script type="text/javascript" src="{$www_root_themes_core}/javascript/tabpane.js"></script>
    
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
    * Drop Down/ Overlapping Content-  Dynamic Drive (www.dynamicdrive.com)
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
    
    </script>
{/move_to}


<div id="hidden_input_container" style="display: none;">
<input type="hidden" name="info[{$wert.module_id}][subs][{$key}][create_sub]" value="1">
</div>

<div id="enter_sub_name" style="position:absolute; border: 1px solid orange; background-color: white; width: 300px; padding: 8px; display:none">
    <center>
    <strong>{t}Name:{/t}</strong>&nbsp;<input class="input_text" type="text" value="" id="subs_name" size="40"><br />
    <input type="checkbox" value="1" name="create_sub_file" id="create_sub_file">Create the submodule file?<br />
    <a href="javascript:sub_add('{$table}', '{$mod_id}', 'check_text_field')">Confirm</a>&nbsp;|&nbsp;<a href="javascript:void(document.getElementById('enter_sub_name').style.display = 'none');">Abort</a><br />
    </center>
</div>

<table id="subs_container" style="display: none;">
    
    <tr id="{$wert.module_id}_sub_{$item.submodule_id}_tr1">
        <td width="40" height="20">
            <a name="{$wert.module_id}_{$key}">
            <strong>{t}Name:{/t}</strong>
        </td>
        <td width="165">
            <span onDblClick="javascript:clip_edit('{$wert.module_id}','_subs_{$key}_name');" id="{$wert.module_id}_subs_{$key}_name_text" style="display: none;"></span>
            <input onblur="javascript:clip_edit('{$wert.module_id}','_subs_{$key}_name');" class="input_text" type="textarea" id="{$wert.module_id}_subs_{$key}_name" style="display: block" name="info[{$wert.module_id}][subs][{$key}][name]" value="{$key}" size="30"></td>
    </tr>
    <tr id="{$wert.module_id}_sub_{$item.submodule_id}_tr2">
        <td height="20">
            <strong>{t}File:{/t}</strong>
        </td>
        <td>
            <span onDblClick="javascript:clip_edit('{$wert.module_id}','_subs_{$key}_file');" id="{$wert.module_id}_subs_{$key}_file_text" style="display: none;"></span>
            <input onblur="javascript:clip_edit('{$wert.module_id}','_subs_{$key}_file');" class="input_text" type="textarea" id="{$wert.module_id}_subs_{$key}_file" style="display: block" name="info[{$wert.module_id}][subs][{$key}][file]" value="" size="30"></td>
    </tr>
    <tr id="{$wert.module_id}_sub_{$item.submodule_id}_tr3">
        <td height="20">
            <strong>Class:</strong>
        </td>
        <td>
            <span onDblClick="javascript:clip_edit('{$wert.module_id}','_subs_{$key}_class');" id="{$wert.module_id}_subs_{$key}_class_text" style="display: none;"></span>
            <input onblur="javascript:clip_edit('{$wert.module_id}','_subs_{$key}_class');" class="input_text" type="textarea" id="{$wert.module_id}_subs_{$key}_class" style="display: block" name="info[{$wert.module_id}][subs][{$key}][class]" value="" size="30"></td>
    </tr>
    <tr id="{$wert.module_id}_sub_{$item.submodule_id}_tr4">
        <td colspan="2" height="20">
        <a href="javascript:sub_delete('{$wert.module_id}_subtest1_{$key}');">{t}Delete submodule{/t} '{$key}' {t}from whitelist{/t}</a>
        </td>
    </tr>
    <tr id="{$wert.module_id}_sub_{$item.submodule_id}_tr5">
        <td colspan="3" height="20">
        &nbsp;
        </td>
    </tr>
    
</table>


{if isset($content.not_in_whitelist)}

    <form action="index.php?mod=controlcenter&sub=modules&action=add_to_whitelist" method="post" accept-charset="UTF-8">
    <table cellspacing="0" cellpadding="0" border="0" width="100%">
    <tr>
        <td class="td_header" width="150">    {t}Module folder problem{/t}     </td>
        <td class="td_header" width="100">    {t}Folder{/t}                    </td>
        <td class="td_header">                {t}Additional information{/t}    </td>
    </tr>

{foreach key=schluessel item=wert from=$content.not_in_whitelist}
<tr>

    <td class="cell1">
        <strong>{t}There is a module folder that is not stored in the databases whitelist.{/t}</strong>
    </td>
    
    <td class="cell2">
        {$wert.folder}
    </td>
    
    <td class="cell1">
        {if $wert.no_module_config == 1}
            <font color="red">{t}The modulename.config.php is missing! You have to add this file manually into the modules folder.{/t}</font>
            <font color="red">{t}Those values are EXAMPLES! They do not represent the current file settings in any way!{/t}</font>
        {/if}
        <table border="0" cellpadding="2" cellspacing="2">
        {if $wert.no_module_config == 1}
            <tr><td><strong>{t}Title:{/t}</strong></td><td><input class="input_text" type="text" name="info[{$wert.folder_name}][title]" value="{$wert.folder_name|ucfirst}"></td></tr>
            <tr><td><strong>{t}Name:<br /><div class="font_mini">?mod=name</div>{/t}</strong></td><td><input class="input_text" type="text" name="info[{$wert.folder_name}][name]" value="{$wert.folder_name}"></td></tr>
            <tr><td><strong>{t}Author:{/t}</strong></td><td><input class="input_text" type="text" name="info[{$wert.folder_name}][author]" value="{t}Your Name{/t}"></td></tr>
            <tr><td><strong>{t}Homepage:{/t}</strong></td><td><input class="input_text" type="text" name="info[{$wert.folder_name}][homepage]" value="http://www.{$wert.folder_name}.com"></td></tr>
            <tr><td><strong>{t}License:{/t}</strong></td><td><input class="input_text" type="text" name="info[{$wert.folder_name}][license]" value="GPL v2"></td></tr>
            <tr><td><strong>{t}Copyright:{/t}</strong></td><td><input class="input_text" type="text" name="info[{$wert.folder_name}][copyright]" value="{t}Your Name{/t}"></td></tr>
            <tr><td><strong>{t}Description:{/t}</strong></td><td><input class="input_text" type="text" name="info[{$wert.folder_name}][description]" value="{$wert.folder_name|ucfirst} module - your description"></td></tr>
            <tr><td><strong>{t}Filename:{/t}</strong></td><td><input class="input_text" type="text" name="info[{$wert.folder_name}][file_name]" value="{$wert.folder_name}.module.php"></td></tr>
            <tr><td><strong>{t}Foldername:{/t}</strong></td><td><input class="input_text" type="text" name="info[{$wert.folder_name}][folder_name]" value="{$wert.folder_name}"></td></tr>
            <tr><td><strong>{t}Classname:{/t}</strong></td><td><input class="input_text" type="text" name="info[{$wert.folder_name}][class_name]" value="module_{$wert.folder_name}"></td></tr>
            <tr><td><strong>{t}Imagename:{/t}</strong></td><td><input class="input_text" type="text" name="info[{$wert.folder_name}][image_name]" value="module_{$wert.folder_name}.jpg"></td></tr>
            <tr><td><strong>{t}Version:{/t}</strong></td><td><input class="input_text" type="text" name="info[{$wert.folder_name}][version]" value="0.1"></td></tr>
            <input type="hidden" name="info[{$wert.folder_name}][subs]" value=""></td></tr>
            <tr><td><strong>{t}Enabled?{/t}</strong></td><td><input type="checkbox" name="info[{$wert.folder_name}][enabled]" value="1"></td></tr>
            <tr><td><strong>{t}Core?{/t}</strong></td><td><input type="checkbox" name="info[{$wert.folder_name}][core]" value="1"></td></tr>
            <tr><td><strong>{t}Add?{/t}</strong></td><td><input type="checkbox" name="info[{$wert.folder_name}][add]" value="1" checked></td></tr>
        {else}
            <tr><td><strong>{t}Title:{/t}</strong></td><td><input class="input_text" type="text" name="info[{$wert.name}][title]" value="{$wert.title|escape:"html"}"></td></tr>
            <tr><td><strong>{t}Name:<br /><div class="font_mini">?mod=name</div>{/t}</strong></td><td><input class="input_text" type="text" name="info[{$wert.name}][name]" value="{$wert.name|escape:"html"}"></td></tr>
            <tr><td><strong>{t}Author:{/t}</strong></td><td><input class="input_text" type="text" name="info[{$wert.name}][author]" value="{$wert.author|escape:"html"}"></td></tr>
            <tr><td><strong>{t}Homepage:{/t}</strong></td><td><input class="input_text" type="text" name="info[{$wert.name}][homepage]" value="{$wert.homepage|escape:"html"}"></td></tr>
            <tr><td><strong>{t}License:{/t}</strong></td><td><input class="input_text" type="text" name="info[{$wert.name}][license]" value="{$wert.license|escape:"html"}"></td></tr>
            <tr><td><strong>{t}Copyright:{/t}</strong></td><td><input class="input_text" type="text" name="info[{$wert.name}][copyright]" value="{$wert.copyright|escape:"html"}"></td></tr>
            <tr><td><strong>{t}Description:{/t}</strong></td><td><input class="input_text" type="text" name="info[{$wert.name}][description]" value="{$wert.description|escape:"html"}"></td></tr>
            <tr><td><strong>{t}Filename:{/t}</strong></td><td><input class="input_text" type="text" name="info[{$wert.name}][file_name]" value="{$wert.file_name|escape:"html"}"></td></tr>
            <tr><td><strong>{t}Foldername:{/t}</strong></td><td><input class="input_text" type="text" name="info[{$wert.name}][folder_name]" value="{$wert.folder_name|escape:"html"}"></td></tr>
            <tr><td><strong>{t}Classname:{/t}</strong></td><td><input class="input_text" type="text" name="info[{$wert.name}][class_name]" value="{$wert.class_name|escape:"html"}"></td></tr>
            <tr><td><strong>{t}Imagename:{/t}</strong></td><td><input class="input_text" type="text" name="info[{$wert.name}][image_name]" value="{$wert.image_name|escape:"html"}"></td></tr>
            <tr><td><strong>{t}Module Version:{/t}</strong></td><td><input class="input_text" type="text" name="info[{$wert.name}][module_version]" value="{$wert.module_version|escape:"html"}"></td></tr>
            <tr><td><strong>{t}Subs:{/t}</strong></td><td><input class="input_text" type="text" name="info[{$wert.name}][subs]" value="{$wert.subs|escape:"html"}"></td></tr>
            <tr><td><strong>{t}Clansuite Version:{/t}</strong></td><td><input class="input_text" type="text" name="info[{$wert.name}][clansuite_version]" value="{$wert.clansuite_version|escape:"html"}"></td></tr>
            <tr><td><strong>{t}Enabled?{/t}</strong></td><td><input type="checkbox" name="info[{$wert.name}][enabled]" value="1"></td></tr>
            <tr><td><strong>{t}Core?{/t}</strong></td><td><input type="checkbox" name="info[{$wert.name}][core]" value="1"></td></tr>
            <tr><td><strong>{t}Add?{/t}</strong></td><td><input type="checkbox" name="info[{$wert.name}][add]" value="1" checked></td></tr>
        {/if}
        </table>
    </td>
</tr>
{/foreach}
</table>
<p align="center">
    <input class="ButtonGrey" type="submit" value="{t}Add the module(s) into the whitelist.{/t}" name="submit">
</p>
</form>
{/if}       {* END if isset ($content.not_in_whitelist) *}


{* #################           UPDATE           ################ *}


<form action="index.php?mod=controlcenter&sub=modules&action=update" method="post" accept-charset="UTF-8">

<table cellspacing="0" cellpadding="0" border="0" width="100%">
<tr>
    <td class="td_header" width="100%" colspan="4">    {t}Normal modules{/t}    </td>
</tr>
<tr>
    <td class="td_header_small" width="120px">              {t}Title{/t}        </td>
    <td class="td_header_small" width="80%">                {t}Information{/t}  </td>
    <td class="td_header_small" width="5%" align="center">  {t}Enabled{/t}      </td>
    <td class="td_header_small" width="5%" align="center">  {t}Delete{/t}       </td>    
</tr>

{foreach key=schluessel item=wert from=$content.whitelisted.normal}

<tr>
    <input type="hidden" name="ids[]" value="{$wert.module_id}">
    <td class="cell1" align="center">
        <strong>{$wert.title} </strong> (#{$wert.module_id})<br />
        <img width="100px" height="100px" src="{$www_root_themes_core}/images/modules/{$wert.image_name}">
    </td>
    
    <td class="cell2">
        <div id="{$wert.module_id}_remember_to_update" style="display: none; padding: 10px;">
            <strong><font color="red">{t}Remember to press the "Update" Button below, to save your changes!{/t}</font></strong>
        </div>
        
        <div class="tab-pane" id="{$wert.name}_tabs">
    
        <script type="text/javascript">
        tp1 = new WebFXTabPane( document.getElementById( "{$wert.name}_tabs" ) );
        </script>
    	
    	<div class="tab-page" id="{$wert.name}_generals">
    	   <h2 class="tab">{t}General{/t}</h2>
    	   <script type="text/javascript">tp1.addTabPage( document.getElementById( "{$wert.name}_generals" ) );</script>
            <table cellpadding="2" cellspacing="2" border="0">
            
            {* Content of $content.generals = Title, Author, Description, Homepage *}
            {foreach key=key item=item from=$content.generals}
                <tr>
                <td width="90"><strong>{t}{$key}:{/t}</strong></td>
                <td width="250" height="25"><span onDblClick="javascript:clip_edit('{$wert.module_id}','_{$wert.name}_{$item}');" id="{$wert.module_id}_{$wert.name}_{$item}_text">{$wert.$item}</span>
                <input onblur="javascript:clip_edit('{$wert.module_id}','_{$wert.name}_{$item}');" class="input_text" type="textarea" id="{$wert.module_id}_{$wert.name}_{$item}" style="display: none;" name="info[{$wert.module_id}][{$item}]" value="{$wert.$item}" size="40"></td>
                </tr>
            {/foreach}
                      
            </table>
        </div>
    
    	<div class="tab-page" id="{$wert.name}_more">
    	   <h2 class="tab"{t}>Moduledetails{/t}</h2>
    	   <script type="text/javascript">tp1.addTabPage( document.getElementById( "{$wert.name}_more" ) );</script>    
    
            <table cellpadding="2" cellspacing="2" border="0">
    
            {foreach key=key item=item from=$content.more}
                <tr>
                <td width="90"><strong>{t}{$key}:{/t}</strong></td>
                <td width="250" height="25"><span onDblClick="javascript:clip_edit('{$wert.module_id}','_{$wert.name}_{$item}');" id="{$wert.module_id}_{$wert.name}_{$item}_text">{$wert.$item}</span>
                <input onblur="javascript:clip_edit('{$wert.module_id}','_{$wert.name}_{$item}');" class="input_text" type="textarea" id="{$wert.module_id}_{$wert.name}_{$item}" style="display: none;" name="info[{$wert.module_id}][{$item}]" value="{$wert.$item}" size="40"></td>
                </tr>
            {/foreach}
                    
            </table>
        </div>
    
    	<div class="tab-page" id="{$wert.name}_subs">
    	   <h2 class="tab">{t}Submodules{/t}</h2>
    	   <script type="text/javascript">tp1.addTabPage( document.getElementById( "{$wert.name}_subs" ) );</script>
           
           <table cellpadding="2" cellspacing="2" border="0">    
            <tr>
                <td><strong>{t}Submodules:{/t}</strong>
                {if is_array($wert.subs)}
                <br />
                <a href="javascript:void(0)" onclick="return sub_add('{$wert.module_id}_subs', '{$wert.module_id}', '', this);">Add a submodule</a>
                {/if}
                </td>
                <td>
                    <table cellspacing="0" cellpadding="0" border="0" width="100%" id="{$wert.module_id}_subs">
                    
                    {if is_array($wert.subs)}
                   
                   {* Debug Subs  *} {$wert.subs|@var_dump}
                    
                    {foreach key=key item=item from=$wert.subs}
                    <tr id="{$wert.module_id}_sub_{$item.submodule_id}_tr1">
                       <td width="40" height="20"><strong>Name</strong> (#{$item.submodule_id}) :</td>
                        <td width="165">
                            <span onDblClick="javascript:clip_edit('{$wert.module_id}','_subs_{$key}_name');" id="{$wert.module_id}_subs_{$key}_name_text">{$key}</span>
                            <input onblur="javascript:clip_edit('{$wert.module_id}','_subs_{$key}_name');" class="input_text" type="textarea" id="{$wert.module_id}_subs_{$key}_name" style="display: none;" name="info[{$wert.module_id}][subs][{$key}][name]" value="{$key}" size="30">
                        </td>
                    </tr>
                    <tr id="{$wert.module_id}_sub_{$item.submodule_id}_tr2">
                        <td height="20"><strong>File:</strong></td>
                        <td>
                            <span onDblClick="javascript:clip_edit('{$wert.module_id}','_subs_{$key}_file');" id="{$wert.module_id}_subs_{$key}_file_text">{$item.file_name}</span>
                            <input onblur="javascript:clip_edit('{$wert.module_id}','_subs_{$key}_file');" class="input_text" type="textarea" id="{$wert.module_id}_subs_{$key}_file" style="display: none;" name="info[{$wert.module_id}][subs][{$key}][file]" value="{$item.file_name}" size="30">
                        </td>
                    </tr>
                    <tr id="{$wert.module_id}_sub_{$item.submodule_id}_tr3">
                        <td height="20"><strong>Class:</strong></td>
                        <td>
                            <span onDblClick="javascript:clip_edit('{$wert.module_id}','_subs_{$key}_class');" id="{$wert.module_id}_subs_{$key}_class_text">{$item.class_name}</span>
                            <input onblur="javascript:clip_edit('{$wert.module_id}','_subs_{$key}_class');" class="input_text" type="textarea" id="{$wert.module_id}_subs_{$key}_class" style="display: none;" name="info[{$wert.module_id}][subs][{$key}][class]" value="{$item.class_name}" size="30">
                        </td>
                    </tr>
                    <tr id="{$wert.module_id}_sub_{$item.submodule_id}_tr4">
                        <td colspan="2" height="20">
                            <a href="javascript:sub_delete('{$wert.module_id}_sub_{$item.submodule_id}');">Delete submodule: '{$key}' (#{$item.submodule_id}) from whitelist</a>
                        </td>
                    </tr>
                    <tr id="{$wert.module_id}_sub_{$item.submodule_id}_tr5">
                        <td colspan="3" height="20">&nbsp;</td>
                    </tr>
                    {/foreach}
                    
                    {else}
                    
                    <tr id="{$wert.module_id}_no_subs">
                    
                        <td>{t}No submodules{/t}</td>
                    
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
                        <a href="javascript:void(0)" onclick="return sub_add('{$wert.module_id}_subs', '{$wert.module_id}', '', this);">Add a submodule</a>
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
    <input class="ButtonGreen" type="submit" value="{t}Update modules{/t}" name="submit" />
</p>
</form>
<br /><br />
<center><a href="javascript:clip_core_mods('1')">{t}Show core modules{/t}</a></center>
<br /><br />

{* ########################################################################################### *}

<span id="core_span_1" style="display: none;">
<form action="index.php?mod=controlcenter&sub=modules&action=update" method="post" accept-charset="UTF-8">
<table cellspacing="0" cellpadding="0" border="0" width="100%">
<tr>
    <td class="td_header" width="100%" colspan="4">
    {t}Core modules{/t}
    </td>
</tr>
<tr>
    <td class="td_header_small" width="120px">              {t}Title{/t}        </td>
    <td class="td_header_small" width="80%">                {t}Information{/t}  </td>
    <td class="td_header_small" width="5%" align="center">  {t}Enabled{/t}      </td>
    <td class="td_header_small" width="5%" align="center">  {t}Delete{/t}       </td>    
</tr>
{foreach key=schluessel item=wert from=$content.whitelisted.core}
<tr>
    <input type="hidden" name="ids[]" value="{$wert.module_id}">
    <td class="cell1" align="center">
        <strong>{$wert.title} </strong> (#{$wert.module_id})<br />
        <img width="100px" height="100px" src="{$www_root_themes_core}/images/{$wert.image_name}">
    </td>
    
    <td class="cell2">
        <div id="{$wert.module_id}_remember_to_update" style="display: none; padding: 10px;"><strong><font color="red">{t}Remember to press the update button below!{/t}</font></strong></div>
        <div class="tab-pane" id="{$wert.name}_tabs">
    
        <script type="text/javascript">
        tp1 = new WebFXTabPane( document.getElementById( "{$wert.name}_tabs" ) );
        </script>
    	<div class="tab-page" id="{$wert.name}_generals">
    	   <h2 class="tab">General</h2>
    	   <script type="text/javascript">tp1.addTabPage( document.getElementById( "{$wert.name}_generals" ) );</script>
            <table cellpadding="2" cellspacing="2" border="0">
            
            {foreach key=key item=item from=$content.generals}
                <tr>
                <td width="90"><strong>{t}{$key}:{/t}</strong></td>
                <td width="250" height="25"><span onDblClick="javascript:clip_edit('{$wert.module_id}','_{$wert.name}_{$item}');" id="{$wert.module_id}_{$wert.name}_{$item}_text">{$wert.$item}</span>
                <input onblur="javascript:clip_edit('{$wert.module_id}','_{$wert.name}_{$item}');" class="input_text" type="textarea" id="{$wert.module_id}_{$wert.name}_{$item}" style="display: none;" name="info[{$wert.module_id}][{$item}]" value="{$wert.$item}" size="40"></td>
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
                <td width="90"><strong>{t}{$key}:{/t}</strong></td>
                <td width="250" height="25"><span onDblClick="javascript:clip_edit('{$wert.module_id}','_{$wert.name}_{$item}');" id="{$wert.module_id}_{$wert.name}_{$item}_text">{$wert.$item}</span>
                <input onblur="javascript:clip_edit('{$wert.module_id}','_{$wert.name}_{$item}');" class="input_text" type="textarea" id="{$wert.module_id}_{$wert.name}_{$item}" style="display: none;" name="info[{$wert.module_id}][{$item}]" value="{$wert.$item}" size="40"></td>
                </tr>
            {/foreach}
                    
            </table>
        </div>
    
    	<div class="tab-page" id="{$wert.name}_subs">
    	   <h2 class="tab">Submodules</h2>
    	   <script type="text/javascript">tp1.addTabPage( document.getElementById( "{$wert.name}_subs" ) );</script>
           
           <table cellpadding="2" cellspacing="2" border="0">    
            <tr>
                <td><strong>{t}Submodules:{/t}</strong>{if is_array($wert.subs)}<br /><a href="javascript:void(0)" onclick="return sub_add('{$wert.module_id}_subs', '{$wert.module_id}', '', this);">Add a submodule</a>{/if}</td>
                <td>
                    <table cellspacing="0" cellpadding="0" border="0" width="100%" id="{$wert.module_id}_subs">
                    
                    {if is_array($wert.subs)}
                    {foreach key=key item=item from=$wert.subs}
                    <tr id="{$wert.module_id}_sub_{$item.submodule_id}_tr1">
                        <td><strong>Name</strong> (#{$item.submodule_id}):</td>
                        <td width="165">
                            <span onDblClick="javascript:clip_edit('{$wert.module_id}','_subs_{$key}_name');" id="{$wert.module_id}_subs_{$key}_name_text">{$key}</span>
                            <input onblur="javascript:clip_edit('{$wert.module_id}','_subs_{$key}_name');" class="input_text" type="textarea" id="{$wert.module_id}_subs_{$key}_name" style="display: none;" name="info[{$wert.module_id}][subs][{$key}][name]" value="{$key}" size="30"></td>
                    </tr>
                    <tr id="{$wert.module_id}_sub_{$item.submodule_id}_tr2">
                        <td height="20">
                            <strong>File:</strong>
                        </td>
                        <td>
                            <span onDblClick="javascript:clip_edit('{$wert.module_id}','_subs_{$key}_file');" id="{$wert.module_id}_subs_{$key}_file_text">{$item.file_name}</span>
                            <input onblur="javascript:clip_edit('{$wert.module_id}','_subs_{$key}_file');" class="input_text" type="textarea" id="{$wert.module_id}_subs_{$key}_file" style="display: none;" name="info[{$wert.module_id}][subs][{$key}][file]" value="{$item.file_name}" size="30"></td>
                    </tr>
                    <tr id="{$wert.module_id}_sub_{$item.submodule_id}_tr3">
                        <td height="20">
                            <strong>Class:</strong>
                        </td>
                        <td>
                            <span onDblClick="javascript:clip_edit('{$wert.module_id}','_subs_{$key}_class');" id="{$wert.module_id}_subs_{$key}_class_text">{$item.class_name}</span>
                            <input onblur="javascript:clip_edit('{$wert.module_id}','_subs_{$key}_class');" class="input_text" type="textarea" id="{$wert.module_id}_subs_{$key}_class" style="display: none;" name="info[{$wert.module_id}][subs][{$key}][class]" value="{$item.class_name}" size="30"></td>
                    </tr>
                    <tr id="{$wert.module_id}_sub_{$item.submodule_id}_tr4">
                        <td colspan="2" height="20">
                        <a href="javascript:sub_delete('{$wert.module_id}_sub_{$item.submodule_id}');">Delete submodule: '{$key}' (#{$item.submodule_id}) from whitelist</a>
                        </td>
                    </tr>
                    <tr id="{$wert.module_id}_sub_{$item.submodule_id}_tr5">
                        <td colspan="3" height="20">
                        &nbsp;
                        </td>
                    </tr>
                    {/foreach}
                    {else}
                    <tr id="{$wert.module_id}_no_subs">
                    <td>
                    {t}No submodules{/t}
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
                        <a href="javascript:void(0)" onclick="return sub_add('{$wert.module_id}_subs', '{$wert.module_id}', '', this);">Add a submodule</a>
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
    <input class="ButtonGreen" type="submit" value="{t}Update core modules{/t}" name="submit" />
</p>
</form>
</span>

<script type="text/javascript">
setupAllTabs();

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
        if( document.getElementById(id + edit).value != '' )
        {
            document.getElementById(id + edit + "_text").innerHTML = document.getElementById(id + edit).value;
        }
        else
        {
            document.getElementById(id + edit + "_text").innerHTML = '- empty -';
        }
        document.getElementById(id + edit + "_text").style.display = "block";
        document.getElementById(id + "_remember_to_update").style.display = "block";
    }
}

</script>