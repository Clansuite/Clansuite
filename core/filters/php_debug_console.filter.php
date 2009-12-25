<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-André Koch © 2005 - onwards
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
    * @author     Jens-André Koch <vain@clansuite.com>
    * @copyright  Jens-André Koch (2005 - onwards)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    *
    * @version    SVN: $Id$
    */

// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' ); }

/**
 * Clansuite PHP_Debug Debugging Console
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Filters
 * @implements  Clansuite_Filter_Interface
 */
class Clansuite_Filter_php_debug_console implements Clansuite_Filter_Interface
{
    private $config     = null;

    public function __construct(Clansuite_Config $config)
    {
       $this->config    = $config;
    }

    public function executeFilter(Clansuite_HttpRequest $request, Clansuite_HttpResponse $response)
    {
        // take the initiative, if themeswitching is enabled in CONFIG
        // or pass through (do nothing)
        if(isset($this->config['error']['webdebug']) and $this->config['error']['webdebug'] == 1)
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
                        'render_mode'          => 'div',     // Renderer mode
                        'restrict_access'      => false,     // Restrict access of debug
                        'allow_url_access'     => true,      // Allow url access
                        'url_key'              => 'key',     // Url key
                        'url_pass'             => 'nounou',  // Url pass
                        'enable_watch'         => true,     // Enable wath of vars
                        'replace_errorhandler' => true,      // Replace the php error handler
                        'lang'                 => 'EN',      // Lang

                        # Renderer specific
                        'HTML_DIV_view_source_script_name' => ROOT . '/libraries/phpdebug/PHP_Debug_ShowSource.php',
                        'HTML_DIV_images_path' =>  WWW_ROOT . '/libraries/phpdebug/images',
                        'HTML_DIV_css_path' =>  WWW_ROOT . '/libraries/phpdebug/css',
                        'HTML_DIV_js_path' =>  WWW_ROOT . '/libraries/phpdebug/js',
                        'HTML_DIV_remove_templates_pattern' => true,
                        'HTML_DIV_templates_pattern' => array('/var/www-protected/php-debug.com' => '/var/www/php-debug')
                );

                # Guess what? => developers from localhost only :) @todo we might change that to a config settings
                # Guess why? => security
                $allowedip = array('127.0.0.1');

                # Initialiaze Object
                $debug = new PHP_Debug($options);

                /**
                 *  Load JS / CSS for PHP Debug Console into the Output Buffer
                 */
                ob_start();
                echo '<script type="text/javascript" src="'.$options['HTML_DIV_js_path'].'/html_div.js"></script>';
                echo '<link rel="stylesheet" type="text/css" media="screen" href="'.$options['HTML_DIV_css_path'].'/html_div.css" />';
                $content = ob_get_contents();
    		    ob_end_clean();

                # unset $options
                unset($options);

                # Set Title to Debug Console
                $debug->add('Clansuite DEBUG INFO');

                # we like to fetch the console contents also into the buffer
                # for displaying it at the end of application runtime
                # for a direct display of the console use $debug->display()
                $content .= $debug->getOutput();

                # we append the console output ($content) to "BEFORE_BODY_END</body>"
                $response->setContent($content, BEFORE_BODY_END);
            }
        }// else => bypass
    }
}
?>