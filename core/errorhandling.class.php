<?php
/**
* Error Handler Class
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
* @version    SVN: $Id: error.class.php 137 2006-06-11 06:12:53Z xsign $
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
* @desc Start of error class
*/
class error
{
    /**
    * @desc Set normal error handlers and load error.xml
    */
    function set_callbacks()
    {
        global $lang, $cfg, $tpl;

        $lang->load_lang('error');
        set_error_handler(array($this, 'advanced_error_handler') );
        set_exception_handler(array($this, 'exception_handler' ) );

        $tpl->register_block("error", array('error',"smarty_error"), false);
    }

    /**
    * @desc Registered Smarty {error level="1" title="Error"}
    */
    static function smarty_error($params, $string, &$smarty)
    {
        global $error, $lang;
        /**
        * @desc Init Vars
        */
        $params['level']   = !isset( $params['level'] ) ? 3 : $params['level'];
        $params['title']   = !isset( $params['level'] ) ? 'Unkown Error' : $params['title'];

        if ( !empty($string) )
        {
            $error->show( $lang->t($params['title']), $lang->t($string), $params['level'] );
        }
    }

    /**
    * @desc Advanced error_handler callback function
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
            $tpl->assign('error_type'    , 1 );
            $tpl->assign('error_head'    , 'FATAL ERROR' );
            $tpl->assign('code'    , $errno );
            $tpl->assign('debug_info'    , $errstr );
            $tpl->assign('file'    , $errfile );
            $tpl->assign('line'    , $errline );
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
    * @desc Script Error Handler
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

    /**
    * @desc Exception Handler
    */

    function exception_handler( $e )
    {
        global $cfg, $lang, $db;

        if ($cfg->suppress_errors == 0 )
        {
            $this->show($e->getCode(), $e->getFile() . ' | Line: ' . $e->getLine() . '<br />' . $e->getMessage() .'<br /><b>Last SQL:&nbsp;</b>' . $db->last_sql, 1 );
        }
    }
}

?>