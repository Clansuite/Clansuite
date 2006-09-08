<h2>Administration of User</h2>

{doc_raw}
            <script type="text/javascript" src="{$www_core_tpl_root}/javascript/clip.js"></script>
            
            {* Include des Tabs-Scripts *}
            <script type="text/javascript" src="{$www_core_tpl_root}/javascript/tabpane.js"></script>
            <link rel="stylesheet" type="text/css" href="{$www_core_tpl_root}/admin/tab.winclassic.css" />
            <link rel="stylesheet" type="text/css" href="{$www_core_tpl_root}/admin/fieldset.css" />
{/doc_raw}

<div class="tab-pane" id="tabPane1">
    
    <script type="text/javascript">
    tp1 = new WebFXTabPane( document.getElementById( "tabPane1" ) );
    //tp1.setClassNameTag( "dynamic-tab-pane-control-luna" );
    //alert( 0 )
    </script>


{* ###############   Tab 1: Edit User Profil   ############## *}
 
 <div class="tab-page" id="tabPage1">
        
        <h2 class="tab">Edit User Profil</h2>
        <script type="text/javascript">tp1.addTabPage( document.getElementById( "tabPage1" ) );</script>
        
        <h3>Edit Profil - {$userprofil.0.nick} #{$userprofil.0.user_id}</h3>
        
        <p>This is your profile. You can choose which information to fill out, other than the required section. This information will be displayed in various places throughout {$website_name}. </p>
        
        <form class="h3sForm" action="index.php?mod=admin&sub=users&action=edit" method="POST" target="_self">
        <!-- Fieldset Gruppe --> 
        <fieldset> 
        <!-- Auszeichnungs- und Steuerelemente -->
                  
                  Choose New Password:
                  
                  Verify New Password:                   
                  
                  <label for=name accesskey=n>name:</label>
                  <input type=text name=name id=name>
                   
                  <label for="select">Gender:</label>
            	  <select name="gernder" id="select">
            		<option value="-----">------</option>
            		<option value="male">male</option>
            		<option value="female">female</option>
            	  </select>            
              
                  <label for=email accesskey=e>e-mail address:</label>
                  <input type=text name=email id=email>
              
                  <label for="birthdate">Birthdate:</label>
                  <input type="text" name="birthdate" id="birthdate">:
                  <small>Change this to 3 dropwdowns? day-month-year. </small>
                   
                  <label for="location">Location:</label>
                  <input type="text" name="location" id="location">
                    
                  <label for="nachricht">Description:</label>
                  <textarea name="desc" rows="5" cols="50" id="desc"></textarea>
	             
              
          </fieldset>
          
        </form>
        
        {* todo : Debugausgabe nur wenn DEBUG = 1 *}
        {if $smarty.const.DEBUG eq "1"} <br /> Debugausgabe des Arrays:  {$userprofil|@var_dump}  {/if}
       
 
 </div> {* close tab-page2 *}


{* ###############   Tab 2: Group Memberships  ############## *}
 
 <div class="tab-page" id="tabPage2">
        
        <h2 class="tab">Group Memberships</h2>
        <script type="text/javascript">tp1.addTabPage( document.getElementById( "tabPage2" ) );</script>
        
        {* todo : Debugausgabe nur wenn DEBUG = 1 *}
        {if $smarty.const.DEBUG eq "1"} Debugausgabe des Arrays: {html_alt_table loop=$groupsofuser}  {/if}
        
        <h3>Group Memberships of {$userprofil.0.nick} #{$userprofil.0.user_id}</h3>
        
        The user {$userprofil.0.nick} #{$userprofil.0.user_id} is member of the following groups:
        
        <form action="index.php?mod=admin&sub=user&action=edit" method="POST">
        <table cellpadding="0" cellspacing="0" border="0" width="100%">
        
        <tr>
            <td class="td_header" width="50" align="center">{translate}ID{/translate}</td>
            <td class="td_header" width="50" align="center">{translate}Position{/translate}</td>
            <td class="td_header" width="200" align="center">{translate}Name{/translate}</td>
            <td class="td_header" width="300" align="center">{translate}Description{/translate}</td>
            <td class="td_header" width="100" align="center">{translate}Icon{/translate}</td>
            <td class="td_header" width="200" align="center">{translate}Image{/translate}</td>
            <td class="td_header" align="center">{translate}Edit{/translate}</td>
            <td class="td_header" align="center">{translate}Delete{/translate}</td>
        </tr>
        
        {foreach key=key item=group from=$groupsofuser}
            
        <tr class="{cycle values="cell1,cell2"}">
            <input type="hidden" name="ids[]" value="{$group.group_id}" />
            <td align="center" height="30">{$group.group_id}</td>
            <td align="center">{$group.pos}</td>
            <td align="center" style="color: {$group.color}; font-weight: bold;">{$group.name}</td>
            <td align="center">{$group.description}</td>
            <td align="center">{$group.icon}</td>
            <td align="center">{$group.image}</td>
            <td align="center"><a class="input_submit" style="position: relative; top: 7px;" href="index.php?mod=admin&sub=groups&action=edit&id={$group.group_id}">Edit</a></td>
            <td align="center"><input type="checkbox" name="delete[]" value="{$group.group_id}"></td>
            
        </tr>
            
        {/foreach}
        
        <tr>
        <td colspan="8" align="right">
            <input class="Button" type="reset" tabindex="3" />
            <input type="submit" name="submit" class="input_submit" value="Delete the selected groups" />
        </td>
        </tr>
        </table>
        </form>
        
        
        
        
        {$groupsofuser|@var_dump}
        
 </div> {* close tab-page2 *}
 
 {* ###############   Tab 3: Contact Information  ############## *}
 
 <div class="tab-page" id="tabPage3">
        
        <h2 class="tab">Contact Information</h2>
        <script type="text/javascript">tp1.addTabPage( document.getElementById( "tabPage3" ) );</script>
        
        <h3>Contact Information of {$userprofil.0.nick} #{$userprofil.0.user_id}</h3>
        
        <form class="h3sForm" action="index.php?mod=admin&sub=users&action=edit" method="POST" target="_self">
        <!-- Auszeichnungs- und Steuerelemente -->
        <fieldset > 
        <input name="group_id" type="hidden" value="{$contactinfo.user_id}">
                           
           <h3> Edit Contact Informations </h3>
    	    
           <label for="Email"> 
                Email 
    			<input id="email" name="email" type="text" value="{$contactinfo.email}" />
    			<small>Must match the email address you just entered above.</small>
    	   </label>
    	   </fieldset> 
    	   
    	   <fieldset> 
    	   
    	   <label for="ICQ"> 
                 ICQ
    			<input id="icq" name="icq" type="text" value="{$contactinfo.icq}" />
    			<small>This is your ICQ number</small>
    	   </label>
    	   
    	   <label for="AIM"> 
                 AIM
    			<input id="AIM" name="AIM" type="text" value="{$contactinfo.AIM}" />
    			<small>This is your AOL Instant Messenger nickname.</small>
    	   </label>
    	   
    	   <label for="YIM"> 
                 YIM
    			<input id="YIM" name="YIM" type="text" value="{$contactinfo.YIM}" />
    			<small>This is your Yahoo! Instant Messenger nickname.</small>
    	   </label>
    	   
    	   <label for="MSN"> 
                 MSN
    			<input id="MSN" name="MSN" type="text" value="{$contactinfo.MSN}" />
    			<small>This is your MSN Instant Messenger address.</small>
    	   </label>
    	   
    	   <label for="Google Talk"> 
                 Google Talk
    			<input id="Google Talk" name="Google Talk" type="text" value="{$contactinfo.googletalk}" />
    			<small>This is your Google Talk address.</small>
    	   </label>
    	   
    	   <label for="Google Talk"> 
                 Google Talk
    			<input id="Google Talk" name="Google Talk" type="text" value="{$contactinfo.googletalk}" />
    			<small>This is your Google Talk address.</small>
    	   </label>
    	   </fieldset> 
    	   
    	   <fieldset > 
    	   <label for="Website title"> 
                 Website title
    			<input id="Website title" name="Website title" type="text" value="{$contactinfo.website}" />
    			<small>This must be included if you specify a URL below.</small>
    	   </label>
    	   
    	   <label for="Website URL:"> 
                 Website title
    			<input id="Website title" name="Website title" type="text" value="{$contactinfo.website}" />
    			<small>This must be a complete URL.</small>
    	   </label>
    	   </fieldset > 
    	  
    		<label for="icon">
                Icon
    			{* <input id="icon" name="icon" type="text" value="iconname?" /> *}
    				<input type="text" id="icon" class="selectFile" name="icon" />
                    <input type="button" name="select" onclick="ImageSelector.select('icon');" />
    		</label>
    		
    		<input class="input_tsubmit" type="submit" name="submit" value="{translate}Edit User{/translate}" />
    			
    	</fieldset>
        </form>
        
        {* todo : Debugausgabe nur wenn DEBUG = 1 *}
        {if $smarty.const.DEBUG eq "1"} Debugausgabe des Arrays: {$contactinfo|@var_dump}  {/if}
        
 </div> {* close tab-page3 *}
</div> {* close pane-page *} 