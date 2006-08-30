<h2>Administration of User</h2>

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


{* ###############   Tab 1: Edit User Profil   ############## *}
 
 <div class="tab-page" id="tabPage1">
        
        <h2 class="tab">Edit User Profil</h2>
        <script type="text/javascript">tp1.addTabPage( document.getElementById( "tabPage1" ) );</script>
        
        <h3>Edit Profil - {$userprofil.0.nick} #{$userprofil.0.user_id}</h3>
        
        <p>This is your profile. You can choose which information to fill out, other than the required section. This information will be displayed in various places throughout {$website_name}. </p>
        
        <form id="h3sForm" action="index.php?mod=admin&sub=users&action=edit" method="POST" target="_self">
            
          <fieldset > 
                     
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
              
                  Birthdate:
                    
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
        
        {$groupsofuser|@var_dump}
        
 </div> {* close tab-page2 *}
 
 {* ###############   Tab 3: Contact Information  ############## *}
 
 <div class="tab-page" id="tabPage3">
        
        <h2 class="tab">Contact Information</h2>
        <script type="text/javascript">tp1.addTabPage( document.getElementById( "tabPage3" ) );</script>
        
        <h3>Contact Information of {$userprofil.0.nick} #{$userprofil.0.user_id}</h3>
        
        <form id="h3sForm" action="index.php?mod=admin&sub=users&action=edit" method="POST" target="_self">
        <fieldset > 
        <input name="group_id" type="hidden" value="{$contactinfo.user_id}">
                           
           <h3> Edit Contact Informations </h3>
    	    
           <label for="Email"> 
                Email 
    			<input id="email" name="email" type="text" value="{$contactinfo.email}" />
    			<small>Must match the email address you just entered above.</small>
    	   </label>
    	   
    	   <label for="ICQ"> 
                This is your ICQ number 
    			<input id="icq" name="icq" type="text" value="{$contactinfo.icq}" />
    	   </label>
    	   
    	   <label for="Email "> 
                Email 
    			<input id="email" name="email" type="text" value="{$contactinfo.email}" />
    	   </label>
    		            				
    		
    		<label for="icon">
                Icon
    			{* <input id="icon" name="icon" type="text" value="iconname?" /> *}
    				<input type="text" id="icon" class="selectFile" name="icon" />
                    <input type="button" name="select" onclick="ImageSelector.select('icon');" />
    		</label>
    		
    		<input class="input_tsubmit" type="submit" name="submit" value="{translate}Edit User{/translate}" />
    			
    	</fieldset>
        </form>
        
        
        Hide
        
        :
        . 	

        AIM:
        This is your AOL Instant Messenger nickname. 	

        YIM:
        This is your Yahoo! Instant Messenger nickname. 	

        MSN:
        This is your MSN Instant Messenger address. 	

        Google Talk:
        This is your Google Talk address. 	

        Website title:
        This must be included if you specify a URL below. 	

        Website URL:
        This must be a complete URL.
        
        {* todo : Debugausgabe nur wenn DEBUG = 1 *}
        {if $smarty.const.DEBUG eq "1"} Debugausgabe des Arrays: {$contactinfo|@var_dump}  {/if}
        
 </div> {* close tab-page3 *}
</div> {* close pane-page *} 