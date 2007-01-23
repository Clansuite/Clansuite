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
        $trail->addStep($lang->t('Help'), '/index.php?mod=admin&sub=help');
        
        switch ($_REQUEST['action'])
        {
            case 'show':
                $trail->addStep($lang->t('Show'), '/index.php?mod=admin&sub=help&action=show'); 
                $this->show();
                break;

            case 'instant_show':
                $this->output .= call_user_func_array( array( $this, 'instant_show' ), $params );
                break;
                
            case 'save_helptext':
                $this->save_helptext();
                break;

            case 'save_related_links':
                $this->save_related_links();
                break;
                                
            default:
                $this->show();
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
        $this->output .= $lang->t('You have created a new module, that currently handles this message');
    }
    
    /**
    * @desc This content can be instantly displayed by adding {mod name="help" func="instant_show" params="mytext"} into a template
    * @desc You have to add the lines as shown above into the case block: $this->output .= call_user_func_array( array( $this, 'instant_show' ), $params );
    */
    function instant_show()
    {
        global $cfg, $db, $tpl, $error, $lang, $functions, $security, $input;
        
        $mod    = $_REQUEST['mod'];
        $sub    = $_REQUEST['sub'];
        $action = $_REQUEST['main_action'];
        
        $stmt = $db->prepare( 'SELECT helptext,related_links FROM ' . DB_PREFIX . 'help WHERE `mod` = ? AND `sub` = ? AND `action` = ?' );
        $stmt->execute( array( $mod, $sub, $action ) );
        $info = $stmt->fetch(PDO::FETCH_ASSOC);
        if ( is_array( $info ) )
        {
            $info['orig_related_links'] = $info['related_links'];
            if( strpos( $info['related_links'], "\n" ) )
            {
                $info['related_links'] = explode( "\n", $info['related_links'] );
            }
            else
            {
                $info['related_links'] = array();   
            }
            $tpl->assign( 'info' , $info );        
        }
        $info['related_links']  = $tpl->fetch( 'admin/help/related_links.tpl' );
        $info['helptext']       = $tpl->fetch( 'admin/help/helptext.tpl' );
        $tpl->assign( 'info' , $info );
        $this->output .= $tpl->fetch( 'admin/help/show.tpl' );
    }
    
    /**
    * @desc AJAX request to save the helptext
    */
    function save_helptext()
    {
        global $db, $tpl;

        $mod            = urldecode($_POST['save_mod']);
        $sub            = urldecode($_POST['save_sub']);
        $action         = urldecode($_POST['save_action']);
        $helptext       = urldecode($_POST['helptext']);

        $stmt = $db->prepare( 'SELECT help_id FROM ' . DB_PREFIX . 'help WHERE `mod` = ? AND `sub` = ? AND `action` = ?' );
        $stmt->execute( array( $mod, $sub, $action ) );
        $check = $stmt->fetch(PDO::FETCH_ASSOC);
        if ( is_array( $check ) )
        {
            $stmt = $db->prepare( 'UPDATE ' . DB_PREFIX . 'help SET `mod` = ?, `sub` = ?, `action` = ?, `helptext` = ? WHERE `help_id` = ?' );
            $stmt->execute( array( $mod, $sub, $action, $helptext, $check['help_id'] ) );
        }
        else
        {
            $stmt = $db->prepare( 'INSERT INTO ' . DB_PREFIX . 'help SET `mod` = ?, `sub` = ?, `action` = ?, `helptext` = ?' );
            $stmt->execute( array( $mod, $sub, $action, $helptext ) );
        }

        $info['helptext'] = $helptext;
        $tpl->assign( 'info' , $info );
        $this->output = $tpl->fetch( 'admin/help/helptext.tpl' );
        $this->suppress_wrapper = 1;       
    }
    
    /**
    * @desc AJAX request to save the related links
    */
    function save_related_links()
    {
        global $db, $tpl;
        
        $mod            = urldecode($_POST['save_mod']);
        $sub            = urldecode($_POST['save_sub']);
        $action         = urldecode($_POST['save_action']);
        $related_links  = urldecode($_POST['related_links']);
        
        $info['orig_related_links'] = $related_links;

        $stmt = $db->prepare( 'SELECT help_id FROM ' . DB_PREFIX . 'help WHERE `mod` = ? AND `sub` = ? AND `action` = ?' );
        $stmt->execute( array( $mod, $sub, $action ) );
        $check = $stmt->fetch(PDO::FETCH_ASSOC);
        if ( is_array( $check ) )
        {
            $stmt = $db->prepare( 'UPDATE ' . DB_PREFIX . 'help SET `mod` = ?, `sub` = ?, `action` = ?, `related_links` = ? WHERE `help_id` = ?' );
            $stmt->execute( array( $mod, $sub, $action, $related_links, $check['help_id'] ) );
        }
        else
        {
            $stmt = $db->prepare( 'INSERT INTO ' . DB_PREFIX . 'help SET `mod` = ?, `sub` = ?, `action` = ?, `related_links` = ?' );
            $stmt->execute( array( $mod, $sub, $action, $related_links ) );
        }

        if ( strpos($related_links, "\n") > 0 )
        {
            $info['related_links'] = explode( "\n", $related_links);
        }
        else
        {
            $info['related_links'] = array();
        }
        $tpl->assign( 'info' , $info );
        $this->output = $tpl->fetch( 'admin/help/related_links.tpl' );
        $this->suppress_wrapper = 1;
    }
}
?>