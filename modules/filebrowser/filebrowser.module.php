<?php
/**
* filebrowser
* The filebrwoser of clansuite
*
* PHP >= version 5.1.4
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
* @link       http://gna.org/projects/clansuite
*
* @author     Florian Wolf
* @copyright  clansuite group
* @license    GPL v2
* @version    SVN: $Id$
* @link       http://www.clansuite.com
*/

/**
* @desc Security Handler
*/
if (!defined('IN_CS'))
{
    die('You are not allowed to view this page statically.' );
}

/**
* @desc Start module class
*/
class module_filebrowser
{
    public $output          = '';
    public $mod_page_title  = '';
    public $additional_head = '';
    public $suppress_wrapper= '';

    /**
    * @desc First function to run - switches between $_REQUEST['action'] Vars to the functions
    * @desc Loads necessary language files
    */

    function auto_run()
    {

        global $lang;
        $params = func_get_args();

        // Construct Page Title
        $this->mod_page_title = $lang->t( 'filebrowser' ) . ' &raquo; ';

        switch ($_REQUEST['action'])
        {
            case 'show':
                $this->mod_page_title .= $lang->t( 'Show' );
                $this->show();
                break;

            case 'instant_show':
                $this->output .= call_user_func_array( array( $this, 'instant_show' ), $params );
                break;

            case 'get_folder':
                $this->get_folder();
                break;

            default:
                $this->show();
                break;
        }

        return array( 'OUTPUT'          => $this->output,
                      'MOD_PAGE_TITLE'  => $this->mod_page_title,
                      'ADDITIONAL_HEAD' => $this->additional_head,
                      'SUPPRESS_WRAPPER'=> $this->suppress_wrapper );
    }

    /**
    * @desc Show the entrance - welcome message etc.
    */
    function show()
    {
        global $cfg, $db, $tpl, $error, $lang, $functions, $security, $input, $perms;

        $perms->check('access_filebrowser');

        $this->instant_show( '', 'filebrowser/wrapper.tpl', 'filebrowser/sections.tpl', 'index_browser' );
    }

    /**
    * @desc This content can be instantly displayed by adding {mod name="filebrowser" func="instant_show" params="mytext"} into a template
    * @desc You have to add the lines as shown above into the case block: $this->output .= call_user_func_array( array( $this, 'instant_show' ), $params );
    */
    function instant_show( $path = ROOT, $template, $section_template, $name )
    {
        global $cfg, $db, $tpl, $error, $lang, $functions, $security, $input;

        foreach( glob( ROOT . $path.'*', GLOB_ONLYDIR ) as $item )
        {
            $folders[str_replace( ROOT, '', $item)] = preg_replace( '#^(.*)/#', '', $item);
        }

        foreach( glob( ROOT . $path.'*' ) as $item )
        {
            if ( !is_dir( $item ) )
            {
                $files[str_replace( ROOT, '', $item)] = preg_replace( '#^(.*)/#', '', $item);
            }
        }

        /**
        * @desc Output files uppon the template
        */
        $tpl->assign( 'name' , $name );
        $tpl->assign( 'section_template' , $section_template );
        if( !defined('FILEBROWSER_AJAX_LOADED') )
        {
            $this->output .= $tpl->fetch( 'filebrowser/ajax.tpl' );
            define('FILEBROWSER_AJAX_LOADED', 1);
        }


        $tpl->assign( 'folders'     , $folders );
        $tpl->assign( 'files'       , $files );
        $tpl->assign( 'section'     , $tpl->fetch( $section_template ) );
        $this->output .= $tpl->fetch( $template );
    }

    /**
    * @desc Get a folder for AJAX
    */
    function get_folder()
    {
        global $cfg, $db, $tpl, $error, $lang, $functions, $security, $input, $perms;

        $perms->check('access_filebrowser');

        $template   = urldecode(urldecode($_POST['section_template']));
        $path       = urldecode(urldecode($_POST['path']));
        $name       = urldecode(urldecode($_POST['name']));

        foreach( glob( $path.'/*', GLOB_ONLYDIR ) as $item )
        {
            $folders[urlencode($item)] = preg_replace( '#^(.*)/#', '', $item);
        }

        foreach( glob( $path.'/*' ) as $item )
        {
            if ( !is_dir( $item ) )
            {
                $files[utf8_encode($item)] = preg_replace( '#^(.*)/#', '', $item);
            }
        }

        /**
        * @desc Raw output, no wrapper
        */
        $tpl->assign( 'name'    , $name );
        $tpl->assign( 'folders' , $folders );
        $tpl->assign( 'files'   , $files );
        $this->output = $tpl->fetch( $template );
        $this->suppress_wrapper = true;
    }
}
?>