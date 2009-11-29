<?php /* Smarty version 2.6.25-dev, created on 2009-11-16 22:27:24
         compiled from C:/xampp/htdocs/projects/Clansuite/modules/systeminfo/templates/action_admin_show.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'modulenavigation', 'C:/xampp/htdocs/projects/Clansuite/modules/systeminfo/templates/action_admin_show.tpl', 3, false),array('block', 't', 'C:/xampp/htdocs/projects/Clansuite/modules/systeminfo/templates/action_admin_show.tpl', 5, false),array('block', 'tabpanel', 'C:/xampp/htdocs/projects/Clansuite/modules/systeminfo/templates/action_admin_show.tpl', 9, false),array('block', 'tabpage', 'C:/xampp/htdocs/projects/Clansuite/modules/systeminfo/templates/action_admin_show.tpl', 11, false),)), $this); ?>

<?php echo smarty_function_modulenavigation(array(), $this);?>

<div class="ModuleHeading">Systeminformation</div>
<div class="ModuleHeadingSmall"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>This overview shows pieces of information about your system, the current state of your webserver-environment and it's plugins.<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></div>

<div class="clansuite-container" style="font-family: Verdana, Arial, sans-serif">

    <?php $this->_tag_stack[] = array('tabpanel', array('name' => 'System Information')); $_block_repeat=true;smarty_block_tabpanel($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>

        <?php $this->_tag_stack[] = array('tabpage', array('name' => 'System Information')); $_block_repeat=true;smarty_block_tabpage($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>

        <h2>System Information</h2>
        <br />

        <h3>Clansuite Information</h3><br/>
        <p>
            Clansuite <?php echo $this->_tpl_vars['clansuite_version']; ?>
 - Milestone: <?php echo $this->_tpl_vars['clansuite_version_name']; ?>
 - State: <?php echo $this->_tpl_vars['clansuite_version_state']; ?>
 - SVN: [<?php echo $this->_tpl_vars['clansuite_revision']; ?>
]
        </p>

        <br />

        <h3>Operating System Information</h3><br />
        <p>
            <?php echo $this->_tpl_vars['sysinfos']['php_uname']; ?>
 (<?php echo $this->_tpl_vars['sysinfos']['php_os']; ?>
 <?php echo $this->_tpl_vars['sysinfos']['php_os_bit']; ?>
).
        </p>

        <?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_tabpage($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

        <?php $this->_tag_stack[] = array('tabpage', array('name' => 'PHP Information')); $_block_repeat=true;smarty_block_tabpage($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>

        <h2>PHP Information</h2>
        <br/>
        <dl>
            <dt>PHP Version <?php echo $this->_tpl_vars['sysinfos']['phpversion']; ?>
</dt>
            <dt>Zend Core Version <?php echo $this->_tpl_vars['sysinfos']['zendversion']; ?>
</dt>
        </dl>
        <br/>

        <h3>PHP Extensions</h3>
        <br />
        <ul>
                        <?php $_from = $this->_tpl_vars['sysinfos']['php_extensions']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['php_extensions'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['php_extensions']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['php_extension']):
        $this->_foreach['php_extensions']['iteration']++;
?>
            <li style="float:none;"> <?php echo $this->_tpl_vars['php_extension']; ?>
 </li>
            <?php endforeach; endif; unset($_from); ?>
        </ul>

        <table>
        <tr>
           <td>
                <h3>PHP Settings</h3>
                <dl>
                    <dt><b>Configuration and Inclusion Paths</b></dt>

                    <dt>Path to php.ini</dt>
                    <dd><?php echo $this->_tpl_vars['sysinfos']['path_to_phpini']; ?>
</dd>
                    <dd>This is the path to the php.ini configuration file. All of the following settings are configurable there.</dd>

                    <dt>Config Include Path</dt>
                    <dd><?php echo $this->_tpl_vars['sysinfos']['cfg_include_path']; ?>
</dd>
                    <dd><small>List of valid inclusion directories. The functions require(), include(), fopen(), file(), readfile() and file_get_contents() will search there for files.</small></dd>

                    <dt>Config File Path</dt>
                    <dd><?php echo $this->_tpl_vars['sysinfos']['cfg_file_path']; ?>
</dd>
                    <dd><small>Lists the configuration file path.</small></dd>

                    <dt><b>General Settings</b></dt>

                    <dt>Zend1 Compat Mode</dt>
                    <dd><?php echo $this->_tpl_vars['sysinfos']['zend_ze1_compatibility_mode']; ?>
</dd>
                    <dd><small>Shows if compatibility mode with Zend Core Engine 1 is enabled.</small></dd>

                    <dt>Memory Limit</dt>
                    <dd><?php echo $this->_tpl_vars['sysinfos']['memory_limit']; ?>
</dd>
                    <dd><small>The amount of memory available for the script. If memory_limit is active it will affect file uploading.
                    The memory_limit should be larger than post_max_size.</small></dd>

                    <dt><b>File Upload Settings</b>

                    <dt>File uploads</dt>
                    <dd><?php echo $this->_tpl_vars['sysinfos']['file_uploads']; ?>
</dd>
                    <dd><small>This settings shows whether or not HTTP file uploads are allowed.</small></dd>

                    <dt>Upload Max Filesize</dt>
                    <dd><?php echo $this->_tpl_vars['sysinfos']['upload_max_filesize']; ?>
</dd>
                    <dd><small>The maximum size of uploaded files.</small></dd>

                    <dt>Post Max Size</dt>
                    <dd><?php echo $this->_tpl_vars['sysinfos']['post_max_size']; ?>
</dd>
                    <dd><small>Sets max size of post data allowed. This setting also affects file uploads.
                    To upload large files, this value must be larger than upload_max_filesize.</small></dd>

                    <dt>Maximum Input Time</dt>
                    <dd><?php echo $this->_tpl_vars['sysinfos']['max_input_time']; ?>
</dd>
                    <dd><small>This maximum time in seconds for parsing input data, like $_POST, $_GET and $_FILES.</small></dd>

                    <dt>Maximum Execution Time</dt>
                    <dd><?php echo $this->_tpl_vars['sysinfos']['max_execution_time']; ?>
</dd>
                    <dd><small>The maximum runtime of a script in seconds before it is terminated by the parser.</small></dd>

                    <dt><b>Security Settings</b>

                    <dt>Remote open - Allow URL fopen</dt>
                    <dd><?php echo $this->_tpl_vars['sysinfos']['allow_url_fopen']; ?>
</dd>
                    <dd><small>This option enables the URL-aware fopen wrappers that enable accessing URL object like files.
                    You might disable this for security reasons, to prevent remote file inclusion attacks.</small></dd>

                    <dt>Remote include - Allow URL include</dt>
                    <dd><?php echo $this->_tpl_vars['sysinfos']['allow_url_include']; ?>
</dd>
                    <dd><small>This option allows the use of URL-aware fopen wrappers with the following functions: include(), include_once(), require(), require_once().
                    You might disable this for security reasons, to prevent remote file inclusion attacks.</small></dd>


                    <dt>Zend Thread Safty</dt>
                    <dd><?php echo $this->_tpl_vars['sysinfos']['zend_thread_safty']; ?>
</dd>
                    <dd><small>Whether or not Zend Thread Safty mode is active.</small></dd>

                    <dt>Safe Mode</dt>
                    <dd><?php echo $this->_tpl_vars['sysinfos']['safe_mode']; ?>
</dd>
                    <dd><small>Whether or not PHP's safe mode is active.</small></dd>

                    <dt>Open Basedir</dt>
                    <dd><?php echo $this->_tpl_vars['sysinfos']['open_basedir']; ?>
</dd>
                    <dd><small>This specifies the directory-tree where files can be opened by PHP. This directive is NOT affected by whether Safe Mode is turned On or Off.</small></dd>

                    <dt>Disabled Functions</dt>
                    <dd><?php echo $this->_tpl_vars['sysinfos']['disable_functions']; ?>
</dd>
                    <dd><small>This directive allows you to disable certain functions for security reasons.</small></dd>

                    <dt>Disabled Classes</dt>
                    <dd><?php echo $this->_tpl_vars['sysinfos']['disable_classes']; ?>
</dd>
                    <dd><small>This directive allows you to disable certain classes for security reasons.</small></dd>

                    <dt>Enable DL</dt>
                    <dd><?php echo $this->_tpl_vars['sysinfos']['enable_dl']; ?>
</dd>
                    <dd><small>Whether or not the dynamical loading of php extension (at runtime) is active.</small></dd>

                    <dt>Magic Quotes GPC</dt>
                    <dd><?php echo $this->_tpl_vars['sysinfos']['magic_quotes_gpc']; ?>
</dd>
                    <dd><small>Whether or not MagicQuotesGPC is active.</small></dd>

                    <dt>Register Globals</dt>
                    <dd><?php echo $this->_tpl_vars['sysinfos']['register_globals']; ?>
</dd>
                    <dd><small>Whether or not Register Globals is active.</small></dd>

                    <dt><b>Charset and Data-handling Settings</b></dt>

                    <dt>Unicode Semantics</dt>
                    <dd><?php echo $this->_tpl_vars['sysinfos']['unicode_semantics']; ?>
</dd>

                    <dt>Filter Default</dt>
                    <dd><?php echo $this->_tpl_vars['sysinfos']['filter_default']; ?>
</dd>
                    <dd><small>Whether or not Filter Default mode is active.</small></dd>

                    <dt>MB String Function Overload</dt>
                    <dd><?php echo $this->_tpl_vars['sysinfos']['mbstring_func_overload']; ?>
</dd>
                    <dd><small>Overloads a set of single byte functions by the mbstring counterparts.</small></dd>

                </dl>
            </td>
        </tr>
        </table>

        <?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_tabpage($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

        <?php $this->_tag_stack[] = array('tabpage', array('name' => 'Webserver Information')); $_block_repeat=true;smarty_block_tabpage($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>

        <h2>Webserver Information</h2>

        <br/>

        The Webserver Interface is <?php echo $this->_tpl_vars['sysinfos']['php_sapi_name']; ?>
 on <?php echo $this->_tpl_vars['sysinfos']['php_uname']; ?>
 (<?php echo $this->_tpl_vars['sysinfos']['php_os']; ?>
). <br /><br />
        <?php if (isset ( $this->_tpl_vars['sysinfos']['php_sapi_cgi'] )): ?> Server-API is using CGI.  <br /><br /> <?php endif; ?>
        <?php if (isset ( $this->_tpl_vars['sysinfos']['apache_get_version'] )): ?> Apache Server Version <?php echo $this->_tpl_vars['sysinfos']['apache_get_version']; ?>
 <br /><br /> <?php endif; ?>
        <?php if (isset ( $this->_tpl_vars['sysinfos']['apache_modules'] )): ?>
        Modules loaded with Apache <br />
        <ul>
                        <?php $_from = $this->_tpl_vars['sysinfos']['apache_modules']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['apache_modules'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['apache_modules']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['apache_module']):
        $this->_foreach['apache_modules']['iteration']++;
?>
            <li style="float:none;"> <?php echo $this->_tpl_vars['apache_module']; ?>
 </li>
            <?php endforeach; endif; unset($_from); ?>
        </ul>
        <?php endif; ?>

        <?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_tabpage($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

        <?php $this->_tag_stack[] = array('tabpage', array('name' => 'Database Information')); $_block_repeat=true;smarty_block_tabpage($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>

        <h2>Database Information</h2>

        <br />

        Database Type: <?php echo $this->_tpl_vars['sysinfos']['pdo']['driver_name']; ?>

        <br/>
        Client: <?php echo $this->_tpl_vars['sysinfos']['pdo']['client_info']; ?>

        <br/>
        Server Version: <?php echo $this->_tpl_vars['sysinfos']['pdo']['server_version']; ?>

                <br/>
        Oracle Nulls: <?php echo $this->_tpl_vars['sysinfos']['pdo']['oracle_nulls']; ?>

        <br/>
        Connection Status: <?php echo $this->_tpl_vars['sysinfos']['pdo']['connection_status']; ?>

        <br/>
        Persistant Connection: <?php echo $this->_tpl_vars['sysinfos']['pdo']['persistent']; ?>

        <br/>
        Case: <?php echo $this->_tpl_vars['sysinfos']['pdo']['attr_case']; ?>

        <br/>
        Database Statistics:
        <ul>
                        <?php $_from = $this->_tpl_vars['sysinfos']['pdo']['server_infos']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['serverinfo']):
?>
            <li><?php echo $this->_tpl_vars['serverinfo']; ?>
</li>
            <?php endforeach; endif; unset($_from); ?>
         </ul>

        <?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_tabpage($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

    <?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_tabpanel($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

</div>