{doc_raw}
<script type="text/javascript" src="{$www_core_tpl_root}/javascript/prototype/prototype.js"></script>
<script type="text/javascript" src="{$www_core_tpl_root}/javascript/scriptaculous/scriptaculous.js"></script>
<script type="text/javascript" src="{$www_core_tpl_root}/javascript/smarty_ajax.js"></script>
<script type="text/javascript" src="{$www_core_tpl_root}/javascript/clip.js"></script>
<link rel="stylesheet" type="text/css" href="{$www_tpl_root}/coffee-with-milk.css" />
{/doc_raw}

{* Debuganzeige, wenn DEBUG = 1 |   {$servers|@var_dump}
{if $smarty.const.DEBUG eq "1"} Debugausgabe des Arrays:   {html_alt_table loop=$servers} {/if} *}

<table>
	<tr>
		<th style="width:50px">{translate}Status{/translate}</th>
		<th style="width:20px">{translate}ID{/translate}</th>
		<th>{translate}Gametype{/translate}</th>
		<th>{translate}Country{/translate}</th>
		<th>{translate}Name{/translate}</th>
		<th>{translate}IP : Port{/translate}</th>
		<th>{translate}Connect{/translate}</th>
	</tr>
{foreach key=key item=server from=$servers}
	<tr>
		<td>
			<img onclick="{ajax_update url='index.php?mod=serverlist&amp;action=get_serverdetails' update_id="server_details_`$server.server_id`" params="server_id=`$server.server_id`" callback="new Effect.SlideDown(\'serverdata_`$server.server_id`\')"}" src="{$www_tpl_root}/images/serverlist/reload_{if $server.response == true}green{else}grey{/if}.png" alt="Refresh Server {$server.server_id}" />
			<img onclick="new Effect.toggle('serverdata_{$server.server_id}', 'slide'); return false;" id="ClipDownImage_{$server.server_id}" style="display:none;" src="{$www_tpl_root}/images/serverlist/dn.gif" alt="Refresh Server {$server.server_id}" />
		</td>
		<td>{$server.server_id}</td>
		<td><img alt="Gametype Icon" src="{$www_tpl_root}/images/serverlist/gametype/{$server.gametype}.ico" /></td>
		<td>
		{if $server.image_country==''}
			<img alt="Country as Empty Icon" src="{$www_core_tpl_root}/images/empty.png" />
		{else}
			<img alt="Country Icon" src="{$www_core_tpl_root}/images/countries/{$server.image_country}" />
		{/if}
		</td>
		<td>{$server.name}</td>
		<td>{$server.ip}:{$server.port}</td>
		<td>
			<a href="hlsw://{$server.ip}:{$server.port}"><img src="{$www_tpl_root}/images/serverlist/hlsw.ico" alt="HLSW Connect" /></a>
			{if $server.csquery_engine == 'steam'}
			<a href='steam: "-applaunch 10 -game cstrike +connect {$server.ip}:{$server.port}"'><img src="{$www_tpl_root}/images/serverlist/steam2.ico" alt="Steam Connect" /></a>
			{/if}
		</td>
	</tr>
	<tr>
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
</table>