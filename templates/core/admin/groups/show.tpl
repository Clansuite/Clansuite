{doc_raw}
            {* StyleSheets *}
            <link rel="stylesheet" type="text/css" href="{$www_core_tpl_root}/admin/js_color_picker_v2.css" />
            <link rel="stylesheet" type="text/css" href="{$www_core_tpl_root}/admin/luna-long.css" />
            <link rel="stylesheet" type="text/css" href="{$www_core_tpl_root}/admin/fieldset.css" />            
            
            {* JavaScripts *}
            <script type="text/javascript" src="{$www_core_tpl_root}/javascript/tabpane.js"></script>
	        <script type="text/javascript" src="{$www_core_tpl_root}/javascript/color_functions.js"></script>		
	        <script type="text/javascript" src="{$www_core_tpl_root}/javascript/js_color_picker_v2.js"></script>
            <script type="text/javascript" src="{$www_core_tpl_root}/javascript/clip.js"></script>
{/doc_raw}

<h2>Administration of Groups</h2>

<div class="tab-pane" id="tabPane1">
    
    <script type="text/javascript">
        tp1 = new WebFXTabPane( document.getElementById( "tabPane1" ) );
    </script>

    {* ###############   Rechte basierte Gruppen   ############## *}
    
    <div class="tab-page" id="tabPage1">
        
        <h2 class="tab">Rechtebasierte Benutzergruppen</h2>
        <script type="text/javascript">tp1.addTabPage( document.getElementById( "tabPage1" ) );</script>
        
            {* Debuganzeige, wenn DEBUG = 1 | {$right_based_groups|@var_dump} 
            {if $smarty.const.DEBUG eq "1"} Debugausgabe des Arrays:   {html_alt_table loop=$right_based_groups} {/if}*}
            
            
            <p>
                <input class="input_submit" type="submit" name="Submit" id="Submit" value="Create New Group" tabindex="1" onclick="javascript:clip_span('rightbased')" />                
            </p>
            <span id="span_rightbased" style="display: none; padding: 8px;">
                 
                <form class="h3sForm" name="create_new_form" action="index.php?mod=admin&sub=groups&action=add_right_group" method="POST">
                            
                    <fieldset  class="border3d"> 
                       
                       <h3>
        			   Add right based Usergroup
        			   </h3>
        				
                       <label for="right_group_name">
            				{translate}Groupname{/translate}
            				<input id="right_group_name" name="right_group_name" type="text" value="Forum-God" />
            			</label>
            				  
            			<label for="color">
                            {translate}Color{/translate} ( <a id="color-href" href="javascript: showColorPicker(document.getElementById('color-href'),document.getElementById('color'));">hex-codes</a> )
            				<input name="color" id="color" type="text" value="Color" />
            			</label>      
            				            			
            			<label for="desc">
                            {translate}Description{/translate}
            				<input id="desc" name="desc" type="text" value="Description" />
            			</label>
            			
            			
            			
            			<label for="icon">
                            Icon
                            <select onChange="document.getElementById('insert_icon').src='{$www_core_tpl_root}/images/groups/'+document.getElementById('tree-insert-custom_icon').options[document.getElementById('tree-insert-custom_icon').options.selectedIndex].text" class="input" id="tree-insert-custom_icon">
                                <option name=""></option>
                                {foreach key=key item=item from=$icons}
                                    <option name="{$item}">{$item}</option>
                                {/foreach}
                            </select>
                            <img src="" id="insert_icon" border="1">
                		</label>
            			
            			<input class="submit" type="submit" name="submit" value="{translate}Add right based Group{/translate}" />
            		
            		</fieldset>
                </form>
            </span>
                
                
            <form action="index.php?mod=admin&sub=groups&action=update" method="POST">        
            <table cellspacing="0" cellpadding="0" border="0" class="border3d" width="100%">           
            <tr>
                <td width="10%" align="center" class="td_header"> {translate}Group ID{/translate}        </td>
                <td align="center" class="td_header">             {translate}Group Position{/translate}  </td>
                <td align="center" class="td_header">             {translate}Icon{/translate}            </td>  
                <td align="center" class="td_header">             {translate}Groupname{/translate}       </td>
                <td align="center" class="td_header">             {translate}Description{/translate}     </td>
                <td align="center" class="td_header">             {translate}Members{/translate}         </td>
                <td align="center" class="td_header">             {translate}Action{/translate}          </td>
                <td align="center" class="td_header">             {translate}Delete{/translate}          </td>
            </tr>

           
            {foreach key=schluessel item=wert from=$right_based_groups}

                <tr class="{cycle values="cell1,cell2"}">
                    <td align="center" height="50"><input type="hidden" name="ids[]" value="{$wert.group_id}">{$wert.group_id}</td>
                    <td align="center">{$wert.pos}</td>
                    <td align="center">{$wert.icon}<img width="100px" height="100px" src="{$www_core_tpl_root}/groups/{$wert.icon}"> </td>
                    <td align="center"> <font color="#{$wert.colour}"> {$wert.name} </font></td>
                    
                    <td align="center">{$wert.description}</td>
                    
                    <td align="center">  
                        {foreach name=usersarray key=schluessel item=userswert from=$wert.users}
                        <a href="index.php?mod=admin&sub=users&action=edit&user_id={$userswert.user_id}">
                        {$userswert.nick}</a>
                        {if !$smarty.foreach.usersarray.last},{/if} 
                        {/foreach}  
                    </td>
                    <td align="center">
                        <a href="index.php?mod=admin&sub=groups&action=edit&group_id={$wert.group_id}" class="input_submit" style="position: relative; top: 15px">Edit</a>
                    </td>
                    <td align="center"> 
                        <input type="hidden" name="ids[]" value="{$wert.module_id}">
                        <input name="delete[]" type="checkbox" value="{$wert.group_id}">
                    </td>
                </tr>   
                 
            {/foreach}
            
            <tr>
               <td colspan="9" height="40" style="padding: 8px">
                    <div>
                        <input class="Button" type="submit" name="Delete" id="Delete" value="Delete Selected Groups" tabindex="2" />
                        <input class="Button" type="reset" tabindex="3" />
                   </div>
                </td>
            </tr>
            
            

            </table>
            </form>

        </fieldset> { * close tab - mainframe fieldset * }
        
    
    </div> {* close pane-page *}


    
    
    {* ###############   Posts basierte Gruppen   ############## *}
    
    {* ###############   Tab - Show   ############## *}
    
    <div class="tab-page" id="tabPage2">
    
        <h2 class="tab">Beitragsbasierte Benutzergruppen</h2>
        <script type="text/javascript">tp1.addTabPage( document.getElementById( "tabPage2" ) );</script>
        
            
        <fieldset id="h3sForm2"> { * open tab - mainframe fieldset * }
       
        {* todo : Debugausgabe nur wenn DEBUG = 1 *}
        {if $smarty.const.DEBUG eq "1"} Debugausgabe des Arrays: {html_alt_table loop=$post_based_groups}  {/if}
        
        <h3>Beitragsbasierte Benutzergruppen</h3>
        
        
        <table cellspacing="0" cellpadding="0" border="0" width="75%">
        <tr>
                <td id="td_header" width="10%" align="center" class="td_header"> {translate}Group ID{/translate}        </td>
                <td id="td_header" align="center" class="td_header">             {translate}Group Position{/translate}  </td>
                <td id="td_header" align="center" class="td_header">             {translate}Icon{/translate}            </td>  
                <td id="td_header" align="center" class="td_header">             {translate}Groupname{/translate}       </td>
                <td id="td_header" align="center" class="td_header">             {translate}Description{/translate}     </td>
                <td id="td_header" align="center" class="td_header">             {translate}Members{/translate}         </td>
                <td id="td_header" align="center" class="td_header">             {translate}Action{/translate}          </td>
                <td id="td_header" align="center" class="td_header">             {translate}Delete{/translate}          </td>
        </tr>
        
        {foreach key=schluessel item=wert from=$post_based_groups}
       
            <tr bgcolor="{cycle values="#eeeeee,#d0d0d0"}">
            
                <input type="hidden" name="ids[]" value="{$wert.group_id}">
               
                <td align="center"> {$wert.group_id} </td>
                <td>                {$wert.icon}    <img width="100px" height="100px" src="{$www_core_tpl_root}/groups/{$wert.icon}"> </td>
                <td> <span id="{$wert.group_id}_{$wert.name}_name_text">{$wert.name}</span>
                     <input class="input_text" type="textarea" id="{$wert.group_id}_{$wert.name}_description" style="display: none" name="info[{$wert.module_id}][description]" value="{$wert.description}" size="40">
                </td>
                
                <td align="center"> {$wert.posts} </td>
                <td align="center">  
               
                                    {foreach name=usersarray key=schluessel item=userswert from=$wert.users}
                                    <a href="index.php?mod=admin&sub=users&action=edit&user_id={$userswert.user_id}">
                                    {$userswert.nick}</a>
                                    {if !$smarty.foreach.usersarray.last},{/if} 
                                    {/foreach}  
                </td>
                <td align="center"> <a href="index.php?mod=admin&sub=groups&action=edit&group_id={$wert.group_id}">Edit</a>     </td>
                <td> 
                    <form action="index.php?mod=admin&sub=groups&action=update" method="POST">
                    <input type="hidden" name="ids[]" value="{$wert.module_id}">
                    <input name="delete[]" type="checkbox" value="{$wert.group_id}"> </td>
                 
            </tr>
        
        {/foreach}
        
        <tr height="20">
           <td colspan="7">
                <div class="Button">
                <input class="Button" type="submit" name="xdelete" id="delete" value="Delete Selected Groups" tabindex="2" />
                </form>
                <input class="Button" type="reset" tabindex="3" />
                <input class="Button" type="submit" name="xsubmit" id="submit" value="Create New Group" tabindex="1" onclick="javascript:clip_span('postbased')" />
               
               </div>
            </td>
        </tr>
        
        </table>
          
        {* ###############   Edit - Post based Groups ############## *}
                 

        
        { * clip.js span * }
        <span id="span_postbased" style="display: none;">
         
            <form class="h3sForm" action="index.php?mod=admin&sub=groups&action=add_post_group" method="POST">
                        
                        <fieldset > 
                           
                           <h3>
        				   Add Post based Usergroup
        				   </h3>
        				    
                           <label for="post_group_name">
            					Groupname
            					<input id="post_group_name" name="post_group_name" type="text" value="Forum-God" />
            				</label>
            				        				
            				<label for="posts">
                                Posts
            					<input id="posts" name="posts" type="text" value="5000" />
            				</label>
            				        				
            				<label for="icon">
                                <select onChange="document.getElementById('insert_icon').src='{$www_core_tpl_root}/images/icons/'+document.getElementById('tree-insert-custom_icon').options[document.getElementById('tree-insert-custom_icon').options.selectedIndex].text" class="input" id="tree-insert-custom_icon">
                                    <option name=""></option>
                                    {foreach key=key item=item from=$icons}
                                        <option name="{$item}">{$item}</option>
                                    {/foreach}
                                </select>
                                <img src="" id="insert_icon" width="16" height="16" border="1">
            				</label>
            				
            				<input class="submit" type="submit" name="submit" value="{translate}Add Post based Group{/translate}" />
            			
            			</fieldset>
            </form>
        </span>
 
    </fieldset> { * close tab - mainframe fieldset * }
    
    </div> {* close pane-page2 *}

</div> {* close pane *}