<?php
   /**
    * Clansuite - just an E-Sport CMS
    * Jens-Andre Koch, Florian Wolf � 2005-2007
    * http://www.clansuite.com/
    *
    * File:         guestbook.module.php
    * Requires:     PHP 5.1.4+
    *
    * Purpose:      Clansuite Module Class - Guestbook
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
if (!defined('IN_CS')) { die('You are not allowed to view this page.' ); }

/**
 * This is the Clansuite Module Class - Guestbook
 *
 * @author     Jens-Andre Koch   <vain@clansuite.com>
 * @copyright  Jens-Andre Koch (2005-$LastChangedDate$)
 * @since      Class available since Release 0.1
 *
 * @package     clansuite
 * @category    module
 * @subpackage  guestbook
 */
class module_guestbook
{
    public $output          = '';
    public $additional_head = '';
    public $suppress_wrapper= '';

    /**
    * General Function Hook of guestbook-Modul
    *
    * 1. Set Pagetitle and Breadcrumbs
    * 2. $_REQUEST['action'] determines the switch
    * 3. function title is added to page title, to complete the title
    * 4. switch-functions are called
    *
    * @global $lang
    * @global $trail
    * @return: array ( OUTPUT, ADDITIONAL_HEAD, SUPPRESS_WRAPPER )
    *
    */

    function auto_run()
    {
        global $lang, $trail;
        $params = func_get_args();

        // Set Pagetitle and Breadcrumbs
        $trail->addStep($lang->t('Guestbook'), '/index.php?mod=guestbook');

        //
        switch ($_REQUEST['action'])
        {

            default:
            case 'show_guestbook':
                $trail->addStep($lang->t('Show'), '/index.php?mod=guestbook&amp;action=show_guestbook');
                $this->show_guestbook();
                break;

            case 'create':
                $trail->addStep($lang->t('Add'), '/index.php?mod=guestbook&amp;action=create');
                $this->create();
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
     */
    function show_guestbook()
    {
        global $cfg, $db, $tpl, $error, $lang, $functions, $security, $input, $perms;


        // Smarty Pagination load and init
        require(ROOT . 'core/smarty/SmartyPaginate.class.php');

        // set URL
        $SmartyPaginate->setUrl('index.php?mod=guestbook&amp;action=show');
        $SmartyPaginate->setUrlVar('page');
        // set items per page
        $SmartyPaginate->setLimit(20);

        // get all guestbook entries
        $stmt = $db->prepare( 'SELECT * FROM ' . DB_PREFIX . 'guestbook' );
        $stmt->execute();
        $guestbook = $stmt->fetchALL(PDO::FETCH_NAMED);

        // if array contains data proceed, else show empty message
        if ( !is_array( $guestbook ) )
        {
            $this->output .= $lang->t('We are sorry to say, but your Guestbook is empty.');
        }
        else
        {
            // total number of guestbook entries by counting the array
            $number_of_guestbook_entries = count($guestbook);

            // Finally: assign total number of rows to SmartyPaginate
            $SmartyPaginate->setTotal($number_of_guestbook_entries);
            // assign the {$paginate} to $tpl (smarty var)
            $SmartyPaginate->assign($tpl);

            $tpl->assign('guestbook', $guestbook);

            /**
             * @desc Handle the output - $lang-t() translates the text.
             */
            $this->output .= $tpl->fetch('guestbook/show.tpl');
        }
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

                // Add gb entry
                $stmt = $db->prepare( 'INSERT INTO ' . DB_PREFIX . 'guestbook
                                       SET  `gb_added` = :gb_added,
                                            `gb_icq` = :gb_icq,
                                            `gb_nick` = :gb_nick,
                                            `gb_email` = :gb_email,
                                            `gb_website` = :gb_website,
                                            `gb_town` = :gb_town,
                                            `gb_text` = :gb_text,
                                            `gb_ip` = :gb_ip' );
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
    * @desc Function: instant_show
    *
    * This content can be instantly displayed by adding this into a template:
    * {mod name="guestbook" func="instant_show" params="mytext"}
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