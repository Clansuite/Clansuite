    <div id="sidebar">
        <div id="stepbar">
            <?=$language['MENU_HEADING']?>
            <div class="step-pass"><?=$language['MENUSTEP1']?> </div>
            <div class="step-on"><?=$language['MENUSTEP2']?></div>
            <div class="step-off"><?=$language['MENUSTEP3']?></div>
            <div class="step-off"><?=$language['MENUSTEP4']?></div>
            <div class="step-off"><?=$language['MENUSTEP5']?></div>
            <div class="step-off"><?=$language['MENUSTEP6']?></div>
            <div class="step-off"><?=$language['MENUSTEP7']?></div>
        </div>
    </div>

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
					            #echo $value;


						  	#s_array[Checking for PHP version 5.2+]
							// toggle
							$csstoggle = ($csstoggle==$css1) ? $css2 : $css1;

							//starting tablerow
						    $table_rows = '<tr class="'. $csstoggle .'">';
							#echo $csstoggle;
							#$table_rows .= '<td>'. $settingname .'=>'. $value['text'] .'</td>';
							$table_rows .= '<td>'. $value['text'] .'</td>';
							$table_rows .= '<td class="col1">' . $value['expected'] . '</td>';
							$table_rows .= '<td class="col2">' . $value['actual'] .'</td>';
							$table_rows .= '<td class="col1">' . $value['status'] .'</td>';
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

						 # REQUIRED CHECKS
						 # Check 1
						 # Setting: PHP-Version
						 $php_version    = phpversion();
						 $compare_result = version_compare($php_version,'5.2.0','>=');
						 $required['php_version']['text']       = $language['PHP_VERSION'];
						 $required['php_version']['expected']   = '>= 5.2.0';
                         $required['php_version']['actual']     = $php_version;
                         $required['php_version']['status']     = empty($compare_result) ? SETTING_FALSE : SETTING_TRUE;

						 # Check 2
						 # Setting: Session Functions
						 $required['session_functions']['text']     = $language['SESSION_FUNCTIONS'];
						 $required['session_functions']['expected'] = 'on';
						 $required['session_functions']['actual']   = function_exists('session_start') ? 'on' : 'off';
						 $required['session_functions']['status']   = function_exists('session_start') ? SETTING_TRUE : SETTING_FALSE;

                         # Check 3
                         # Setting: PDO
                         $required['pdo_library']['text']     = $language['PDO_LIBRARY'];
                         $required['pdo_library']['expected'] = 'on';
                         $required['pdo_library']['actual']   = class_exists('pdo') ? 'on' : 'off';
                         $required['pdo_library']['status']   = class_exists('pdo') ? SETTING_TRUE : SETTING_FALSE;

                         # Check 4
                         # Setting: PDO MySQL
                         $required['pdo_mysql_library']['text']     = $language['PDO_MYSQL_LIBRARY'];
                         $required['pdo_mysql_library']['expected'] = 'on';
                         $required['pdo_mysql_library']['actual']   = in_array('mysql', PDO::getAvailableDrivers() ) ? 'on' : 'off';
                         $required['pdo_mysql_library']['status']   = in_array('mysql', PDO::getAvailableDrivers() ) ? SETTING_TRUE : SETTING_FALSE;

						 #Checking if session.save_path is writable

						 # Check 3
						 # Setting: Database

						 # Checking write permission on \smarty\templates_c

						 # Checking write permission on \smarty\cache

						 # Checking write permission on CONFIG-FILE


						 # Check 3
						 # Setting:

						 # RECOMMENDED CHECKS
						 # Check 1
						 # Setting: PHP memory limit
						 $memory_limit = ini_get('memory_limit');
						 $recommended['php_memory_limit']['text'] 		= $language['PHP_MEMORY_LIMIT'];
						 $recommended['php_memory_limit']['expected'] 	= 'min 16MB';
						 $recommended['php_memory_limit']['actual']     = '('. $memory_limit .')';
						 $recommended['php_memory_limit']['status'] 	= ($memory_limit >= 16 ) ? SETTING_TRUE : SETTING_FALSE;

						 #Checking file uploads
						 $recommended['file_uploads']['text']		= $language['FILE_UPLOADS'];
						 $recommended['file_uploads']['expected']	= 'on';
						 $recommended['file_uploads']['actual']	    = get_php_setting('file_uploads',true, 'string');
						 $recommended['file_uploads']['status'] 	= get_php_setting('file_uploads',true, 'img');

						 #Checking max upload file size (min 2M, recommend 10M)

						 #Checking for basic XML (expat) support

						 #Checking RegisterGlobals
						 $recommended['register_globals']['text'] 		= $language['REGISTER_GLOBALS'];
						 $recommended['register_globals']['expected']	= 'off';
						 $recommended['register_globals']['actual'] 	= ini_get('register_globals') ? 'on' : 'off';
                         $recommended['register_globals']['status'] 	= ini_get('register_globals') ? SETTING_FALSE: SETTING_TRUE;

						 #Checking
						 $recommended['magic_quotes_gpc']['text'] 		= $language['MAGIC_QUOTES_GPC'];
						 $recommended['magic_quotes_gpc']['expected']	= 'off';
						 $recommended['magic_quotes_gpc']['actual'] 	= get_php_setting('magic_quotes_gpc',false,'string');
						 $recommended['magic_quotes_gpc']['status'] 	= get_php_setting('magic_quotes_gpc',false,'img');

						 $recommended['magic_quotes_runtime']['text'] 		= $language['MAGIC_QUOTES_RUNTIME'];
						 $recommended['magic_quotes_runtime']['expected']	= 'off';
						 $recommended['magic_quotes_runtime']['actual'] 	= get_php_setting('magic_quotes_runtime',false,'string');
                         $recommended['magic_quotes_runtime']['status'] 	= get_php_setting('magic_quotes_runtime',false,'img');

						 #Checking for tokenizer functions
						 #$recommended['']['']
						 #$recommended['']['']

						 #Checking for tokenizer functions
						 $recommended['tokenizer']['text'] 		= $language['TOKENIZER'];
						 $recommended['tokenizer']['expected']	= 'on';
						 $recommended['tokenizer']['actual'] 	= function_exists('token_get_all') ? 'on' : 'off';
                         $recommended['tokenizer']['status'] 	= function_exists('token_get_all') ? SETTING_TRUE : SETTING_FALSE;

						 #Checking for GD
						 $recommended['extension_gd']['text'] 		= $language['EXTENSION_GD'];
						 $recommended['extension_gd']['expected']	= 'on';
						 $recommended['extension_gd']['actual'] 	= extension_loaded('gd') ? 'on' : 'off';
						 $recommended['extension_gd']['status'] 	= extension_loaded('gd') ? SETTING_TRUE : SETTING_FALSE;


						 #Checking for allow_url_fopen
						 $recommended['allow_url_fopen']['text'] 		= $language['ALLOW_URL_FOPEN'];
						 $recommended['allow_url_fopen']['expected']	= 'on';
                         $recommended['allow_url_fopen']['actual'] 		= get_php_setting('allow_url_fopen',true,'string');
						 $recommended['allow_url_fopen']['status'] 		= get_php_setting('allow_url_fopen',true,'img');


						 #Checking for Safe mode
						 $recommended['safe_mode']['text'] 			= $language['SAFE_MODE'];
						 $recommended['safe_mode']['expected']		= 'off';
						 $recommended['safe_mode']['actual'] 		= get_php_setting('safe_mode',false,'string');
						  $recommended['safe_mode']['status'] 		= get_php_setting('safe_mode',false,'img');

						 #Checking OpenBaseDir
						 $recommended['open_basedir']['text'] 		= $language['OPEN_BASEDIR'];
						 $recommended['open_basedir']['expected']	= 'off';
						 $recommended['open_basedir']['actual'] 	= get_php_setting('open_basedir',false,'string');
						 $recommended['open_basedir']['status'] 	= get_php_setting('open_basedir',false,'img');

						 ?>

						 <table class="settings" border="0">
						 		<thead class="tbhead">
									<tr><td class="tdcaption" colspan="4"><?=$language['STEP2_SYSTEMSETTING_REQUIRED']?></td></tr>
						            <tr>
										<th><?=$language['STEP2_SETTING']?></th>
										<th><?=$language['STEP2_SETTING_EXPECTED']?></th>
										<th><?=$language['STEP2_SETTING_ACTUAL']?></th>
										<th><?=$language['STEP2_SETTING_STATUS']?></th>
									</tr>
								</thead>
							<tbody>
							        <?php setting_rows($required); ?>


						            <tr><td class="tdcaption" colspan="4"><?=$language['STEP2_SYSTEMSETTING_RECOMMENDED']?></td></tr>
								    <tr class="tbhead">
										<th><?=$language['STEP2_SETTING']?></th>
										<th><?=$language['STEP2_SETTING_EXPECTED']?></th>
										<th><?=$language['STEP2_SETTING_ACTUAL']?></th>
										<th><?=$language['STEP2_SETTING_STATUS']?></th>
    								</tr>

									<?php setting_rows($recommended); ?>
							</tbody>
						</table>

            <div id="content_footer">
            <div class="navigation">

                        <span style="font-size:10px;">
                            <?=$language['CLICK_NEXT_TO_PROCEED']?>
                            <br />
                            <?=$language['CLICK_BACK_TO_RETURN']?>
                        </span>

						<form action="index.php" method="post">
	            			<div class="alignright">
	                            <input type="submit" value="<?=$language['NEXTSTEP']?>" class="ButtonGreen" name="step_forward" />
	            			</div>

							<div class="alignleft">
	                            <input type="submit" value="<?=$language['BACKSTEP']?>" class="ButtonRed" name="step_backward" />
	                            <input type="hidden" name="lang" value="<?=$_SESSION['lang']?>" />
	                        </div>
                         </form>
                    </div><!-- div navigation end -->
			</div> <!-- div content_footer end -->

        	</div> <!-- div accordion end -->

        </div> <!-- div content_middle end -->

    </div> <!-- div content end -->