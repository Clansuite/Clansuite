<h2>Administration of Groups</h2>

{doc_raw}
            <script type="text/javascript" src="{$www_core_tpl_root}/javascript/clip.js"></script>
            
            {* Include des Tabs-Scripts *}
            <script type="text/javascript" src="{$www_core_tpl_root}/javascript/tabpane.js"></script>
            <link rel="stylesheet" type="text/css" href="{$www_core_tpl_root}/admin/tab.winclassic.css" />
{/doc_raw}

<div class="tab-pane" id="tabPane1">
    
    <script type="text/javascript">
    tp1 = new WebFXTabPane( document.getElementById( "tabPane1" ) );
    //tp1.setClassNameTag( "dynamic-tab-pane-control-luna" );
    //alert( 0 )
    </script>

{* ###############   Rechte basierte Gruppen   ############## *}
    
    <div class="tab-page" id="tabPage1">
        
        <h2 class="tab" >Rechtebasierte Benutzergruppen</h2>
        <script type="text/javascript">tp1.addTabPage( document.getElementById( "tabPage1" ) );</script>
            
        <fieldset id="h3sForm"> 
        
        {* todo : Debugausgabe nur wenn DEBUG = 1 *}
        {if debug == "1"} Debugausgabe des Arrays:  {html_alt_table loop=$right_based_groups}  {/if}
        
        
        <h3>Rechtebasierte Benutzergruppen</h3>
        
       
        <table cellspacing="0" cellpadding="0" border="0" width="75%">
        <thead align="center">
        <tr>
            <td id="td_header" width="10%"> {translate}Group ID{/translate}        </td>
            <td id="td_header">             {translate}Group Pos{/translate}       </td>
            <td id="td_header">             {translate}Icon{/translate}            </td>  
            <td id="td_header">             {translate}Groupname{/translate}       </td>
            <td id="td_header">             {translate}Description{/translate}     </td>
            <td id="td_header">             {translate}Members{/translate}         </td>
            <td id="td_header">             {translate}Action{/translate}          </td>
            <td id="td_header">             {translate}Delete{/translate}          </td>
        </tr>
        </thead>
       
         {foreach key=schluessel item=wert from=$right_based_groups}
       
            <tr bgcolor="{cycle values="#eeeeee,#d0d0d0"}">
            
                <input type="hidden" name="ids[]" value="{$wert.group_id}">
               
                <td align="center"> {$wert.group_id} </td>
                <td align="center"> {$wert.pos} </td>
                <td>        ico        {$wert.icon}    <img width="100px" height="100px" src="{$www_core_tpl_root}/groups/{$wert.icon}"> </td>
                <td align="center"> <font color="#{$wert.colour}"> {$wert.name} </font></td>
                
                <td align="center"> {$wert.description} desc</td>
                
                <td align="center"> <a href="index.php?mod=admin&sub=groups&action=edit_members&group_id={$wert.group_id}">Membernicksarray</a> </td>
                <td align="center"> <a href="index.php?mod=admin&sub=groups&action=edit&group_id={$wert.group_id}">Edit</a>     </td>
                <td> 
                    <form action="index.php?mod=admin&sub=groups&action=update" method="POST">
                    <input type="hidden" name="ids[]" value="{$wert.module_id}">
                    <input name="delete[]" type="checkbox" value="{$wert.group_id}"> </td>
                 
            </tr>
        
        {/foreach}
        
        </fieldset>
        
        {* Actions - Buttons *}
        
        <tr height="20">
           <td colspan="8">
                <div class="Button">
                <input class="Button" type="submit" name="Delete" id="Delete" value="Delete Selected Groups" tabindex="2" />
                <input class="Button" type="reset" tabindex="3" />
                </form>
                <input class="Button" type="submit" name="Submit" id="Submit" value="Create New Group" tabindex="1" onclick="javascript:clip_span('rightbased')" />
               </div>
            </td>
        </tr>
        
        </table>
        
         {* ###############   Edit - Right based Groups   ############## *}
                 
        {doc_raw}
        <link rel="stylesheet" type="text/css" href="{$www_core_tpl_root}/admin/groups/fieldset.css" />
        {literal}
        <script type="text/javascript" src="{/literal}{$www_root}{literal}/core/imagemanager/assets/dialog.js"></script>
        <script type="text/javascript" src="{/literal}{$www_root}{literal}/core/imagemanager/IMEStandalone.js"></script>
        <script type="text/javascript">
        //<![CDATA[
        
        //Create a new Imanager Manager, needs the directory where the manager is
        //and which language translation to use.
        var manager = new ImageManager('{/literal}{$www_root}{literal}/core/imagemanager','en');
	   
	   
		//Image Manager wrapper. Simply calls the ImageManager
		ImageSelector = 
		{
			//This is called when the user has selected a file
			//and clicked OK, see popManager in IMEStandalone to 
			//see the parameters returned.
			update : function(params)
			{
				if(this.field && this.field.value != null)
				{  this.field.value = params.f_file; //params.f_url
				   filename = this.field.value;
				}
			},
			//open the Image Manager, updates the textfield
			//value when user has selected a file.
			select: function(textfieldID)
			{
				this.field = document.getElementById(textfieldID);
				manager.popManager(this);	
			}
		}
		
        // ]]>
        </script>
        {/literal}
        {/doc_raw}
        
        <br />
        
        { * clip.js span * }
        <span id="span_rightbased" style="display: none;">
         
            <form id="h3sForm"
                  action="index.php?mod=admin&sub=groups&action=add_right_group" method="POST">
                        
                        <fieldset > 
                           
                           <h3>
        				   Add right based Usergroup
        				   </h3>
        				    
                           <label for="right_group_name">
            					Groupname
            					<input id="right_group_name" name="right_group_name" type="text" value="Forum-God" />
            				</label>
            				            				
            					<label for="desc">
                                Description
            					<input id="desc" name="desc" type="text" value="Description" />
            				</label>
            				
            				<label for="icon">
                                Icon
            					{* <input id="icon" name="icon" type="text" value="iconname?" /> *}
            						<input type="text" id="icon" class="selectFile" name="icon" />
    				                <input type="button" name="select" onclick="ImageSelector.select('icon');" />
                			</label>
            				
            				<input class="submit" type="submit" name="submit" value="{translate}Add right based Group{/translate}" />
            			
            			</fieldset>
            </form>
        </span>
 
    </fieldset> { * close tab - mainframe fieldset * }
        
    
    </div> {* close pane-page *}

{* ###############   Posts basierte Gruppen   ############## *}
    
    {* ###############   Tab - Show   ############## *}
    
    <div class="tab-page" id="tabPage2">
    
        <h2 class="tab">Beitragsbasierte Benutzergruppen</h2>
        <script type="text/javascript">tp1.addTabPage( document.getElementById( "tabPage2" ) );</script>
        
            
        <fieldset id="h3sForm"> { * open tab - mainframe fieldset * }
        
        {* todo : Debugausgabe nur wenn DEBUG = 1 *}
        {if debug == "1"} Debugausgabe des Arrays: {html_alt_table loop=$post_based_groups}  {/if}
        
        <h3>Beitragsbasierte Benutzergruppen</h3>
        
        
        <table cellspacing="0" cellpadding="0" border="0" width="75%">
        <thead align="center">
        <tr>
            <td id="td_header" width="10%"> {translate}Group ID{/translate}        </td>
            <td id="td_header">             {translate}Icon{/translate}            </td>
            <td id="td_header">             {translate}Groupname{/translate}       </td>
            <td id="td_header">             {translate}Posts{/translate}           </td>    
            <td id="td_header">             {translate}Members{/translate}         </td>
            <td id="td_header">             {translate}Action{/translate}          </td>
            <td id="td_header">             {translate}Delete{/translate}          </td>
        </tr>
        </thead>
        
        {foreach key=schluessel item=wert from=$post_based_groups}
       
            <tr bgcolor="{cycle values="#eeeeee,#d0d0d0"}">
            
                <input type="hidden" name="ids[]" value="{$wert.group_id}">
               
                <td align="center"> {$wert.group_id} </td>
                <td>                {$wert.icon}    <img width="100px" height="100px" src="{$www_core_tpl_root}/groups/{$wert.icon}"> </td>
                <td> <span id="{$wert.group_id}_{$wert.name}_name_text">{$wert.name}</span>
                     <input class="input_text" type="textarea" id="{$wert.group_id}_{$wert.name}_description" style="display: none" name="info[{$wert.module_id}][description]" value="{$wert.description}" size="40">
                </td>
                
                <td align="center"> {$wert.posts} </td>
                <td align="center"> <a href="index.php?mod=admin&sub=groups&action=edit_members&group_id={$wert.group_id}">Membernicksarray</a> </td>
                <td align="center"> <a href="index.php?mod=admin&sub=groups&action=edit&group_id={$wert.group_id}">Edit</a>     </td>
                <td> 
                    <form action="index.php?mod=admin&sub=groups&action=update" method="POST">
                    <input type="hidden" name="ids[]" value="{$wert.module_id}">
                    <input name="delete[]" type="checkbox" value="{$wert.group_id}"> </td>
                 
            </tr>
        
        {/foreach}
                
        {* Actions - Buttons *}
        
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
                 
        {doc_raw}
        <link rel="stylesheet" type="text/css" href="{$www_core_tpl_root}/admin/groups/fieldset.css" />
        {/doc_raw}
        
        <br />
        
        { * clip.js span * }
        <span id="span_postbased" style="display: none;">
         
            <form id="h3sForm"
                  action="index.php?mod=admin&sub=groups&action=add_post_group" method="POST">
                        
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
            					<input id="posts" name="posts" type="text" value="5000?" />
            				</label>
            				        				
            				<label for="icon">
                                Icon
            					<input id="icon" name="icon" type="text" value="iconname?" />
            				</label>
            				
            				<input class="submit" type="submit" name="submit" value="{translate}Add Post based Group{/translate}" />
            			
            			</fieldset>
            </form>
        </span>
 
    </fieldset> { * close tab - mainframe fieldset * }
    
    </div> {* close pane-page2 *}

</div> {* close pane *}