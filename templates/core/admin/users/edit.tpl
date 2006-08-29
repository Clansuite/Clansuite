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
             
        {* todo : Debugausgabe nur wenn DEBUG = 1 *}
        {if $smarty.const.DEBUG eq "1"} Debugausgabe des Arrays: {html_alt_table loop=$userprofil}  {/if}
        
        <h3>Edit Profil - {$userprofil.0.nick} #{$userprofil.0.user_id}</h3>
        
        {$userprofil|@var_dump}
 
 </div> {* close tab-page2 *}


{* ###############   Tab 2: Group Memberships  ############## *}
 
 <div class="tab-page" id="tabPage2">
        
        <h2 class="tab">Group Memberships</h2>
        <script type="text/javascript">tp1.addTabPage( document.getElementById( "tabPage2" ) );</script>
        
        {* todo : Debugausgabe nur wenn DEBUG = 1 *}
        {if $smarty.const.DEBUG eq "1"} Debugausgabe des Arrays: {html_alt_table loop=$groupsofuser}  {/if}
        
        <h3>Group Memberships - {$userprofil.0.nick} #{$userprofil.0.user_id}</h3>
        
        {$groupsofuser|@var_dump}
        
 </div> {* close tab-page2 *}
 
  </div> {* close pane-page *}
 
 