{doc_raw}
<script type="text/javascript" src="{$www_root_tpl_core}/javascript/prototype/prototype.js"></script>
<script type="text/javascript" src="{$www_root_tpl_core}/javascript/scriptaculous/scriptaculous.js"></script>
<script type="text/javascript" src="{$www_root_tpl_core}/javascript/smarty_ajax.js"></script>
<script type="text/javascript" src="{$www_root_tpl_core}/javascript/clip.js"></script>
<link rel="stylesheet" type="text/css" href="{$www_root_tpl}/coffee-with-milk.css" />
{/doc_raw}

{* Debuganzeige, wenn DEBUG = 1 |   {$servers|@var_dump}
{if $smarty.const.DEBUG eq "1"} Debugausgabe des Arrays:   {html_alt_table loop=$servers} {/if} *}
   
<table id="tabledesign-coffee" cellpadding="0" cellspacing="0" border="0" width="100%">      
        
        <thead>
            <tr>
                <td width=50>{t}Status{/t}</td>
                <td>{t}ID{/t}</td>
                <td>{t}Gametype{/t}</td>
                <td>{t}Country{/t}</td>
                <td>{t}Name{/t}</td>
                <td>{t}IP : Port{/t}</td>
                <td>{t}Connect{/t}</td>
            </tr>
          </thead>
          
          <tbody>  
            {foreach key=key item=server from=$servers}
               
                <tr>
                   <td width=50><img onclick="{ajax_update url='index.php?mod=serverlist&amp;action=get_serverdetails'
                                                  update_id="server_details_`$server.server_id`" 
                                                  params="server_id=`$server.server_id`"
                                                  callback="new Effect.SlideDown(\'serverdata_`$server.server_id`\')"
                                     }"
                            src="{$www_root_tpl}/images/serverlist/reload_{if $server.response == true}green{else}grey{/if}.png" 
                            alt="Refresh Server {$server.server_id}" />
                       
                        <img onclick="new Effect.toggle('serverdata_{$server.server_id}', 'slide'); return false;"
                             id="ClipDownImage_{$server.server_id}"
                             style="display: none;"
                             src="{$www_root_tpl}/images/serverlist/dn.gif" 
                             alt="Refresh Server {$server.server_id}" />
                        
                   </td>
                   <td>{$server.server_id}</td>
                   <td><img alt="Gametype Icon" src="{$www_root_tpl}/images/serverlist/gametype/{$server.gametype}.ico" /></td>
                   <td>
                        {if $server.image_country==''}
                            <img alt="Country as Empty Icon" src="{$www_root_tpl_core}/images/empty.png" width="16" height="16" />
                        {else}
                            <img alt="Country Icon" src="{$www_root_tpl_core}/images/countries/{$server.image_country}" />
                        {/if}
                    </td>
                    <td>{$server.name}</td>
                    <td>{$server.ip}:{$server.port}</td>
                    <td>
                        <a href="hlsw://{$server.ip}:{$server.port}"><img src="{$www_root_tpl}/images/serverlist/hlsw.ico" class="border3d" alt="HLSW Connect" /></a>
                        {if $server.csquery_engine == 'steam'}
                        <a href='steam: "-applaunch 10 -game cstrike +connect {$server.ip}:{$server.port}"'><img src="{$www_root_tpl}/images/serverlist/steam2.ico" class="border3d" alt="Steam Connect" /></a>
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