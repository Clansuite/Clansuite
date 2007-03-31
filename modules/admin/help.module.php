<?php
/**
* help
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
class module_admin_help
{
    public $output          = '';
    public $additional_head = '';
    public $suppress_wrapper= '';

    /**
    * @desc First function to run - switches between $_REQUEST['action'] Vars to the functions
    * @desc Loads necessary language files
    */

    function auto_run()
    {

        global $lang, $trail;
        $params = func_get_args();

        // Set Pagetitle and Breadcrumbs
        $trail->addStep($lang->t('Admin'), '/index.php?mod=admin');
        $trail->addStep($lang->t('Help'), '/index.php?mod=admin&amp;sub=help');

        switch ($_REQUEST['action'])
        {
            default:
            case 'show':
                $trail->addStep($lang->t('Show'), '/index.php?mod=admin&amp;sub=help&amp;action=show');
                $this->show();
                break;

            case 'instant_show':
                $this->output .= call_user_func_array( array( $this, 'instant_show' ), $params );
                break;

            case 'get_helptext':
                $this->get_helptext();
                break;

            case 'get_related_links':
                $this->get_related_links();
                break;

            case 'save_helptext':
                $this->save_helptext();
                break;

            case 'save_related_links':
                $this->save_related_links();
                break;

        }

        return array( 'OUTPUT'          => $this->output,
                      'ADDITIONAL_HEAD' => $this->additional_head,
                      'SUPPRESS_WRAPPER'=> $this->suppress_wrapper );
    }

    /**
    * @desc Show the entrance - welcome message etc.
    */
    function show()
    {
        global $cfg, $db, $tpl, $error, $lang, $functions, $security, $input;

        /**
        * @desc Handle the output - $lang-t() translates the text.
        */
        $this->output .= $lang->t('This is the help module...stuff todo here...');
    }

    /**
    * @desc This content can be instantly displayed by adding {mod name="help" func="instant_show" params="mytext"} into a template
    * @desc You have to add the lines as shown above into the case block: $this->output .= call_user_func_array( array( $this, 'instant_show' ), $params );
    */
    function instant_show()
    {
        global $cfg, $db, $tpl, $error, $lang, $functions, $security, $input;

        /**
        * @desc Incoming Vars
        */
        $mod    = $_REQUEST['mod'];
        $sub    = $_REQUEST['sub'];
        $action = $_REQUEST['main_action'];

        // Get helptext & related links from DB
        $stmt = $db->prepare( 'SELECT helptext,related_links FROM ' . DB_PREFIX . 'help
                               WHERE `mod` = ? AND `sub` = ? AND `action` = ?' );
        $stmt->execute( array( $mod, $sub, $action ) );
        $info = $stmt->fetch(PDO::FETCH_ASSOC);

        // BBCode load class and init
        require_once( ROOT_CORE . '/bbcode.class.php' );
        $bbcode = new bbcode();
        $info['helptext'] = $bbcode->parse($info['helptext']);
        $info['related_links'] = $bbcode->parse($info['related_links']);

        // output
        $tpl->assign( 'help_edit_mode', $cfg->help_edit_mode );
        $tpl->assign( 'info' , $info );
        //var_dump($info['helptext']);
        $this->output .= $tpl->fetch( 'admin/help/show.tpl' );
    }

    /**
    * @desc AJAX request to get the helptext in raw from database
    */
    function get_helptext()
    {
        global $db;

        /**
        * @desc Incoming Vars
        */
        $mod    = $_GET['m'];
        $sub    = $_GET['s'];
        $action = $_GET['a'];

        /**
        * @desc Get Help from DB
        */
        $stmt = $db->prepare( 'SELECT helptext FROM ' . DB_PREFIX . 'help
                               WHERE `mod` = ? AND `sub` = ? AND `action` = ?' );
        $stmt->execute( array( $mod, $sub, $action ) );
        $result = $stmt->fetch(PDO::FETCH_NAMED);

        // Helptext in Raw from Database
        $this->output = $result['helptext'];
        $this->suppress_wrapper = true;
    }

    /**
    * AJAX request to get the helptext in raw from database
    *
    * @global $db
    */
    function get_related_links()
    {
        global $db;

        /**
        * @desc Incoming Vars
        */
        $mod    = $_GET['m'];
        $sub    = $_GET['s'];
        $action = $_GET['a'];

        $stmt = $db->prepare( 'SELECT related_links FROM ' . DB_PREFIX . 'help
                               WHERE `mod` = ? AND `sub` = ? AND `action` = ?' );
        $stmt->execute( array( $mod, $sub, $action ) );
        $result = $stmt->fetch(PDO::FETCH_NAMED);

        $this->output = $result['related_links'];
        $this->suppress_wrapper = true;
    }

    /**
    * AJAX request to save the helptext
    * 1. save helptext in raw with bbcodes on - into database
    * 2. return helptext with formatted bbcode = raw to html-style
    *
    * @global $db
    * @global $tpl
    */
    function save_helptext()
    {
        global $db, $tpl;
        /**
        * @desc Incoming Vars
        */
        $mod            = urldecode($_GET['m']);
        $sub            = urldecode($_GET['s']);
        $action         = urldecode($_GET['a']);
        $helptext       = urldecode($_POST['value']);

        // Get Helptext
        $stmt = $db->prepare( 'SELECT help_id, helptext FROM ' . DB_PREFIX . 'help
                               WHERE `mod` = ? AND `sub` = ? AND `action` = ?' );
        $stmt->execute( array( $mod, $sub, $action, $helptext ) );
        $check = $stmt->fetch(PDO::FETCH_ASSOC);

        if ( is_array( $check ) )
        {
            $stmt = $db->prepare( 'UPDATE ' . DB_PREFIX . 'help
                                   SET `mod` = ?, `sub` = ?, `action` = ?, `helptext` = ?
                                   WHERE `help_id` = ?' );
            $stmt->execute( array( $mod, $sub, $action, $helptext, $check['help_id'] ) );
        }
        else
        {
            $stmt = $db->prepare( 'INSERT INTO ' . DB_PREFIX . 'help
                                   SET `mod` = ?, `sub` = ?, `action` = ?, `helptext` = ?' );
            $stmt->execute( array( $mod, $sub, $action, $helptext ) );
        }

        // Transform RAW text to BB-formatted Text
        require_once( ROOT_CORE . '/bbcode.class.php' );
        $bbcode = new bbcode();
        $parsed_helptext = $bbcode->parse($helptext);

        $this->output .= $parsed_helptext;
        $this->suppress_wrapper = 1;
    }

    /**
    * @desc AJAX request to save the related links
    */
    function save_related_links()
    {
        global $db, $tpl;

        /**
        * @desc Incoming Vars
        */
        $mod            = urldecode($_GET['m']);
        $sub            = urldecode($_GET['s']);
        $action         = urldecode($_GET['a']);
        $related_links  = urldecode($_POST['value']);

        // Get related Links from DB
        $stmt = $db->prepare( 'SELECT help_id FROM ' . DB_PREFIX . 'help
                               WHERE `mod` = ? AND `sub` = ? AND `action` = ?' );
        $stmt->execute( array( $mod, $sub, $action ) );
        $check = $stmt->fetch(PDO::FETCH_ASSOC);

        if ( is_array( $check ) )
        {
            $stmt = $db->prepare( 'UPDATE ' . DB_PREFIX . 'help
                                   SET `mod` = ?, `sub` = ?, `action` = ?, `related_links` = ? WHERE `help_id` = ?' );
            $stmt->execute( array( $mod, $sub, $action, $related_links, $check['help_id'] ) );
        }
        else
        {
            $stmt = $db->prepare( 'INSERT INTO ' . DB_PREFIX . 'help
                                   SET `mod` = ?, `sub` = ?, `action` = ?, `related_links` = ?' );
            $stmt->execute( array( $mod, $sub, $action, $related_links ) );
        }

        // Transform RAW text to BB-formatted Text
        require_once( ROOT_CORE . '/bbcode.class.php' );
        $bbcode = new bbcode();
        $parsed_related_links = $bbcode->parse($related_links);

        $this->output .= $parsed_related_links;
        $this->suppress_wrapper = 1;
    }
}
?>