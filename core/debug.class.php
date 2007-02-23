<?php
/**
* Debug Handler Class
*
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
*    You should have received a copy of the GNU General Public License
*    along with this program; if not, write to the Free Software
*    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*
* @author     Florian Wolf <xsign.dll@clansuite.com>
* @author     Jens-Andre Koch <vain@clansuite.com>
* @copyright  2006 Clansuite Group
* @license    see COPYING.txt
* @version    SVN: $Id: debug.class.php 144 2006-06-11 22:59:35Z vain $
* @link       http://gna.org/projects/clansuite
* @since      File available since Release 0.1
*/


/**
* @desc Security Handler
*/
if (!defined('IN_CS'))
{
    die('You are not allowed to view this page statically.' );
}

/**
* @desc Start debug class
*/
class debug
{
    /**
     * Returns PDO attributes array
     */
    function return_pdo_attributes_array()
    {
       global $db,$cfg;

       // save current errorlevel
       $old_suppress = $cfg->suppress_errors;
       
       // Suppress Errors 
       // If Attribut unknown to Db-Driver, Error is thrown
       // Cause: SQLSTATE[IM001]: Driver does not support this function: driver does not support that attribute
       $cfg->suppress_errors = 1;

       // Set PDO Errormode Silent
       $old_pdo_errormode = $db->getAttribute(PDO::ATTR_ERRMODE);
       $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);

       // Whitelist PDO::ATTR_* Attributs 
       $attributes_names = array(   "AUTOCOMMIT",
                                    "ERRMODE",
                                    "CASE",
                                    "CLIENT_VERSION",
                                    "CONNECTION_STATUS",
                                    "PERSISTENT",
                                    "SERVER_INFO",
                                    "SERVER_VERSION");
                                    
        // Loop over PDO::ATTR_* with $attributes_names
        foreach ($attributes_names as $val)
        {
          $attributes['PDO::ATTR_'.$val] = $db->getAttribute(constant('PDO::ATTR_'.$val));
        }

        // Set PDO Errormode back to old Errorlevel
        $db->setAttribute(PDO::ATTR_ERRMODE, $old_pdo_errormode);

        // Set Suppress_errors to $old_errorlevel
        $cfg->suppress_errors = $old_suppress;

        return $attributes;
    }

    /**
    * @desc Print debug console
    */

    function show_console()
    {
        global $db, $tpl, $cfg, $error, $lang, $modules;

        // Setup Arrays
        $debug_superglobals = array();
        $debug_db = array();
        $debug = array();

        // Superglobals ($_VARS)
        $debug_superglobals['cookies']       = $_COOKIE;
        $debug_superglobals['get']           = $_GET;
        $debug_superglobals['post']          = $_POST;
        $debug_superglobals['request']       = $_REQUEST;
        $debug_superglobals['session']       = $_SESSION;
        $debug_superglobals['files']         = $_FILES;
        #$debug_superglobals['server']        = $_SERVER;
        #$debug_superglobals['env']           = $_ENV;
        #$debug_superglobals['globals']      = $GLOBALS;

        // Database related Informations
        $debug_db['queries']       = $db->queries;
        $debug_db['prepares']      = $db->prepares;
        $debug_db['execs']         = $db->execs;
        $debug_db['attributes']    = $this->return_pdo_attributes_array();

        // Config Settings, Errors, Languages, Modules
        $debug['config']       = $cfg;
        $debug['error_log']     = $error->error_log;
        $debug['lang_loaded']   = $lang->loaded;
        $debug['mods_loaded']   = $modules->loaded;
        
        // Toggle for DebugConsole to be displayed inline or as popup
        $debug['debug_popup']   = $cfg->debug_popup;

        // Assign Arrays to tpl-vars
        $tpl->assign( 'debug_globals'       , $debug_superglobals );
        $tpl->assign( 'debug_db'            , $debug_db );
        $tpl->assign( 'debug'               , $debug );
        
        // Display Debug Template
        // by addingRawContent before DisplayDoc( maintemplate) is called
        // at position body_post, that means before site-closing by </body></html>
        $tpl->addRawContent( $tpl->fetch('debug.tpl'), '', 'body_post' );
       
    }
}
?>