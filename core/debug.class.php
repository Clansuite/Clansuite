<?php
/**
* Debug Handler Class
*
* PHP versions 5.1.4
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
       
       // Fehleranzeige unterdrücken 
       // Wenn das Attribut dem Db-Driver unbekannt ist, wird ein Fehler aufgeworfen.
       // Grund: SQLSTATE[IM001]: Driver does not support this function: driver does not support that attribute
       $old_suppress = $cfg->suppress_errors;
       $cfg->suppress_errors = 1;
       
       // PDO Errormode für Datenermittlung auf Silent setzen
       $old_pdo_errormode = $db->getAttribute(PDO::ATTR_ERRMODE);
       $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
             
              
       $attributes_names = array(   "AUTOCOMMIT",
                                    "ERRMODE",
                                    "CASE", 
                                    "CLIENT_VERSION",
                                    "CONNECTION_STATUS",
                                    "PERSISTENT",
                                    "SERVER_INFO",
                                    "SERVER_VERSION");
        
        foreach ($attributes_names as $val)
        {
          $attributes['PDO::ATTR_'.$val] = $db->getAttribute(constant('PDO::ATTR_'.$val));
        }
        
        // PDO Errormode zurücksetzen auf alten Wert
        $db->setAttribute(PDO::ATTR_ERRMODE, $old_pdo_errormode);
        
        // reset suppressor
        $cfg->suppress_errors = $old_suppress;
        
        return $attributes;
    }
    
    /**
    * @desc Print debug console
    */

    function show_console()
    {
        global $db, $tpl, $cfg, $error, $lang, $modules;
        
        $debug = array();
        $debug['request']       = $_REQUEST;
        $debug['session']       = $_SESSION;
        $debug['cookies']       = $_COOKIE;
        $debug['post']          = $_POST;
        $debug['get']           = $_GET;
        $debug['queries']       = $db->queries;
        $debug['prepares']      = $db->prepares;
        $debug['execs']         = $db->execs;
        $debug['attributes']    = $this->return_pdo_attributes_array();
        $debug['configs']       = $cfg;
        $debug['error_log']     = $error->error_log;
        $debug['lang_loaded']   = $lang->loaded;
        $debug['debug_popup']   = $cfg->debug_popup;
        $debug['mods_loaded']   = $modules->loaded;

        $tpl->assign( 'debug'  , $debug );
        $tpl->display( 'debug.tpl' );
    }
}
?>