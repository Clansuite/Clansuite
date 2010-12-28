{* {$sysinfos|var_dump} *}

{modulenavigation}
<div class="ModuleHeading">Systeminformation</div>
<div class="ModuleHeadingSmall">{t}This overview shows pieces of information about your system, the current state of your webserver-environment and it's plugins.{/t}</div>

{tabpanel name="System Information"}

    {tabpage name="System Information"}

    <h2>System Information</h2>
    <br />

    <h3>Clansuite Information</h3><br/>
    <p>
        Clansuite {$clansuite_version} - Milestone: {$clansuite_version_name} - State: {$clansuite_version_state} - SVN: [{$clansuite_revision}]
    </p>

    <br />

    <h3>Operating System Information</h3><br />
    <p>
        {$sysinfos.php_uname} ({$sysinfos.php_os} {$sysinfos.php_os_bit}).
    </p>

    {/tabpage}

    {tabpage name="PHP Information"}

    <h2>PHP Information</h2>
    <br/>
    <dl>
        <dt>PHP Version {$sysinfos.phpversion}</dt>
        <dt>Zend Core Version {$sysinfos.zendversion}</dt>
    </dl>
    <br/>

    <h3>PHP Extensions</h3>
    <br />
    <ul>
        {* DEBUG: {$sysinfos.php_extensions|var_dump} *}
        {foreach name=php_extensions from=$sysinfos.php_extensions item=php_extension}
        <li style="float:none;"> {$php_extension} </li>
        {/foreach}
    </ul>

    <table>
    <tr>
       <td>
            <h3>PHP Settings</h3>
            <dl>
                <dt><b>Configuration and Inclusion Paths</b></dt>

                <dt>Path to php.ini</dt>
                <dd>{$sysinfos.path_to_phpini}</dd>
                <dd>This is the path to the php.ini configuration file. All of the following settings are configurable there.</dd>

                <dt>Config Include Path</dt>
                <dd>{$sysinfos.cfg_include_path}</dd>
                <dd><small>List of valid inclusion directories. The functions require(), include(), fopen(), file(), readfile() and file_get_contents() will search there for files.</small></dd>

                <dt>Config File Path</dt>
                <dd>{$sysinfos.cfg_file_path}</dd>
                <dd><small>Lists the configuration file path.</small></dd>

                <dt><b>General Settings</b></dt>

                <dt>Zend1 Compat Mode</dt>
                <dd>{$sysinfos.zend_ze1_compatibility_mode}</dd>
                <dd><small>Shows if compatibility mode with Zend Core Engine 1 is enabled.</small></dd>

                <dt>Memory Limit</dt>
                <dd>{$sysinfos.memory_limit}</dd>
                <dd><small>The amount of memory available for the script. If memory_limit is active it will affect file uploading.
                The memory_limit should be larger than post_max_size.</small></dd>

                <dt><b>File Upload Settings</b>

                <dt>File uploads</dt>
                <dd>{$sysinfos.file_uploads}</dd>
                <dd><small>This settings shows whether or not HTTP file uploads are allowed.</small></dd>

                <dt>Upload Max Filesize</dt>
                <dd>{$sysinfos.upload_max_filesize}</dd>
                <dd><small>The maximum size of uploaded files.</small></dd>

                <dt>Post Max Size</dt>
                <dd>{$sysinfos.post_max_size}</dd>
                <dd><small>Sets max size of post data allowed. This setting also affects file uploads.
                To upload large files, this value must be larger than upload_max_filesize.</small></dd>

                <dt>Maximum Input Time</dt>
                <dd>{$sysinfos.max_input_time}</dd>
                <dd><small>This maximum time in seconds for parsing input data, like $_POST, $_GET and $_FILES.</small></dd>

                <dt>Maximum Execution Time</dt>
                <dd>{$sysinfos.max_execution_time}</dd>
                <dd><small>The maximum runtime of a script in seconds before it is terminated by the parser.</small></dd>

                <dt><b>Security Settings</b>

                <dt>Remote open - Allow URL fopen</dt>
                <dd>{$sysinfos.allow_url_fopen}</dd>
                <dd><small>This option enables the URL-aware fopen wrappers that enable accessing URL object like files.
                You might disable this for security reasons, to prevent remote file inclusion attacks.</small></dd>

                <dt>Remote include - Allow URL include</dt>
                <dd>{$sysinfos.allow_url_include}</dd>
                <dd><small>This option allows the use of URL-aware fopen wrappers with the following functions: include(), include_once(), require(), require_once().
                You might disable this for security reasons, to prevent remote file inclusion attacks.</small></dd>


                <dt>Zend Thread Safty</dt>
                <dd>{$sysinfos.zend_thread_safty}</dd>
                <dd><small>Whether or not Zend Thread Safty mode is active.</small></dd>

                <dt>Safe Mode</dt>
                <dd>{$sysinfos.safe_mode}</dd>
                <dd><small>Whether or not PHP's safe mode is active.</small></dd>

                <dt>Open Basedir</dt>
                <dd>{$sysinfos.open_basedir}</dd>
                <dd><small>This specifies the directory-tree where files can be opened by PHP. This directive is NOT affected by whether Safe Mode is turned On or Off.</small></dd>

                <dt>Disabled Functions</dt>
                <dd>{$sysinfos.disable_functions}</dd>
                <dd><small>This directive allows you to disable certain functions for security reasons.</small></dd>

                <dt>Disabled Classes</dt>
                <dd>{$sysinfos.disable_classes}</dd>
                <dd><small>This directive allows you to disable certain classes for security reasons.</small></dd>

                <dt>Enable DL</dt>
                <dd>{$sysinfos.enable_dl}</dd>
                <dd><small>Whether or not the dynamical loading of php extension (at runtime) is active.</small></dd>

                <dt>Magic Quotes GPC</dt>
                <dd>{$sysinfos.magic_quotes_gpc}</dd>
                <dd><small>Whether or not MagicQuotesGPC is active.</small></dd>

                <dt>Register Globals</dt>
                <dd>{$sysinfos.register_globals}</dd>
                <dd><small>Whether or not Register Globals is active.</small></dd>

                <dt><b>Charset and Data-handling Settings</b></dt>

                <dt>Unicode Semantics</dt>
                <dd>{$sysinfos.unicode_semantics}</dd>

                <dt>Filter Default</dt>
                <dd>{$sysinfos.filter_default}</dd>
                <dd><small>Whether or not Filter Default mode is active.</small></dd>

                <dt>MB String Function Overload</dt>
                <dd>{$sysinfos.mbstring_func_overload}</dd>
                <dd><small>Overloads a set of single byte functions by the mbstring counterparts.</small></dd>

            </dl>
        </td>
    </tr>
    </table>

    {/tabpage}

    {tabpage name="Webserver Information"}

    <h2>Webserver Information</h2>

    <br/>

    The Webserver Interface is {$sysinfos.php_sapi_name} on {$sysinfos.php_uname} ({$sysinfos.php_os}). <br /><br />
    {if isset($sysinfos.php_sapi_cgi)} Server-API is using CGI.  <br /><br /> {/if}
    {if isset($sysinfos.apache_get_version)} Apache Server Version {$sysinfos.apache_get_version} <br /><br /> {/if}
    {if isset($sysinfos.apache_modules)}
    Modules loaded with Apache <br />
    <ul>
        {* DEBUG: {$sysinfos.apache_modules|var_dump} *}
        {foreach name=apache_modules item=apache_module from=$sysinfos.apache_modules}
        <li style="float:none;"> {$apache_module} </li>
        {/foreach}
    </ul>
    {/if}

    {/tabpage}

    {tabpage name="Database Information"}

    <h2>Database Information</h2>

    <br />

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
        {* Debug Array: {$sysinfos.pdo.server_infos|var_dump} *}
        {foreach item=serverinfo from=$sysinfos.pdo.server_infos}
        <li>{$serverinfo}</li>
        {/foreach}
     </ul>

    {/tabpage}

{/tabpanel}