<?php
   /**
    * Clansuite - just an E-Sport CMS
    * Jens-Andre Koch, Florian Wolf © 2005-2007
    * http://www.clansuite.com/
    *
    * File:         general.module.php
    * Requires:     PHP 5.1.4+
    *
    * Purpose:      Clansuite Module Class - general
    *               The submodule for the general profile data
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
 * This is the Clansuite Module Class - module_account_general
 *
 * Description:  The submodule for the general profile data
 *
 * @author     Florian Wolf, Jens-AndrÃ© Koch
 * @copyright  ClanSuite Group
 * @link       http://www.clansuite.com
 * @since      Class available since Release 0.1
 *
 * @package     clansuite
 * @category    module
 * @subpackage  module_account_general
 */
class module_account_general
{
    public $output          = '';
    public $additional_head = '';
    public $suppress_wrapper= '';

    /**
     * @desc General Function Hook of general-Modul
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
        $trail->addStep($lang->t('General Prfile Data'), 'index.php?mod=general');

        //
        switch ($_REQUEST['action'])
        {

            default:
            case 'show':
                $trail->addStep($lang->t('Show'), 'index.php?mod=general&amp;action=show');
                $this->show();
                break;

            case 'edit':
                $this->edit();
                break;

            case 'show_avatar':
                $this->show_avatar();
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
    * Show the general profile stuff
    *
    * @global $db
    * @global $lang
    * @global $tpl
    */
    function show()
    {
        global  $db, $lang, $tpl;

        // Incoming Vars
        $user_id = isset($_GET['id']) ? $_GET['id'] : $_SESSION['user']['user_id'];

        // DB Select
        $stmt = $db->prepare('SELECT p.*,u.nick,i.* FROM '. DB_PREFIX .'profiles_general AS p LEFT JOIN '. DB_PREFIX .'users AS u ON u.user_id = p.user_id LEFT JOIN '. DB_PREFIX .'images AS i ON i.image_id = p.image_id WHERE u.user_id = ?');
        $stmt->execute( array( $user_id ) );
        $info = $stmt->fetch(PDO::FETCH_NAMED);

        if( count($info) > 0 )
        {
            // BBCode load class and init
            require_once( ROOT_CORE . '/bbcode.class.php' );
            $bbcode = new bbcode();
            $info['custom_text'] = $bbcode->parse($info['custom_text']);

            // Gender
            if( $info['gender'] == 'female' )
            {
                $info['gender'] = $lang->t('Female');
            }
            else if( $info['gender'] == 'male' )
            {
                $info['gender'] = $lang->t('Male');
            }

            foreach( $info as $key => $value )
            {
                $info[$key] = empty($value) ? '<span class="not_specified">' . $lang->t('not specified') . '</span>' : $value;
                if( $key == 'country' && empty($value) )
                {
                    $info[$key] = 'not_specified';
                }
            }

            // Output
            $tpl->assign( 'info' , $info );
            $this->output .= $tpl->fetch('account/profile/general.tpl');
            $this->suppress_wrapper = 1;
        }
        else
        {
            $functions->redirect( 'index.php', 'metatag|newsite', 3, $lang->t( 'No such user!' ) );
        }
    }

    /**
    * Edit the general profile stuff
    *
    * @global $db
    * @global $lang
    * @global $tpl
    * @global $functions
    * @global $security
    * @global $cfg
    * @global $error
    * @global $input
    */
    function edit()
    {
        global  $db, $lang, $tpl, $functions, $security, $cfg, $error, $input;

        // Incoming vars
        $submit     = $_POST['submit'];
        $profile    = isset($_POST['profile']) ? $_POST['profile'] : array();
        $avatar     = isset($_FILES['avatar']) ? $_FILES['avatar'] : $_POST['avatar'];

        if( !empty($submit) )
        {
            // Profile Update
            $profile['shaped_birthday'] = strtotime( $profile['birthday']['day'] .'-'. $profile['birthday']['month'] . '-' . $profile['birthday']['year'] );
            $profile['shaped_timestamp'] = strtotime( $profile['timestamp']['day'] .'-'. $profile['timestamp']['month'] . '-' . $profile['timestamp']['year'] );
            $profile['user_id'] = $_SESSION['user']['user_id'];

            $profile['avatar_type'] == ( $profile['avatar_type'] == 'url' OR $profile['avatar_type'] == 'upload' ) ? $profile['avatar_type'] : $security->intruder_alert();

            // Get the image
            $stmt = $db->prepare('SELECT i.image_id FROM ' . DB_PREFIX .'profiles_general p,' . DB_PREFIX .'images i WHERE i.image_id = p.image_id AND p.user_id = ?');
            $stmt->execute( array($_SESSION['user']['user_id'] ) );
            $image_result = $stmt->fetch(PDO::FETCH_NAMED);

            // all valid extensions
            $extensions = array('jpg', 'gif');

            if( is_array($image_result) )
            {
                $image_id = $image_result['image_id'];

                if( $profile['avatar_type'] == 'upload' )
                {
                    // delete old image before proceed
                    foreach( $extensions as $key => $value )
                    {
                        if( file_exists( ROOT_UPLOAD .'/images/avatars/' . $profile['user_id'] . '.' . $value ) )
                            unlink( ROOT_UPLOAD .'/images/avatars/' . $profile['user_id'] . '.' . $value );
                    }

                    require( ROOT_CORE . '/upload.class.php' );
                    $upload = new upload( $avatar, ROOT_UPLOAD .'/images/avatars/', $profile['user_id'], $extensions );
                    if( $upload->done )
                    {
                        $stmt = $db->prepare('UPDATE ' . DB_PREFIX . 'images SET location = ?, type = ? WHERE user_id = ? AND image_id = ?');
                        $stmt->execute( array( 'images/avatars/' . $upload->filename, 'upload', $profile['user_id'], $image_id ) );
                    }
                    else
                    {
                        $error->show('Upload Failure', 'There has been an upload failure!', 1, 'index.php?mod=account&sub=profile&action=show');
                    }
                }
                else
                {
                    if( $input->check( $avatar, 'is_url' ) )
                    {
                        $stmt = $db->prepare('UPDATE ' . DB_PREFIX . 'images SET location = ?, type = ? WHERE user_id = ? AND image_id = ?');
                        $stmt->execute( array( $avatar, 'url', $profile['user_id'], $image_id ) );
                    }
                    else
                    {
                        $error->show('URL Failure', 'This is not a valid URL!', 1, 'index.php?mod=account&sub=profile&action=show');
                    }
                }
            }
            else
            {
                if( $profile['avatar_type'] == 'upload' )
                {
                    // delete old image before proceed
                    foreach( $extensions as $key => $value )
                    {
                        if( file_exists( ROOT_UPLOAD .'/images/avatars/' . $profile['user_id'] . '.' . $value ) )
                            unlink( ROOT_UPLOAD .'/images/avatars/' . $profile['user_id'] . '.' . $value );
                    }

                    require( ROOT_CORE . '/upload.class.php' );
                    $upload = new upload( $avatar, ROOT_UPLOAD .'/images/avatars/', $profile['user_id'], $extensions );
                    if( $upload->done )
                    {
                        $stmt = $db->prepare('INSERT INTO ' . DB_PREFIX . 'images SET location = ?, type = ?, user_id = ?');
                        $stmt->execute( array( 'images/avatars/' . $upload->filename, 'upload', $profile['user_id'] ) );
                    }
                    else
                    {
                        $error->show('Upload Failure', 'There has been an upload failure!', 1, 'index.php?mod=account&sub=profile&action=show');
                    }
                }
                else
                {
                    if( $input->check( $avatar, 'is_url' ) )
                    {
                        $stmt = $db->prepare('INSERT INTO ' . DB_PREFIX . 'images SET location = ?, type = ?, user_id = ?');
                        $stmt->execute( array( $avatar, 'url', $profile['user_id'] ) );
                    }
                    else
                    {
                        $error->show('URL Failure', 'This is not a valid URL!', 1, 'index.php?mod=account&sub=profile&action=show');
                    }
                }
                $image_id = $db->lastInsertId();
            }


            $profile['image_id'] = $image_id;

            $profile_sets = '`icq` = :icq, `state` = :state, `msn` = :msn, `first_name` = :first_name, `last_name` = :last_name, `gender` = :gender,
                             `birthday` = :shaped_birthday, `height` = :height, `address` = :address, `zipcode` = :zipcode,
                             `city` = :city, `country` = :country, `homepage` = :homepage, `skype` = :skype, `phone` = :phone,
                             `mobile` = :mobile, `custom_text` = :custom_text, `timestamp` = :shaped_timestamp, `image_id` = :image_id';
            $stmt = $db->prepare('UPDATE ' . DB_PREFIX . 'profiles_general SET ' . $profile_sets . ' WHERE user_id = :user_id');
            $stmt->execute( $profile );

            if( count( $image_result ) > 0 )
            {
                $functions->redirect( 'index.php?mod=account&sub=profile&action=show', 'metatag|newsite', 3, $lang->t( 'The profile has been edited.' ) );
            }
            else
            {
                $functions->redirect( 'index.php', 'metatag|newsite', 3, $lang->t( 'No such user!' ) );
            }
        }
        else
        {
            // Get thte profile
            $stmt = $db->prepare('SELECT * FROM ' . DB_PREFIX .'profiles_general WHERE user_id = ?');
            $stmt->execute( array($_SESSION['user']['user_id'] ) );
            $profile = $stmt->fetch(PDO::FETCH_NAMED);
            unset($profile['general_id']);
            unset($profile['user_id']);

            $tpl->assign('profile', $profile);

            // ouput
            $this->output .= $tpl->fetch('account/profile/edit_general.tpl');
            $this->suppress_wrapper = 1;
        }
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
            $stmt = $db->prepare( 'SELECT i.*,p.user_id FROM ' . DB_PREFIX . 'profiles_general p LEFT JOIN ' . DB_PREFIX . 'images i ON i.image_id = p.image_id WHERE p.user_id = ?' );
            $stmt->execute( array( $id ) );
            $result = $stmt->fetch(PDO::FETCH_NAMED);

            require( ROOT_CORE . '/image.class.php' );
            $img = new image( ROOT_UPLOAD . '/' . $result['location'] );
            $img->resize( 150, 150 );
            $img->show();
        }
    }

    /**
     * Instant Show
     *
     * Content of a module can be instantly displayed by adding the
     * {mod name="general" sub="admin" func="instant_show" params="mytext"}
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