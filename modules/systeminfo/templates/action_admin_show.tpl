{include file="modulenavigation.tpl"}
<div class="ModuleHeading">Systeminformation</div>
<div class="ModuleHeadingSmall">Die Systeminformation zeigt Ihnen den aktuellen Status der Clansuite Webserver-Umgebung und der Erweiterungen an.</div>

<div class="clansuite-container">

    <table>
    <thead class="tr_header">
        <th  colspan="2">System Informations</th>
    </thead>
    <tbody>
    <tr>
        <td class="cell2" width="30%">Clansuite Information</td>
        <td class="cell1" width="70%">Clansuite {$clansuite_version} - Milestone: {$clansuite_version_name} - State: {$clansuite_version_state} - SVN: [{$clansuite_revision}]</td>
    </tr>
    <tr class="tr_row1">
        <td>Operating System Information</td>
        <td>{$sysinfos.php_uname} ({$sysinfos.php_os}).</td>
    </tr>
    <tr class="tr_row1">
        <td class="tr_row2">PHP Information</td>
        <td>
            PHP Version {$sysinfos.phpversion} - Zend Core Version {$sysinfos.zendversion} <br /><br />
            Extensions loaded with PHP <br />
                <ul>
                    {* DEBUG: {$sysinfos.php_extensions|var_dump} *}
                    {foreach name=php_extensions from=$sysinfos.php_extensions item=php_extension}
                    <li> {$php_extension} </li>
                    {/foreach}
                </ul>
        </td>
    </tr>
    <tr class="tr_row1">
        <td>Apache Information</td>
        <td>
            The Webserver Interface is {$sysinfos.php_sapi_name} on {$sysinfos.php_uname} ({$sysinfos.php_os}). <br /><br />
            {if isset($sysinfos.php_sapi_cgi)} Server-API is using CGI.  <br /><br /> {/if}
            Apache Server Version {$sysinfos.apache_get_version} <br /><br />
            Modules loaded with Apache <br />
                <ul>
                    {* DEBUG: {$sysinfos.apache_modules|var_dump} *}
                    {foreach name=apache_modules from=$sysinfos.apache_modules item=apache_module}
                    <li> {$apache_module} </li>
                    {/foreach}

                </ul>
        </td>
    </tr>

    <tr class="tr_row1">
        <td>Database Information</td>
        <td>
            Database Type: {$sysinfos.pdo.driver_name}
            <br/>
            Client: {$sysinfos.pdo.client_info}
            <br/>
            Server Version: {$sysinfos.pdo.server_version}
            {* <br/>
            {$sysinfos.pdo.timeout}
            <br/>
            {$sysinfos.pdo.prefetch}   *}
            <br/>
            Oracle Nulls: {$sysinfos.pdo.oracle_nulls}
            <br/>
            Connection Status: {$sysinfos.pdo.connection_status}
            <br/>
            Persistant Connection: {$sysinfos.pdo.persistent}
            <br/>
            Case: {$sysinfos.pdo.attr_case}
            <br/>
            Database Statistics:
            <ul>
                {* Debug Array: {$sysinfos.pdo.server_infos|@var_dump} *}
                {foreach item=serverinfo from=$sysinfos.pdo.server_infos}
                <li>{$serverinfo}</li>
                {/foreach}
             </ul>
        </td>
    </tr>

    </tbody>
    </table>
</div>