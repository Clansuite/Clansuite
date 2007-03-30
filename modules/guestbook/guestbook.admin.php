<?php
   /**
    * Clansuite - just an E-Sport CMS
    * Jens-Andre Koch, Florian Wolf � 2005-2007
    * http://www.clansuite.com/
    *
    * File:         guestbook.module.php
    * Requires:     PHP 5.1.4+
    *
    * Purpose:      Clansuite Module Class - Guestbook Admin
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
 * This is the Clansuite Module Class - Guestbook Admin
 *
 * @author     Jens-Andre Koch   <vain@clansuite.com>
 * @copyright  Jens-Andre Koch (2005-$LastChangedDate$)
 * @since      Class available since Release 0.1
 *
 * @package     clansuite
 * @category    module
 * @subpackage  guestbook_admin
 */
class module_guestbook_admin
{
    public $output              = '';
    public $additional_head     = '';
    public $suppress_wrapper    = '';

    /**
     * First function to run - switches between $_REQUEST['action'] Vars to the functions
     * Loads necessary language files
     */

    function auto_run()
    {
        global $lang,$trail;
        $params = func_get_args();

        // Set Pagetitle and Breadcrumbs
        $trail->addStep($lang->t('Admin'), '/index.php?mod=admin');
        $trail->addStep($lang->t('Guestbook'), '/index.php?mod=guestbook&amp;sub=admin');

        switch ($_REQUEST['action'])
        {
            default:
            case 'show':
                $trail->addStep($lang->t('Show'), '/index.php?mod=guestbook&amp;sub=admin&amp;action=show');
                $this->show();
                break;

            case 'add_admincomment':
                $trail->addStep($lang->t('Add Admincomment'), '/index.php?mod=guestbook&amp;sub=admin&amp;action=add_admincomment');
                $this->add_admincomment();
                break;


            case 'delete':
                $this->delete();
                break;

        }

        return array( 'OUTPUT'          => $this->output,
                      'ADDITIONAL_HEAD' => $this->additional_head,
                      'SUPPRESS_WRAPPER'=> $this->suppress_wrapper );
    }

    /**
     * Show the entrance - welcome message etc.
     */
    function show()
    {
        global $cfg, $db, $tpl, $error, $lang, $functions, $security, $input, $perms;

        // Smarty Pagination load and init
        require( ROOT_CORE . '/smarty/SmartyPaginate.class.php');
        // required connect
        SmartyPaginate::connect();
        // set URL
        SmartyPaginate::setUrl('index.php?mod=guestbook&sub=admin&action=show');
        SmartyPaginate::setUrlVar('page');
        // set items per page
        SmartyPaginate::setLimit(20);

         // SmartyColumnSort -- Easy sorting of html table columns.
        require( ROOT_CORE . '/smarty/SmartyColumnSort.class.php');
        // A list of database columns to use in the table.
        $columns = array( 'gb_id', 'gb_added');
        // Create the columnsort object
        $columnsort = &new SmartyColumnSort($columns);
        // And set the the default sort column and order.
        $columnsort->setDefault('gb_added', 'desc');
        // Get sort order from columnsort
        $sortorder = $columnsort->sortOrder(); // Returns 'name ASC' as default

        // $newsarchiv = newsentries mit nick und category
        $stmt = $db->prepare('SELECT * FROM ' . DB_PREFIX .'guestbook
                              ORDER BY '. $sortorder .' LIMIT ?,?');

        $stmt->bindParam(1, SmartyPaginate::getCurrentIndex(), PDO::PARAM_INT );
        $stmt->bindParam(2, SmartyPaginate::getLimit(), PDO::PARAM_INT );
        $stmt->execute();
        $guestbook_entries = $stmt->fetchAll(PDO::FETCH_NAMED);

        // Get Number of Rows
        $rows = $db->prepare('SELECT COUNT(*) FROM '. DB_PREFIX .'guestbook');
        $rows->execute();
        $count = $rows->fetch(PDO::FETCH_NUM);
        // DEBUG - show total numbers of last Select
        // echo 'Found Rows: ' . $count;

        // Finally: assign total number of rows to SmartyPaginate
        SmartyPaginate::setTotal($count[0]);
        // assign the {$paginate} to $tpl (smarty var)
        SmartyPaginate::assign($tpl);

            // give $newslist array to Smarty for template output
        $tpl->assign('guestbook', $guestbook_entries);

        /**
         * Handle the output - $lang-t() translates the text.
         */
        $this->output = $tpl->fetch('guestbook/show_admin.tpl');

    }

    function add_admincomment()
    {
        global $tpl, $db, $functions, $input, $lang;

        /**
         * Incoming vars
         */
        $submit     = $_POST['submit'];
        $info       = isset($_POST['info']) ? $_POST['info'] : array();
        $gb_id      = isset($_GET['id']) ? (int)$_GET['id'] : (int)$_POST['info']['gb_id'];

        /**
         * @desc Insert on submit, no error
         */
        if ( !empty( $submit ) && count($errors) == 0 )
        {
             /**
             * Set Error: admin comment
             */
            if( empty($info['gb_admincomment']) ) $errors['fill_form']    = 1;

            /**
             * @desc Insert the area into the DB
             */
              echo 'info';    var_dump($info);
            $stmt = $db->prepare( 'UPDATE ' . DB_PREFIX . 'guestbook SET gb_admincomment = ? WHERE gb_id = ?');
            $stmt->execute( array ( $info['gb_admincomment'], $info['gb_id'] ) );

            /**
             * @desc Redirect...
             */
            $functions->redirect( 'index.php?mod=guestbook&sub=admin&action=show', 'metatag|newsite', 3, $lang->t( 'The admincomment added.' ), 'admin' );
        }
        else
        {
            // submit is empty
            // so attach gb_id from url to the first output of the add_admincomment.tpl
             $info['gb_id'] = $gb_id;
        }

        // Output
        $tpl->assign( 'errors'  , $errors );
        $tpl->assign( 'info'    , $info);
        $this->output .= $tpl->fetch( 'guestbook/add_admincomment.tpl' );
    }

    function delete()
    {
        global $db, $functions, $input, $lang;

        // Init
        $submit     = $_POST['submit'];
        $confirm    = $_POST['confirm'];
        $abort      = $_POST['abort'];
        $ids        = isset($_POST['ids'])      ? $_POST['ids'] : array();
        $ids        = isset($_POST['confirm'])  ? unserialize(urldecode($_GET['ids'])) : $ids;
        $delete     = isset($_POST['delete'])   ? $_POST['delete'] : array();
        $delete     = isset($_POST['confirm'])  ? unserialize(urldecode($_GET['delete'])) : $delete;

        echo 'to delete :' . count($delete);
        var_dump($delete);

        echo 'ids:';
        var_dump($ids);

        // Check, if there is a delete request
        if ( count($delete) < 1 )
        {
            $functions->redirect( 'index.php?mod=guestbook&sub=admin&action=show', 'metatag|newsite', 3, $lang->t( 'Aborted ! So there were no guestbook entries selected to delete!  ' ), 'admin' );
        }

        // Abort...
        if ( isset( $_POST['abort'] ) )
        {
            $functions->redirect( 'index.php?mod=guestbook&sub=admin&action=show' );
        }

        // Create the select to fetch (the Entries to delete) from DB
        // to have more infos to ask and decide on deletion
        $select = 'SELECT gb_id, gb_nick, gb_text FROM ' . DB_PREFIX . 'guestbook WHERE ';
        foreach ( $delete as $key => $id )
        {
            $select .= 'gb_id = ' . $id . ' OR ';
        }
        // code by xsign
        // @todo explain reason for settings this: [OR user_id = -1000]
        $select .= 'gb_id = -1000';

        // prepare and execute the constructed select
        $stmt = $db->prepare( $select );
        $stmt->execute();
        while( $result = $stmt->fetch(PDO::FETCH_ASSOC) )
        {
            if( in_array( $result['gb_id'], $delete  ) )
            {
                $names = '<br /># ' . $result['gb_id'] . ' by ' . $result['gb_nick'] . ' <b>' .  $result['gb_text'] . '</b>';
            }
            $all_gb_entries_to_delete[] = $result;
        }

        // Delete Groups
        foreach( $all_gb_entries_to_delete as $key => $value )
        {
            if ( count ( $delete ) > 0 )
            {
                if ( in_array( $value['gb_id'], $ids ) )
                {
                    $d = in_array( $value['gb_id'], $delete  ) ? 1 : 0;
                    if ( !isset ( $_POST['confirm'] ) )
                    {
                        $functions->redirect( 'index.php?mod=guestbook&sub=admin&action=delete&ids=' . urlencode(serialize($ids)) . '&delete=' . urlencode(serialize($delete)), 'confirm', 3, $lang->t( 'You have selected the following guestbook entry(ies) to delete: ' . $names ), 'admin' );
                    }
                    else
                    {
                        if ( $d == 1 )
                        {
                            $stmt = $db->prepare( 'DELETE FROM ' . DB_PREFIX . 'guestbook WHERE gb_id = ?' );
                            $stmt->execute( array($value['gb_id']) );
                        }
                    }
                }
            }
        }

        // Redirect to main
        $functions->redirect( 'index.php?mod=guestbook&sub=admin&action=show', 'metatag|newsite', 3, $lang->t( 'The selected guestbook entr(y/ies) were deleted.' ), 'admin' );

    }

}

?>