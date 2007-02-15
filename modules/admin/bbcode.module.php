<?php
/**
* Modulename:   bbcode
* Description:  Edit or add BB Code Styles
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
* @copyright  ClanSuite Group
* @license    GPL v2
* @version    SVN: $Id$
* @link       http://www.clansuite.com
*/

// Security Handler
if (!defined('IN_CS')) { die('You are not allowed to view this page.' ); }

// Begin of class module_admin_bbcode
class module_admin_bbcode
{
    public $output          = '';
    public $additional_head = '';
    public $mod_page_title  = '';
    public $suppress_wrapper= '';

    /**
    * @desc General Function Hook of bbcode-Modul
    *
    * 1. page title of modul is set
    * 2. $_REQUEST['action'] determines the switch
    * 3. function title is added to page title, to complete the title
    * 4. switch-functions are called
    *
    * @return: array ( OUTPUT, MOD_PAGE_TITLE, ADDITIONAL_HEAD, SUPPRESS_WRAPPER )
    *
    */

    function auto_run()
    {

        global $lang, $trail;
        $params = func_get_args();

        // Set Pagetitle and Breadcrumbs
        $trail->addStep($lang->t('Admin'), '/index.php?mod=admin');
        $trail->addStep($lang->t('BB Code Editor'), '/index.php?mod=admin&sub=bbcode');

        // Set Page Title
        $this->mod_page_title = $lang->t( 'BB Code Editor' ) . ' &raquo; ';

        //
        switch ($_REQUEST['action'])
        {

            default:
            case 'show':
                $this->mod_page_title .= $lang->t( 'Show' );
                $this->show();
                break;

            case 'create':
                $this->create();
                break;

            case 'ajaxupdate_bbcode':
                $this->ajaxupdate_bbcode();
                break;

            case 'instant_show':
                $this->output .= call_user_func_array( array( $this, 'instant_show' ), $params );
                break;

        }

        return array( 'OUTPUT'          => $this->output,
                      'MOD_PAGE_TITLE'  => $this->mod_page_title,
                      'ADDITIONAL_HEAD' => $this->additional_head,
                      'SUPPRESS_WRAPPER'=> $this->suppress_wrapper );
    }


     /**
    * @desc Function: Show
    */
    function show()
    {
        global $db, $tpl;

        /**
        * @desc Load the BB Code Vars
        */
        $stmt = $db->prepare('SELECT * FROM ' . DB_PREFIX . 'bb_code');
        $stmt->execute();
        $bb_codes = $stmt->fetchAll(PDO::FETCH_NAMED);

        /**
        * @desc Preview
        */
        require_once( ROOT_CORE . '/bbcode.class.php' );
        $bbcode = new bbcode();
        foreach( $bb_codes as $key => $code )
        {
            $bb_codes[$key]['preview'] = $bbcode->parse('['.$code['name'].']Preview[/'.$code['name'].']');
        }

        /**
        * @desc Handle the output
        */
        $tpl->assign('bb_codes', $bb_codes);
        $this->output .= $tpl->fetch('admin/bbcode/show.tpl');
    }

    /**
    * @desc Create a BB Code
    */
    function create()
    {
        global $db, $functions, $lang;
        $infos = $_POST['info'];

        $stmt = $db->prepare('INSERT INTO ' . DB_PREFIX . 'bb_code
                              SET name = :name,
                                  start_tag = :start_tag,
                                  end_tag = :end_tag,
                                  content_type = :content_type,
                                  allowed_in = :allowed_in,
                                  not_allowed_in = :not_allowed_in');
        $stmt->execute( $infos );

        /**
        * @desc Redirect...
        */
        $functions->redirect( 'index.php?mod=admin&sub=bbcode', 'metatag|newsite', 3, $lang->t( 'The BB Code has been created.' ), 'admin' );
    }

    /**
    * @desc Ajax Update Function called from show_all.tpl
    * $_POST
    * &table=table_for_modules_8&value=Filebrowser123&cell=8_filebrowser_title&_=
    */
    function ajaxupdate_bbcode()
    {
        global $cfg, $db, $tpl, $error, $lang, $functions, $security, $input;

        /**
        * @ desc Incoming Vars
        */
        $value         = urldecode($_POST['value']);
        $cell_string   = urldecode($_POST['cell']);

        $pattern = '!([0-9]+)_(.+)!is';
        if( preg_match($pattern, $cell_string) )
        {
            $result = preg_match($pattern, $cell_string, $subpattern);

            $bbcode_id      = $subpattern[1];
            $bbcode_dbfield = $subpattern[2];

            // update field in db
            $stmt = $db->prepare( 'UPDATE ' . DB_PREFIX . 'bb_code SET ' . $bbcode_dbfield . ' = ?
                                                                   WHERE bb_code_id = ?' );
            $stmt->execute( array(  $value, $bbcode_id ) );

            $stmt = $db->prepare( 'SELECT ' . $bbcode_dbfield . ' FROM ' . DB_PREFIX . 'bb_code WHERE bb_code_id = ?' );
            $stmt->execute( array( $bbcode_id ) );
            $result = $stmt->fetch();
            $this->output = htmlspecialchars($result[$bbcode_dbfield]);
        }
        else
        {
            $this->output = '###AJAXERROR###';
        }

        // suppress mainframe
        $this->suppress_wrapper = true;
    }

    /**
    * @desc Function: instant_show
    *
    * This content can be instantly displayed by adding this into a template:
    * {mod name="bbcode" func="instant_show" params="mytext"}
    *
    * You have to add the lines as shown above into the case block:
    * $this->output .= call_user_func_array( array( $this, 'instant_show' ), $params );
    */
    function instant_show($my_text)
    {
        global $cfg, $db, $tpl, $error, $lang, $functions, $security, $input, $perms;

        /**
        * @desc Handle the output - $lang-t() translates the text.
        */
        $this->output .= $lang->t($my_text);
    }
}
?>