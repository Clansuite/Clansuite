<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-Andr� Koch � 2005 - onwards
    * http://www.clansuite.com/
    *
    * This file is part of "Clansuite - just an eSports CMS".
    *
    * LICENSE:
    *
    *    This program is free software; you can redistribute it and/or modify
    *    it under the terms of the GNU General Public License as published by
    *    the Free Software Foundation; either version 2 of the License, or
    *    (at your option) any later version.
    *
    *    This program is distributed in the hope that it will be useful,
    *    but WITHOUT ANY WARRANTY; without even the implied warranty of
    *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    *    GNU General Public License for more details.
    *
    *    You should have received a copy of the GNU General Public License
    *    along with this program; if not, write to the Free Software
    *    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
    *
    * @license    GNU/GPL v2 or (at your option) any later version, see "/doc/LICENSE".
    *
    * @author     Jens-Andr� Koch <vain@clansuite.com>
    * @copyright  Jens-Andr� Koch (2005 - onwards)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    *
    * @version    SVN: $Id: view_smarty.class.php 2530 2008-09-18 23:12:04Z vain $
    */

// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' ); }

/**
 * Clansuite PHP_Debug Debugging Console
 *
 *
 * @package clansuite
 * @subpackage filters
 * @implements FilterInterface
 */
class php_debug_console implements Filter_Interface
{
    private $config     = null;

    function __construct(Clansuite_Config $config)
    {
       $this->config    = $config;
    }

    public function executeFilter(httprequest $request, httpresponse $response)
    {
        // take the initiative, if themeswitching is enabled in CONFIG
        // or pass through (do nothing)
        if($this->config['switches']['webdebug'] == 1)
        {
            /**
             *  ================================================
             * Initialize PHP_Debug Web-Debuggign Console
             *  ================================================
             */
            if(DEBUG)
            {
                # Additional ini path for PHPDEBUG
                define('ADD_PHPDEBUG_ROOT', ROOT_LIBRARIES . 'phpdebug' );
                set_include_path(ADD_PHPDEBUG_ROOT . PATH_SEPARATOR. get_include_path());

                # Load Library
                require_once ROOT_LIBRARIES . 'phpdebug/PHP/Debug.php';

                # Setup Options for the PHPDebug Object
                $options = array(
                        # General Options
                        'render_type'          => 'HTML',    // Renderer type
                        'render_mode'          => 'Div',     // Renderer mode
                        'restrict_access'      => false,     // Restrict access of debug
                        'allow_url_access'     => true,      // Allow url access
                        'url_key'              => 'key',     // Url key
                        'url_pass'             => 'nounou',  // Url pass
                        'enable_watch'         => true,     // Enable wath of vars
                        'replace_errorhandler' => true,      // Replace the php error handler
                        'lang'                 => 'EN',      // Lang

                        # Renderer specific
                        'HTML_DIV_view_source_script_name' => '/libraries/phpdebug/PHP_Debug_ShowSource.php',
                        'HTML_DIV_images_path' => '/libraries/phpdebug/images',
                        'HTML_DIV_css_path' => '/libraries/phpdebug/css',
                        'HTML_DIV_js_path' => '/libraries/phpdebug/js',
                        'HTML_DIV_remove_templates_pattern' => true,
                        'HTML_DIV_templates_pattern' => array('/var/www-protected/php-debug.com' => '/var/www/php-debug')
                );

                # Guess what? => developers from localhost only :)
                $allowedip = array('127.0.0.1');

                # Initialiaze Object
                $debug = new PHP_Debug($options);

                # Set Title
                $debug->add('Clansuite DEBUG INFO');

                /* Load JS / CSS */
                ?>
                <script type="text/javascript" src="<?php echo $options['HTML_DIV_js_path']; ?>/html_div.js"></script>
                <link rel="stylesheet" type="text/css" media="screen" href="<?php echo $options['HTML_DIV_css_path']; ?>/html_div.css" />
                <?php

                # unset $options
                unset($options);

                # display the console
                $debug->display();
            }
        }// else => bypass
    }
}
?>