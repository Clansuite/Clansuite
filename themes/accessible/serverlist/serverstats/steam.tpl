{* DEBUG {$serverdata|@var_dump} *}

<div style="float: right"><img src="{$www_root_theme}images/serverlist/up.gif" 
onclick="Effect.SlideUp('serverdata_{$serverdata.server_id}'); Effect.Appear(ClipDownImage_{$serverdata.server_id})" />
</div>


<span>
<h4>Serverdetails for {$serverdata.gamename} Engine based Server</h3>
{$serverdata.time}
</span>


<dl>
    <dt>Servername:</dt>   <dd>{$serverdata.servertitle}</dd>
    <dt>Address:</dt>      <dd>{$serverdata.address} {$serverdata.queryport}</dd>
    <dt>Version:</dt>      <dd>{$serverdata.gameversion} -</dd>
    
    <dt>Ping:</dt>         <dd>{$serverdata.response} ms</dd>
    <dt>Players:</dt>      <dd>{$serverdata.numplayers}/{$serverdata.maxplayers}</dd>
    <dt>VAC :</dt>         <dd>{if $serverdata.secure == '1'}ON{else}OFF{/if}</dd>
    <dt>Password  :</dt>   <dd>{if $serverdata.password == '0'} None {else} required {/if}</dd>
    <dt>Current Map:</dt>  <dd><div style="float:left; text-align:center;"> 
                                {$serverdata.mapname}
                                
                                <br />
                                
                                {if $serverdata.mapfile == 'unknown_map.png' }
                            	<img src='{$www_root_theme}images/serverlist/maps/{$serverdata.mapfile}';
                            	alt="No Picture for {$serverdata.mapname}" style="border: 1px solid #000000;">
                                {else}
                            	
                            	<img src="{$www_root_theme}images/serverlist/maps/{$serverdata.mapfile}" 
                            	alt="Picture for {$serverdata.mapname}" style="border: 1px solid #000000;">
                            	{/if}
                            	</div>
                           </dd>   
</dl>

<hr style="clear:both; width: 80%;">

{* ####################### Playerlist ###################### *}

<a href="javascript:clip_span('player_{$serverdata.server_id}')">:: Player Information</a>

<span id="span_player_{$serverdata.server_id}" style="display: none; font-size: small;">

<dl>
    <dd>
    {* DEBUG {$serverdata.players|@var_dump} *}
    <table>
       <tr>
           <td>#</td>
           <td>Playername</td>
           <td>Frags</td>
           <td>Onlinetime</td>
       </tr>
        
       {foreach key=key item=item from=$serverdata.players}
       <tr>
            <td>    {$key}          </td>
            <td>    {$item.name}    </td>
            <td>    {$item.score}   </td>
            <td>    {$item.time}    </td>
       </tr>
       {/foreach}
       
    </table>    
    </dd>
</dl>	

</span>

{* ####################### Server Rules & Settings ###################### *}

<a href="javascript:clip_span('rules_{$serverdata.server_id}')">:: Rules & Settings</a>

<span id="span_rules_{$serverdata.server_id}" style="display: none; font-size: small;">

<dl>
    <dd>
    {* DEBUG {$serverdata.rules|@var_dump} *}
    <table>
        <tr>
            <td>Setting</td>
            <td>Value</td>
        </tr>
        
       {foreach key=key item=item from=$serverdata.rules}
       <tr>
            <td>    {$key}          </td>
            <td>    {$item}    </td>
       </tr>       
       {/foreach}
              
    </table>    
</dl>

</span>
		
        
