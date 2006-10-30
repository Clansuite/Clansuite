{doc_raw}
    <link rel="stylesheet" type="text/css" href="{$www_core_tpl_root}/admin/adminmenu/DynamicTree.css" />

    {* Tabs *}
    <link rel="stylesheet" type="text/css" href="{$www_core_tpl_root}/admin/luna.css" />
    <script type="text/javascript" src="{$www_core_tpl_root}/javascript/tabpane.js"></script>
    
    
    <script language="JavaScript1.5" type="text/javascript" src="{$www_core_tpl_root}/javascript/log4javascript.js"></script>
    
    <script type="text/javascript" src="{$www_core_tpl_root}/javascript/prototype.js"></script>
    <script type="text/javascript" src="{$www_core_tpl_root}/javascript/moo.fx.js"></script>
	<script type="text/javascript" src="{$www_core_tpl_root}/javascript/EditInPlace.js"></script>
	
    {* Edit in Place *} {literal}    
    <style type="text/css">
		/* Define the basic CSS used by EditInPlace */
        .eip_mouseover      { background-color: #ff9; padding: 3px; }
        .eip_empty          { color: #afafaf; }
        .eip_savebutton     { background-color: #0063dc; color: #fff; 
                              text-transform: uppercase; font-weight: bold;}
        .eip_cancelbutton   { background-color: #ddd; color: #666; 
                              font-weight: bold;}
        .eip_saving img     { vertical-align:text-top; }
        .eip_saving         { color: #aaa;}
    </style>
    
   	<script type="text/javascript">
		Event.observe(window, 'load', init, false);
		
		function init() {
		
	        // animated GIF and saving message
        	EditInPlace.defaults["saving_text"] = "<img src='{$www_core_tpl_root}/core/images/ajax/1.gif' /> <span class='eip_" + EditInPlace.defaults["saving_class"] + "'>Saving changes, please wait.</span>";
        	
        	// Change String at Element with id "edittest"
			// and cancel editing form when clicked away from.
			EditInPlace.makeEditable({ 
			                id: 'edittest',
			                type: 'text',			                
			                save_url: 'optionedit.php',
			                on_blur: 'cancel'
			                });
          
	
	        // ######### Table Edit in Place ##############
	         
	        // Prototype Array Ansatz -> 
	        // fÅr jedes td element innerhalb von table 
	        // editfunktion einbinden 
	        
	        // $$("table.editable td").each( EditInPlace.makeEditable({
            //       	                    id: cell,
            //       	                    type: 'text',
            //       	                    save_url: 'index.php',
            //      	                    on_blur: 'cancel'
            //      	                    });
	        //  )
           
           
           }
	</script>
	{/literal}
	
{/doc_raw}


{* TAB PANE 1 - MODULES - NOT IN WHITELIST *}

<div class="tab-pane" id="tab-pane-1">

   <div class="tab-page">
      <h2 class="tab">NEW</h2>

      <span id="edittest">You can edit this line with a single click.</span>
      
      <br/>
      <strong> Test for editable table:  </strong>
      
      <table class="editable">
      
          <thead>
            <tr><td>Col-1</td><td>Col-2</td><td>Col-3</td></tr>
         </thead>
            
          <tr>
            <td id="edit_r1c1">R1-C1</td>
            <td id="edit_r1c2">R1-C2</td>
            <td id="edit_r1c3">R1-C3</td>
          </tr>
          <tr>
            <td id="edit_r2c1">R2-C1</td>
            <td id="edit_r2c2">R2-C2</td>
            <td id="edit_r2c3">R2-C3</td>
          </tr>
          <tr>
            <td id="edit_r3c1">R3-C1</td>
            <td id="edit_r3c2">R3-C2</td>
            <td id="edit_r3c3">R3-C3</td>
          </tr>
      
      </table>

   </div>
   
</div>  <!-- tab pane 1 closed -->


<br />


{* TAB PANE 2 - MODULES - IN WHITELIST | PAGES - NORMAL, CORE *}

<div class="tab-pane" id="tab-pane-2">

   <div class="tab-page">
      <h2 class="tab">Normal</h2>

      <table cellspacing="0" cellpadding="0" border="0" width="100%">
        <tr>
            <td class="td_header" colspan="4">    {translate}Normal modules{/translate}    </td>
        </tr>
        <tr>
            <td class="td_header_small" width="120px">              {translate}Title{/translate}        </td>
            <td class="td_header_small" width="80%">                {translate}Information{/translate}  </td>
            <td class="td_header_small" width="5%" align="center">  {translate}Enabled{/translate}      </td>
            <td class="td_header_small" width="5%" align="center">  {translate}Delete{/translate}       </td>    
        </tr>
        
        {foreach key=schluessel item=wert from=$content.whitelisted.normal}

        <tr>
            <input type="hidden" name="ids[]" value="{$wert.module_id}">
            <td class="cell1" align="center">
                <b>{$wert.title} </b> (#{$wert.module_id})<br />
                <img width="100px" height="100px" src="{$www_core_tpl_root}/images/modules/{$wert.image_name}">
            </td>
            
            <td class="cell2">
                               
                <div class="tab-pane" id="{$wert.name}_tabs">
            
                <script type="text/javascript">
                tp1 = new WebFXTabPane( document.getElementById( "{$wert.name}_tabs" ) );
                </script>
            	
            	<div class="tab-page" id="{$wert.name}_generals">
            	   <h2 class="tab">{translate}General{/translate}</h2>
            	   <script type="text/javascript">tp1.addTabPage( document.getElementById( "{$wert.name}_generals" ) );</script>
                    <table cellpadding="2" cellspacing="2" border="0">
                    
                    {* Content of $content.generals = Title, Author, Description, Homepage *}
                    {foreach key=key item=item from=$content.generals}
                        <tr>
                        <td width="90"><b>{translate}{$key}:{/translate}</b></td>
                        <td width="250" height="25"><span onDblClick="javascript:clip_edit('{$wert.module_id}','_{$wert.name}_{$item}');" id="{$wert.module_id}_{$wert.name}_{$item}_text">{$wert.$item}</span>
                        <input onBlur="javascript:clip_edit('{$wert.module_id}','_{$wert.name}_{$item}');" class="input_text" type="textarea" id="{$wert.module_id}_{$wert.name}_{$item}" style="display: none;" name="info[{$wert.module_id}][{$item}]" value="{$wert.$item}" size="40"></td>
                        </tr>
                    {/foreach}
                              
                    </table>
                </div>
            
            	<div class="tab-page" id="{$wert.name}_more">
            	   <h2 class="tab"{translate}>Moduledetails{/translate}</h2>
            	   <script type="text/javascript">tp1.addTabPage( document.getElementById( "{$wert.name}_more" ) );</script>    
            
                    <table cellpadding="2" cellspacing="2" border="0">
            
                    {foreach key=key item=item from=$content.more}
                        <tr>
                        <td width="90"><b>{translate}{$key}:{/translate}</b></td>
                        <td width="250" height="25"><span onDblClick="javascript:clip_edit('{$wert.module_id}','_{$wert.name}_{$item}');" id="{$wert.module_id}_{$wert.name}_{$item}_text">{$wert.$item}</span>
                        <input onBlur="javascript:clip_edit('{$wert.module_id}','_{$wert.name}_{$item}');" class="input_text" type="textarea" id="{$wert.module_id}_{$wert.name}_{$item}" style="display: none;" name="info[{$wert.module_id}][{$item}]" value="{$wert.$item}" size="40"></td>
                        </tr>
                    {/foreach}
                            
                    </table>
                </div>
            
            	<div class="tab-page" id="{$wert.name}_subs">
            	   <h2 class="tab">{translate}Submodules{/translate}</h2>
            	   <script type="text/javascript">tp1.addTabPage( document.getElementById( "{$wert.name}_subs" ) );</script>
                   
                   <table cellpadding="2" cellspacing="2" border="0">    
                    <tr>
                        <td><b>{translate}Submodules:{/translate}</b>
                        {if is_array($wert.subs)}
                        <br />
                        <a href="javascript:void(0)" onClick="return sub_add('{$wert.module_id}_subs', '{$wert.module_id}', '', this);">Add a submodule</a>
                        {/if}
                        </td>
                        <td>
                            <table cellspacing="0" cellpadding="0" border="0" width="100%" id="{$wert.module_id}_subs">
                            
                            {if is_array($wert.subs)}
                           
                           {* Debug Subs  *} {$wert.subs|@var_dump}
                            
                            {foreach key=key item=item from=$wert.subs}
                            <tr id="{$wert.module_id}_sub_{$item.submodule_id}_tr1">
                               <td width="40" height="20"><b>Name</b> (#{$item.submodule_id}) :</td>
                                <td width="165">
                                    <span onDblClick="javascript:clip_edit('{$wert.module_id}','_subs_{$key}_name');" id="{$wert.module_id}_subs_{$key}_name_text">{$key}</span>
                                    <input onBlur="javascript:clip_edit('{$wert.module_id}','_subs_{$key}_name');" class="input_text" type="textarea" id="{$wert.module_id}_subs_{$key}_name" style="display: none;" name="info[{$wert.module_id}][subs][{$key}][name]" value="{$key}" size="30">
                                </td>
                            </tr>
                            <tr id="{$wert.module_id}_sub_{$item.submodule_id}_tr2">
                                <td height="20"><b>File:</b></td>
                                <td>
                                    <span onDblClick="javascript:clip_edit('{$wert.module_id}','_subs_{$key}_file');" id="{$wert.module_id}_subs_{$key}_file_text">{$item.file_name}</span>
                                    <input onBlur="javascript:clip_edit('{$wert.module_id}','_subs_{$key}_file');" class="input_text" type="textarea" id="{$wert.module_id}_subs_{$key}_file" style="display: none;" name="info[{$wert.module_id}][subs][{$key}][file]" value="{$item.file_name}" size="30">
                                </td>
                            </tr>
                            <tr id="{$wert.module_id}_sub_{$item.submodule_id}_tr3">
                                <td height="20"><b>Class:</b></td>
                                <td>
                                    <span onDblClick="javascript:clip_edit('{$wert.module_id}','_subs_{$key}_class');" id="{$wert.module_id}_subs_{$key}_class_text">{$item.class_name}</span>
                                    <input onBlur="javascript:clip_edit('{$wert.module_id}','_subs_{$key}_class');" class="input_text" type="textarea" id="{$wert.module_id}_subs_{$key}_class" style="display: none;" name="info[{$wert.module_id}][subs][{$key}][class]" value="{$item.class_name}" size="30">
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
                            
                                <td>{translate}No submodules{/translate}</td>
                            
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

   </div>

   <div class="tab-page">
      <h2 class="tab">Core</h2>

      <table cellspacing="0" cellpadding="0" border="0" width="100%">
        <tr>
            <td class="td_header" colspan="4">   {translate}Core modules{/translate}  </td>
        </tr>
        <tr>
            <td class="td_header_small" width="120px">              {translate}Title{/translate}        </td>
            <td class="td_header_small" width="80%">                {translate}Information{/translate}  </td>
            <td class="td_header_small" width="5%" align="center">  {translate}Enabled{/translate}      </td>
            <td class="td_header_small" width="5%" align="center">  {translate}Delete{/translate}       </td>    
        </tr>
        </table>

   </div>
   
</div> <!-- tab pane 2 closed -->


{* Init TabPane *}
<script type="text/javascript">
setupAllTabs();
</script>