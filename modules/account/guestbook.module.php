<?php
   /**
    * Clansuite - just an E-Sport CMS
    * Jens-Andre Koch, Florian Wolf � 2005-2007
    * http://www.clansuite.com/
    *
    * File:         guestbook.module.php
    * Requires:     PHP 5.1.4+
    *
    * Purpose:      Clansuite Module Class - guestbook
    *               The guestbook submodule for the profile
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
 *  Security Handler
 */
if (!defined('IN_CS')) { die('You are not allowed to view this page.'); }

/**
 * This is the Clansuite Module Class - module_account_guestbook
 *
 * Description:  The guestbook submodule for the profile
 *
 * @author     Florian Wolf, Jens-André Koch
 * @copyright  ClanSuite Group
 * @link       http://www.clansuite.com
 * @since      Class available since Release 0.1
 *
 * @package     clansuite
 * @category    module
 * @subpackage  module_account_guestbook
 */
class module_account_guestbook
{
    public $output          = '';
    public $additional_head = '';
    public $suppress_wrapper= '';

    /**
     * @desc General Function Hook of guestbook-Modul
     *
     * 1. Set Pagetitle and Breadcrumbs
     * 2. $_REQUEST['action'] determines the switch
     * 3. function title is added to page title, to complete the title
     * 4. switch-functions are called
     *
     * @return: array ( OUTPUT, ADDITIONAL_HEAD, SUPPRESS_WRAPPER )
     */

    function auto_run()
    {

        global $lang, $trail, $perms;
        $params = func_get_args();

        // Set Pagetitle and Breadcrumbs
        $trail->addStep($lang->t('User Guestbook'), 'index.php?mod=guestbook');

        //
        switch ($_REQUEST['action'])
        {

            default:
            case 'show':
                $trail->addStep($lang->t('Show'), 'index.php?mod=guestbook&amp;action=show');
                $this->show();
                break;

            case 'instant_show':
                $this->output .= call_user_func_array( array( $this, 'instant_show' ), $params );
                break;

        }

        return array( 'OUTPUT'          => $this->output,
                      'ADDITIONAL_HEAD' => $this->additional_head,
                      'SUPPRESS_WRAPPER'=> $this->suppress_wrapper );
    }

    /**
    * Function: Show Guestbook
    * @todo: change setLimit to a Variable for editing by user from (Guestbook Module Settings)
    * @global $tpl
    * @global $db
    * @global $lang
    */
    function show()
    {
        global $tpl, $db, $lang;

        // Incoming Vars
        $id = isset($_GET['id']) ? $_GET['id'] : $_SESSION['user']['user_id'];

        // Check if given id
        if( !isset($id) OR empty($id) )
        {
            $functions->redirect( 'index.php', 'metatag|newsite', 3, $lang->t( 'Please give a valid user id.' ) );
        }

        // Check if guest
        if( $id == 0 )
        {
            $functions->redirect( 'index.php?mod=account&action=register', 'metatag|newsite', 3, $lang->t( 'We are sorry, but guest don\'t have profiles. Please register.' ) );
        }

        // Smarty Pagination load and init
        require(ROOT . 'core/smarty/SmartyPaginate.class.php');

        // set URL
        $SmartyPaginate->setUrl('index.php?mod=guestbook&amp;action=show');
        $SmartyPaginate->setUrlVar('page');
        // set items per page
        $SmartyPaginate->setLimit(20);

        // get all guestbook entries
        $stmt = $db->prepare( 'SELECT i.*,g.*,u.nick FROM ' . DB_PREFIX . 'profiles_guestbook g LEFT JOIN ' . DB_PREFIX . 'images i ON (i.image_id = g.image_id) LEFT JOIN ' . DB_PREFIX . 'users u ON (g.to = u.user_id) WHERE g.to = ? ORDER BY g.gb_added DESC' );
        $stmt->execute( array($id) );
        $guestbook = $stmt->fetchAll(PDO::FETCH_NAMED);

        // if array contains data proceed, else show empty message
        if ( !is_array( $guestbook ) OR count($guestbook) == 0 )
        {
            $err['gb_empty'] = 1;
        }
        else
        {
            // total number of guestbook entries by counting the array
            $number_of_guestbook_entries = count($guestbook);

            // Finally: assign total number of rows to SmartyPaginate
            $SmartyPaginate->setTotal($number_of_guestbook_entries);
            // assign the {$paginate} to $tpl (smarty var)
            $SmartyPaginate->assign($tpl);

            // Get the BB-Code Class
            require_once( ROOT_CORE . '/bbcode.class.php' );
            $bbcode = new bbcode();

            // Set 'not specified's
            foreach( $guestbook as $entry_key => $entry_value )
            {
                foreach( $entry_value as $key => $value )
                {
                    switch( $key )
                    {
                        case 'nick':
                            if( empty($value) )
                                $guestbook[$entry_key][$key] = $guestbook[$entry_key]['gb_nick'];
                            break;

                        case 'gb_comment':
                            if( empty($value) )
                                unset($guestbook[$entry_key][$key]);
                            else
                                $guestbook[$entry_key][$key] = $bbcode->parse($guestbook[$entry_key][$key]);
                            break;

                        case 'gb_text':
                            $guestbook[$entry_key][$key] = $bbcode->parse($guestbook[$entry_key][$key]);
                            break;

                        case 'gb_website':

                            break;

                        default:
                            $guestbook[$entry_key][$key] = empty($value) ? '<span class="not_specified">' . $lang->t('not specified') . '</span>' : $value;
                            break;
                    }
                }
            }
        }

        $tpl->assign( 'guestbook', $guestbook);
        $tpl->assign( 'err' , $err );
        $this->output .= $tpl->fetch('account/profile/guestbook.tpl');
        $this->suppress_wrapper = 1;
    }

    /**
    * AJAX request to save the comment
    * 1. save comment in raw with bbcodes on - into database
    * 2. return comment with formatted bbcode = raw to html-style
    *
    * @global $db
    * @global $tpl
    * @global $functions
    * @global $lang
    * @global $perms
    */
    function create()
    {
        global $db, $tpl, $functions, $lang, $perms;

        // Permissions check
        if( $perms->check('create_gb_entries', 'no_redirect') == true )
        {

            // Incoming Vars
            $infos  = $_POST['infos'];
            $submit = isset($_POST['submit']) ? $_POST['submit'] : '';
            $gb_id  = isset($_GET['id']) ? $_GET['id'] : 0;
            $front  = isset($_GET['front']) ? $_GET['front'] : 0;

            if( !empty( $submit ) )
            {
                // Set user stuff
                $infos['gb_ip'] = $_SESSION['client_ip'];
                $infos['gb_added'] = time();
                $infos['user_id'] = $_SESSION['user']['user_id'];

                // Get an image, if existing
                if( $infos['user_id'] != 0 )
                {
                    $stmt = $db->prepare('SELECT image_id FROM ' . DB_PREFIX . 'profiles_general WHERE user_id = ?');
                    $stmt->execute( array($infos['user_id']) );
                    $result = $stmt->fetch(PDO::FETCH_NAMED);
                    $infos['image_id'] = $result['image_id'];
                }
                else
                {
                    $infos['image_id'] = 0;
                }

                // Add gb entry
                $stmt = $db->prepare( 'INSERT INTO ' . DB_PREFIX . 'guestbook
                                       SET  `gb_added` = :gb_added,
                                            `gb_icq` = :gb_icq,
                                            `gb_nick` = :gb_nick,
                                            `gb_email` = :gb_email,
                                            `gb_website` = :gb_website,
                                            `gb_town` = :gb_town,
                                            `gb_text` = :gb_text,
                                            `gb_ip` = :gb_ip,
                                            `user_id` = :user_id,
                                            `image_id` = :image_id' );
                $stmt->execute( $infos );

                if( $infos['front'] == 1 )
                {
                    // Redirect on finish
                    $functions->redirect( 'index.php?mod=guestbook&action=show', 'metatag|newsite', 3, $lang->t( 'The guestbook entry has been created.' ) );
                }
                else
                {
                    // Redirect on finish
                    $functions->redirect( 'index.php?mod=guestbook&sub=admin&action=show', 'metatag|newsite', 3, $lang->t( 'The guestbook entry has been created.' ), 'admin' );
                }

            }

            $stmt = $db->prepare('SELECT * FROM ' . DB_PREFIX . 'guestbook WHERE gb_id = ?');
            $stmt->execute( array( $gb_id ) );
            $result = $stmt->fetch( PDO::FETCH_NAMED );

            $tpl->assign( 'infos', $result);
            $tpl->assign( 'front', $front);
            $this->output = $tpl->fetch('guestbook/create.tpl');
        }
        else
        {
            $this->output = $lang->t('You do not have sufficient rights.') . '<br /><input class="ButtonRed" type="button" onclick="Dialog.okCallback()" value="Abort"/>';
        }
        $this->suppress_wrapper = 1;
    }

    /**
    * Send a img header
    *
    * @global $db
    */
    function show_avatar()
    {
        global $db;

        // Incoming vars
        $id = isset($_GET['id']) ? $_GET['id'] : 0;

        if( $id != 0 )
        {
            $stmt = $db->prepare( 'SELECT i.*,g.gb_id FROM ' . DB_PREFIX . 'guestbook g LEFT JOIN ' . DB_PREFIX . 'images i ON i.image_id = g.image_id WHERE g.gb_id = ?' );
            $stmt->execute( array( $id ) );
            $result = $stmt->fetch(PDO::FETCH_NAMED);

            require( ROOT_CORE . '/image.class.php' );
            $img = new image( ROOT_UPLOAD . '/' . $result['location'] );
            $img->resize( 150, 100 );
            $img->show();
        }
    }

    /**
    * Edit the guestbook profile stuff
    *
    * @global $db
    * @global $lang
    * @global $tpl
    */
    function edit()
    {
        global  $db, $lang, $tpl;

        // ouput
        $this->output .= $tpl->fetch('account/profile/edit_guestbook.tpl');
        $this->suppress_wrapper = 1;
    }

    /**
     * Instant Show
     *
     * Content of a module can be instantly displayed by adding the
     * {mod name="guestbook" sub="admin" func="instant_show" params="mytext"}
     * block into a template.
     *
     * You have to add the lines as shown above into the case block:
     * $this->output .= call_user_func_array( array( $this, 'instant_show' ), $params );
     *
     * @global $cfg, $db, $tpl, $error, $lang, $functions, $security, $input, $perms, $users
    */

    function instant_show($my_text)
    {
        global $cfg, $db, $tpl, $error, $lang, $functions, $security, $input, $perms, $users;

        // Add $lang-t() translated text to the output.
        $this->output .= $lang->t($my_text);
    }
}
?>