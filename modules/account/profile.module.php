<?php
/**
* Modulename:   profile
* Description:  The profile submodule
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

// Begin of class module_account_profile
class module_account_profile
{
    public $output          = '';
    public $additional_head = '';
    public $suppress_wrapper= '';

    /**
    * @desc General Function Hook of profile-Modul
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

        global $lang,$trail;
        $params = func_get_args();

        // Set Pagetitle and Breadcrumbs
        $trail->addStep($lang->t('Profile'), '/index.php?mod=profile');

        //
        switch ($_REQUEST['action'])
        {

            default:
            case 'show':
                $trail->addStep($lang->t('Show'), '/index.php?mod=profile&action=show');
                $this->show();
                break;

            case 'get_custom_text':
                $trail->addStep($lang->t('Ajax Update'), '/index.php?mod=profile&action=get_custom_text');
                $this->get_custom_text();
                break;

            case 'ajax_update':
                $trail->addStep($lang->t('Ajax Update'), '/index.php?mod=profile&action=ajax_update');
                $this->ajax_update();
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
    * @desc Function: Show
    */
    function show()
    {
        global $cfg, $db, $tpl, $error, $lang, $functions, $security, $input, $perms, $users;

        // DB Select
        $stmt = $db->prepare('SELECT * FROM '. DB_PREFIX .'profiles WHERE user_id = ?');
        $stmt->execute( array( $_SESSION['user']['user_id'] ) );
        $info = $stmt->fetch(PDO::FETCH_NAMED);

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
        else
        {
            $info['gender'] = '-';
        }

        // Output
        $tpl->assign( 'info' , $info );
        $this->output .= $tpl->fetch('account/profile/show.tpl');
    }

    /**
    * @desc Function: Show
    */
    function get_custom_text()
    {
        global $cfg, $db, $tpl, $error, $lang, $functions, $security, $input, $perms, $users;

        // DB Select
        $stmt = $db->prepare('SELECT custom_text FROM '. DB_PREFIX .'profiles WHERE user_id = ?');
        $stmt->execute( array( $_SESSION['user']['user_id'] ) );
        $info = $stmt->fetch(PDO::FETCH_NAMED);

        // Output
        $this->output .= $info['custom_text'];
        $this->suppress_wrapper = true;
    }

    /**
    * @desc Function: Update the stuff
    */
    function ajax_update()
    {
        global $cfg, $db, $tpl, $error, $lang, $functions, $security, $input, $perms, $users;

        /**
        * @desc Incoming vars
        */
        $value  = urldecode($_POST['value']);
        $cell   = isset($_POST['cell']) ? urldecode($_POST['cell']) : urldecode($_GET['cell']);


        // whitelist for $modules_dbfields
        $whitelist = array( 'zipcode',
                            'homepage',
                            'birthday',
                            'gender',
                            'height',
                            'address',
                            'city',
                            'country',
                            'icq',
                            'msn',
                            'skype',
                            'phone',
                            'mobile',
                            'custom_text',
                            'first_name',
                            'last_name' );

        // check if $modules_dbfield exists in $whitelist
        if( in_array($cell, $whitelist) )
        {
            if( $cell == 'birthday' )
            {
                $old_value = $value;
                $value = strtotime($value);
            }
            // if yes, update that field in db
            $stmt = $db->prepare( 'UPDATE ' . DB_PREFIX . 'profiles SET `' . $cell . '` = ?, `timestamp` = ?
                                                                   WHERE user_id = ?' );
            $stmt->execute( array(  $value, time(), $_SESSION['user']['user_id'] ) );
        }
        else
        {
            $security->intruder_alert();
        }

        // Convert timestamp back
        if( $cell == 'birthday' ) $value = $old_value;

        if( $cell == 'custom_text' )
        {
            // BBCode load class and init
            require_once( ROOT_CORE . '/bbcode.class.php' );
            $bbcode = new bbcode();
            $value = $bbcode->parse($value);
        }

        if( $cell == 'gender' )
        {
            if( $value == 'female' )
            {
                $value = $lang->t('Female');
            }
            else if( $value == 'male' )
            {
                $value = $lang->t('Male');
            }
            else
            {
                $value = '-';
            }
        }

        // Output + Suppress wrappering!
        $this->output = $value;
        $this->suppress_wrapper = true;
    }
}
?>