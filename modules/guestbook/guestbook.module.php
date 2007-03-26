<?php
   /**
    * Clansuite - just an E-Sport CMS
    * Jens-Andre Koch, Florian Wolf © 2005-2007
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
                $trail->addStep($lang->t('Show'), '/index.php?mod=guestbook&action=show_guestbook');
                $this->show_guestbook();
                break;

            case 'add_guestbook_entry':
                $trail->addStep($lang->t('Add'), '/index.php?mod=guestbook&action=add_guestbook_entry');
                $this->add_guestbook_entry();
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
        // required connect
        SmartyPaginate::connect();
        // set URL
        SmartyPaginate::setUrl('index.php?mod=guestbook&amp;action=show');
        SmartyPaginate::setUrlVar('page');
        // set items per page
        SmartyPaginate::setLimit(20);

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
            SmartyPaginate::setTotal($number_of_guestbook_entries);
            // assign the {$paginate} to $tpl (smarty var)
            SmartyPaginate::assign($tpl);
           
            $tpl->assign('guestbook', $guestbook);
    
            /**
             * @desc Handle the output - $lang-t() translates the text.
             */
            $this->output .= $tpl->fetch('guestbook/show.tpl');
        }
    }

    /**
     * @desc Add Entry Guestbook
     */
    function add_guestbook_entry()
    {
        global $cfg, $db, $tpl, $error, $lang, $functions, $security, $input, $perms;

        /**
         * Incoming vars
         */
        $infos          = isset($_POST['info']) ? $_POST['info'] : array();
        
        /**
         * Set Error: message and gbname were empty
         */
        if( empty($infos['gbname']) )       $errors['no_gbname']    = 1;
        if( empty($infos['gbmessage']) )    $errors['no_message']   = 1;

        if( !empty($infos) )
        {
            if( count($errors) == 0 )
            {
                /**
                 * Insert data into DB
                 *
                 * SQL Structure:
                 * gb_id, gb_added, gb_nick, gb_email, gb_icq, gb_website, gb_town, gb_text, gb_ip
                 */
                $stmt = $db->prepare('INSERT INTO '. DB_PREFIX .'guestbook (gb_added, gb_nick, gb_email, gb_icq, gb_website, gb_town, gb_text, gb_ip) VALUES (?,?,?,?,?,?,?,?)');
                
                $stmt->execute( array( time(),
                                       $infos['gbname'],
                                       $infos['gbemail'],
                                       $infos['gbicq'],
                                       $infos['gbwebsite'],
                                       $infos['gbtown'],
                                       $infos['gbmessage'],
                                       $_SESSION['client_ip']
                                       ) );
           
                $functions->redirect( 'index.php?mod=guestbook&action=show', 'metatag|newsite', 3, $lang->t( 'The entry was added successfully to the guestbook.' ) );
            }
        }
        else
        {
            /**
             * Set Error: User did not fill any fields
             */
            $errors['no_infos'] = 1;
        }
        

        // Output
        $tpl->assign( 'message_errors'  , $errors );
        $this->output .= $tpl->fetch( 'guestbook/show.tpl' );
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