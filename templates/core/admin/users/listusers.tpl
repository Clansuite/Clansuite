<h2>Administration :: Users </h2>

{doc_raw}
            <script type="text/javascript" src="{$www_core_tpl_root}/javascript/clip.js"></script>
{/doc_raw}

{* todo : Debugausgabe nur wenn DEBUG = 1 *}
{if debug == "1"} Debugausgabe des Arrays:  {html_alt_table loop=$users}   {/if}

    <h3>All Users</h3>    <a href="index.php?mod=admin&sub=users&action=search">- Search -</a>
    
    
    <table cellspacing="0" cellpadding="0" border="0" width="75%">
    <thead align="center">
    <tr>
        <td id="td_header" width="10%"> {translate}user id{/translate}        </td>
        <td id="td_header">             {translate}email{/translate}       </td>
        <td id="td_header">             {translate}nick{/translate}            </td>  
        <td id="td_header">             {translate}joined{/translate}       </td>
        <td id="td_header">             {translate}first name{/translate}     </td>
        <td id="td_header">             {translate}last name{/translate}         </td>
        <td id="td_header">             {translate}Action{/translate}          </td>
        <td id="td_header">             {translate}Delete{/translate}          </td>
    </tr>
    </thead>
    
    {foreach key=schluessel item=wert from=$users}
       
            <tr bgcolor="{cycle values="#eeeeee,#d0d0d0"}">
            
                <input type="hidden" name="ids[]" value="{$wert.group_id}">
               
                <td align="center"> {$wert.user_id}     </td>
                <td align="center"> {$wert.email}       </td>
                <td align="center"> {$wert.nick}        </td>
                <td align="center"> {$wert.joined}      </td>
                <td align="center"> {$wert.first_name}  </td>
                <td align="center"> {$wert.last_name}   </td>
                <td align="center"> <a href="index.php?mod=admin&sub=users&action=edit&user_id={$wert.user_id}">Edit</a>     </td>
                <td> 
                    <form action="index.php?mod=admin&sub=users&action=update" method="POST">
                    <input type="hidden" name="ids[]" value="{$wert.users_id}">
                    <input name="delete[]" type="checkbox" value="{$wert.users_id}"> </td>
                    
                    
                {*
                <td> ico {$wert.icon} <img width="100px" height="100px" src="{$www_core_tpl_root}/groups/{$wert.icon}"> </td>
                <td align="center"> <font color="#{$wert.colour}"> {$wert.name} </font></td>
                <td align="center"> <a href="index.php?mod=admin&sub=groups&action=edit_members&group_id={$wert.group_id}">Membernicksarray</a> </td>
                *}
                
            </tr>
        
        {/foreach} 
        
        {* Actions - Buttons *}
        
        <tr height="20">
           <td colspan="8">
                <div class="Button">
                <input class="Button" type="submit" name="Delete" id="Delete" value="Delete Selected Groups" tabindex="2" />
                <input class="Button" type="reset" tabindex="3" />
                </form>
                <input class="Button" type="submit" name="Submit" id="Submit" value="Create New User" tabindex="1" onclick="javascript:clip_span('create_user')" />
               </div>
            </td>
        </tr>
        
        </table>
        
        <br />
        
         { * clip.js span * }
        <span id="span_create_user" style="display: none; width: 75%;">
        
            <form id="h3sForm"
             action="index.php?mod=admin&sub=users&action=add" method="POST" target="_self">
        
            <fieldset> 
                          
               <h3> Create new User </h3>
        	    
        	    <label for="firstname">
        			First Name
        			<input id="firstname" name="firstname" type="text" value="first name" />
        		</label>
        	    
               <label for="usernick">
        			Nickname
        			<input id="usernick" name="usernick" type="text" value="nickname" />
        		</label>
        		
        		<label for="lastname">
        			Nickname
        			<input id="lastname" name="lastname" type="text" value="last name" />
        		</label>	
        		
        		<label for="email">
        			Email
        			<input id="email" name="email" type="text" value="email" />
        		</label>
        		
        		<label for="user_picture">
                    Icon
        			{* <input id="icon" name="icon" type="text" value="iconname?" /> *}
        				<input type="text" id="icon" class="selectFile" name="icon" />
                        <input type="button" name="select" onclick="ImageSelector.select('icon');" />
        		</label>
        		
        		<input class="Button" type="submit" name="submit" value="{translate}Create User{/translate}" />
        			
        	 </fieldset>
        
        </form>
        
        </span>