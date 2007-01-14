<?php
/**
* Categories Admin Handler Class
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
* @author     Jens-Andre Koch   <vain@clansuite.com>
* @author     Florian Wolf      <xsign.dll@clansuite.com>
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
* @desc Admin Module - Categories Class
*/
class module_admin_categories
{
    public $output          = '';
    public $mod_page_title  = '';
    public $additional_head = '';
    public $suppress_wrapper= '';
    
    /**
    * @desc First function to run - switches between $_REQUEST['action'] Vars to the functions
    * @desc Loading necessary language files
    */

    function auto_run()
    {
        global $lang;
        
        $this->mod_page_title = $lang->t( 'Administration of Categories' ) . ' &raquo; ';
        
        switch ($_REQUEST['action'])
        {
            default:
            case 'show':
                $this->mod_page_title .= $lang->t( 'Show Categories' );
                $this->show_categories();
                break;
                
            case 'create':
                $this->mod_page_title .= $lang->t( 'Create Category' );
                $this->create_categories();
                break;
    
            case 'edit':
                $this->mod_page_title .= $lang->t( 'Edit Categories' );
                $this->edit_categories();
                break;
                
            case 'delete':
                $this->mod_page_title .= $lang->t( 'Delete Categories' );
                $this->delete_categories();
                break;
     
        }
        
        return array( 'OUTPUT'          => $this->output,
                      'MOD_PAGE_TITLE'  => $this->mod_page_title,
                      'ADDITIONAL_HEAD' => $this->additional_head,
                      'SUPPRESS_WRAPPER'=> $this->suppress_wrapper );
    }
    
    /**
    * @desc Show categories
    */

    function show_categories()
    {
        global $db, $tpl, $error, $lang;

       
        $stmt = $db->prepare( 'SELECT * FROM ' . DB_PREFIX . 'categories' );
        $stmt->execute( );
        $categories = $stmt->fetchAll(PDO::FETCH_NAMED);
       
        $i = 0;
        foreach( $categories as $category )
        {   
            // if category belongs to a module
            if ( $category['module_id'] != NULL ) {            
            // get modulname by module_id of every category
            $stmt2 = $db->prepare('SELECT name FROM ' . DB_PREFIX . 'modules WHERE module_id = ?');
            $stmt2->execute(  array ( $category['module_id'] ) );
            $categories[$i]['module_name'] = $stmt2->fetch(PDO::FETCH_COLUMN);
            }
            else 
            // if category belongs not to any module
            { 
            $categories[$i]['module_name'] = 'none';
            }
        $i++;            
        }
                
        if ( is_array( $categories ) )
        {
            $tpl->assign('categories', $categories);
        }
        else
        {
        $this->output .= 'No Categories could be found.';
        }
       
        $this->output .= $tpl->fetch('admin/categories/show.tpl');
    }
    
    /**
    * @desc Create a Category
    */
    function create_categories()
    {
        global $db, $tpl, $error, $lang, $functions, $input;
       
        /**
        * @desc Init
        */
        $submit     = $_POST['submit'];
        $info       = $_POST['info'];      
        $sets       = '';        
        /**
        * @desc Icons & Images
        */
        $icons  = array();    
        foreach( glob( TPL_ROOT . '/core/images/categories/icons/{*.jpg,*.JPG,*.png,*.PNG,*.gif,*.GIF}', GLOB_BRACE) as $file )
        {
            $icons[] = preg_replace( '#^(.*)/#', '', $file);   
        }
        $tpl->assign( 'icons'   , $icons );
        

        $images  = array();    
        foreach( glob( TPL_ROOT . '/core/images/categories/images/{*.jpg,*.JPG,*.png,*.PNG,*.gif,*.GIF}', GLOB_BRACE) as $file )
        {
            $images[] = preg_replace( '#^(.*)/#', '', $file);   
        }
        $tpl->assign( 'images'   , $images );
        
        /**
        * @desc Select all modules
        */
        $stmt = $db->prepare( 'SELECT module_id, name FROM ' . DB_PREFIX . 'modules' );
        $stmt->execute();
        $modules= $stmt->fetchAll(PDO::FETCH_ASSOC);
        
            
        if ( !empty( $submit ) )
        {
            /**
            * @desc Category already stored?
            */
            $stmt = $db->prepare( 'SELECT * FROM ' . DB_PREFIX . 'categories WHERE name = ?' );
            $stmt->execute( array( $info['name'] ) );
            $cats = $stmt->fetch(PDO::FETCH_ASSOC);
            if ( is_array( $cats ) )
            {
                $err['name_already'] = 1;        
            }
            
            /**
            * @desc Form filled?
            */
            if( empty($info['name']) OR 
                empty($info['description']))
            {
                $err['fill_form'] = 1;   
            }
        }
        
        /**
        * @desc No errors - procceed to edit
        */
        if ( !empty($submit) AND count($err) == 0 )
        { print 'insert';
            /**
            * @desc Update the DB
            */
            $sets =  'module_id = ?, sortorder = ?, name = ?, description = ?, image = ?,icon = ?,  color = ?';
            $stmt = $db->prepare( 'INSERT ' . DB_PREFIX . 'categories SET ' . $sets );
            $stmt->execute( array ( $info['module_id'],
                                    $info['sortorder'],
                                    $info['name'],
                                    $info['description'], 
                                    $info['image'],
                                    $info['icon'],
                                    $info['color']) );
             
            /**
            * @desc Redirect...
            */
            $functions->redirect( 'index.php?mod=admin&sub=categories&action=show', 'metatag|newsite', 3, $lang->t( 'The Category has been created.' ), 'admin' );
    
        }
        
        /**
        * @desc Assign & Show template
        */
        $tpl->assign( 'modules'     , $modules );
        $tpl->assign( 'err'         , $err );
        $this->output .= $tpl->fetch('admin/categories/create.tpl');
       
    }
    
             
    
    /**
    * @desc Edit Categories
    */

    function edit_categories()
    {
         global $db, $tpl, $error, $lang, $functions, $input;
       
        /**
        * @desc Init
        */
        $submit     = $_POST['submit'];
        $info       = $_POST['info'];
        $id         = isset($_GET['id']) ? (int)$_GET['id'] : (int)$_POST['info']['id'];
        $sets       = '';
        $images     = array();            
        $icons      = array();
        var_dump($info);
        
        /**
        * @desc Get the images
        */
        foreach( glob( TPL_ROOT . '/core/images/categories/icons/{*.jpg,*.JPG,*.png,*.PNG,*.gif,*.GIF}', GLOB_BRACE) as $file )
        {
            $icons[] = preg_replace( '#^(.*)/#', '', $file);   
        }
        $tpl->assign( 'icons'   , $icons );
        

        foreach( glob( TPL_ROOT . '/core/images/categories/images/{*.jpg,*.JPG,*.png,*.PNG,*.gif,*.GIF}', GLOB_BRACE) as $file )
        {
            $images[] = preg_replace( '#^(.*)/#', '', $file);   
        }
        $tpl->assign( 'images'   , $images );
        
        /**
        * @desc Category already stored?
        */
        if( !empty( $submit ) )
        {
            if ( !empty( $info['name'] ) )
            {
                $stmt = $db->prepare( 'SELECT * FROM ' . DB_PREFIX . 'categories WHERE cat_id != ? AND name = ?' );
                $stmt->execute( array( $info['cat_id'], $info['name'] ) );
                $cat_already = $stmt->fetch(PDO::FETCH_ASSOC);
                if ( is_array( $cat_already ) )
                {
                    $err['name_already'] = 1;        
                }
            }
        }
        else
        {
            /**
            * @desc Select the categories
            */
            $stmt = $db->prepare( 'SELECT * FROM ' . DB_PREFIX . 'categories WHERE cat_id = ?' );
            $stmt->execute( array( $id ) );
            $info = $stmt->fetch(PDO::FETCH_ASSOC);
            
            /**
            * @desc Select all avaiable modules
            */
            $stmt2 = $db->prepare('SELECT module_id, name FROM ' . DB_PREFIX . 'modules ');
            $stmt2->execute();
            $modules = $stmt2->fetchALL(PDO::FETCH_ASSOC);
            
        }
        
        /**
        * @desc Form filled?
        */
        if( ( empty($info['name'] ) OR 
              empty($info['description']) ) AND !empty($submit) )
        {
            $err['fill_form'] = 1;   
        }        
        
        if ( !empty($submit) AND count($err) == 0 )
        {
            /**
            * @desc Update the DB
            */
            $sets =  'module_id = ?, sortorder = ?, name = ?, description = ?, image = ?,icon = ?,  color = ?';
            $stmt = $db->prepare( 'UPDATE ' . DB_PREFIX . 'categories SET ' . $sets . ' WHERE cat_id = ?' );
            $stmt->execute( array ( $info['module_id'],
                                    $info['sortorder'],
                                    $info['name'],
                                    $info['description'], 
                                    $info['image'],
                                    $info['icon'],
                                    $info['color'],
                                    $info['cat_id'] ) );
                                                                              
        /**
        * @desc Redirect...
        */
        $functions->redirect( 'index.php?mod=admin&sub=categories&action=show', 'metatag|newsite', 3, $lang->t( 'The categories have been edited.' ), 'admin' );
        
        }
        
        
        /**
        * @desc Assign & show template
        */
        $tpl->assign( 'err'         , $err );
        $tpl->assign( 'info'        , $info );
        $tpl->assign( 'modules'     , $modules );
        $this->output .= $tpl->fetch('admin/categories/edit.tpl');
     }
     
      /**
    * @desc Delete categories
    */
    function delete_categories()
    {
        global $db, $functions, $input, $lang;
        
        /**
        * @desc Init
        */
        $submit     = $_POST['submit'];
        $confirm    = $_POST['confirm'];
        $ids        = isset($_POST['ids'])      ? $_POST['ids'] : array();
        $ids        = isset($_POST['confirm'])  ? unserialize(urldecode($_GET['ids'])) : $ids;
        $delete     = isset($_POST['delete'])   ? $_POST['delete'] : array();
        $delete     = isset($_POST['confirm'])  ? unserialize(urldecode($_GET['delete'])) : $delete;
        
        if ( count($delete) < 1 )
        { 
            $functions->redirect( 'index.php?mod=admin&sub=categories&action=show_all', 'metatag|newsite', 3, $lang->t( 'No categories selected to delete! Aborted... ' ), 'admin' );
        }
        
        /**
        * @desc Abort...
        */
        if ( isset( $_POST['abort'] ) )
        {
            $functions->redirect( 'index.php?mod=admin&sub=categories&action=show_all' );
        }
        
        /**
        * @desc Select from DB
        */
        $stmt = $db->prepare( 'SELECT cat_id, name FROM ' . DB_PREFIX . 'categories' );
        $stmt->execute();
        $all_categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach( $all_categories as $key => $value )
        {
            if( in_array( $value['cat_id'], $delete  ) )
            {
                $names .= '<br /><b>' .  $value['name'] . '</b>';
            }
        }       
        
        
        /**
        * @desc Delete categories
        */
        foreach( $all_categories as $key => $value )
        {
            if ( count ( $delete ) > 0 )
            {
                if ( in_array( $value['cat_id'], $ids ) )
                {
                    $d = in_array( $value['cat_id'], $delete  ) ? 1 : 0;
                    if ( !isset ( $_POST['confirm'] ) )
                    {
                        $functions->redirect( 'index.php?mod=admin&sub=categories&action=delete&ids=' . urlencode(serialize($ids)) . '&delete=' . urlencode(serialize($delete)), 'confirm', 3, $lang->t( 'You have selected the following categories to delete: ' . $names ), 'admin' );
                    }
                    else
                    {
                        if ( $d == 1 )
                        {
                            $stmt = $db->prepare( 'DELETE FROM ' . DB_PREFIX . 'categories WHERE cat_id = ?' );
                            $stmt->execute( array($value['cat_id']) );
                        }
                    }
                }
            }
        }
        
        $functions->redirect( 'index.php?mod=admin&sub=categories&action=show_all', 'metatag|newsite', 3, $lang->t( 'The categories have been delete.' ), 'admin' );
        
    }
    

}
?>