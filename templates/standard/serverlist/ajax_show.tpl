{doc_raw}
<script src="{$www_core_tpl_root}/javascript/prototype/prototype.js" type="text/javascript"></script>
<script src="{$www_core_tpl_root}/javascript/scriptaculous/scriptaculous.js" type="text/javascript"></script>
<script src="{$www_core_tpl_root}/javascript/smarty_ajax.js" type="text/javascript"></script>
<script src="{$www_core_tpl_root}/javascript/clip.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="{$www_tpl_root}/coffee-with-milk.css" />
{/doc_raw}

{* Debuganzeige, wenn DEBUG = 1 |   {$servers|@var_dump}
{if $smarty.const.DEBUG eq "1"} Debugausgabe des Arrays:   {html_alt_table loop=$servers} {/if} *}
   
<form action="index.php?mod=serverlist&sub=admin&action=delete" method="POST">
    
    <table id="tabledesign-coffee" cellpadding="0" cellspacing="0" border="0" width="100%">      
        
        <thead>
        	<tr>
        	    <td>{translate}Status{/translate}</td>
        		<td>{translate}ID{/translate}</td>
        		<td>{translate}Gametype{/translate}</td>
        		<td>{translate}Country{/translate}</td>
        		<td>{translate}Name{/translate}</td>
        		<td>{translate}IP : Port{/translate}</td>
        		<td>{translate}Connect{/translate}</td>
        	</tr>
          </thead>
          
          <tbody>  
            {foreach key=key item=server from=$servers}
               
                <tr>
                   <td><img onclick="{ajax_update url='index.php?mod=serverlist&action=get_serverdetails'
                                                   update_id="server_details_`$server.server_id`" 
                                                   params="server_id=`$server.server_id`"
                                                   callback="new Effect.SlideDown(\'serverdata_`$server.server_id`\')"
                                     }"
                        src="{$www_core_tpl_root}/images/serverlist/reload_{if $server.response == true}green{else}grey{/if}.png" 
                        alt="Refresh Server {$server.server_id}">
                   </td>
                   <td>{$server.server_id}</td>
                   <td><img src="{$www_core_tpl_root}/images/serverlist/gametype/{$server.gametype}.ico"></td>
                   <td>
                        {if $server.image_country==''}
                            <img src="{$www_core_tpl_root}/images/empty.png" width="16" height="16">
                        {else}
                            <img src="{$www_core_tpl_root}/images/countries/{$server.image_country}">
                        {/if}
                    </td>
                    <td>{$server.name}</td>
                    <td>{$server.ip}:{$server.port}</td>
                    <td>
                    <a href="hlsw://{$server.ip}:{$server.port}"><img src="{$www_core_tpl_root}/images/serverlist/hlsw.ico" class="border3d" alt="HLSW Connect""></a>
                    {if $server.csquery_engine == 'steam'}
                    <a href='steam: "-applaunch 10 -game cstrike +connect {$server.ip}:{$server.port}"'><img src="{$www_core_tpl_root}/images/serverlist/steam2.ico" class="border3d" alt="Steam Connect"></a>
                    {/if}
                    </td>
                </tr>
                
                <tr class="odd"> 
                    <td colspan="10">
                        
                        <div style="display: none;" id="serverdata_{$server.server_id}">
                       
                        {* Show ServerStats based an Gametype or noresponse.tpl *}
                        <div id="server_details_{$server.server_id}">
                            {* Before Ajax Serverdata {$server|@var_dump} *}
                        </div>
                        
                        </div>
                    
                    </td>
                </tr>
            {/foreach}
        </tbody>
    </table>
    
</form>