<?php
   /**
    * Clansuite - just an E-Sport CMS
    * Jens-Andre Koch, Florian Wolf © 2005-2007
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

            case 'save_comment':
                $this->save_comment();
                break;

            case 'get_comment':
                $this->get_comment();
                break;

            case 'show_single':
                $this->show_single();
                break;

            case 'edit':
                $this->edit();
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
    * Show all guestbook entries and give the possibility to edit/delete
    *
    * @global $cfg
    * @global $db
    * @global $tpl
    * @global $error
    * @global $lang
    * @global $functions
    * @global $security
    * @global $input
    * @global $perms
    */
    function show()
    {
        global $cfg, $db, $tpl, $error, $lang, $functions, $security, $input, $perms;

        // Smarty Pagination load and init
        require( ROOT_CORE . '/smarty/SmartyPaginate.class.php');

        // set URL
        $SmartyPaginate->setUrl('index.php?mod=guestbook&sub=admin&action=show');
        $SmartyPaginate->setUrlVar('page');
        // set items per page
        $SmartyPaginate->setLimit(20);

         // SmartyColumnSort -- Easy sorting of html table columns.
        require( ROOT_CORE . '/smarty/SmartyColumnSort.class.php');
        // A list of database columns to use in the table.
        $columns = array( 'gb_id', 'gb_added', 'gb_nick', 'gb_email', 'gb_icq', 'gb_website', 'gb_town', 'gb_text', 'gb_admincomment', 'gb_ip');
        // Create the columnsort object
        $columnsort = &new SmartyColumnSort($columns);
        // And set the the default sort column and order.
        $columnsort->setDefault('gb_added', 'desc');
        // Get sort order from columnsort
        $sortorder = $columnsort->sortOrder(); // Returns 'name ASC' as default

        // read * db -> $guestbook_entries
        // db fields gb_id, gb_added, gb_nick, gb_email, gb_icq, gb_website, gb_town, gb_text, gb_admincomment, gb_ip
        $stmt = $db->prepare('SELECT * FROM ' . DB_PREFIX .'guestbook
                              ORDER BY '. $sortorder .' LIMIT ?,?');

        $stmt->bindParam(1, $SmartyPaginate->getCurrentIndex(), PDO::PARAM_INT );
        $stmt->bindParam(2, $SmartyPaginate->getLimit(), PDO::PARAM_INT );
        $stmt->execute();
        $guestbook_entries = $stmt->fetchAll(PDO::FETCH_NAMED);

        // Get Number of Rows
        $rows = $db->prepare('SELECT COUNT(*) FROM '. DB_PREFIX .'guestbook');
        $rows->execute();
        $count = $rows->fetch(PDO::FETCH_NUM);
        // DEBUG - show total numbers of last Select
        // echo 'Found Rows: ' . $count;

        // Finally: assign total number of rows to SmartyPaginate
        $SmartyPaginate->setTotal($count[0]);
        // assign the {$paginate} to $tpl (smarty var)
        $SmartyPaginate->assign($tpl);

        // Transform RAW text to BB-formatted Text
        require_once( ROOT_CORE . '/bbcode.class.php' );
        $bbcode = new bbcode();
        foreach( $guestbook_entries as $key => $value )
        {
            $guestbook_entries[$key]['gb_text'] = $bbcode->parse($value['gb_text']);
            $guestbook_entries[$key]['gb_comment'] = $bbcode->parse($value['gb_comment']);
        }

        // give $newslist array to Smarty for template output
        $tpl->assign('guestbook', $guestbook_entries);

        /**
         * Handle the output - $lang-t() translates the text.
         */
        $this->output = $tpl->fetch('guestbook/admin_show.tpl');

    }

    /**
    * AJAX request to save the comment
    * 1. save comment in raw with bbcodes on - into database
    * 2. return comment with formatted bbcode = raw to html-style
    *
    * @global $db
    * @global $tpl
    */
    function save_comment()
    {
        global $db, $tpl;
        /**
        * @desc Incoming Vars
        */
        $gb_id      = urldecode($_GET['id']);
        $comment    = urldecode($_POST['value']);

        /**
        * @desc Get comment from DB
        */
        $stmt = $db->prepare( 'SELECT gb_comment FROM ' . DB_PREFIX . 'guestbook
                               WHERE `gb_id` = ?' );
        $stmt->execute( array( $gb_id ) );
        $result = $stmt->fetch(PDO::FETCH_NAMED);

        // Add/Modify comment
        $stmt = $db->prepare( 'UPDATE ' . DB_PREFIX . 'guestbook
                               SET `gb_comment` = ? WHERE `gb_id` = ?' );
        $stmt->execute( array( $comment, $gb_id ) );

        // Transform RAW text to BB-formatted Text
        require_once( ROOT_CORE . '/bbcode.class.php' );
        $bbcode = new bbcode();
        $parsed_comment = $bbcode->parse($comment);

        $this->output .= $parsed_comment;
        $this->suppress_wrapper = 1;
    }

    /**
    * AJAX request to get the helptext in raw from database
    *
    * @global $db
    */
    function get_comment()
    {
        global $db;

        /**
        * @desc Incoming Vars
        */
        $gb_id = $_GET['id'];

        /**
        * @desc Get comment from DB
        */
        $stmt = $db->prepare( 'SELECT gb_comment FROM ' . DB_PREFIX . 'guestbook
                               WHERE `gb_id` = ?' );
        $stmt->execute( array( $gb_id ) );
        $result = $stmt->fetch(PDO::FETCH_NAMED);

        // Helptext in Raw from Database
        $this->output = $result['gb_comment'];
        $this->suppress_wrapper = true;
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
    function edit()
    {
        global $db, $tpl, $functions, $lang, $perms;

        // Permissions check
        if( $perms->check('cc_edit_gb', 'no_redirect') == true )
        {

            // Incoming Vars
            $infos  = $_POST['infos'];
            $submit = isset($_POST['submit']) ? $_POST['submit'] : '';
            $gb_id  = isset($_GET['id']) ? $_GET['id'] : 0;
            $front  = isset($_GET['front']) ? $_GET['front'] : 0;

            if( !empty( $submit ) )
            {
                // Add/Modify comment
                $stmt = $db->prepare( 'UPDATE ' . DB_PREFIX . 'guestbook
                                       SET  `gb_icq` = :gb_icq,
                                            `gb_nick` = :gb_nick,
                                            `gb_email` = :gb_email,
                                            `gb_website` = :gb_website,
                                            `gb_town` = :gb_town,
                                            `gb_text` = :gb_text,
                                            `gb_ip` = :gb_ip,
                                            `gb_comment` = :gb_comment
                                       WHERE `gb_id` = :gb_id' );
                $stmt->execute( $infos );

                if( $infos['front'] == 1 )
                {
                    // Redirect on finish
                    $functions->redirect( 'index.php?mod=guestbook&action=show', 'metatag|newsite', 3, $lang->t( 'The guestbook entry has been edited.' ) );
                }
                else
                {
                    // Redirect on finish
                    $functions->redirect( 'index.php?mod=guestbook&sub=admin&action=show', 'metatag|newsite', 3, $lang->t( 'The guestbook entry has been edited.' ), 'admin' );
                }

            }

            $stmt = $db->prepare('SELECT * FROM ' . DB_PREFIX . 'guestbook WHERE gb_id = ?');
            $stmt->execute( array( $gb_id ) );
            $result = $stmt->fetch( PDO::FETCH_NAMED );

            $tpl->assign( 'infos', $result);
            $tpl->assign( 'front', $front);
            $this->output = $tpl->fetch('guestbook/admin_edit.tpl');
        }
        else
        {
            $this->output = $lang->t('You do not have sufficient rights.') . '<br /><input class="ButtonRed" type="button" onclick="Dialog.okCallback()" value="Abort"/>';
        }
        $this->suppress_wrapper = 1;
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

    /**
    * Show a single news
    *
    * @global $db
    * @global $lang
    * @global $functions
    * @global $input
    * @global $tpl
    * @global $cfg
    * @global $perms
    */
    function show_single()
    {
        global $db, $functions, $input, $lang, $tpl, $cfg, $perms;

        // Incoming vars
        $gb_id = $_GET['id'];

        if( $perms->check('cc_view_gb', 'no_redirect') == true )
        {
            $stmt = $db->prepare('SELECT * FROM ' . DB_PREFIX . 'guestbook WHERE gb_id = ?');
            $stmt->execute( array( $gb_id ) );
            $result = $stmt->fetch( PDO::FETCH_NAMED );

            $tpl->assign( 'infos', $result);
            $this->output = $tpl->fetch('guestbook/admin_edit.tpl');
        }
        else
        {
            $this->output = $lang->t('You are not allowed to view single news.');
        }
        $this->suppress_wrapper = 1;
    }
}