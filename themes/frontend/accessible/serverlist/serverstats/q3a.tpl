{* DEBUG   {$serverdata|@var_dump} *}

<div style="float: right"><img src="{$www_root_theme}images/serverlist/up.gif" 
onclick="Effect.SlideUp('serverdata_{$serverdata.server_id}')"; /></div>

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
    <dt>Punkbuster :</dt>  <dd>{if $serverdata.rules.sv_punkbuster == '1'}ON{else}OFF{/if}</dd>
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

<hr style="clear:both;">

<a href="javascript:clip_span('player_{$serverdata.server_id}')">:: Player Information</a>

<span id="span_player_{$serverdata.server_id}" style="display: none;">

<dl>
    <dt>Player Information</dt><dd>{$serverdata.players}</dd>
</dl>	

</span>

<a href="javascript:clip_span('rules_{$serverdata.server_id}')">:: Rules</a>

<span id="span_rules_{$serverdata.server_id}" style="display: none;">

<dl>
    <dt>Rules & Settings</dt><dd>{$serverdata.players}</dd>
</dl>	

</span>
