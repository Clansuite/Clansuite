<?php
/**
* Static Pages Admin Module Class
*
* PHP versions 5.1.4
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
* @license    ???
* @version    SVN: $Id$
* @link       http://gna.org/projects/clansuite
* @since      File available since Release 0.1
*/

/**
* @desc Security Handler
*/
if (!defined('IN_CS'))
{
    die('You are not allowed to view this page statically.' );
}

/**
* @desc Admin Module - Static Pages Class
*/
class module_static_admin
{
    public $output          = '';
    public $additional_head = '';
    public $suppress_wrapper= '';
    private $used = array();
    
    /**
    * @desc First function to run - switches between $_REQUEST['action'] Vars to the functions
    * @desc Loading necessary language files
    */

    function auto_run()
    {
        global $lang, $trail;
                
        // Set Pagetitle and Breadcrumbs
        $trail->addStep($lang->t('Admin'), '/index.php?mod=admin');
        $trail->addStep($lang->t('Static Pages'), '/index.php?mod=static&sub=admin');
       
        switch ($_REQUEST['action'])
        {
            default:    
            case 'show':
                $trail->addStep($lang->t('Overview'), '/index.php?mod=static&sub=admin&action=show');
                $this->show();
                break;
            
            case 'create':
                $trail->addStep($lang->t('Create'), '/index.php?mod=static&sub=admin&action=create');
                $this->create();
                break;
                
            case 'edit':
                $trail->addStep($lang->t('Edit'), '/index.php?mod=static&sub=admin&action=edit');
                $this->edit();
                break;
        }
        
        return array( 'OUTPUT'          => $this->output,
                      'ADDITIONAL_HEAD' => $this->additional_head,
                      'SUPPRESS_WRAPPER'=> $this->suppress_wrapper );
    }

           
    /**
    * @desc Create a static page
    */
    function create()
    {
        global $cfg, $db, $tpl, $error, $lang, $input, $functions;

        $html           = $_POST['html'];
        $description    = $_POST['description'];
        $title          = $_POST['title'];
        $url            = $_POST['url'];
        $submit         = $_POST['submit'];
        $iframe         = $_POST['iframe'];
        $iframe_height  = $_POST['iframe_height'];
        
        if ( !empty( $submit ) )
        {
            if ( empty( $description ) OR
                 empty( $title ) )
            {
                $err['fill_form'] = 1;
            }
            
            if (  ( !$input->check( $description        , 'is_abc|is_int|is_custom', '_\s' ) OR
                    !$input->check( $title              , 'is_abc|is_int|is_custom', '_\s' ) OR
                    !$input->check( $iframe_height      , 'is_int' ) )
                    AND !$err['fill_form'] )
            {
                $err['no_special_chars'] = 1;
            }
                 
            if ( !$input->check( $url, 'is_url' ) AND !empty( $url ) )
            {
                $err['give_correct_url'] = 1;
            }
            
            $stmt = $db->prepare( 'SELECT id FROM ' . DB_PREFIX . 'static_pages WHERE title = ?' );
            $stmt->execute( array( $title ) );
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ( is_array( $result ) )
            {
                $err['static_already_exist'] = 1;
            }
            
            if ( count( $err ) == 0 )
            {
                $stmt = $db->prepare( 'INSERT INTO ' . DB_PREFIX . 'static_pages ( title, description, url, html, iframe, iframe_height ) VALUES ( ?, ?, ?, ?, ?, ? )' );
                $stmt->execute( array( $title, $description, $url, $html, $iframe, $iframe_height ) );
                
                $functions->redirect( 'index.php?mod=admin&sub=static&action=show', 'metatag|newsite', 3, $lang->t( 'The static page was successfully created...' ), 'admin' );
            }
        }
        
        $tpl->assign( 'description' , $description );
        $tpl->assign( 'title'       , $title );
        $tpl->assign( 'url'         , $url );
        $tpl->assign( 'html'        , $html);
        $tpl->assign( 'err'         , $err);
        $this->output .= $tpl->fetch('static/create.tpl');
    }

    /**
    * @desc Edit a static page
    */
    function edit()
    {
        global $cfg, $db, $tpl, $error, $lang, $functions, $input;

        $info['html']           = $_POST['html'];
        $info['description']    = $_POST['description'];
        $info['title']          = $_POST['title'];
        $info['orig_title']     = $_POST['orig_title'];
        $info['url']            = $_POST['url'];
        $info['iframe']         = $_POST['iframe'];
        $info['iframe_height']  = $_POST['iframe_height'];
        $info['submit']         = $_POST['submit'];
        $info['id']             = $_POST['id'];      
            
        if ( !empty( $info['submit'] ) )
        {
            if ( empty( $info['description'] ) OR
                 empty( $info['title'] ) )
            {
                $err['fill_form'] = 1;
            }
            
            if (  ( !$input->check( $info['description']    , 'is_abc|is_int|is_custom', '_\s' ) OR
                    !$input->check( $info['title']          , 'is_abc|is_int|is_custom', '_\s' ) OR
                    !$input->check( $info['iframe_height']  , 'is_int' ) )
                    AND !$err['fill_form'] )
            {
                $err['no_special_chars'] = 1;
            }
                 
            if ( !$input->check( $info['url'], 'is_url' ) AND !empty( $url ) )
            {
                $err['give_correct_url'] = 1;
            }
            
            $stmt = $db->prepare( 'SELECT id FROM ' . DB_PREFIX . 'static_pages WHERE title = ?' );
            $stmt->execute( array( $info['title'] ) );
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ( is_array( $result ) AND $info['orig_title'] != $info['title'] )
            {
                $err['static_already_exist'] = 1;
            }
            
            if ( count( $err ) == 0 )
            {
                $stmt = $db->prepare( 'UPDATE ' . DB_PREFIX . 'static_pages SET title = ?, description = ?, url = ?, html = ?, iframe = ?, iframe_height = ? WHERE id = ?' );
                $stmt->execute( array( $info['title'], $info['description'], $info['url'], $info['html'], $info['iframe'], $info['iframe_height'], $info['id'] ) );
                
                $functions->redirect( 'index.php?mod=admin&sub=static&action=show', 'metatag|newsite', 3, $lang->t( 'The static page was successfully changed...' ), 'admin' );
            }
        }
        else
        {
            if ( !empty( $info['id'] ) )
            {
                $stmt = $db->prepare( 'SELECT * FROM ' . DB_PREFIX . 'static_pages WHERE id = ?' );
                $stmt->execute( array( $info['id'] ) );
                $info = $stmt->fetch(PDO::FETCH_ASSOC);
            }
        }

        $tpl->assign( 'err'  , $err);
        $tpl->assign( 'info' , $info);
        $this->output .= $tpl->fetch('static/edit.tpl');
    }
    
    /**
    * @desc List all static pages
    */
    function show()
    {
        global $cfg, $db, $tpl, $error, $lang, $functions;

        $info['html']           = '';
        $info['description']    = '';
        $info['title']          = '';
        $info['url']            = '';
        $info['iframe']         = '';
        $info['iframe_height']  = '';
        $info['id']             = '';        
            
        $stmt = $db->prepare( 'SELECT * FROM ' . DB_PREFIX . 'static_pages ORDER BY title ASC' );
        $stmt->execute();
        
        $info = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $tpl->assign( 'info', $info);
        $this->output .= $tpl->fetch('static/show.tpl');
    }
}
?>