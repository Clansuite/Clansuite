<?php
/**
* Modulename:   messaging
* Description:  The messaging module of clansuite
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
* @author     Florian Wolf, Jens-André Koch
* @copyright  ClanSuite Group
* @license    GPL v2
* @version    SVN: $Id$
* @link       http://www.clansuite.com
*/

// Security Handler
if (!defined('IN_CS')) { die('You are not allowed to view this page.' ); }

// Begin of class module_messaging
class module_messaging
{
    public $output          = '';
    public $additional_head = '';
    public $suppress_wrapper= '';

    /**
    * @desc General Function Hook of messaging-Modul
    *
    * 1. Set Pagetitle and Breadcrumbs
    * 2. $_REQUEST['action'] determines the switch
    * 3. function title is added to page title, to complete the title
    * 4. switch-functions are called
    *
    * @return: array ( OUTPUT, ADDITIONAL_HEAD, SUPPRESS_WRAPPER )
    *
    */

    function auto_run()
    {

        global $lang, $trail, $perms;

        // Check permissions
        $perms->check('use_messaging_system');

        $params = func_get_args();

        // Set Pagetitle and Breadcrumbs
        $trail->addStep($lang->t('Messaging'), '/index.php?mod=messaging');

        //
        switch ($_REQUEST['action'])
        {

            default:
            case 'show':
                $trail->addStep($lang->t('Incoming messages'), '/index.php?mod=messaging&amp;action=show');
                $this->show_incoming();
                break;

            case 'show_outgoing':
                $trail->addStep($lang->t('Outgoing messages'), '/index.php?mod=messaging&amp;action=show_outgoing');
                $this->show_outgoing();
                break;

            case 'read':
                $trail->addStep($lang->t('Read messages'), '/index.php?mod=messaging&amp;action=read');
                $this->read();
                break;

            case 'mark':
                $trail->addStep($lang->t('Mark messages'), '/index.php?mod=messaging&amp;action=mark');
                $this->mark();
                break;

            case 'multiple_mark_read':
                $trail->addStep($lang->t('Multiple mark messages as read'), '/index.php?mod=messaging&amp;action=multiple_mark_read');
                $this->multiple_mark_read();
                break;

            case 'multiple_mark_unread':
                $trail->addStep($lang->t('Multiple mark messages as unread'), '/index.php?mod=messaging&amp;action=multiple_mark_unread');
                $this->multiple_mark_unread();
                break;

            case 'delete':
                $trail->addStep($lang->t('Delete messages'), '/index.php?mod=messaging&amp;action=delete');
                $this->delete();
                break;

            case 'get_back':
                $trail->addStep($lang->t('Get messages back'), '/index.php?mod=messaging&amp;action=get_back');
                $this->get_back();
                break;

            case 'get_new_messages_count':
                $this->output .= call_user_func_array( array( $this, 'get_new_messages_count' ), $params );
                break;

            case 'create':
                $trail->addStep($lang->t('Create'), '/index.php?mod=messaging&amp;action=create');
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
    * @desc Function: Get the number of new messages
    */
    function get_new_messages_count()
    {
        global $cfg, $db, $tpl, $error, $lang, $functions, $security, $input, $perms;

        // Get all incoming messages
        $stmt = $db->prepare('SELECT COUNT(*) FROM ' . DB_PREFIX . 'messages WHERE `to` = ? AND `read` = 0');
        $stmt->execute( array( $_SESSION['user']['user_id'] ) );
        $messages = $stmt->fetch(PDO::FETCH_NUM);

        return $messages[0];
    }

    /**
    * @desc Function: Show all incoming messages
    */
    function show_incoming()
    {
        global $cfg, $db, $tpl, $error, $lang, $functions, $security, $input, $perms;

        // Get all incoming messages
        $stmt = $db->prepare('SELECT `message_id`,`headline`,`from`,`to`,`message`,`timestamp`,`read` FROM ' . DB_PREFIX . 'messages WHERE `to` = ? ORDER BY `timestamp` DESC');
        $stmt->execute( array( $_SESSION['user']['user_id'] ) );
        $messages = $stmt->fetchAll(PDO::FETCH_NAMED);

        // BBCode load class and init
        require_once( ROOT_CORE . '/bbcode.class.php' );
        $bbcode = new bbcode();
        foreach( $messages as $key => $value )
        {
            $messages[$key]['message'] = $bbcode->parse($messages[$key]['message']);
        }

        // Output
        $tpl->assign( 'menu'    , $this->menu() );
        $tpl->assign( 'messages', $messages );
        $this->output .= $tpl->fetch('messaging/show_incoming.tpl');
    }

    /**
    * @desc Function: Show all outgoing messages
    */
    function show_outgoing()
    {
        global $cfg, $db, $tpl, $error, $lang, $functions, $security, $input, $perms;

        // Get all incoming messages
        $stmt = $db->prepare('SELECT `message_id`,`headline`,`from`,`to`,`message`,`timestamp`,`read` FROM ' . DB_PREFIX . 'messages WHERE `from` = ?');
        $stmt->execute( array( $_SESSION['user']['user_id'] ) );
        $messages = $stmt->fetchAll(PDO::FETCH_NAMED);

        // BBCode load class and init
        require_once( ROOT_CORE . '/bbcode.class.php' );
        $bbcode = new bbcode();
        foreach( $messages as $key => $value )
        {
            $messages[$key]['message'] = $bbcode->parse($messages[$key]['message']);
        }

        // Output
        $tpl->assign( 'menu'    , $this->menu() );
        $tpl->assign( 'messages', $messages );
        $this->output .= $tpl->fetch('messaging/show_outgoing.tpl');
    }

    /**
    * @desc Function: Read a message
    */
    function read()
    {
        global $cfg, $db, $tpl, $error, $lang, $functions, $security, $input, $perms;

        // Incoming Vars
        $message_id = $_GET['id'];

        // Get message
        $stmt = $db->prepare('SELECT `message_id`,`headline`,`from`,`to`,`message`,`timestamp`,`read` FROM ' . DB_PREFIX . 'messages WHERE `message_id` = ? AND `to` = ?');
        $stmt->execute( array( $message_id, $_SESSION['user']['user_id'] ) );
        $message = $stmt->fetch(PDO::FETCH_NAMED);

        if( is_array( $message ) )
        {
            // Update -> message is now marked as read!
            $stmt = $db->prepare('UPDATE '. DB_PREFIX .'messages SET `read` = 1 WHERE `message_id` = ?');
            $stmt->execute( array( $message_id ) );

            // Get user data
            $stmt = $db->prepare('SELECT nick FROM '. DB_PREFIX .'users WHERE user_id = ?');
            $stmt->execute( array( $message['from'] ) );
            $result = $stmt->fetch(PDO::FETCH_NAMED);
            $message['from_user'] = $result['nick'];

            // BBCode load class and init
            require_once( ROOT_CORE . '/bbcode.class.php' );
            $bbcode = new bbcode();
            $message['bb_message'] = $message['message'];
            $message['message'] = $bbcode->parse($message['message']);
        }
        else
        {
            $functions->redirect( 'index.php?mod=messaging&action=show', 'metatag|newsite', 3, $lang->t( 'No message to read.' ) );
        }

        // Output
        $tpl->assign( 'menu'    , $this->menu() );
        $tpl->assign( 'message' , $message );
        $this->output .= $tpl->fetch('messaging/read.tpl');
    }

    /**
    * @desc Function: Mark messages as read
    */
    function multiple_mark_read()
    {
        global $cfg, $db, $tpl, $error, $lang, $functions, $security, $input, $perms;

        // Incoming Vars
        $message_id = (int) $_GET['id'];
        $infos      = isset($_POST['infos']) ? $_POST['infos'] : array();
        $message_ids= array();

        if( count($infos) > 0 )
        {
            // Get message
            $stmt = $db->prepare('SELECT `message_id`,`headline`,`from`,`to`,`message`,`timestamp`,`read` FROM ' . DB_PREFIX . 'messages WHERE `message_id` = ? AND `to` = ?');
            foreach( $infos['message_id'] as $key => $value )
            {
                $stmt->execute( array( $value, $_SESSION['user']['user_id'] ) );
                $message = $stmt->fetch(PDO::FETCH_NAMED);
                if( is_array( $message ) )
                {
                    $message_ids[] = $message['message_id'];
                }
            }

            if( count( $message_ids ) > 0 )
            {
                // Update -> message is now marked as read!
                $stmt = $db->prepare('UPDATE '. DB_PREFIX .'messages SET `read` = 1 WHERE `message_id` = ?');
                foreach( $message_ids as $key => $value )
                {
                    $stmt->execute( array( $value ) );
                }

                $functions->redirect( 'index.php?mod=messaging&action=show', 'metatag|newsite', 2, $lang->t( 'Done.' ) );
            }
            else
            {
                $functions->redirect( 'index.php?mod=messaging&action=show', 'metatag|newsite', 3, $lang->t( 'No messages to mark.' ) );
            }
        }
        else
        {
            $functions->redirect( 'index.php?mod=messaging&action=show', 'metatag|newsite', 3, $lang->t( 'Error...' ) );
        }
    }

    /**
    * @desc Function: Mark messages as read
    */
    function multiple_mark_unread()
    {
        global $cfg, $db, $tpl, $error, $lang, $functions, $security, $input, $perms;

        // Incoming Vars
        $message_id = (int) $_GET['id'];
        $infos      = isset($_POST['infos']) ? $_POST['infos'] : array();
        $message_ids= array();

        if( count($infos) > 0 )
        {
            // Get message
            $stmt = $db->prepare('SELECT `message_id`,`headline`,`from`,`to`,`message`,`timestamp`,`read` FROM ' . DB_PREFIX . 'messages WHERE `message_id` = ? AND `to` = ?');
            foreach( $infos['message_id'] as $key => $value )
            {
                $stmt->execute( array( $value, $_SESSION['user']['user_id'] ) );
                $message = $stmt->fetch(PDO::FETCH_NAMED);
                if( is_array( $message ) )
                {
                    $message_ids[] = $message['message_id'];
                }
            }

            if( count( $message_ids ) > 0 )
            {
                // Update -> message is now marked as read!
                $stmt = $db->prepare('UPDATE '. DB_PREFIX .'messages SET `read` = 0 WHERE `message_id` = ?');
                foreach( $message_ids as $key => $value )
                {
                    $stmt->execute( array( $value ) );
                }

                $functions->redirect( 'index.php?mod=messaging&action=show', 'metatag|newsite', 2, $lang->t( 'Done.' ) );
            }
            else
            {
                $functions->redirect( 'index.php?mod=messaging&action=show', 'metatag|newsite', 3, $lang->t( 'No messages to mark.' ) );
            }
        }
        else
        {
            $functions->redirect( 'index.php?mod=messaging&action=show', 'metatag|newsite', 3, $lang->t( 'Error...' ) );
        }
    }

    /**
    * @desc Function: Mark a message read or unread
    */
    function mark()
    {
        global $cfg, $db, $tpl, $error, $lang, $functions, $security, $input, $perms;

        // Incoming Vars
        $message_id = (int) $_GET['id'];
        $read       = (int) $_GET['read'];

        // Get message
        $stmt = $db->prepare('SELECT `message_id`,`headline`,`from`,`to`,`message`,`timestamp`,`read` FROM ' . DB_PREFIX . 'messages WHERE `message_id` = ? AND `to` = ?');
        $stmt->execute( array( $message_id, $_SESSION['user']['user_id'] ) );
        $message = $stmt->fetch(PDO::FETCH_NAMED);

        if( is_array( $message ) )
        {
            // Update -> message is now marked as read!
            $stmt = $db->prepare('UPDATE '. DB_PREFIX .'messages SET `read` = ? WHERE `message_id` = ?');
            $stmt->execute( array( $read, $message_id ) );

            $functions->redirect( 'index.php?mod=messaging&action=show', 'metatag|newsite', 2, $lang->t( 'Done.' ) );
        }
        else
        {
            $functions->redirect( 'index.php?mod=messaging&action=show', 'metatag|newsite', 3, $lang->t( 'No message to mark.' ) );
        }
    }

    /**
    * @desc Function: Delete a message or get it back
    */
    function delete()
    {
        global $cfg, $db, $tpl, $error, $lang, $functions, $security, $input, $perms;

        // Incoming Vars & declarations
        $message_id = isset($_GET['id']) ? $_GET['id'] : 0;
        $infos      = isset($_POST['infos']) ? $_POST['infos'] : array();
        $message_ids= array();

        // Delete single message
        if( count($infos) == 0 )
        {
            // Get message to delete
            $stmt = $db->prepare('SELECT `message_id`,`headline`,`from`,`to`,`message`,`timestamp`,`read` FROM ' . DB_PREFIX . 'messages WHERE `message_id` = ? AND `to` = ?');
            $stmt->execute( array( $message_id, $_SESSION['user']['user_id'] ) );
            $message = $stmt->fetch(PDO::FETCH_NAMED);

            if( is_array( $message ) )
            {
                // Delete message from DB
                $stmt = $db->prepare('DELETE FROM '. DB_PREFIX .'messages WHERE `message_id` = ?');
                $stmt->execute( array( $message_id ) );

                // All done
                $functions->redirect( 'index.php?mod=messaging&action=show', 'metatag|newsite', 2, $lang->t( 'Done.' ) );
            }
            else
            {
                // Error
                $functions->redirect( 'index.php?mod=messaging&action=show', 'metatag|newsite', 3, $lang->t( 'No message to delete.' ) );
            }
        }
        /*
        * @desc Delete multiple messages
        */
        else
        {
            // Get message ids to delete
            $stmt = $db->prepare('SELECT `message_id`,`headline`,`from`,`to`,`message`,`timestamp`,`read` FROM ' . DB_PREFIX . 'messages WHERE `message_id` = ? AND `to` = ?');
            foreach( $infos['message_id'] as $key => $value )
            {
                $stmt->execute( array( $value, $_SESSION['user']['user_id'] ) );
                $message = $stmt->fetch(PDO::FETCH_NAMED);
                if( is_array( $message ) )
                {
                    $message_ids[] = $message['message_id'];
                }
            }

            if( count( $message_ids ) != 0 )
            {
                // Delete all messages
                $stmt = $db->prepare('DELETE FROM '. DB_PREFIX .'messages WHERE `message_id` = ?');
                foreach( $message_ids as $key => $value )
                {
                    $stmt->execute( array( $value ) );
                }

                // All done
                $functions->redirect( 'index.php?mod=messaging&action=show', 'metatag|newsite', 2, $lang->t( 'Done.' ) );
            }
            else
            {
                // Error
                $functions->redirect( 'index.php?mod=messaging&action=show', 'metatag|newsite', 3, $lang->t( 'No messages to delete.' ) );
            }
        }
    }

    /**
    * @desc Function: Get message(s) back
    */
    function get_back()
    {
        global $cfg, $db, $tpl, $error, $lang, $functions, $security, $input, $perms;

        // Incoming Vars & declarations
        $message_id = isset($_GET['id']) ? $_GET['id'] : 0;
        $infos      = isset($_POST['infos']) ? $_POST['infos'] : array();
        $message_ids= array();

        // Delete single message
        if( count($infos) == 0 )
        {
            // Get message for bringing back
            $stmt = $db->prepare('SELECT `message_id`,`headline`,`from`,`to`,`message`,`timestamp`,`read` FROM ' . DB_PREFIX . 'messages WHERE `message_id` = ? AND `from` = ? AND `read` = 0');
            $stmt->execute( array( $message_id, $_SESSION['user']['user_id'] ) );
            $message = $stmt->fetch(PDO::FETCH_NAMED);

            if( is_array( $message ) )
            {
                // Delete message from DB
                $stmt = $db->prepare('DELETE FROM '. DB_PREFIX .'messages WHERE `message_id` = ?');
                $stmt->execute( array( $message_id ) );

                // All done
                $functions->redirect( 'index.php?mod=messaging&action=show_outgoing', 'metatag|newsite', 2, $lang->t( 'Done.' ) );
            }
            else
            {
                // Error
                $functions->redirect( 'index.php?mod=messaging&action=show_outgoing', 'metatag|newsite', 3, $lang->t( 'No message to get back.' ) );
            }
        }
        /*
        * @desc Get back multiple messages
        */
        else
        {
            // Get message ids to bring back
            $stmt = $db->prepare('SELECT `message_id`,`headline`,`from`,`to`,`message`,`timestamp`,`read` FROM ' . DB_PREFIX . 'messages WHERE `message_id` = ? AND `from` = ? AND `read` = 0');
            foreach( $infos['message_id'] as $key => $value )
            {
                $stmt->execute( array( $value, $_SESSION['user']['user_id'] ) );
                $message = $stmt->fetch(PDO::FETCH_NAMED);
                if( is_array( $message ) )
                {
                    $message_ids[] = $message['message_id'];
                }
            }

            if( count( $message_ids ) != 0 )
            {
                // Delete all messages
                $stmt = $db->prepare('DELETE FROM '. DB_PREFIX .'messages WHERE `message_id` = ?');
                foreach( $message_ids as $key => $value )
                {
                    $stmt->execute( array( $value ) );
                }

                // All done
                $functions->redirect( 'index.php?mod=messaging&action=show_outgoing', 'metatag|newsite', 2, $lang->t( 'Done.' ) );
            }
            else
            {
                // Error
                $functions->redirect( 'index.php?mod=messaging&action=show_outgoing', 'metatag|newsite', 3, $lang->t( 'No messages to get back.' ) );
            }
        }
    }
    /**
    * @desc Function: Create a new message
    */
    function create()
    {
        global $cfg, $db, $tpl, $error, $lang, $functions, $security, $input, $perms;

        // Incoming vars
        $submit         = isset($_POST['submit']) ? $_POST['submit'] : '';
        $infos          = isset($_POST['info']) ? $_POST['info'] : array();
        $result         = array();
        $reply_id       = isset($_GET['reply_id']) ? $_GET['reply_id'] : '';
        $message_infos  = array();

        if( !empty($submit) )
        {
            if( empty($infos['message']) )  $errors['no_message']   = 1;
            if( empty($infos['headline']) ) $errors['no_headline']  = 1;
            if( empty($infos['to']) )       $errors['no_users']     = 1;

            if( !empty($infos) )
            {
                if( count($errors) == 0 )
                {
                    // Check for user existence
                    $users = explode(';',$infos['to']);
                    $stmt = $db->prepare('SELECT user_id,nick FROM '. DB_PREFIX .'users WHERE nick = ?');
                    foreach( $users as $key => $value )
                    {
                        $stmt->execute( array( $value ) );
                        $user_check = $stmt->fetch(PDO::FETCH_NAMED);
                        if( is_array( $user_check ) )
                        {
                            array_push($result, $user_check);
                        }
                        else
                        {
                            $errors['users_not_found'] = 1;
                            $errors['users'] .= $value;
                        }
                    }

                    // Send to all existing users
                    if( count($result) > 0 )
                    {
                        // Insert data into DB
                        $stmt = $db->prepare('INSERT INTO '. DB_PREFIX .'messages SET `headline` = ?, `message` = ?, `to` = ?, `from` = ?, `timestamp` = ?, `read` = ?');
                        foreach( $result as $key => $value )
                        {
                            $stmt->execute( array( $infos['headline'],
                                                   $infos['message'],
                                                   $value['user_id'],
                                                   $_SESSION['user']['user_id'],
                                                   time(),
                                                   0 ) );
                        }
                        $functions->redirect( 'index.php?mod=messaging&action=show', 'metatag|newsite', 3, $lang->t( 'The message has been sent.' ) );
                    }
                    else
                    {
                        // Give error if one or more users don't exist
                        $errors['users_not_found'] = 1;
                    }
                }
            }
            else
            {
                $errors['no_infos'] = 1;
            }
        }
        else
        {
            if( !empty($reply_id) )
            {
                // Get message
                $stmt = $db->prepare('SELECT `message_id`,`headline`,`from`,`to`,`message`,`timestamp`,`read` FROM ' . DB_PREFIX . 'messages WHERE `message_id` = ? AND ( `from` = ? OR `to` = ? )');
                $stmt->execute( array( $reply_id, $_SESSION['user']['user_id'], $_SESSION['user']['user_id'] ) );
                $message_infos = $stmt->fetch(PDO::FETCH_NAMED);

                // Get user data
                $stmt = $db->prepare('SELECT nick FROM '. DB_PREFIX .'users WHERE user_id = ?');
                $stmt->execute( array( $message_infos['from'] ) );
                $result = $stmt->fetch(PDO::FETCH_NAMED);
                $message_infos['from_user'] = $result['nick'];
            }
        }

        // Output
        $tpl->assign( 'message_infos'   , $message_infos );
        $tpl->assign( 'message_errors'  , $errors );
        $tpl->assign( 'menu'            , $this->menu() );
        $this->output .= $tpl->fetch( 'messaging/create.tpl' );
        $this->suppress_wrapper = 1;
    }

    /**
    * @desc Generate menu
    */
    function menu()
    {
        global $db, $tpl;

        // Count incoming messages
        $stmt = $db->prepare('SELECT COUNT(*) FROM ' . DB_PREFIX .'messages WHERE `to` = ?');
        $stmt->execute( array( $_SESSION['user']['user_id'] ) );
        $incoming_count = $stmt->fetch(PDO::FETCH_NUM);

        // Count outgoing messages
        $stmt = $db->prepare('SELECT COUNT(*) FROM ' . DB_PREFIX .'messages WHERE `from` = ?');
        $stmt->execute( array( $_SESSION['user']['user_id'] ) );
        $outgoing_count = $stmt->fetch(PDO::FETCH_NUM);

        // Output
        $tpl->assign('incoming_count', $incoming_count[0]);
        $tpl->assign('outgoing_count', $outgoing_count[0]);
        return $tpl->fetch('messaging/menu.tpl');
    }
}
?>