<?php
   /**
    * Clansuite - just an E-Sport CMS
    * Jens-Andre Koch, Florian Wolf © 2005-2007
    * http://www.clansuite.com/
    *
    * File:         debug.class.php
    * Requires:     PHP 5.1.4+
    *
    * Purpose:      Clansuite Core Class for Debugging
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
    * @license    GNU/GPL, see COPYING.txt
    *
    * @author     Jens-Andre Koch   <vain@clansuite.com>
    * @author     Florian Wolf      <xsign.dll@clansuite.com>
    * @copyright  Jens-Andre Koch (2005-$LastChangedDate$), Florian Wolf (2006-2007)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    * @since      File available since Release 0.1
    *
    * @version    SVN: $Id$
    */

/**
 * Security Handler
 */
if (!defined('IN_CS')){ die('You are not allowed to view this page.' );}


/**
 * This Clansuite Core Class for Debugging
 *
 * @author     Jens-Andre Koch   <vain@clansuite.com>
 * @author     Florian Wolf      <xsign.dll@clansuite.com>
 * @copyright  Jens-Andre Koch (2005-$LastChangedDate$), Florian Wolf (2006-2007)
 * @since      Class available since Release 0.1
 *
 * @package     clansuite
 * @category    core
 * @subpackage  debugging
 */
class debug
{
    /**
     * This method returns the PDO attributes array
     *
     * @global $cfg
     * @global $db
     * @return $attributes
     */

    function return_pdo_attributes_array()
    {
       global $db,$cfg;

       /**
        * save current errorlevel
        */

       $old_suppress = $cfg->suppress_errors;

       /**
        * Suppress Errors
        * If Attribut unknown to Db-Driver, Error is thrown
        * Cause: SQLSTATE[IM001]: Driver does not support this function: driver does not support that attribute
        */

       $cfg->suppress_errors = 1;

       /**
        * Set PDO Errormode Silent
        */

       $old_pdo_errormode = $db->getAttribute(PDO::ATTR_ERRMODE);
       $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);

       /**
        * Whitelist PDO::ATTR_* Attributs
        */

       $attributes_names = array(   "AUTOCOMMIT",
                                    "ERRMODE",
                                    "CASE",
                                    "CLIENT_VERSION",
                                    "CONNECTION_STATUS",
                                    "PERSISTENT",
                                    "SERVER_INFO",
                                    "SERVER_VERSION");

        /**
         * Loop over PDO::ATTR_* with $attributes_names
         */

        foreach ($attributes_names as $val)
        {
          $attributes['PDO::ATTR_'.$val] = $db->getAttribute(constant('PDO::ATTR_'.$val));
        }

        /**
         * Set PDO Errormode back to old Errorlevel
         */

        $db->setAttribute(PDO::ATTR_ERRMODE, $old_pdo_errormode);

        /**
         * Set Suppress_errors to $old_errorlevel
         */

        $cfg->suppress_errors = $old_suppress;

        return $attributes;
    }

    /**
     * Print debug console
     *
     * This function is a really messy array splitting and merging operation
     *
     * It provides the following array informations for debugging purposes:
     * - all superglobals
     * - all kind of database related informations
     * - Config Settings
     * - Error Log
     * - Loaded Languages
     * - Loaded Modules
     *
     * Includes a toggle for debug console popup
     *
     * The Infos are added as Raw Content to Smarty
     * at the position 'body_post', which means directly after the <body> tag
     * like "<body>debug_console_infos"
     *
     * @global $db
     * @global $tpl
     * @global $cfg
     * @global $error
     * @global $lang
     * @global $modules
     * @todo note by vain: check with xdebug for array doubling. guess
     *       it's ok, because of the better debug overview
     */

    function show_console()
    {
        global $db, $tpl, $cfg, $error, $lang, $modules;

        /**
         * Setup Arrays
         */

        $debug_superglobals = array();
        $debug_db = array();
        $debug = array();

        /**
         * Superglobals ($_VARS)
         * some vars excluded, because not needed
         */

        $debug_superglobals['cookies']       = $_COOKIE;
        $debug_superglobals['get']           = $_GET;
        $debug_superglobals['post']          = $_POST;
        $debug_superglobals['request']       = $_REQUEST;
        $debug_superglobals['session']       = $_SESSION;
        $debug_superglobals['files']         = $_FILES;
        #$debug_superglobals['server']        = $_SERVER;
        #$debug_superglobals['env']           = $_ENV;
        #$debug_superglobals['globals']      = $GLOBALS;

        /**
         * Database related Informations
         */

        $debug_db['queries']       = $db->queries;
        $debug_db['prepares']      = $db->prepares;
        $debug_db['execs']         = $db->execs;
        $debug_db['attributes']    = $this->return_pdo_attributes_array();

        /**
         * Config Settings, Errors, Languages, Modules
         */

        $debug['config']       = $cfg;
        $debug['error_log']     = $error->error_log;
        $debug['lang_loaded']   = $lang->loaded;
        $debug['mods_loaded']   = $modules->loaded;

        /**
         * Toggle for DebugConsole to be displayed inline or as popup
         */

        $debug['debug_popup']   = $cfg->debug_popup;

        /**
         * Assign Arrays to tpl-vars
         */

        $tpl->assign( 'debug_globals'       , $debug_superglobals );
        $tpl->assign( 'debug_db'            , $debug_db );
        $tpl->assign( 'debug'               , $debug );

        /**
         * Display Debug Template
         * by addingRawContent before DisplayDoc( maintemplate) is called
         * at position body_post, that means before site-closing by </body></html>
         */

        $tpl->addRawContent( $tpl->fetch('debug.tpl'), '', 'body_post' );
    }
}
?>