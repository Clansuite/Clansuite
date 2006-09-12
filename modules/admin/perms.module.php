<?php
/**
* Permission Administration Modul
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

//----------------------------------------------------------------
// Security Handler
//----------------------------------------------------------------
if (!defined('IN_CS'))
{
    die('You are not allowed to view this page statically.' );
}

//----------------------------------------------------------------
// Admin Module - Permissions Class
//----------------------------------------------------------------
class module_admin_permissions
{
    public $output          = '';
    public $mod_page_title  = '';
    public $additional_head = '';
    public $suppress_wrapper= '';
    
    //----------------------------------------------------------------
    // First function to run - switches between $_REQUEST['action'] Vars to the functions
    // Loading necessary language files
    //----------------------------------------------------------------
    function auto_run()
    {
        global $lang;
        
        $this->mod_page_title = $lang->t('Control Center - Administration of Groups' );
        
        switch ($_REQUEST['action'])
        {
            case 'show_all':
                $this->mod_page_title = $lang->t( 'Show Permissions' );
                $this->show_all();
                break;
                                
            case 'edit_right':
                $this->mod_page_title = $lang->t( 'Edit Permissions' );
                $this->edit_right();
                break;

            case 'edit_area':
                $this->mod_page_title = $lang->t( 'Edit Area' );
                $this->edit_area();
                break;
                                
            case 'create_right':
                $this->mod_page_title = $lang->t( 'Create Permission' );
                $this->create_right();
                break;

            case 'create_area':
                $this->mod_page_title = $lang->t( 'Create Area' );
                $this->create_area();
                break;
                                
            case 'delete_right':
                $this->mod_page_title = $lang->t( 'Delete Permission' );
                $this->delete_right();
                break;

            case 'delete_area':
                $this->mod_page_title = $lang->t( 'Delete Area' );
                $this->delete_area();
                break;
                            
            default:
                $this->mod_page_title = $lang->t( 'Show Permissions' );
                $this->show_all();
            break;
        }
        
        return array( 'OUTPUT'          => $this->output,
                      'MOD_PAGE_TITLE'  => $this->mod_page_title,
                      'ADDITIONAL_HEAD' => $this->additional_head,
                      'SUPPRESS_WRAPPER'=> $this->suppress_wrapper );
    }
    
    /**
    * @desc Show all rights depending on areas
    */
    function show_all()
    {
        global $db, $tpl, $error, $lang;
        
        /**
        * @desc Select the areas and assing the rights
        */
        $stmt3 = $db->prepare( 'SELECT area_id, name, description FROM ' . DB_PREFIX . 'areas ORDER BY name ASC' );
        $stmt3->execute();
        $areas_result = $stmt3->fetchAll(PDO::FETCH_ASSOC);
        foreach( $areas_result as $area )
        {
            $stmt4 = $db->prepare( 'SELECT right_id, description, name FROM ' . DB_PREFIX . 'rights WHERE area_id = ? ORDER BY name ASC' );
            $stmt4->execute( array( $area['area_id'] ) );
            $rights_result = $stmt4->fetchAll(PDO::FETCH_ASSOC);
            $areas[$area['area_id']] = $area;
            foreach( $rights_result as $right )
            {
                $rights[$area['area_id']][$right['name']] = $right;
            }
            
            $stmt5 = $db->prepare( 'SELECT ri.* FROM ' . DB_PREFIX . 'rights AS ri LEFT JOIN ' . DB_PREFIX . 'areas AS ar ON ri.area_id=ar.area_id WHERE ar.area_id IS NULL ORDER BY ri.name ASC' );
            $stmt5->execute();
            $unassigned = $stmt5->fetchAll(PDO::FETCH_ASSOC);
        }
        
        $tpl->assign( 'unassigned'  , $unassigned );
        $tpl->assign( 'rights'      , $rights );
        $tpl->assign( 'areas'       , $areas );
        $this->output .= $tpl->fetch('admin/permissions/show.tpl');
    }
    
    /**
    * @desc Edit a right
    */
    function edit_right()
    {
        global $db, $tpl, $error, $lang, $functions, $input;
       
       /**
       * @desc Init
       */
        $submit     = $_POST['submit'];
        $info       = $_POST['info'];
        $right_id   = isset($_GET['right_id'])  ? (int) $_GET['right_id']   : $_POST['right_id'];
        $sets       = '';

        if ( empty( $submit ) )
        {
            /**
            * @desc Select the right from DB      
            */
            $stmt = $db->prepare( 'SELECT * FROM ' . DB_PREFIX . 'rights WHERE right_id = ?' );
            $stmt->execute( array( $right_id ) );
            $info = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ( !is_array( $info ) )
            {
                $functions->redirect( 'index.php?mod=admin&sub=permissions&action=show_all', 'metatag|newsite', 3, $lang->t( 'There is no right with that id.' ), 'admin' );
            }
        }
        else
        {
            /**
            * @desc Select the right from DB      
            */
            $stmt = $db->prepare( 'SELECT name FROM ' . DB_PREFIX . 'rights WHERE right_id != ? AND name = ?' );
            $stmt->execute( array( $right_id , $info['name'] ) );
            $check = $stmt->fetch(PDO::FETCH_ASSOC);
            if ( is_array( $check ) )
            {
                $err['name_already'];   
            }
            
            /**
            * @desc Fill form?
            */
            if ( empty( $info['name'] ) OR empty( $info['description'] ) )
            {
                $err['fill_form'];   
            }
        }
        
        /**
        * @desc Select the areas
        */
        $stmt3 = $db->prepare( 'SELECT area_id, name, description FROM ' . DB_PREFIX . 'areas ORDER BY name ASC' );
        $stmt3->execute();
        $areas = $stmt3->fetchAll(PDO::FETCH_ASSOC);
        
        /**
        * @desc Update on submit
        */
        if ( !empty( $submit ) && count($err) == 0 )
        {        
            /**
            * @desc Update right in DBe
            */
            $sets =  'name = ?, description = ?, area_id = ?';
            $stmt = $db->prepare( 'UPDATE ' . DB_PREFIX . 'rights SET ' . $sets . ' WHERE right_id = ?' );
            $stmt->execute( array ( $info['name'], $info['description'], $info['area_id'], $info['right_id'] ) );
            
            /**
            * @desc Redirect...
            */
            $functions->redirect( 'index.php?mod=admin&sub=permissions&action=show_all', 'metatag|newsite', 3, $lang->t( 'The right has been edited.' ), 'admin' );
    
        }

        $tpl->assign( 'areas'   , $areas );
        $tpl->assign( 'err'     , $err );
        $tpl->assign( 'info'    , $info);
        $this->output .= $tpl->fetch('admin/permissions/edit_right.tpl');
    }
  
    /**
    * @desc Create a right
    */
    function create_right()
    {
        global $db, $tpl, $error, $lang, $functions, $input;
       
       /**
       * @desc Init
       */
        $submit     = $_POST['submit'];
        $info       = $_POST['info'];
        $area_id    = isset($_GET['area_id'])   ? (int) $_GET['area_id']    : $_POST['area_id'];
        $sets       = '';

        if ( !empty( $submit ) )
        {
            /**
            * @desc Select the right and check for duplicated
            */
            $stmt = $db->prepare( 'SELECT name FROM ' . DB_PREFIX . 'rights WHERE name = ?' );
            $stmt->execute( array( $info['name'] ) );
            $check = $stmt->fetch(PDO::FETCH_ASSOC);
            if ( is_array( $check ) )
            {
                $err['name_already'];   
            }
            
            /**
            * @desc Fill form?
            */
            if ( empty( $info['name'] ) OR empty( $info['description'] ) )
            {
                $err['fill_form'];   
            }
        }
        
        /**
        * @desc Select the areas
        */
        $stmt3 = $db->prepare( 'SELECT area_id, name, description FROM ' . DB_PREFIX . 'areas ORDER BY name ASC' );
        $stmt3->execute();
        $areas = $stmt3->fetchAll(PDO::FETCH_ASSOC);
        
        /**
        * @desc Insert on submit, no error
        */
        if ( !empty( $submit ) && count($err) == 0 )
        {        
            /**
            * @desc Insert right into the DB
            */
            $sets =  'name = ?, description = ?, area_id = ?';
            $stmt = $db->prepare( 'INSERT ' . DB_PREFIX . 'rights SET ' . $sets );
            $stmt->execute( array ( $info['name'], $info['description'], $info['area_id'] ) );
            
            /**
            * @desc Redirect...
            */
            $functions->redirect( 'index.php?mod=admin&sub=permissions&action=show_all', 'metatag|newsite', 3, $lang->t( 'The right has been created.' ), 'admin' );
    
        }

        $tpl->assign( 'areas'   , $areas );
        $tpl->assign( 'err'     , $err );
        $tpl->assign( 'info'    , $info);
        $this->output .= $tpl->fetch('admin/permissions/create_right.tpl');
    
    }
    
    /**
    * @desc Create a area
    */
    function create_area()
    {
        global $db, $tpl, $error, $lang, $functions, $input;
       
       /**
       * @desc Init
       */
        $submit     = $_POST['submit'];
        $info       = $_POST['info'];
        $sets       = '';

        if ( !empty( $submit ) )
        {
            /**
            * @desc Select the area from and check if duplicated
            */
            $stmt = $db->prepare( 'SELECT name FROM ' . DB_PREFIX . 'areas WHERE name = ?' );
            $stmt->execute( array( $info['name'] ) );
            $check = $stmt->fetch(PDO::FETCH_ASSOC);
            if ( is_array( $check ) )
            {
                $err['name_already'];   
            }
            
            /**
            * @desc Fill form?
            */
            if ( empty( $info['name'] ) OR empty( $info['description'] ) )
            {
                $err['fill_form'];   
            }
        }
        
        /**
        * @desc Insert on submit, no error
        */
        if ( !empty( $submit ) && count($err) == 0 )
        {        
            /**
            * @desc Insert the area into the DB
            */
            $sets =  'name = ?, description = ?';
            $stmt = $db->prepare( 'INSERT ' . DB_PREFIX . 'areas SET ' . $sets );
            $stmt->execute( array ( $info['name'], $info['description'] ) );
            
            /**
            * @desc Redirect...
            */
            $functions->redirect( 'index.php?mod=admin&sub=permissions&action=show_all', 'metatag|newsite', 3, $lang->t( 'The area has been created.' ), 'admin' );
    
        }

        $tpl->assign( 'err'     , $err );
        $tpl->assign( 'info'    , $info);
        $this->output .= $tpl->fetch('admin/permissions/create_area.tpl');
    
    }
    
    /**
    * @desc Edit a area
    */
    function edit_area()
    {
        global $db, $tpl, $error, $lang, $functions, $input;
       
       /**
       * @desc Init
       */
        $submit     = $_POST['submit'];
        $info       = $_POST['info'];
        $area_id    = isset($_GET['area_id'])   ? (int) $_GET['area_id']    : $_POST['area_id'];
        $sets       = '';

        if ( empty( $submit ) )
        {
            /**
            * @desc Select the right from DB      
            */
            $stmt = $db->prepare( 'SELECT area_id, name, description FROM ' . DB_PREFIX . 'areas WHERE area_id = ?' );
            $stmt->execute( array( $area_id ) );
            $info = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ( !is_array( $info ) )
            {
                $functions->redirect( 'index.php?mod=admin&sub=permissions&action=show_all', 'metatag|newsite', 3, $lang->t( 'There is no area with that id.' ), 'admin' );
            }
        }
        else
        {
            /**
            * @desc Select the area from and check if duplicated
            */
            $stmt = $db->prepare( 'SELECT name FROM ' . DB_PREFIX . 'areas WHERE area_id != ? AND name = ?' );
            $stmt->execute( array( $area_id , $info['name'] ) );
            $check = $stmt->fetch(PDO::FETCH_ASSOC);
            if ( is_array( $check ) )
            {
                $err['name_already'];   
            }
            
            /**
            * @desc Fill form?
            */
            if ( empty( $info['name'] ) OR empty( $info['description'] ) )
            {
                $err['fill_form'];   
            }
        }
        
        /**
        * @desc Insert on submit, no error
        */
        if ( !empty( $submit ) && count($err) == 0 )
        {        
            /**
            * @desc Insert the area into the DB
            */
            $sets =  'name = ?, description = ?';
            $stmt = $db->prepare( 'UPDATE ' . DB_PREFIX . 'areas SET ' . $sets . ' WHERE area_id = ?');
            $stmt->execute( array ( $info['name'], $info['description'], $info['area_id'] ) );
            
            /**
            * @desc Redirect...
            */
            $functions->redirect( 'index.php?mod=admin&sub=permissions&action=show_all', 'metatag|newsite', 3, $lang->t( 'The area has been updated.' ), 'admin' );
        }

        $tpl->assign( 'err'     , $err );
        $tpl->assign( 'info'    , $info);
        $this->output .= $tpl->fetch('admin/permissions/edit_area.tpl');
    
    }
    
    
    /**
    * @desc Delete rights
    */
    function delete_right()
    {
        global $db, $functions, $input, $lang;
        
        /**
        * @desc Init     
        */
        $submit     = $_POST['submit'];
        $confirm    = $_POST['confirm'];
        $abort      = $_POST['abort'];
        $ids        = isset($_POST['ids'])      ? $_POST['ids'] : array();
        $ids        = isset($_POST['confirm'])  ? unserialize(urldecode($_GET['ids'])) : $ids;
        $delete     = isset($_POST['delete'])   ? $_POST['delete'] : array();
        $delete     = isset($_POST['confirm'])  ? unserialize(urldecode($_GET['delete'])) : $delete;
        
        /**
        * @desc Nothing selected ?
        */
        if ( count($delete) == 0 )
        { 
            $functions->redirect( 'index.php?mod=admin&sub=permissions&action=show_all', 'metatag|newsite', 3, $lang->t( 'No rights have been selected! Aborted... ' ), 'admin' );
        }
        
        /**
        * @desc Abort
        */
        if ( !empty( $abort ) )
        {
            $functions->redirect( 'index.php?mod=admin&sub=permissions&action=show_all', 'metatag|newsite', 3, $lang->t( 'Aborted... ' ), 'admin' );
        }
        
        /**
        * @desc DB Select
        */
        $stmt = $db->prepare( 'SELECT right_id, name FROM ' . DB_PREFIX . 'rights' );
        $stmt->execute();
        $all_permissions = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        /**
        * @desc Right Names
        */
        foreach( $all_permissions as $key => $value )
        {
            if( in_array( $value['right_id'], $delete  ) )
            {
                $names .= '<br /><b>' .  $value['name'] . '</b>';
            }
        }       

        /**
        * @desc Delete relating to the the ids
        */
        foreach( $all_permissions as $key => $value )
        {
            if ( count ( $delete ) > 0 )
            {
                if ( in_array( $value['right_id'], $ids ) )
                {
                    $d = in_array( $value['right_id'], $delete  ) ? 1 : 0;
                    if ( !isset ( $_POST['confirm'] ) )
                    {
                        $functions->redirect( 'index.php?mod=admin&sub=permissions&action=delete_right&ids=' . urlencode(serialize($ids)) . '&delete=' . urlencode(serialize($delete)), 'confirm', 3, $lang->t( 'You have selected the following right(s) to be deleted: ' . $names ), 'admin' );
                    }
                    else
                    {
                        if ( $d == 1 )
                        {
                            $stmt = $db->prepare( 'DELETE FROM ' . DB_PREFIX . 'rights WHERE right_id = ?' );
                            $stmt->execute( array($value['right_id']) );
                        }
                    }
                }
            }
        }
        
        $functions->redirect( 'index.php?mod=admin&sub=permissions&action=show_all', 'metatag|newsite', 3, $lang->t( 'The rights have been deleted.' ), 'admin' );
    }
    
    /**
    * @desc Delete rights
    */
    function delete_area()
    {
        global $db, $functions, $input, $lang;
        
        /**
        * @desc Init     
        */
        $submit     = $_POST['submit'];
        $confirm    = $_POST['confirm'];
        $abort      = $_POST['abort'];
        $area_id    = $_GET['area_id'];
        
        /**
        * @desc Abort
        */
        if ( !empty( $abort ) )
        {
            $functions->redirect( 'index.php?mod=admin&sub=permissions&action=show_all', 'metatag|newsite', 3, $lang->t( 'Aborted... ' ), 'admin' );
        }
        
        /**
        * @desc DB Select
        */
        $stmt = $db->prepare( 'SELECT name FROM ' . DB_PREFIX . 'areas WHERE area_id = ?' );
        $stmt->execute( array( $area_id ) );
        $area = $stmt->fetch(PDO::FETCH_ASSOC);    

        /**
        * @desc Delete the area after confirmation
        */
        if( !empty( $confirm ) )
        {
            $stmt = $db->prepare( 'DELETE FROM ' . DB_PREFIX . 'areas WHERE area_id = ?' );
            $stmt->execute( array( $area_id ) );            
        }
        else
        {
            $functions->redirect( 'index.php?mod=admin&sub=permissions&action=delete_area&area_id=' . $area_id, 'confirm', 3, $lang->t( 'You have selected the following area to be deleted:<br /> ' . $area['name'] ), 'admin' );
        }
        
        $functions->redirect( 'index.php?mod=admin&sub=permissions&action=show_all', 'metatag|newsite', 3, $lang->t( 'The area has been deleted.' ), 'admin' );
    }
}
?>