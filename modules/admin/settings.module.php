<?php
/**
* settings
* This is the Admin Control Panel
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
* @author     Jens-André Koch, Florian Wolf
* @copyright  Clansuite Group
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
class module_admin_settings
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

        // Set Pagetitle
        $this->mod_page_title = $lang->t( 'Admin Interface' ) . ' &raquo; ';

        switch ($_REQUEST['action'])
        {
            default:
            case 'show':
                $this->mod_page_title .= $lang->t( 'Show' );
                $this->show();
                break;

            case 'update':
                $this->mod_page_title .= $lang->t( 'Update' );
                $this->update();
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
        global $cfg, $db, $tpl, $error, $lang, $functions, $security, $input;

        global $cfg, $tpl, $error, $lang;

        $tpl->assign('cfg', $cfg);
        $this->output .= $tpl->fetch('admin/settings/settings.tpl');

    }

    /**
    * @desc This content can be instantly displayed by adding {mod name="settings" func="instant_show" params="mytext"} into a template
    * @desc You have to add the lines as shown above into the case block: $this->output .= call_user_func_array( array( $this, 'instant_show' ), $params );
    */
    function update()
    {
        global $cfg, $db, $tpl, $error, $lang, $functions, $security, $input;

        /**
        * @desc Handle the update
        */
        var_dump($_POST['config']);
        $this->output .= '';
    }
}
?>