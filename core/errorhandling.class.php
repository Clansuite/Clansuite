<?php
   /**
    * Clansuite - just an E-Sport CMS
    * Jens-Andre Koch, Florian Wolf © 2005-2007
    * http://www.clansuite.com/
    *
    * File:         errorhandling.class.php
    * Requires:     PHP 5.1.4+
    *
    * Purpose:      Clansuite Core Class for Error Handling
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
 * This Clansuite Core Class for Error Handling
 *
 * @author     Jens-Andre Koch   <vain@clansuite.com>
 * @author     Florian Wolf      <xsign.dll@clansuite.com>
 * @copyright  Jens-Andre Koch (2005-$LastChangedDate$), Florian Wolf (2006-2007)
 * @since      Class available since Release 0.1
 *
 * @package     clansuite
 * @category    core
 * @subpackage  error
 */
class error
{
    /**
     * This sets up the error callbacks 
     *
     * - sets up error handlers 
     * - sets up exception handlers 
     * - loads the languagefile error.xml
     * - registers the {error} block which calls smarty_error function 
     *
     * @global $lang
     * @global $cfg
     * @global $tpl
     *
     * @see error::smarty_error()
     * @see error::advanced_error_handler()
     * @see error::exception_handler()
     */
     
    function set_callbacks()
    {
        global $lang, $tpl, $cfg;

        // set error handling language to default language
        $lang->load_lang('error', $cfg->language);
        
        set_error_handler(array($this, 'advanced_error_handler') );
        set_exception_handler(array($this, 'exception_handler' ) );

        $tpl->register_block("error", array('error',"smarty_error"), false);
    }

    /**
     * This registeres the Smarty Block {error level="1" title="Error"}
     *
     * @param array $params mixed array
     * @param string $string contains string data
     * @param array $smarty smarty array
     * @access static
     */
    static function smarty_error($params, $string, &$smarty)
    {
        global $error, $lang;
        
        /**
         * Init Vars 
         * - level 
         * - title
         */
        $params['level']   = !isset( $params['level'] ) ? 3 : $params['level'];
        $params['title']   = !isset( $params['level'] ) ? 'Unkown Error' : $params['title'];

        if ( !empty($string) )
        {
            $error->show( $lang->t($params['title']), $lang->t($string), $params['level'] );
        }
    }

    /**
     * Advanced error_handler callback function
     *
     * This is basically a switch defining the actions taken, 
     * in case of serveral PHP Error States
     *
     * @param integer $errno contains the error as integer
     * @param string $errstr contains error string info
     * @param string $errfile contains the filename with occuring error
     * @param string $errline contains the line of error
     * @global $debug
     * @global $tpl
     * @global $cfg
     * @todo is the gloal debug needed? $debug contains the pdo error, no??
     */
    function advanced_error_handler( $errno, $errstr, $errfile, $errline )
    {
        global $debug, $tpl, $cfg;

        switch ($errno)
        {
        case E_COMPILE_ERROR:
        case E_CORE_ERROR:
        case E_USER_ERROR:
        case E_ERROR:
            $tpl->assign('error_type'       , 1 );
            $tpl->assign('error_head'       , 'FATAL ERROR' );
            $tpl->assign('code'             , $errno );
            $tpl->assign('debug_info'       , $errstr );
            $tpl->assign('file'             , $errfile );
            $tpl->assign('line'             , $errline );
            die( $cfg->suppress_errors == 0 ? $tpl->display('error.tpl' ) : '' );
        case E_PARSE:
        case E_COMPILE_WARNING:
        case E_CORE_WARNING:
        case E_USER_WARNING:
        case E_WARNING:
            if ($cfg->suppress_errors == 0 )
            {
                echo "<b>Warning:</b> $errno: $errstr | File: $errfile | Line: $errline<br />";
            }
            if ( defined('DEBUG') && DEBUG===1 )
            {
                $this->error_log['warning'][] = "$errno: $errstr | File: $errfile | Line: $errline";
            }
            break;
        case E_USER_NOTICE:
            if ( defined('DEBUG') && DEBUG===1 )
            {
                $this->error_log['user_notice'][] = "$errno: $errstr | File: $errfile | Line: $errline";
            }
        case E_NOTICE:
            if ( defined('DEBUG') && DEBUG===1 )
            {
                $this->error_log['notice'][] = "$errno: $errstr | File: $errfile | Line: $errline";
            }
            break;

        default:
            if ( defined('DEBUG') && DEBUG===1 )
            {
                $this->error_log['unknown'][] = "$errno: $errstr | File: $errfile | Line: $errline";
            }
            break;
        }
    }
    
    /**
     * Exception Handler callback function 
     *
     * This is used to trigger the output via error::show
     *
     * @param $e
     * @global $cfg
     * @global $lang
     * @global $db
     *
     * @see error::show()
     */

    function exception_handler( $e )
    {
        global $cfg, $db;

        if ($cfg->suppress_errors == 0 )
        {
            $this->show($e->getCode(), $e->getFile() . ' | Line: ' . $e->getLine() . '<br />' . $e->getMessage() .'<br /><b>Last SQL:&nbsp;</b>' . $db->last_sql, 1 );
        }
    }

    /**
     * This method is used to show errors
     *
     * It a switch on different error level 
     * it assings the error strings 
     * outputs the error.tpl
     *
     * @param string $error_head contains the Name of the Error
     * @param string $string contains errorstring
     * @param integer $level contains errorlvl
     * @param string $redirect contains redirect url
     * @global $tpl
     */

    function show( $error_head = 'Unknown Error', $string = '', $level = 3, $redirect = '' )
    {
        global $tpl;

        switch ( $level )
        {
            case '1':
                $tpl->assign('error_type'    , 1 );
                $tpl->assign('error_head'    , $error_head );
                $tpl->assign('debug_info'    , $string );
                $redirect!='' ? $tpl->assign('redirect', '<meta http-equiv="refresh" content="5; URL=' . $redirect . '">') : '';
                $content = $tpl->fetch( 'error.tpl' );
                die( $content );
                break;

            case '2':
                $tpl->assign('error_type'    , 2 );
                $tpl->assign('error_head'    , $error_head );
                $tpl->assign('debug_info'    , $string );
                return( $tpl->fetch( 'error.tpl' ) );
                break;

            case '3':
                $tpl->assign('error_type'    , 3 );
                $tpl->assign('error_head'    , $error_head );
                $tpl->assign('debug_info'    , $string );
                echo( $tpl->fetch( 'error.tpl' ) );
                break;
        }
    }   
}
?>