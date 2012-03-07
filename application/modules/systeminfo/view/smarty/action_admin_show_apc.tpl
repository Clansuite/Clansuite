{modulenavigation}

{* {$apc_sysinfos.sma_info|dump:APC_SYSINFOS_sma_info} *}
{* {$apc_sysinfos.cache_info|dump:APC_SYSINFOS_cache_info} *}
{* {$apc_sysinfos.system_cache_info|dump:APC_SYSINFOS_system_cache_info} *}
{* {$apc_sysinfos.settings|dump:APC_SYSINFOS_settings} *}

<div class="ModuleHeading">{t}Statistics for Alternative PHP Cache {$apc_sysinfos.version}{/t}</div>
<div class="ModuleHeadingSmall">{t}Current Status and statistics of AP-Cache.{/t}</div>

{if empty($apc_sysinfos.version)}

    {messagebox level="info"}
        <strong>Alternative PHP Cache is  <u> not loaded. </u> !</strong>
        <br />
        Enable the PHP Extension 'extension=php_apc.dll' in php.ini.
    {/messagebox}

{else}

<h2>General Cache Informations</h2>
<table>
    <tr><td width="30%">Alternative PHP Cache</td>  <td>{$apc_sysinfos.version}</td></tr>
    <tr><td>PHP Version</td>         <td>{$apc_sysinfos.phpversion}</td></tr>
    <tr><td>APC Host</td>            <td>{$smarty.server.SERVER_NAME} {* $hostname *}</td></tr>
    <tr><td>Server Software</td>     <td>{$smarty.server.SERVER_SOFTWARE}</td></tr>
    <tr><td>Shared Memory</td>       <td>{$apc_sysinfos.sma_info.num_seg} Segment(s) with {$apc_sysinfos.sma_info.seg_size|megabytes}
                                     ({$apc_sysinfos.system_cache_info.memory_type} memory, {$apc_sysinfos.system_cache_info.locking_type} locking)</td></tr>
</table>

<h2>System Cache Informations</h2>
<table>
    <tr><td>Start Time</td>          <td>{$apc_sysinfos.system_cache_info.start_time|dateformat}</td></tr>
	<tr><td>Uptime</td>              <td>{$apc_sysinfos.system_cache_info.start_time|duration}</td></tr>
	<tr><td>Time to Live</td>        <td>{$apc_sysinfos.system_cache_info.ttl}</td></tr>
	<tr><td>Cache Full Counter</td>  <td>{$apc_sysinfos.system_cache_info.expunges}</td></tr>
	<tr><td>File Upload Support</td> <td>{$apc_sysinfos.system_cache_info.file_upload_progress}</td></tr>
	<tr><td>Cache Size Files</td>    <td>{$apc_sysinfos.system_cache_info.size_files}</td></tr>

</table>

<h2>Stats</h2>
<table>
<thead>
    <th>Host</th><th>Version</th>
    <th>Hits</th><th>Misses</th><th>Hit Rate</th>
    <th>MemTotal</th><th>MemUsed</th><th>MemFree</th>
    <th>Files cached</th><th>Files deleted</th>
    <th>Action</th>
</thead>
<tbody>
<tr>
    <td>Host</td>
    <td>Version</td>
    <td>{$apc_sysinfos.system_cache_info.num_hits}</td>
    <td>{$apc_sysinfos.system_cache_info.num_misses}</td>
    <td>{$apc_sysinfos.system_cache_info.hit_rate_percentage}</td>
    <td>{$apc_sysinfos.sma_info.mem_size|megabytes}</td>
    <td>{$apc_sysinfos.sma_info.mem_used|megabytes}</td>
    <td>{$apc_sysinfos.sma_info.avail_mem|megabytes} {$apc_sysinfos.sma_info.mem_avail_percentage}</td>
    <td>{$apc_sysinfos.system_cache_info.files_cached}</td>
    <td>{$apc_sysinfos.system_cache_info.files_deleted}</td>
</tr>
</tbody>
</table>

{openflashchart width="220" heigth="110" url="index.php?mod=systeminfo&sub=admin&action=return_ofc_hitrates"}

<h2>Runtime Settings</h2>
{* Debug {$apc_sysinfos.settings|dump} *}

<table>
<tr><td>Name</td><td>global_value</td><td>local_value</td><td>access</td><td>accessname</td></tr>

{foreach name=outer from=$apc_sysinfos.settings key=key item=value}
    <tr><td><b>{$key|replace:'apc.':''}</b></td>
    {foreach from=$value key=key item=value}
        <td>{$value}</td>
    {/foreach}
    </tr>
{/foreach}
</table>

{/if}