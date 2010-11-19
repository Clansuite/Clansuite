{doc_raw target="head"}
<script type="text/javascript" src="{$www_root_themes_core}javascript/prototype/prototype.js"></script>
<script type="text/javascript" src="{$www_root_themes_core}javascript/scriptaculous/scriptaculous.js"></script>
<script type="text/javascript" src="{$www_root_themes_core}javascript/smarty_ajax.js"></script>
<script type="text/javascript" src="{$www_root_themes_core}javascript/clip.js"></script>
<link rel="stylesheet" type="text/css" href="{$www_root_theme}coffee-with-milk.css" />
{/move_to}

{* Debuganzeige, wenn DEBUG = 1 |   {$servers|var_dump}
{if $smarty.const.DEBUG eq "1"} Debugausgabe des Arrays:   {html_alt_table loop=$servers} {/if} *}

<table>
	<tr>
		<th style="width:50px">{t}Status{/t}</th>
		<th style="width:20px">{t}ID{/t}</th>
		<th>{t}Gametype{/t}</th>
		<th>{t}Country{/t}</th>
		<th>{t}Name{/t}</th>
		<th>{t}IP : Port{/t}</th>
		<th>{t}Connect{/t}</th>
	</tr>
{foreach key=key item=server from=$servers}
	<tr>
		<td>
			<img onclick="{ajax_update url='index.php?mod=serverlist&amp;action=get_serverdetails' update_id="server_details_`$server.server_id`" params="server_id=`$server.server_id`" callback="new Effect.SlideDown(\'serverdata_`$server.server_id`\')"}" src="{$www_root_theme}images/serverlist/reload_{if $server.response == true}green{else}grey{/if}.png" alt="Refresh Server {$server.server_id}" />
			<img onclick="new Effect.toggle('serverdata_{$server.server_id}', 'slide'); return false;" id="ClipDownImage_{$server.server_id}" style="display:none;" src="{$www_root_theme}images/serverlist/dn.gif" alt="Refresh Server {$server.server_id}" />
		</td>
		<td>{$server.server_id}</td>
		<td><img alt="Gametype Icon" src="{$www_root_theme}images/serverlist/gametype/{$server.gametype}.ico" /></td>
		<td>
		{if $server.image_country==''}
			<img alt="Country as Empty Icon" src="{$www_root_themes_core}images/empty.png" />
		{else}
			<img alt="Country Icon" src="{$www_root_themes_core}images/countries/{$server.image_country}" />
		{/if}
		</td>
		<td>{$server.name}</td>
		<td>{$server.ip}:{$server.port}</td>
		<td>
			<a href="hlsw://{$server.ip}:{$server.port}"><img src="{$www_root_theme}images/serverlist/hlsw.ico" alt="HLSW Connect" /></a>
			{if $server.csquery_engine == 'steam'}
			<a href='steam: "-applaunch 10 -game cstrike +connect {$server.ip}:{$server.port}"'><img src="{$www_root_theme}images/serverlist/steam2.ico" alt="Steam Connect" /></a>
			{/if}
		</td>
	</tr>
	<tr>
		<td colspan="10">
			<div style="display: none;" id="serverdata_{$server.server_id}">
			{* Show ServerStats based an Gametype or noresponse.tpl *}
				<div id="server_details_{$server.server_id}">
				{* Before Ajax Serverdata {$server|var_dump} *}
				</div>
			</div>
		</td>
	</tr>
{/foreach}
</table>