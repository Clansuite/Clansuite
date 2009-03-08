<?php
/**
 * Security Handler
 */
if (!defined('IN_CS')){ die( 'Clansuite not loaded. Direct Access forbidden.' );}
?>
    <div id="content" class="narrowcolumn">
        <div id="content_middle">
            <div class="accordion">
                <h2 class="headerstyle">
                    <img src="images/64px-Utilities-system-monitor.svg.png" border="0" style="vertical-align:middle" alt="installstep image" />
                    <?=$language['STEP2_SYSTEMCHECK']?>
                </h2>
                <p><?=$language['STEP2_IN_GENERAL']?></p>
                <p><?=$language['STEP2_SYSTEMSETTINGS_REQUIRED']?></p>
                <p><?=$language['STEP2_SYSTEMSETTINGS_RECOMMENDED']?></p>
                <p><?=$language['STEP2_SYSTEMSETTINGS_TAKEACTION']?></p>
                <p><?=$language['STEP2_SYSTEMSETTINGS_CHECK_VALUES']?></p>

                         <?php
                         /**
                          * Print alternating Table-Rows
                          * Settings array = $array['settingname']['status']
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

                                #$table_rows .= '<td>'. $settingname .'=>'. $value['text'] .'</td>';
                                $table_rows .= '<td>'. $value['text'] .'</td>';
                                $table_rows .= '<td class="col1" align="center">' . $value['expected'] . '</td>';
                                $table_rows .= '<td class="col2" align="center">' . $value['actual'] .'</td>';
                                $table_rows .= '<td class="col1" align="center">' . $value['status'] .'</td>';

                                // ending tablerow
                                $table_rows .= '</tr>';

                                echo $table_rows;
                            }
                         }

                         # Case-Images, to determine if a certain Setting is OK or NOT
                         define('SETTING_TRUE', '<img src="images/true.gif" alt="OK" height="16" width="16" border="0" />');
                         define('SETTING_FALSE', '<img src="images/false.gif" alt="NOT" height="16" width="16" border="0" />');

                         /**
                          * get_php_setting
                          * wrapper for ini_get
                          *
                          * returns bool if param $get_value = true
                          * else the image defined by SETTING_TRUE/False
                          */
                         function get_php_setting($php_functionname,$expected_value, $return_way = 'img')
                         {
                            // get value of setting as 1 or 0
                            $value = ini_get($php_functionname);
                            if ($value != 1)
                            {
                                $value = 0;
                            }
                            else
                            {
                                $value = 1;
                            }
                            //echo $php_functionname .' - '.$value .' - ist:'. ini_get($php_functionname) .'- soll: '. $expected_value .'<br />';
                            switch($return_way)
                            {
                                case 'int':
                                                return $value;
                                case 'string':
                                                return $value ? 'on' : 'off';
                                default:
                                case 'img' :
                                                if ($expected_value == true)
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
                             $tempdir = ini_get("session.save_path");
                             # filehandle for temp file
                             $temp_file_name = tempnam($tempdir, "FOO FIGHTERS");

                             if (!empty($temp_file_name))
                             {
                                $handle = fopen($temp_file_name, "w");
                                fwrite($handle, "writing to tempfile");
                                fclose($handle);
                                unlink($temp_file_name);
                                return true;
                             }
                             else
                             {
                                return $temp_filename;
                             }
                         }

                         # REQUIRED CHECKS

                         # Setting: PHP-Version
                         $php_version    = phpversion();
                         $compare_result = version_compare($php_version,'5.2.0','>=');
                         $required['php_version']['text']       = $language['PHP_VERSION'];
                         $required['php_version']['expected']   = '>= 5.2.0';
                         $required['php_version']['actual']     = $php_version;
                         $required['php_version']['status']     = empty($compare_result) ? SETTING_FALSE : SETTING_TRUE;

                         # Setting: Session Functions
                         $required['session_functions']['text']     = $language['SESSION_FUNCTIONS'];
                         $required['session_functions']['expected'] = 'on';
                         $required['session_functions']['actual']   = function_exists('session_start') ? 'on' : 'off';
                         $required['session_functions']['status']   = function_exists('session_start') ? SETTING_TRUE : SETTING_FALSE;

                         # Setting: PDO
                         $required['pdo_library']['text']     = $language['PDO_LIBRARY'];
                         $required['pdo_library']['expected'] = 'on';
                         $required['pdo_library']['actual']   = class_exists('pdo') ? 'on' : 'off';
                         $required['pdo_library']['status']   = class_exists('pdo') ? SETTING_TRUE : SETTING_FALSE;

                         # Setting: PDO MySQL
                         $required['pdo_mysql_library']['text']     = $language['PDO_MYSQL_LIBRARY'];
                         $required['pdo_mysql_library']['expected'] = 'on';
                         $required['pdo_mysql_library']['actual']   = in_array('mysql', PDO::getAvailableDrivers() ) ? 'on' : 'off';
                         $required['pdo_mysql_library']['status']   = in_array('mysql', PDO::getAvailableDrivers() ) ? SETTING_TRUE : SETTING_FALSE;

              # NOT USED # Checking if session.save_path is writable
              # NOT USED # Setting: Database

                         # Permissions Check: write on systems temporary directory
                         $required['is_writable_temp_dir']['text']     = $language['IS_WRITEABLE_TEMP_DIR'];
                         $required['is_writable_temp_dir']['expected'] = 'w';
                         $required['is_writable_temp_dir']['actual']   = check_temporary_dir() ? 'w' : '---';
                         $required['is_writable_temp_dir']['status']   = check_temporary_dir() ? SETTING_TRUE : SETTING_FALSE;

                         # Permissions Check: write on \clansuite root
                         $required['is_writable_clansuite_root']['text']     = $language['IS_WRITEABLE_CLANSUITE_ROOT'];
                         $required['is_writable_clansuite_root']['expected'] = 'w';
                         $required['is_writable_clansuite_root']['actual']   = is_writeable(ROOT) ? 'w' : '---';
                         $required['is_writable_clansuite_root']['status']   = is_writeable(ROOT) ? SETTING_TRUE : SETTING_FALSE;

                         # Permissions Check: write on \smarty\templates_c
                         $required['is_writable_smarty_templates_c']['text']     = $language['IS_WRITEABLE_SMARTY_TEMPLATES_C'];
                         $required['is_writable_smarty_templates_c']['expected'] = 'w';
                         $required['is_writable_smarty_templates_c']['actual']   = is_writeable(ROOT . '/libraries/smarty/templates_c') ? 'w' : '---';
                         $required['is_writable_smarty_templates_c']['status']   = is_writeable(ROOT . '/libraries/smarty/templates_c') ? SETTING_TRUE : SETTING_FALSE;

                         # Permissions Check: write on \smarty\cache
                         $required['is_writable_smarty_cache']['text']     = $language['IS_WRITEABLE_SMARYT_CACHE'];
                         $required['is_writable_smarty_cache']['expected'] = 'w';
                         $required['is_writable_smarty_cache']['actual']   = is_writeable(ROOT . '/libraries/smarty/cache') ? 'w' : '---';
                         $required['is_writable_smarty_cache']['status']   = is_writeable(ROOT . '/libraries/smarty/cache') ? SETTING_TRUE : SETTING_FALSE;

                         # Permissions Check: write on uploads folder
                         $required['is_writable_uploads']['text']     = $language['IS_WRITEABLE_UPLOADS'];
                         $required['is_writable_uploads']['expected'] = 'w';
                         $required['is_writable_uploads']['actual']   = is_writeable(ROOT . '/uploads') ? 'w' : '---';
                         $required['is_writable_uploads']['status']   = is_writeable(ROOT . '/uploads') ? SETTING_TRUE : SETTING_FALSE;

                         # Permissions Check: read on Configuration Template File
                         $required['is_readable_config_template']['text']     = $language['IS_READABLE_CONFIG_TEMPLATE'];
                         $required['is_readable_config_template']['expected'] = 'r';
                         $required['is_readable_config_template']['actual']   = is_readable(INSTALLATION_ROOT . 'clansuite.config.installer') ? 'r' : '---';
                         $required['is_readable_config_template']['status']   = is_readable(INSTALLATION_ROOT . 'clansuite.config.installer') ? SETTING_TRUE : SETTING_FALSE;

                         # RECOMMENDED CHECKS

                         # Setting: PHP memory limit
                         $memory_limit = ini_get('memory_limit');
                         $recommended['php_memory_limit']['text']       = $language['PHP_MEMORY_LIMIT'];
                         $recommended['php_memory_limit']['expected']   = 'min 16MB';
                         $recommended['php_memory_limit']['actual']     = '('. $memory_limit .')';
                         $recommended['php_memory_limit']['status']     = ($memory_limit >= 16 ) ? SETTING_TRUE : SETTING_FALSE;

                         # Checking file uploads
                         $recommended['file_uploads']['text']       = $language['FILE_UPLOADS'];
                         $recommended['file_uploads']['expected']   = 'on';
                         $recommended['file_uploads']['actual']     = get_php_setting('file_uploads',true, 'string');
                         $recommended['file_uploads']['status']     = get_php_setting('file_uploads',true, 'img');


                         #Checking max upload file size (min 2M, recommend 10M)

                         # Checking for basic XML (expat) support

                         # Checking RegisterGlobals
                         $recommended['register_globals']['text']       = $language['REGISTER_GLOBALS'];
                         $recommended['register_globals']['expected']   = 'off';
                         $recommended['register_globals']['actual']     = ini_get('register_globals') ? 'on' : 'off';
                         $recommended['register_globals']['status']     = ini_get('register_globals') ? SETTING_FALSE: SETTING_TRUE;

                         # Checking for allow_url_fopen
                         $recommended['allow_url_fopen']['text']        = $language['ALLOW_URL_FOPEN'];
                         $recommended['allow_url_fopen']['expected']    = 'on';
                         $recommended['allow_url_fopen']['actual']      = get_php_setting('allow_url_fopen',true,'string');
                         $recommended['allow_url_fopen']['status']      = get_php_setting('allow_url_fopen',true,'img');

                         # Checking for Safe mode
                         $recommended['safe_mode']['text']          = $language['SAFE_MODE'];
                         $recommended['safe_mode']['expected']      = 'off';
                         $recommended['safe_mode']['actual']        = get_php_setting('safe_mode',false,'string');
                         $recommended['safe_mode']['status']        = get_php_setting('safe_mode',false,'img');

                         # Checking OpenBaseDir
                         $recommended['open_basedir']['text']       = $language['OPEN_BASEDIR'];
                         $recommended['open_basedir']['expected']   = 'off';
                         $recommended['open_basedir']['actual']     = get_php_setting('open_basedir',false,'string');
                         $recommended['open_basedir']['status']     = get_php_setting('open_basedir',false,'img');

                         # Checking magic_quotes_gpc
                         $recommended['magic_quotes_gpc']['text']       = $language['MAGIC_QUOTES_GPC'];
                         $recommended['magic_quotes_gpc']['expected']   = 'off';
                         $recommended['magic_quotes_gpc']['actual']     = get_php_setting('magic_quotes_gpc',false,'string');
                         $recommended['magic_quotes_gpc']['status']     = get_php_setting('magic_quotes_gpc',false,'img');

                         # Checking magic_quotes_runtime
                         $recommended['magic_quotes_runtime']['text']       = $language['MAGIC_QUOTES_RUNTIME'];
                         $recommended['magic_quotes_runtime']['expected']   = 'off';
                         $recommended['magic_quotes_runtime']['actual']     = get_php_setting('magic_quotes_runtime',false,'string');
                         $recommended['magic_quotes_runtime']['status']     = get_php_setting('magic_quotes_runtime',false,'img');

                         # Checking output_buffering
                         $recommended['output_buffering']['text']       = $language['OUTPUT_BUFFERING'];
                         $recommended['output_buffering']['expected']   = 'off';
                         $recommended['output_buffering']['actual']     = get_php_setting('output_buffering',false,'string');
                         $recommended['output_buffering']['status']     = get_php_setting('output_buffering',false,'img');

                         # Checking for PHP Extension : HASH
                         $recommended['extension_hash']['text']       = $language['EXTENSION_HASH'];
                         $recommended['extension_hash']['expected']   = 'on';
                         $recommended['extension_hash']['actual']     = extension_loaded('hash') ? 'on' : 'off';
                         $recommended['extension_hash']['status']     = extension_loaded('hash') ? SETTING_TRUE : SETTING_FALSE;

                         # Checking for PHP Extension : gettext
                         $recommended['extension_gettext']['text']     = $language['EXTENSION_GETTEXT'];
                         $recommended['extension_gettext']['expected'] = 'on';
                         $recommended['extension_gettext']['actual']   = extension_loaded('gettext') ? 'on' : 'off';
                         $recommended['extension_gettext']['status']   = extension_loaded('gettext') ? SETTING_TRUE : SETTING_FALSE;

                         # Checking for PHP Extension : tokenizer
                         $recommended['extension_tokenizer']['text']      = $language['EXTENSION_TOKENIZER'];
                         $recommended['extension_tokenizer']['expected']  = 'on';
                         $recommended['extension_tokenizer']['actual']    = function_exists('token_get_all') ? 'on' : 'off';
                         $recommended['extension_tokenizer']['status']    = function_exists('token_get_all') ? SETTING_TRUE : SETTING_FALSE;

                         #  Checking for PHP Extension : GD
                         $recommended['extension_gd']['text']       = $language['EXTENSION_GD'];
                         $recommended['extension_gd']['expected']   = 'on';
                         $recommended['extension_gd']['actual']     = extension_loaded('gd') ? 'on' : 'off';
                         $recommended['extension_gd']['status']     = extension_loaded('gd') ? SETTING_TRUE : SETTING_FALSE;

                         #  Checking for PHP Extension : XML
                         $recommended['extension_xml']['text']       = $language['EXTENSION_XML'];
                         $recommended['extension_xml']['expected']   = 'on';
                         $recommended['extension_xml']['actual']     = extension_loaded('xml') ? 'on' : 'off';
                         $recommended['extension_xml']['status']     = extension_loaded('xml') ? SETTING_TRUE : SETTING_FALSE;

                         #  Checking for PHP Extension : SimpleXML
                         $recommended['extension_simplexml']['text']       = $language['EXTENSION_SIMPLEXML'];
                         $recommended['extension_simplexml']['expected']   = 'on';
                         $recommended['extension_simplexml']['actual']     = extension_loaded('SimpleXML') ? 'on' : 'off';
                         $recommended['extension_simplexml']['status']     = extension_loaded('SimpleXML') ? SETTING_TRUE : SETTING_FALSE;

                         #  Checking for PHP Extension : Suhosin
                         $recommended['extension_suhosin']['text']       = $language['EXTENSION_SUHOSIN'];
                         $recommended['extension_suhosin']['expected']   = 'on';
                         $recommended['extension_suhosin']['actual']     = extension_loaded('suhosin') ? 'on' : 'off';
                         $recommended['extension_suhosin']['status']     = extension_loaded('suhosin') ? SETTING_TRUE : SETTING_FALSE;

                         #  Checking for PHP Extension : Skein Hash
                         $recommended['extension_skein']['text']       = $language['EXTENSION_SKEIN'];
                         $recommended['extension_skein']['expected']   = 'on';
                         $recommended['extension_skein']['actual']     = extension_loaded('skein') ? 'on' : 'off';
                         $recommended['extension_skein']['status']     = extension_loaded('skein') ? SETTING_TRUE : SETTING_FALSE;
                         
                         #  Checking for PHP Extension : GeoIP
                         $recommended['extension_geoip']['text']       = $language['EXTENSION_GEOIP'];
                         $recommended['extension_geoip']['expected']   = 'on';
                         $recommended['extension_geoip']['actual']     = extension_loaded('geoip') ? 'on' : 'off';
                         $recommended['extension_geoip']['status']     = extension_loaded('geoip') ? SETTING_TRUE : SETTING_FALSE;
                                                 
                         #  Checking for PHP Extension : CURL
                         $recommended['extension_curl']['text']       = $language['EXTENSION_CURL'];
                         $recommended['extension_curl']['expected']   = 'on';
                         $recommended['extension_curl']['actual']     = extension_loaded('curl') ? 'on' : 'off';
                         $recommended['extension_curl']['status']     = extension_loaded('curl') ? SETTING_TRUE : SETTING_FALSE;                                               
                                               
                         ?>
                <table class="settings" border="0">
                    <thead class="tbhead">
                        <tr>
                            <td class="tdcaption" colspan="4"><?=$language['STEP2_SYSTEMSETTING_REQUIRED']?></td>
                        </tr>
                        <tr>
                            <th><?=$language['STEP2_SETTING']?></th>
                            <th><?=$language['STEP2_SETTING_EXPECTED']?></th>
                            <th><?=$language['STEP2_SETTING_ACTUAL']?></th>
                            <th><?=$language['STEP2_SETTING_STATUS']?></th>
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
                            <td class="tdcaption" colspan="4"><?=$language['STEP2_SYSTEMSETTING_RECOMMENDED']?></td>
                        </tr>
                        <tr>
                            <th><?=$language['STEP2_SETTING']?></th>
                            <th><?=$language['STEP2_SETTING_EXPECTED']?></th>
                            <th><?=$language['STEP2_SETTING_ACTUAL']?></th>
                            <th><?=$language['STEP2_SETTING_STATUS']?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php setting_rows($recommended); ?>
                    </tbody>
                </table>
                <!--
                <br />
                <div style="text-align:center;">
                <script language="javascript"> function reload() { window.location.reload(true); }</script>
		        <input class="button" type="button" name="Re-check" value="Re-check" onClick="reload();" tabindex="2">
                </div>
                -->
                <div id="content_footer">
                    <div class="navigation">
                        <span style="font-size:10px;">
                            <?=$language['CLICK_NEXT_TO_PROCEED']?><br />
                            <?=$language['CLICK_BACK_TO_RETURN']?>
                        </span>
                        <form action="index.php" method="post">
                            <div class="alignright">
                                <input type="submit" value="<?=$language['NEXTSTEP']?>" class="ButtonGreen" name="step_forward" tabindex="1" />
                            </div>
                            <div class="alignleft">
                                <input type="submit" value="<?=$language['BACKSTEP']?>" class="ButtonRed" name="step_backward" tabindex="3" />
                                <input type="hidden" name="lang" value="<?=$_SESSION['lang']?>" />
                            </div>
                        </form>
                    </div><!-- div navigation end -->
                </div> <!-- div content_footer end -->

            </div> <!-- div accordion end -->
        </div> <!-- div content_middle end -->
    </div> <!-- div content end -->