    <div id="content" class="narrowcolumn">
        <div id="content_middle">
            <div class="accordion">
                <h2 class="headerstyle">
                    <img src="assets/images/64px-Utilities-system-monitor.svg.png" style="vertical-align:middle" alt="installstep image" />
                    <?php echo $language['STEP2_SYSTEMCHECK']; ?>
                </h2>
                <p><?php echo $language['STEP2_IN_GENERAL']; ?></p>
                <p><?php echo $language['STEP2_SYSTEMSETTINGS_REQUIRED']; ?></p>
                <p><?php echo $language['STEP2_SYSTEMSETTINGS_RECOMMENDED']; ?></p>
                <p><?php echo $language['STEP2_SYSTEMSETTINGS_TAKEACTION']; ?></p>
                <p><?php if (get_cfg_var('cfg_file_path')):
                         echo $language['STEP2_SYSTEMSETTINGS_PHPINI']; ?>
                         <br />"<strong><?php echo get_cfg_var('cfg_file_path') ?></strong>".</p>
                   <?php endif; ?>
                <p><?php echo $language['STEP2_SYSTEMSETTINGS_CHECK_VALUES']; ?></p>
                <?php
                    // Case-Images, to determine if a certain Setting is OK or NOT
                    define('SETTING_TRUE',  '<img src="assets/images/true.gif" alt="OK" height="16" width="16" />');
                    define('SETTING_FALSE', '<img src="assets/images/false.gif" alt="NOT" height="16" width="16" />');

                    // determine Strings for ON, OFF, R, W
                    define('SETTING_EXPECTED_ON', $language['STEP2_SETTING_EXPECTED_ON']);
                    define('SETTING_EXPECTED_OFF', $language['STEP2_SETTING_EXPECTED_OFF']);

                    /**
                     * Renders alternating table rows.
                     *
                     * @param array settings
                     */
                    function renderSettingRows(array $settings)
                    {
                       // introduce vars
                       $table_rows = null;
                       $csstoggle = null;

                       // css names
                       $css1 = 'row1';
                       $css2 = 'row2';

                        foreach ($settings as $setting => $value) {
                           // css name toggle
                           $csstoggle = ($csstoggle == $css1) ? $css2 : $css1;

                           $table_rows = '<tr class="' . $csstoggle . '">';
                           $table_rows .= '<td data-name="' . $setting . '">' . $value['label'] . '</td>';
                           $table_rows .= '<td class="col1">' . $value['expected'] . '</td>';
                           $table_rows .= '<td class="col2">' . $value['actual'] . '</td>';
                           $table_rows .= '<td class="col1">' . $value['status'] . '</td>';
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
                     * Gets the value of a php.ini configuration option with various return options.
                     *
                     * @param string php.ini configuration option or function name
                     * @return depends on the specified $return_type (int, string, img)
                     */
                    function getPHPSetting($phpvar, $expected_value, $return_type = 'img')
                    {
                       // get boolean value of setting as 1 or 0
                       $value = (int) iniFlag($phpvar);

                       #echo $phpvar .' - '.$value .' - ist:'. ini_get($phpvar) .'- soll: '. $expected_value .'<br />';

                       if ($return_type === 'int') {
                           return $value;
                       }

                       if ($return_type === 'string') {
                           return $value ? SETTING_EXPECTED_ON : SETTING_EXPECTED_OFF;
                       }

                       if ($return_type === 'img') {
                           if ($expected_value === true) {
                               return $value ? SETTING_TRUE : SETTING_FALSE;
                           } else {
                               return $value ? SETTING_FALSE : SETTING_TRUE;
                           }
                       }
                    }

                    /**
                     * Checks the useability of the temporary directory
                     */
                    function checkTemporaryDir()
                    {
                        // filehandle for temp file
                        $temp_file_name = tempnam(sys_get_temp_dir(), "FOO FIGHTERS");

                        if (empty($temp_file_name) === false) {
                           file_put_contents($temp_file_name, 'Writing FOO to tempfile.');
                           unlink($temp_file_name);

                           return true;
                        } else {
                           return $temp_file_name;
                        }
                    }

                    /**
                     * REQUIREMENT CHECKS
                     */

                    // Setting: PHP-Version
                    $required_php_version = '5.3';
                    $compare_result = version_compare(PHP_VERSION, $required_php_version,'>=');
                    $required['php_version']['label']      = $language['PHP_VERSION'];
                    $required['php_version']['expected']   = '>= ' . $required_php_version;
                    $required['php_version']['actual']     = PHP_VERSION;
                    $required['php_version']['status']     = empty($compare_result) ? SETTING_FALSE : SETTING_TRUE;

                    // Setting: Session Functions
                    $required['session_functions']['label']    = $language['SESSION_FUNCTIONS'];
                    $required['session_functions']['expected'] = SETTING_EXPECTED_ON;
                    $required['session_functions']['state']   = function_exists('session_start');
                    $required['session_functions']['actual']   = $required['session_functions']['state'] ? SETTING_EXPECTED_ON : SETTING_EXPECTED_OFF;
                    $required['session_functions']['status']   = $required['session_functions']['state'] ? SETTING_TRUE : SETTING_FALSE;

                    // Checking for correct session.auto_start configuration in php.ini
                    $required['session.auto_start']['label']      = $language['SESSION_AUTO_START'];
                    $required['session.auto_start']['expected']   = SETTING_EXPECTED_OFF;
                    $required['session.auto_start']['actual']     = getPHPSetting('session.auto_start', false, 'string');
                    $required['session.auto_start']['status']     = getPHPSetting('session.auto_start', false, 'img');

                    // Setting: PDO MySQL
                    $required['extension_pdo_mysql']['label']    = $language['EXTENSION_PDO_MYSQL'];
                    $required['extension_pdo_mysql']['expected'] = SETTING_EXPECTED_ON;
                    $required['extension_pdo_mysql']['state']    = in_array('mysql', PDO::getAvailableDrivers() );
                    $required['extension_pdo_mysql']['actual']   = $required['extension_pdo_mysql']['state'] ? SETTING_EXPECTED_ON : SETTING_EXPECTED_OFF;
                    $required['extension_pdo_mysql']['status']   = $required['extension_pdo_mysql']['state'] ? SETTING_TRUE : SETTING_FALSE;

                    // Permissions Check: write to systems temporary directory
                    $required['is_writable_temp_dir']['label']    = $language['IS_WRITABLE_TEMP_DIR'];
                    $required['is_writable_temp_dir']['expected'] = 'w';
                    $required['is_writable_temp_dir']['state']    = checkTemporaryDir();
                    $required['is_writable_temp_dir']['actual']   = $required['is_writable_temp_dir']['state'] ? 'w' : '---';
                    $required['is_writable_temp_dir']['status']   = $required['is_writable_temp_dir']['state'] ? SETTING_TRUE : SETTING_FALSE;

                    // Permissions Check: write to \Clansuite root folder
                    $required['is_writable_clansuite_root']['label']    = $language['IS_WRITABLE_CLANSUITE_ROOT'];
                    $required['is_writable_clansuite_root']['expected'] = 'w';
                    $required['is_writable_clansuite_root']['state']    = is_writable(APPLICATION_PATH);
                    $required['is_writable_clansuite_root']['actual']   = $required['is_writable_clansuite_root']['state'] ? 'w' : '---';
                    $required['is_writable_clansuite_root']['status']   = $required['is_writable_clansuite_root']['state'] ? SETTING_TRUE : SETTING_FALSE;

                    // Permissions Check: write to \Clansuite\Uploads folder
                    $required['is_writable_uploads']['label']    = $language['IS_WRITABLE_UPLOADS'];
                    $required['is_writable_uploads']['expected'] = 'w';
                    $required['is_writable_uploads']['state']    = is_writable(APPLICATION_PATH . 'Uploads');
                    $required['is_writable_uploads']['actual']   = $required['is_writable_uploads']['state'] ? 'w' : '---';
                    $required['is_writable_uploads']['status']   = $required['is_writable_uploads']['state'] ? SETTING_TRUE : SETTING_FALSE;

                    // Permissions Check: write to \Clansuite\Cache folder
                    $required['is_writable_cache']['label']    = $language['IS_WRITABLE_CACHE_DIR'];
                    $required['is_writable_cache']['expected'] = 'w';
                    $required['is_writable_cache']['state']    = is_writable(ROOT_CACHE);
                    $required['is_writable_cache']['actual']   = $required['is_writable_cache']['state'] ? 'w' : '---';
                    $required['is_writable_cache']['status']   = $required['is_writable_cache']['state'] ? SETTING_TRUE : SETTING_FALSE;

                    // Permissions Check: write to \Clansuite\Configuration folder
                    $required['is_writable_configuration']['label']    = $language['IS_WRITABLE_CONFIGURATION'];
                    $required['is_writable_configuration']['expected'] = 'w';
                    $required['is_writable_configuration']['state']    = is_writable(APPLICATION_PATH . 'Configuration');
                    $required['is_writable_configuration']['actual']   = $required['is_writable_configuration']['state'] ? 'w' : '---';
                    $required['is_writable_configuration']['status']   = $required['is_writable_configuration']['state'] ? SETTING_TRUE : SETTING_FALSE;

                    // Permissions Check: read Configuration Template File
                    $required['is_readable_config_template']['label']    = $language['IS_READABLE_CONFIG_TEMPLATE'];
                    $required['is_readable_config_template']['expected'] = 'r';
                    $required['is_readable_config_template']['state']    = is_readable(INSTALLATION_ROOT . 'config.skeleton.ini');
                    $required['is_readable_config_template']['actual']   = $required['is_readable_config_template']['state']? 'r' : '---';
                    $required['is_readable_config_template']['status']   = $required['is_readable_config_template']['state'] ? SETTING_TRUE : SETTING_FALSE;

                    // Checking for correct date.timezone configuration in php.ini
                    $required['datetimezone']['label']      = $language['DATE_TIMEZONE'];
                    $required['datetimezone']['expected']   = SETTING_EXPECTED_ON;
                    $required['datetimezone']['state']      = ini_get('date.timezone');
                    $required['datetimezone']['actual']     = $required['datetimezone']['state'] ? SETTING_EXPECTED_ON : SETTING_EXPECTED_OFF;
                    $required['datetimezone']['status']     = $required['datetimezone']['state'] ? SETTING_TRUE : SETTING_FALSE;

                    // RECOMMENDED CHECKS

                    // Setting: PHP memory limit
                    $memory_limit = ini_get('memory_limit');
                    $recommended_memory_limit = 32;
                    $recommended['php_memory_limit']['label']      = $language['PHP_MEMORY_LIMIT'];
                    $recommended['php_memory_limit']['expected']   = 'min '. $recommended_memory_limit .'MB';
                    $recommended['php_memory_limit']['actual']     = '('. $memory_limit .')';
                    $recommended['php_memory_limit']['status']     = ($memory_limit >= $recommended_memory_limit ) ? SETTING_TRUE : SETTING_FALSE;
                    unset($memory_limit, $recommended_memory_limit);

                    // Checking file uploads
                    $recommended['file_uploads']['label']      = $language['FILE_UPLOADS'];
                    $recommended['file_uploads']['expected']   = SETTING_EXPECTED_ON;
                    $recommended['file_uploads']['actual']     = getPHPSetting('file_uploads',true, 'string');
                    $recommended['file_uploads']['status']     = getPHPSetting('file_uploads',true, 'img');

                    // Checking max_upload_filesize
                    $max_upload_filesize = ini_get('upload_max_filesize');
                    $recommended['max_upload_filesize']['label']      = $language['MAX_UPLOAD_FILESIZE'];
                    $recommended['max_upload_filesize']['expected']   = 'min 2MB';
                    $recommended['max_upload_filesize']['actual']     = '('. $max_upload_filesize .')';
                    $recommended['max_upload_filesize']['status']     = ($max_upload_filesize >= 2 ) ? SETTING_TRUE : SETTING_FALSE;
                    unset($max_upload_filesize);

                    // Checking post_max_size
                    // @todo post_max_size > max_upload_filesize
                    $post_max_size = ini_get('post_max_size');
                    $recommended['post_max_size']['label']      = $language['POST_MAX_SIZE'];
                    $recommended['post_max_size']['expected']   = 'min 2MB';
                    $recommended['post_max_size']['actual']     = '('. $post_max_size .')';
                    $recommended['post_max_size']['status']     = ($post_max_size >= 2 ) ? SETTING_TRUE : SETTING_FALSE;
                    unset($post_max_size);

                    // Checking for allow_url_fopen
                    $recommended['allow_url_fopen']['label']       = $language['ALLOW_URL_FOPEN'];
                    $recommended['allow_url_fopen']['expected']    = SETTING_EXPECTED_ON;
                    $recommended['allow_url_fopen']['actual']      = getPHPSetting('allow_url_fopen',true,'string');
                    $recommended['allow_url_fopen']['status']      = getPHPSetting('allow_url_fopen',true,'img');

                    // Checking for allow_url_include
                    $recommended['allow_url_include']['label']       = $language['ALLOW_URL_INCLUDE'];
                    $recommended['allow_url_include']['expected']    = SETTING_EXPECTED_ON;
                    $recommended['allow_url_include']['actual']      = getPHPSetting('allow_url_include',true,'string');
                    $recommended['allow_url_include']['status']      = getPHPSetting('allow_url_include',true,'img');

                    // Checking for Safe mode
                    $recommended['safe_mode']['label']         = $language['SAFE_MODE'];
                    $recommended['safe_mode']['expected']      = SETTING_EXPECTED_OFF;
                    $recommended['safe_mode']['actual']        = getPHPSetting('safe_mode',false,'string');
                    $recommended['safe_mode']['status']        = getPHPSetting('safe_mode',false,'img');

                    // Checking OpenBaseDir
                    $recommended['open_basedir']['label']      = $language['OPEN_BASEDIR'];
                    $recommended['open_basedir']['expected']   = SETTING_EXPECTED_OFF;
                    $recommended['open_basedir']['actual']     = getPHPSetting('open_basedir',false,'string');
                    $recommended['open_basedir']['status']     = getPHPSetting('open_basedir',false,'img');

                    // Checking magic_quotes_gpc
                    $recommended['magic_quotes_gpc']['label']      = $language['MAGIC_QUOTES_GPC'];
                    $recommended['magic_quotes_gpc']['expected']   = SETTING_EXPECTED_OFF;
                    $recommended['magic_quotes_gpc']['actual']     = getPHPSetting('magic_quotes_gpc',false,'string');
                    $recommended['magic_quotes_gpc']['status']     = getPHPSetting('magic_quotes_gpc',false,'img');

                    // Checking magic_quotes_runtime
                    $recommended['magic_quotes_runtime']['label']      = $language['MAGIC_QUOTES_RUNTIME'];
                    $recommended['magic_quotes_runtime']['expected']   = SETTING_EXPECTED_OFF;
                    $recommended['magic_quotes_runtime']['actual']     = getPHPSetting('magic_quotes_runtime',false,'string');
                    $recommended['magic_quotes_runtime']['status']     = getPHPSetting('magic_quotes_runtime',false,'img');

                    // Checking output_buffering
                    $recommended['output_buffering']['label']      = $language['OUTPUT_BUFFERING'];
                    $recommended['output_buffering']['expected']   = SETTING_EXPECTED_OFF;
                    $recommended['output_buffering']['actual']     = getPHPSetting('output_buffering',false,'string');
                    $recommended['output_buffering']['status']     = getPHPSetting('output_buffering',false,'img');

                    // Checking presence of XSLTProcessor
                    $recommended['xsltprocessor']['label']      = $language['XSLT_PROCESSOR'];
                    $recommended['xsltprocessor']['expected']   = SETTING_EXPECTED_ON;
                    $recommended['xsltprocessor']['state']      = class_exists('XSLTProcessor', false);
                    $recommended['xsltprocessor']['actual']     = $recommended['xsltprocessor']['state'] ? SETTING_EXPECTED_ON : SETTING_EXPECTED_OFF;
                    $recommended['xsltprocessor']['status']     = $recommended['xsltprocessor']['state'] ? SETTING_TRUE : SETTING_FALSE;

                    //  Checking for PHP Extension : APC (used in Clansuite_APC_Cache)
                    $recommended['extension_apc']['label']      = $language['EXTENSION_APC'];
                    $recommended['extension_apc']['expected']   = SETTING_EXPECTED_ON;
                    $recommended['extension_apc']['state']      = extension_loaded('apc');
                    $recommended['extension_apc']['actual']     = $recommended['extension_apc']['state'] ? SETTING_EXPECTED_ON : SETTING_EXPECTED_OFF;
                    $recommended['extension_apc']['status']     = $recommended['extension_apc']['state'] ? SETTING_TRUE : SETTING_FALSE;

                     //  Checking for PHP Calendar : Calendar
                    $recommended['extension_calendar']['label']      = $language['EXTENSION_CALENDAR'];
                    $recommended['extension_calendar']['expected']   = SETTING_EXPECTED_ON;
                    $recommended['extension_calendar']['state']      = extension_loaded('calendar');
                    $recommended['extension_calendar']['actual']     = $recommended['extension_calendar']['state'] ? SETTING_EXPECTED_ON : SETTING_EXPECTED_OFF;
                    $recommended['extension_calendar']['status']     = $recommended['extension_calendar']['state'] ? SETTING_TRUE : SETTING_FALSE;

                     //  Checking for PHP Extension : cURL
                    $recommended['extension_curl']['label']      = $language['EXTENSION_CURL'];
                    $recommended['extension_curl']['expected']   = SETTING_EXPECTED_ON;
                    $recommended['extension_curl']['state']      = extension_loaded('curl');
                    $recommended['extension_curl']['actual']     = $recommended['extension_curl']['state'] ? SETTING_EXPECTED_ON : SETTING_EXPECTED_OFF;
                    $recommended['extension_curl']['status']     = $recommended['extension_curl']['state'] ? SETTING_TRUE : SETTING_FALSE;

                    //  Checking for PHP Extension : GD (used systemwide, e.g. on captcha)
                    $recommended['extension_gd']['label']      = $language['EXTENSION_GD'];
                    $recommended['extension_gd']['expected']   = SETTING_EXPECTED_ON;
                    $recommended['extension_gd']['state']      = extension_loaded('gd');
                    $recommended['extension_gd']['actual']     = $recommended['extension_gd']['state'] ? SETTING_EXPECTED_ON : SETTING_EXPECTED_OFF;
                    $recommended['extension_gd']['status']     = $recommended['extension_gd']['state'] ? SETTING_TRUE : SETTING_FALSE;

                    //  Checking for PHP Extension : GeoIP
                    $recommended['extension_geoip']['label']      = $language['EXTENSION_GEOIP'];
                    $recommended['extension_geoip']['expected']   = SETTING_EXPECTED_ON;
                    $recommended['extension_geoip']['state']      = extension_loaded('geoip');
                    $recommended['extension_geoip']['actual']     = $recommended['extension_geoip']['state'] ? SETTING_EXPECTED_ON : SETTING_EXPECTED_OFF;
                    $recommended['extension_geoip']['status']     = $recommended['extension_geoip']['state'] ? SETTING_TRUE : SETTING_FALSE;

                    // Checking for PHP Extension : gettext (used in Clansuite_Localization)
                    $recommended['extension_gettext']['label']    = $language['EXTENSION_GETTEXT'];
                    $recommended['extension_gettext']['expected'] = SETTING_EXPECTED_ON;
                    $recommended['extension_gettext']['state']    = extension_loaded('gettext');
                    $recommended['extension_gettext']['actual']   = $recommended['extension_gettext']['state'] ? SETTING_EXPECTED_ON : SETTING_EXPECTED_OFF;
                    $recommended['extension_gettext']['status']   = $recommended['extension_gettext']['state'] ? SETTING_TRUE : SETTING_FALSE;

                    // Checking for PHP Extension : HASH (used in Clansuite_Security)
                    $recommended['extension_hash']['label']      = $language['EXTENSION_HASH'];
                    $recommended['extension_hash']['expected']   = SETTING_EXPECTED_ON;
                    $recommended['extension_hash']['state']      = extension_loaded('hash');
                    $recommended['extension_hash']['actual']     = $recommended['extension_hash']['state'] ? SETTING_EXPECTED_ON : SETTING_EXPECTED_OFF;
                    $recommended['extension_hash']['status']     = $recommended['extension_hash']['state'] ? SETTING_TRUE : SETTING_FALSE;

                    // Checking for PHP Extension : mbstring
                    $recommended['extension_mbstring']['label']    = $language['EXTENSION_MBSTRING'];
                    $recommended['extension_mbstring']['expected'] = SETTING_EXPECTED_ON;
                    $recommended['extension_mbstring']['state']    = extension_loaded('mbstring');
                    $recommended['extension_mbstring']['actual']   = $recommended['extension_mbstring']['state'] ? SETTING_EXPECTED_ON : SETTING_EXPECTED_OFF;
                    $recommended['extension_mbstring']['status']   = $recommended['extension_mbstring']['state'] ? SETTING_TRUE : SETTING_FALSE;

                    //  Checking for PHP Extension : MCrypt (used in Clansuite_Security)
                    $recommended['extension_mcrypt']['label']      = $language['EXTENSION_MCRYPT'];
                    $recommended['extension_mcrypt']['expected']   = SETTING_EXPECTED_ON;
                    $recommended['extension_mcrypt']['state']      = extension_loaded('mcrypt');
                    $recommended['extension_mcrypt']['actual']     = $recommended['extension_mcrypt']['state'] ? SETTING_EXPECTED_ON : SETTING_EXPECTED_OFF;
                    $recommended['extension_mcrypt']['status']     = $recommended['extension_mcrypt']['state'] ? SETTING_TRUE : SETTING_FALSE;

                    //  Checking for PHP Extension : MEMCACHED? or memcache? (used in Clansuite_Memcache_Cache)
                    $recommended['extension_memcache']['label']      = $language['EXTENSION_MEMCACHE'];
                    $recommended['extension_memcache']['expected']   = SETTING_EXPECTED_ON;
                    $recommended['extension_memcache']['state']      = extension_loaded('memcache');
                    $recommended['extension_memcache']['actual']     = $recommended['extension_memcache']['state'] ? SETTING_EXPECTED_ON : SETTING_EXPECTED_OFF;
                    $recommended['extension_memcache']['status']     = $recommended['extension_memcache']['state'] ? SETTING_TRUE : SETTING_FALSE;

                     //  Checking for PHP Extension : PCRE
                    $recommended['extension_pcre']['label']      = $language['EXTENSION_PCRE'];
                    $recommended['extension_pcre']['expected']   = SETTING_EXPECTED_ON;
                    $recommended['extension_pcre']['state']      = extension_loaded('pcre');
                    $recommended['extension_pcre']['actual']     = $recommended['extension_pcre']['state'] ? SETTING_EXPECTED_ON : SETTING_EXPECTED_OFF;
                    $recommended['extension_pcre']['status']     = $recommended['extension_pcre']['state'] ? SETTING_TRUE : SETTING_FALSE;

                    //  Checking for PHP Extension : SimpleXML (used systemwide for xml parsing)
                    $recommended['extension_simplexml']['label']      = $language['EXTENSION_SIMPLEXML'];
                    $recommended['extension_simplexml']['expected']   = SETTING_EXPECTED_ON;
                    $recommended['extension_simplexml']['state']      = extension_loaded('SimpleXML');
                    $recommended['extension_simplexml']['actual']     = $recommended['extension_simplexml']['state'] ? SETTING_EXPECTED_ON : SETTING_EXPECTED_OFF;
                    $recommended['extension_simplexml']['status']     = $recommended['extension_simplexml']['state'] ? SETTING_TRUE : SETTING_FALSE;

                    //  Checking for PHP Extension : Skein Hash (used in Clansuite_Security)
                    $recommended['extension_skein']['label']      = $language['EXTENSION_SKEIN'];
                    $recommended['extension_skein']['expected']   = SETTING_EXPECTED_ON;
                    $recommended['extension_skein']['state']      = extension_loaded('skein');
                    $recommended['extension_skein']['actual']     = $recommended['extension_skein']['state'] ? SETTING_EXPECTED_ON : SETTING_EXPECTED_OFF;
                    $recommended['extension_skein']['status']     = $recommended['extension_skein']['state'] ? SETTING_TRUE : SETTING_FALSE;

                    //  Checking for PHP Extension : Suhosin
                    $recommended['extension_suhosin']['label']      = $language['EXTENSION_SUHOSIN'];
                    $recommended['extension_suhosin']['expected']   = SETTING_EXPECTED_ON;
                    $recommended['extension_suhosin']['state']      = extension_loaded('suhosin');
                    $recommended['extension_suhosin']['actual']     = $recommended['extension_suhosin']['state'] ? SETTING_EXPECTED_ON : SETTING_EXPECTED_OFF;
                    $recommended['extension_suhosin']['status']     = $recommended['extension_suhosin']['state'] ? SETTING_TRUE : SETTING_FALSE;

                    //  Checking for PHP Extension : SYCK (is a YAML-Parser used in Clansuite_YAML_Config)
                    $recommended['extension_syck']['label']      = $language['EXTENSION_SYCK'];
                    $recommended['extension_syck']['expected']   = SETTING_EXPECTED_ON;
                    $recommended['extension_syck']['state']      = extension_loaded('syck');
                    $recommended['extension_syck']['actual']     = $recommended['extension_syck']['state'] ? SETTING_EXPECTED_ON : SETTING_EXPECTED_OFF;
                    $recommended['extension_syck']['status']     = $recommended['extension_syck']['state'] ? SETTING_TRUE : SETTING_FALSE;

                    // Checking for PHP Extension : tokenizer
                    $recommended['extension_tokenizer']['label']     = $language['EXTENSION_TOKENIZER'];
                    $recommended['extension_tokenizer']['expected']  = SETTING_EXPECTED_ON;
                    $recommended['extension_tokenizer']['state']     = function_exists('token_get_all');
                    $recommended['extension_tokenizer']['actual']    = $recommended['extension_tokenizer']['state'] ? SETTING_EXPECTED_ON : SETTING_EXPECTED_OFF;
                    $recommended['extension_tokenizer']['status']    = $recommended['extension_tokenizer']['state'] ? SETTING_TRUE : SETTING_FALSE;

                    //  Checking for PHP Extension : XML
                    $recommended['extension_xml']['label']      = $language['EXTENSION_XML'];
                    $recommended['extension_xml']['expected']   = SETTING_EXPECTED_ON;
                    $recommended['extension_xml']['state']      = extension_loaded('xml');
                    $recommended['extension_xml']['actual']     = $recommended['extension_xml']['state'] ? SETTING_EXPECTED_ON : SETTING_EXPECTED_OFF;
                    $recommended['extension_xml']['status']     = $recommended['extension_xml']['state'] ? SETTING_TRUE : SETTING_FALSE;
                ?>
                <table class="settings">
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
                        <?php renderSettingRows($required); ?>
                    </tbody>
                </table>
                <br />
                <table class="settings">
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
                        <?php renderSettingRows($recommended); ?>
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
                        <span class="font-10">
                            <?php echo $language['CLICK_NEXT_TO_PROCEED']; ?><br />
                            <?php echo $language['CLICK_BACK_TO_RETURN']; ?>
                        </span>
                        <form action="index.php" method="post">
                            <div class="alignright">
                                <?php
                                $button_inactive = false;
                                foreach ($required as $required_item) {
                                    if ($required_item['status'] === SETTING_FALSE) {
                                        $button_inactive = true;
                                        break;
                                    }
                                }

                                if ($button_inactive === true) {
                                ?>
                                    <input type="submit" value="<?php echo $language['NEXTSTEP']; ?>"
                                           title="<?php echo $language['STEP2_FIX_REQUIRED_SETTINGS_TOOLTIP']; ?>"
                                           disabled="disabled" class="ButtonGrey" name="step_forward" tabindex="1" />
                                <?php
                                } else {
                                ?>
                                    <input type="submit" value="<?php echo $language['NEXTSTEP']; ?>" class="ButtonGreen" name="step_forward" tabindex="1" />
                                <?php
                                }
                                ?>
                            </div>
                            <div class="alignleft">
                                <input type="submit" value="<?php echo $language['BACKSTEP']; ?>" class="ButtonRed" name="step_backward" tabindex="3" />
                                <input type="hidden" name="lang" value="<?php echo $_SESSION['lang']; ?>" />
                                <input type="hidden" name="submitted_step" value="2" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
