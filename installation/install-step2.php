<?php
# Security Handler
if (defined('IN_CS') === false)
{
    die( 'Clansuite not loaded. Direct Access forbidden.' );
}
?>
    <div id="content" class="narrowcolumn">
        <div id="content_middle">
            <div class="accordion">
                <h2 class="headerstyle">
                    <img src="images/64px-Utilities-system-monitor.svg.png" border="0" style="vertical-align:middle" alt="installstep image" />
                    <?php echo $language['STEP2_SYSTEMCHECK']; ?>
                </h2>
                <p><?php echo $language['STEP2_IN_GENERAL']; ?></p>
                <p><?php echo $language['STEP2_SYSTEMSETTINGS_REQUIRED']; ?></p>
                <p><?php echo $language['STEP2_SYSTEMSETTINGS_RECOMMENDED']; ?></p>
                <p><?php echo $language['STEP2_SYSTEMSETTINGS_TAKEACTION']; ?></p>
                <p><?php if (get_cfg_var('cfg_file_path')):
                         echo $language['STEP2_SYSTEMSETTINGS_PHPINI']; ?>
                         "<strong><?php echo get_cfg_var('cfg_file_path') ?></strong>".</p>
                   <?php endif; ?>
                <p><?php echo $language['STEP2_SYSTEMSETTINGS_CHECK_VALUES']; ?></p>
                         <?php
                         # Case-Images, to determine if a certain Setting is OK or NOT
                         define('SETTING_TRUE',  '<img src="images/true.gif" alt="OK" height="16" width="16" border="0" />');
                         define('SETTING_FALSE', '<img src="images/false.gif" alt="NOT" height="16" width="16" border="0" />');

                         # determine Strings for ON, OFF, R, W
                         define('SETTING_EXPECTED_ON', $language['STEP2_SETTING_EXPECTED_ON']);
                         define('SETTING_EXPECTED_OFF', $language['STEP2_SETTING_EXPECTED_OFF']);

                         /**
                          * echo alternating table rows
                          * 
                          * Datastructure of "settings" array
                          * $array['settingname']['status']
                          */
                         function setting_rows($settings_array)
                         {
                            //introduce vars
                            $table_rows = null;
                            $csstoggle = null;

                            // css names
                            $css1 = 'row1';
                            $css2 = 'row2';

                            foreach ($settings_array as $settingname => $value)
                            {
                                // toggle
                                $csstoggle = ($csstoggle==$css1) ? $css2 : $css1;

                                //starting tablerow
                                $table_rows = '<tr class="'. $csstoggle .'">';

                                #$table_rows .= '<td>'. $settingname .'=>'. $value['label'] .'</td>';
                                $table_rows .= '<td>'. $value['label'] .'</td>';
                                $table_rows .= '<td class="col1" align="center">' . $value['expected'] . '</td>';
                                $table_rows .= '<td class="col2" align="center">' . $value['actual'] .'</td>';
                                $table_rows .= '<td class="col1" align="center">' . $value['status'] .'</td>';

                                // ending tablerow
                                $table_rows .= '</tr>';

                                echo $table_rows;
                            }
                         }

                         /**
                          * Gets the boolean value of a php.ini configuration option.
                          * 
                          * @param string php configuration option or function name
                          * @return bool
                          */
                          function iniFlag($phpvar)
                          {
                              $status = strtolower(ini_get($phpvar));
                              return $status === 'on' or $status === 'true' or $status === '1';
                          }

                         /**
                          * get_php_setting
                          *
                          * @param string php configuration option or function name
                          * returns bool if param $get_value = true
                          * else the image defined by SETTING_TRUE/False
                          */
                         function get_php_setting($phpvar, $expected_value, $return_way = 'img')
                         {
                            # get boolean value of setting as 1 or 0
                            $value = (int) iniFlag($phpvar);

                            #echo $phpvar .' - '.$value .' - ist:'. ini_get($phpvar) .'- soll: '. $expected_value .'<br />';
                            
                            switch($return_way)
                            {
                                case 'int':
                                                return $value;
                                case 'string':
                                                return $value ? SETTING_EXPECTED_ON : SETTING_EXPECTED_OFF;
                                default:
                                case 'img' :
                                                if ($expected_value === true)
                                                {
                                                    return $value ? SETTING_TRUE : SETTING_FALSE;
                                                }
                                                else
                                                {
                                                    return $value ? SETTING_FALSE : SETTING_TRUE;
                                                }
                            }
                         }

                         /**
                          * Checks the useability of the temporary directory
                          */
                         function check_temporary_dir()
                         {
                             # filehandle for temp file
                             $temp_file_name = tempnam(sys_get_temp_dir(), "FOO FIGHTERS");

                             if (empty($temp_file_name) === false)
                             {
                                $handle = fopen($temp_file_name, "w");
                                fwrite($handle, "writing to tempfile");
                                fclose($handle);
                                unlink($temp_file_name);
                                return true;
                             }
                             else
                             {
                                return $temp_file_name;
                             }
                         }

                         # REQUIRED CHECKS

                         # Setting: PHP-Version
                         $required_php_version = '5.3';
                         $compare_result = version_compare(PHP_VERSION, $required_php_version,'>=');
                         $required['php_version']['label']      = $language['PHP_VERSION'];
                         $required['php_version']['expected']   = '>= ' . $required_php_version;
                         $required['php_version']['actual']     = PHP_VERSION;
                         $required['php_version']['status']     = empty($compare_result) ? SETTING_FALSE : SETTING_TRUE;

                         # Setting: Session Functions
                         $required['session_functions']['label']    = $language['SESSION_FUNCTIONS'];
                         $required['session_functions']['expected'] = SETTING_EXPECTED_ON;
                         $required['session_functions']['actual']   = function_exists('session_start') ? SETTING_EXPECTED_ON : SETTING_EXPECTED_OFF;
                         $required['session_functions']['status']   = function_exists('session_start') ? SETTING_TRUE : SETTING_FALSE;

                         # Checking for correct session.auto_start configuration in php.ini
                         $required['session.auto_start']['label']      = $language['SESSION_AUTO_START'];
                         $required['session.auto_start']['expected']   = SETTING_EXPECTED_OFF;
                         $required['session.auto_start']['actual']     = get_php_setting('session.auto_start', false, 'string');
                         $required['session.auto_start']['status']     = get_php_setting('session.auto_start', false, 'img');

                         # Setting: PDO
                         $required['pdo_library']['label']    = $language['PDO_LIBRARY'];
                         $required['pdo_library']['expected'] = SETTING_EXPECTED_ON;
                         $required['pdo_library']['actual']   = class_exists('pdo') ? SETTING_EXPECTED_ON : SETTING_EXPECTED_OFF;
                         $required['pdo_library']['status']   = class_exists('pdo') ? SETTING_TRUE : SETTING_FALSE;

                         # Setting: PDO MySQL
                         $required['pdo_mysql_library']['label']    = $language['PDO_MYSQL_LIBRARY'];
                         $required['pdo_mysql_library']['expected'] = SETTING_EXPECTED_ON;
                         $required['pdo_mysql_library']['actual']   = in_array('mysql', PDO::getAvailableDrivers() ) ? SETTING_EXPECTED_ON : SETTING_EXPECTED_OFF;
                         $required['pdo_mysql_library']['status']   = in_array('mysql', PDO::getAvailableDrivers() ) ? SETTING_TRUE : SETTING_FALSE;

                         # Checking for Reflection Class (used by DI-Phemto, maybe missing on modified PHP Versions)
                         $required['class_reflection']['label']      = $language['CLASS_REFLECTION'];
                         $required['class_reflection']['expected']   = SETTING_EXPECTED_ON;
                         $required['class_reflection']['actual']     = class_exists('Reflection',false) ? SETTING_EXPECTED_ON : SETTING_EXPECTED_OFF;
                         $required['class_reflection']['status']     = class_exists('Reflection',false) ? SETTING_TRUE : SETTING_FALSE;

                         # Checking for SPL
                         $required['extension_spl']['label']      = $language['EXTENSION_SPL'];
                         $required['extension_spl']['expected']   = SETTING_EXPECTED_ON;
                         $required['extension_spl']['actual']     = extension_loaded("SPL") ? SETTING_EXPECTED_ON : SETTING_EXPECTED_OFF;
                         $required['extension_spl']['status']     = extension_loaded("SPL") ? SETTING_TRUE : SETTING_FALSE;

              # NOT USED # Checking if session.save_path is writable
              # NOT USED # Setting: Database

                         # Permissions Check: write on systems temporary directory
                         $required['is_writable_temp_dir']['label']    = $language['IS_WRITEABLE_TEMP_DIR'];
                         $required['is_writable_temp_dir']['expected'] = 'w';
                         $required['is_writable_temp_dir']['actual']   = check_temporary_dir() ? 'w' : '---';
                         $required['is_writable_temp_dir']['status']   = check_temporary_dir() ? SETTING_TRUE : SETTING_FALSE;

                         # Permissions Check: write on \clansuite root
                         $required['is_writable_clansuite_root']['label']    = $language['IS_WRITEABLE_CLANSUITE_ROOT'];
                         $required['is_writable_clansuite_root']['expected'] = 'w';
                         $required['is_writable_clansuite_root']['actual']   = is_writeable(ROOT) ? 'w' : '---';
                         $required['is_writable_clansuite_root']['status']   = is_writeable(ROOT) ? SETTING_TRUE : SETTING_FALSE;

                         # Permissions Check: write on \clansuite\cache
                         $required['is_writable_clansuite_cache']['label']    = $language['IS_WRITEABLE_CACHE_DIR'];
                         $required['is_writable_clansuite_cache']['expected'] = 'w';
                         $required['is_writable_clansuite_cache']['actual']   = is_writeable(ROOT_CACHE) ? 'w' : '---';
                         $required['is_writable_clansuite_cache']['status']   = is_writeable(ROOT_CACHE) ? SETTING_TRUE : SETTING_FALSE;

                         # Permissions Check: write on uploads folder
                         $required['is_writable_uploads']['label']    = $language['IS_WRITEABLE_UPLOADS'];
                         $required['is_writable_uploads']['expected'] = 'w';
                         $required['is_writable_uploads']['actual']   = is_writeable(ROOT . 'uploads') ? 'w' : '---';
                         $required['is_writable_uploads']['status']   = is_writeable(ROOT . 'uploads') ? SETTING_TRUE : SETTING_FALSE;

                         # Permissions Check: read on Configuration Template File
                         $required['is_readable_config_template']['label']    = $language['IS_READABLE_CONFIG_TEMPLATE'];
                         $required['is_readable_config_template']['expected'] = 'r';
                         $required['is_readable_config_template']['actual']   = is_readable(INSTALLATION_ROOT . 'clansuite.config.installer') ? 'r' : '---';
                         $required['is_readable_config_template']['status']   = is_readable(INSTALLATION_ROOT . 'clansuite.config.installer') ? SETTING_TRUE : SETTING_FALSE;

                         # Checking for correct date.timezone configuration in php.ini
                         $required['datetimezone']['label']      = $language['DATE_TIMEZONE'];
                         $required['datetimezone']['expected']   = SETTING_EXPECTED_ON;
                         $required['datetimezone']['actual']     = ini_get("date.timezone") ? SETTING_EXPECTED_ON : SETTING_EXPECTED_OFF;
                         $required['datetimezone']['status']     = ini_get("date.timezone") ? SETTING_TRUE : SETTING_FALSE;

                          # Checking RegisterGlobals
                         $required['register_globals']['label']      = $language['REGISTER_GLOBALS'];
                         $required['register_globals']['expected']   = SETTING_EXPECTED_OFF;
                         $required['register_globals']['actual']     = ini_get('register_globals') ? SETTING_EXPECTED_ON : SETTING_EXPECTED_OFF;
                         $required['register_globals']['status']     = ini_get('register_globals') ? SETTING_FALSE: SETTING_TRUE;

                         # RECOMMENDED CHECKS

                         # Setting: PHP memory limit
                         $memory_limit = ini_get('memory_limit');
                         $recommended_memory_limit = 32;
                         $recommended['php_memory_limit']['label']      = $language['PHP_MEMORY_LIMIT'];
                         $recommended['php_memory_limit']['expected']   = 'min '. $recommended_memory_limit .'MB';
                         $recommended['php_memory_limit']['actual']     = '('. $memory_limit .')';
                         $recommended['php_memory_limit']['status']     = ($memory_limit >= $recommended_memory_limit ) ? SETTING_TRUE : SETTING_FALSE;

                         # Checking file uploads
                         $recommended['file_uploads']['label']      = $language['FILE_UPLOADS'];
                         $recommended['file_uploads']['expected']   = SETTING_EXPECTED_ON;
                         $recommended['file_uploads']['actual']     = get_php_setting('file_uploads',true, 'string');
                         $recommended['file_uploads']['status']     = get_php_setting('file_uploads',true, 'img');

                         # Checking max_upload_filesize
                         $max_upload_filesize = ini_get('upload_max_filesize');
                         $recommended['max_upload_filesize']['label']      = $language['MAX_UPLOAD_FILESIZE'];
                         $recommended['max_upload_filesize']['expected']   = 'min 2MB';
                         $recommended['max_upload_filesize']['actual']     = '('. $max_upload_filesize .')';
                         $recommended['max_upload_filesize']['status']     = ($max_upload_filesize >= 2 ) ? SETTING_TRUE : SETTING_FALSE;

                         # Checking post_max_size
                         # @todo post_max_size > max_upload_filesize
                         $post_max_size = ini_get('post_max_size');
                         $recommended['post_max_size']['label']      = $language['POST_MAX_SIZE'];
                         $recommended['post_max_size']['expected']   = 'min 2MB';
                         $recommended['post_max_size']['actual']     = '('. $post_max_size .')';
                         $recommended['post_max_size']['status']     = ($post_max_size >= 2 ) ? SETTING_TRUE : SETTING_FALSE;
                        
                         # Checking for allow_url_fopen
                         $recommended['allow_url_fopen']['label']       = $language['ALLOW_URL_FOPEN'];
                         $recommended['allow_url_fopen']['expected']    = SETTING_EXPECTED_ON;
                         $recommended['allow_url_fopen']['actual']      = get_php_setting('allow_url_fopen',true,'string');
                         $recommended['allow_url_fopen']['status']      = get_php_setting('allow_url_fopen',true,'img');

                         # Checking for allow_url_include
                         $recommended['allow_url_include']['label']       = $language['ALLOW_URL_INCLUDE'];
                         $recommended['allow_url_include']['expected']    = SETTING_EXPECTED_ON;
                         $recommended['allow_url_include']['actual']      = get_php_setting('allow_url_include',true,'string');
                         $recommended['allow_url_include']['status']      = get_php_setting('allow_url_include',true,'img');

                         # Checking for Safe mode
                         $recommended['safe_mode']['label']         = $language['SAFE_MODE'];
                         $recommended['safe_mode']['expected']      = SETTING_EXPECTED_OFF;
                         $recommended['safe_mode']['actual']        = get_php_setting('safe_mode',false,'string');
                         $recommended['safe_mode']['status']        = get_php_setting('safe_mode',false,'img');

                         # Checking OpenBaseDir
                         $recommended['open_basedir']['label']      = $language['OPEN_BASEDIR'];
                         $recommended['open_basedir']['expected']   = SETTING_EXPECTED_OFF;
                         $recommended['open_basedir']['actual']     = get_php_setting('open_basedir',false,'string');
                         $recommended['open_basedir']['status']     = get_php_setting('open_basedir',false,'img');

                         # Checking magic_quotes_gpc
                         $recommended['magic_quotes_gpc']['label']      = $language['MAGIC_QUOTES_GPC'];
                         $recommended['magic_quotes_gpc']['expected']   = SETTING_EXPECTED_OFF;
                         $recommended['magic_quotes_gpc']['actual']     = get_php_setting('magic_quotes_gpc',false,'string');
                         $recommended['magic_quotes_gpc']['status']     = get_php_setting('magic_quotes_gpc',false,'img');

                         # Checking magic_quotes_runtime
                         $recommended['magic_quotes_runtime']['label']      = $language['MAGIC_QUOTES_RUNTIME'];
                         $recommended['magic_quotes_runtime']['expected']   = SETTING_EXPECTED_OFF;
                         $recommended['magic_quotes_runtime']['actual']     = get_php_setting('magic_quotes_runtime',false,'string');
                         $recommended['magic_quotes_runtime']['status']     = get_php_setting('magic_quotes_runtime',false,'img');

                         # Checking short open tag
                         $recommended['short_open_tag']['label']      = $language['SHORT_OPEN_TAG'];
                         $recommended['short_open_tag']['expected']   = SETTING_EXPECTED_OFF;
                         $recommended['short_open_tag']['actual']     = get_php_setting('short_open_tag',false,'string');
                         $recommended['short_open_tag']['status']     = get_php_setting('short_open_tag',false,'img');

                         # Checking output_buffering
                         $recommended['output_buffering']['label']      = $language['OUTPUT_BUFFERING'];
                         $recommended['output_buffering']['expected']   = SETTING_EXPECTED_OFF;
                         $recommended['output_buffering']['actual']     = get_php_setting('output_buffering',false,'string');
                         $recommended['output_buffering']['status']     = get_php_setting('output_buffering',false,'img');

                         # Checking presence of XSLTProcessor
                         $recommended['xsltprocessor']['label']      = $language['XSLT_PROCESSOR'];
                         $recommended['xsltprocessor']['expected']   = SETTING_EXPECTED_ON;
                         $recommended['xsltprocessor']['actual']     = class_exists('XSLTProcessor') ? SETTING_EXPECTED_ON : SETTING_EXPECTED_OFF;
                         $recommended['xsltprocessor']['status']     = class_exists('XSLTProcessor') ? SETTING_TRUE : SETTING_FALSE;

                         # Checking for PHP Extension : HASH (used in Clansuite_Security)
                         $recommended['extension_hash']['label']      = $language['EXTENSION_HASH'];
                         $recommended['extension_hash']['expected']   = SETTING_EXPECTED_ON;
                         $recommended['extension_hash']['actual']     = extension_loaded('hash') ? SETTING_EXPECTED_ON : SETTING_EXPECTED_OFF;
                         $recommended['extension_hash']['status']     = extension_loaded('hash') ? SETTING_TRUE : SETTING_FALSE;

                         # Checking for PHP Extension : gettext (used in Clansuite_Localization)
                         $recommended['extension_gettext']['label']    = $language['EXTENSION_GETTEXT'];
                         $recommended['extension_gettext']['expected'] = SETTING_EXPECTED_ON;
                         $recommended['extension_gettext']['actual']   = extension_loaded('gettext') ? SETTING_EXPECTED_ON : SETTING_EXPECTED_OFF;
                         $recommended['extension_gettext']['status']   = extension_loaded('gettext') ? SETTING_TRUE : SETTING_FALSE;

                         # Checking for PHP Extension : tokenizer
                         $recommended['extension_tokenizer']['label']      = $language['EXTENSION_TOKENIZER'];
                         $recommended['extension_tokenizer']['expected']  = SETTING_EXPECTED_ON;
                         $recommended['extension_tokenizer']['actual']    = function_exists('token_get_all') ? SETTING_EXPECTED_ON : SETTING_EXPECTED_OFF;
                         $recommended['extension_tokenizer']['status']    = function_exists('token_get_all') ? SETTING_TRUE : SETTING_FALSE;

                         #  Checking for PHP Extension : GD (used systemwide, e.g. on captcha)
                         $recommended['extension_gd']['label']      = $language['EXTENSION_GD'];
                         $recommended['extension_gd']['expected']   = SETTING_EXPECTED_ON;
                         $recommended['extension_gd']['actual']     = extension_loaded('gd') ? SETTING_EXPECTED_ON : SETTING_EXPECTED_OFF;
                         $recommended['extension_gd']['status']     = extension_loaded('gd') ? SETTING_TRUE : SETTING_FALSE;

                         #  Checking for PHP Extension : XML
                         $recommended['extension_xml']['label']      = $language['EXTENSION_XML'];
                         $recommended['extension_xml']['expected']   = SETTING_EXPECTED_ON;
                         $recommended['extension_xml']['actual']     = extension_loaded('xml') ? SETTING_EXPECTED_ON : SETTING_EXPECTED_OFF;
                         $recommended['extension_xml']['status']     = extension_loaded('xml') ? SETTING_TRUE : SETTING_FALSE;

                         #  Checking for PHP Extension : PCRE
                         $recommended['extension_pcre']['label']      = $language['EXTENSION_PCRE'];
                         $recommended['extension_pcre']['expected']   = SETTING_EXPECTED_ON;
                         $recommended['extension_pcre']['actual']     = extension_loaded('pcre') ? SETTING_EXPECTED_ON : SETTING_EXPECTED_OFF;
                         $recommended['extension_pcre']['status']     = extension_loaded('pcre') ? SETTING_TRUE : SETTING_FALSE;

                         #  Checking for PHP Extension : SimpleXML (used systemwide for xml parsing)
                         $recommended['extension_simplexml']['label']      = $language['EXTENSION_SIMPLEXML'];
                         $recommended['extension_simplexml']['expected']   = SETTING_EXPECTED_ON;
                         $recommended['extension_simplexml']['actual']     = extension_loaded('SimpleXML') ? SETTING_EXPECTED_ON : SETTING_EXPECTED_OFF;
                         $recommended['extension_simplexml']['status']     = extension_loaded('SimpleXML') ? SETTING_TRUE : SETTING_FALSE;

                         #  Checking for PHP Extension : Suhosin
                         $recommended['extension_suhosin']['label']      = $language['EXTENSION_SUHOSIN'];
                         $recommended['extension_suhosin']['expected']   = SETTING_EXPECTED_ON;
                         $recommended['extension_suhosin']['actual']     = extension_loaded('suhosin') ? SETTING_EXPECTED_ON : SETTING_EXPECTED_OFF;
                         $recommended['extension_suhosin']['status']     = extension_loaded('suhosin') ? SETTING_TRUE : SETTING_FALSE;

                         #  Checking for PHP Extension : Skein Hash (used in Clansuite_Security)
                         $recommended['extension_skein']['label']      = $language['EXTENSION_SKEIN'];
                         $recommended['extension_skein']['expected']   = SETTING_EXPECTED_ON;
                         $recommended['extension_skein']['actual']     = extension_loaded('skein') ? SETTING_EXPECTED_ON : SETTING_EXPECTED_OFF;
                         $recommended['extension_skein']['status']     = extension_loaded('skein') ? SETTING_TRUE : SETTING_FALSE;

                         #  Checking for PHP Extension : GeoIP
                         $recommended['extension_geoip']['label']      = $language['EXTENSION_GEOIP'];
                         $recommended['extension_geoip']['expected']   = SETTING_EXPECTED_ON;
                         $recommended['extension_geoip']['actual']     = extension_loaded('geoip') ? SETTING_EXPECTED_ON : SETTING_EXPECTED_OFF;
                         $recommended['extension_geoip']['status']     = extension_loaded('geoip') ? SETTING_TRUE : SETTING_FALSE;

                         #  Checking for PHP Extension : CURL
                         $recommended['extension_curl']['label']      = $language['EXTENSION_CURL'];
                         $recommended['extension_curl']['expected']   = SETTING_EXPECTED_ON;
                         $recommended['extension_curl']['actual']     = extension_loaded('curl') ? SETTING_EXPECTED_ON : SETTING_EXPECTED_OFF;
                         $recommended['extension_curl']['status']     = extension_loaded('curl') ? SETTING_TRUE : SETTING_FALSE;

                         #  Checking for PHP Extension : SYCK (is a YAML-Parser used in Clansuite_YAML_Config)
                         $recommended['extension_syck']['label']      = $language['EXTENSION_SYCK'];
                         $recommended['extension_syck']['expected']   = SETTING_EXPECTED_ON;
                         $recommended['extension_syck']['actual']     = extension_loaded('syck') ? SETTING_EXPECTED_ON : SETTING_EXPECTED_OFF;
                         $recommended['extension_syck']['status']     = extension_loaded('syck') ? SETTING_TRUE : SETTING_FALSE;

                         #  Checking for PHP Extension : APC (used in Clansuite_APC_Cache)
                         $recommended['extension_apc']['label']      = $language['EXTENSION_APC'];
                         $recommended['extension_apc']['expected']   = SETTING_EXPECTED_ON;
                         $recommended['extension_apc']['actual']     = extension_loaded('apc') ? SETTING_EXPECTED_ON : SETTING_EXPECTED_OFF;
                         $recommended['extension_apc']['status']     = extension_loaded('apc') ? SETTING_TRUE : SETTING_FALSE;

                         #  Checking for PHP Extension : MEMCACHED? or memcache? (used in Clansuite_Memcache_Cache)
                         $recommended['extension_memcache']['label']      = $language['EXTENSION_MEMCACHE'];
                         $recommended['extension_memcache']['expected']   = SETTING_EXPECTED_ON;
                         $recommended['extension_memcache']['actual']     = extension_loaded('memcache') ? SETTING_EXPECTED_ON : SETTING_EXPECTED_OFF;
                         $recommended['extension_memcache']['status']     = extension_loaded('memcache') ? SETTING_TRUE : SETTING_FALSE;

                         #  Checking for PHP Extension : MCrypt (used in Clansuite_Security)
                         $recommended['extension_mcrypt']['label']      = $language['EXTENSION_MCRYPT'];
                         $recommended['extension_mcrypt']['expected']   = SETTING_EXPECTED_ON;
                         $recommended['extension_mcrypt']['actual']     = extension_loaded('mcrypt') ? SETTING_EXPECTED_ON : SETTING_EXPECTED_OFF;
                         $recommended['extension_mcrypt']['status']     = extension_loaded('mcrypt') ? SETTING_TRUE : SETTING_FALSE;

                         #  Checking for PHP Calendar : Calendar
                         $recommended['extension_calendar']['label']      = $language['EXTENSION_CALENDAR'];
                         $recommended['extension_calendar']['expected']   = SETTING_EXPECTED_ON;
                         $recommended['extension_calendar']['actual']     = extension_loaded('calendar') ? SETTING_EXPECTED_ON : SETTING_EXPECTED_OFF;
                         $recommended['extension_calendar']['status']     = extension_loaded('calendar') ? SETTING_TRUE : SETTING_FALSE;

                         ?>
                <table class="settings" border="0">
                    <thead class="tbhead">
                        <tr>
                            <td class="tdcaption" colspan="4"><?php echo $language['STEP2_SYSTEMSETTING_REQUIRED']; ?></td>
                        </tr>
                        <tr>
                            <th><?php echo $language['STEP2_SETTING']; ?></th>
                            <th><?php echo $language['STEP2_SETTING_EXPECTED']; ?></th>
                            <th><?php echo $language['STEP2_SETTING_ACTUAL']; ?></th>
                            <th><?php echo $language['STEP2_SETTING_STATUS']; ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php setting_rows($required); ?>
                    </tbody>
                </table>
                <br />
                <table class="settings" border="0">
                    <thead class="tbhead">
                        <tr>
                            <td class="tdcaption" colspan="4"><?php echo $language['STEP2_SYSTEMSETTING_RECOMMENDED']; ?></td>
                        </tr>
                        <tr>
                            <th><?php echo $language['STEP2_SETTING']; ?></th>
                            <th><?php echo $language['STEP2_SETTING_EXPECTED']; ?></th>
                            <th><?php echo $language['STEP2_SETTING_ACTUAL']; ?></th>
                            <th><?php echo $language['STEP2_SETTING_STATUS']; ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php setting_rows($recommended); ?>
                    </tbody>
                </table>
                <!--
                <br />
                <div style="text-align:center;">
                <script type="text/javascript"> function reload() { window.location.reload(true); }</script>
                <input class="button" type="button" name="Re-check" value="Re-check" onClick="reload();" tabindex="2">
                </div>
                -->
                <div id="content_footer">
                    <div class="navigation">
                        <span style="font-size:10px;">
                            <?php echo $language['CLICK_NEXT_TO_PROCEED']; ?><br />
                            <?php echo $language['CLICK_BACK_TO_RETURN']; ?>
                        </span>
                        <form action="index.php" method="post">
                            <div class="alignright">
                                <?php
                                foreach($required as $required_item)
                                {
                                    if($required_item['status'] === SETTING_TRUE)
                                    {
                                        $button_inactive = true;   
                                    }
                                    break;
                                }
                                
                                if($button_inactive === true)
                                {
                                ?> 
                                    <input value="<?php echo $language['NEXTSTEP']; ?>" deactivated="deactivated" class="ButtonGrey" name="step_backward" tabindex="3" />    
                                <?php
                                }
                                else
                                {
                                ?>                                 
                                    <input type="submit" value="<?php echo $language['NEXTSTEP']; ?>" class="ButtonGreen" name="step_forward" tabindex="1" />
                                <?php
                                }
                                ?>                                
                            </div>
                            <div class="alignleft">
                                <input type="submit" value="<?php echo $language['BACKSTEP']; ?>" class="ButtonRed" name="step_backward" tabindex="3" />
                                <input type="hidden" name="lang" value="<?php echo $_SESSION['lang']; ?>" />
                            </div>
                        </form>
                    </div><!-- div navigation end -->
                </div> <!-- div content_footer end -->

            </div> <!-- div accordion end -->
        </div> <!-- div content_middle end -->
    </div> <!-- div content end -->