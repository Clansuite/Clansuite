<?php
/**
* Group Administration Modul
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

/* 
-- Tabellenstruktur für Tabelle `cs_usergroups`

CREATE TABLE `cs_usergroups` (
  `group_id` int(5) unsigned NOT NULL auto_increment,
  `sortorder` tinyint(4) unsigned NOT NULL default '1',
  `name` varchar(75) default NULL,
  `desc` varchar(255) default NULL,
  `icon` varchar(255) default NULL,
  `color` varchar(10) NOT NULL,
  `posts` tinyint(5) default NULL,
  PRIMARY KEY  (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Daten für Tabelle `cs_usergroups`

INSERT INTO `cs_usergroups` VALUES (1, 1, 'Administrator', NULL, '0000FF', NULL);
*/

//----------------------------------------------------------------
// Security Handler
//----------------------------------------------------------------
if (!defined('IN_CS'))
{
    die('You are not allowed to view this page statically.' );
}

//----------------------------------------------------------------
// Admin Module - Config Class
//----------------------------------------------------------------
class module_admin_groups
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
        
        $this->mod_page_title = $lang->t( 'Administration of Groups' ) . ' &raquo; ';
        
        switch ($_REQUEST['action'])
        {
            default:
            case 'show':
                $this->mod_page_title .= $lang->t( 'Show groups' );
                $this->show_groups();
                break;
            
            case 'create':
                $this->mod_page_title .= $lang->t( 'Create a new group' );
                $this->create();
                break;
          
            case 'edit':
                $this->mod_page_title .= $lang->t( 'Edit a group' );
                $this->edit();
                break;
                
            case 'delete':
                $this->delete();
                break;
                
            case 'members':
                $this->mod_page_title .= $lang->t( 'Show Group and its Members' );
                $this->show_group_members($group_id);
                break;
         
         }
        
        return array( 'OUTPUT'          => $this->output,
                      'MOD_PAGE_TITLE'  => $this->mod_page_title,
                      'ADDITIONAL_HEAD' => $this->additional_head,
                      'SUPPRESS_WRAPPER'=> $this->suppress_wrapper );
    }
    
    /**
    * @desc Show all groups
    */
    function show_groups()
    {
        global $db, $tpl, $error, $lang;

        /**
        * @desc Init
        */
        $icons  = array();        
        $groups = array();
        
        /**
        * @desc Extrapolate icons from dir
        */
        $icons = glob( TPL_ROOT . '/core/images/groups/{*.jpg,*.JPG,*.png,*.PNG,*.gif,*.GIF}', GLOB_BRACE);
        
        /**
        * @desc Get the DB result sets
        * Abfrage der Benutzergruppen
        */
        $stmt = $db->prepare( 'SELECT * FROM ' . DB_PREFIX . 'groups ORDER BY sortorder ASC' );
        $stmt->execute();
        $groups = $stmt->fetchAll(PDO::FETCH_NAMED);
        
        // Anzahl der Gruppen durchlaufen + User ermitteln
        $runtimes = count($groups);
        for ($i=0; $i<=$runtimes-1; $i++) 
        {
         //echo $i .' / ' . $runtimes . ' : ' . $right_based_groups[$i]['name'] . ' - ' . $right_based_groups[$i]['group_id']  . '<br />';        
       
        // Abfrage der User aus der jeweiligen Gruppe
                 
            $stmt2 = $db->prepare('SELECT cs.nick, cs.user_id 
                                   FROM ' . DB_PREFIX . 'user_groups cu,
                                        ' . DB_PREFIX . 'users cs
                                   WHERE cs.user_id = cu.user_id
                                   AND cu.group_id = ?');
                                   
            $stmt2->execute( array ( $groups[$i]['group_id'] ));
            $user_in_group = $stmt2->fetchAll(PDO::FETCH_NAMED);
            //var_dump($test);
            $groups[$i]['users'] = $user_in_group;
        }
          
        /**
        * @desc Assing to template & output
        */
        $tpl->assign( 'icons'   , $icons );
        $tpl->assign( 'groups'  , $groups );
        
        $this->output .= $tpl->fetch('admin/groups/show.tpl');
    }
    
    /**
    * @desc Create a new group
    */
    function create()
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
        foreach( glob( TPL_ROOT . '/core/images/groups/icons/{*.jpg,*.JPG,*.png,*.PNG,*.gif,*.GIF}', GLOB_BRACE) as $file )
        {
            $icons[] = preg_replace( '#^(.*)/#', '', $file);   
        }
        $tpl->assign( 'icons'   , $icons );
        

        $images  = array();    
        foreach( glob( TPL_ROOT . '/core/images/groups/images/{*.jpg,*.JPG,*.png,*.PNG,*.gif,*.GIF}', GLOB_BRACE) as $file )
        {
            $images[] = preg_replace( '#^(.*)/#', '', $file);   
        }
        $tpl->assign( 'images'   , $images );
        
        /**
        * @desc Select the areas and assing the rights
        */
        $stmt3 = $db->prepare( 'SELECT * FROM ' . DB_PREFIX . 'areas' );
        $stmt3->execute();
        $areas_result = $stmt3->fetchAll(PDO::FETCH_ASSOC);
        foreach( $areas_result as $area )
        {
            $stmt4 = $db->prepare( 'SELECT * FROM ' . DB_PREFIX . 'rights WHERE area_id = ?' );
            $stmt4->execute( array( $area['area_id'] ) );
            $rights_result = $stmt4->fetchAll(PDO::FETCH_ASSOC);
            foreach( $rights_result as $rights )
            {
                $areas[$area['name']][$rights['name']]['right_id']       = $rights['right_id'];
                $areas[$area['name']][$rights['name']]['name']           = $rights['name'];
                $areas[$area['name']][$rights['name']]['description']    = $rights['description'];
            }
        }
            
        if ( !empty( $submit ) )
        {
            /**
            * @desc Group already stored?
            */
            $stmt = $db->prepare( 'SELECT * FROM ' . DB_PREFIX . 'groups WHERE name = ?' );
            $stmt->execute( array( $info['name'] ) );
            $group = $stmt->fetch(PDO::FETCH_ASSOC);
            if ( is_array( $group ) )
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
        {
            /**
            * @desc Update the DB
            */
            $sets =  'sortorder = ?, name = ?, description = ?, icon = ?, image = ?, color = ?';
            $stmt = $db->prepare( 'INSERT ' . DB_PREFIX . 'groups SET ' . $sets );
            $stmt->execute( array ( $info['sortorder'],
                                    $info['name'],
                                    $info['description'], 
                                    $info['icon'],
                                    $info['image'],
                                    $info['color'] ) );

            
            /**
            * @desc Get the id
            */
            $stmt = $db->prepare( 'SELECT group_id FROM ' . DB_PREFIX . 'groups WHERE name = ?' );
            $stmt->execute( array( $info['name'] ) );
            $id_array = $stmt->fetch(PDO::FETCH_ASSOC);
                        
            /**
            * @desc Insert the rights
            */            
            $sets =  'right_id = ?, group_id = ?';
            $stmt = $db->prepare( 'INSERT ' . DB_PREFIX . 'group_rights SET ' . $sets );
            $info['rights'] = array_unique( $info['rights'] );
            foreach( $info['rights'] as $right_id )
            {
                $stmt->execute( array ( $right_id, $id_array['group_id'] ) );
            }
                        
            /**
            * @desc Redirect...
            */
            $functions->redirect( 'index.php?mod=admin&sub=groups&action=show', 'metatag|newsite', 3, $lang->t( 'The group has been created.' ), 'admin' );
    
        }
        
        /**
        * @desc Assign & Show template
        */
        $tpl->assign( 'areas'       , $areas );
        $tpl->assign( 'err'         , $err );
        $this->output .= $tpl->fetch('admin/groups/create.tpl');
       
    }
    
    /**
    * @desc Edit a group
    */
    function edit()
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
        
        /**
        * @desc Get the images
        */
        foreach( glob( TPL_ROOT . '/core/images/groups/icons/{*.jpg,*.JPG,*.png,*.PNG,*.gif,*.GIF}', GLOB_BRACE) as $file )
        {
            $icons[] = preg_replace( '#^(.*)/#', '', $file);   
        }
        $tpl->assign( 'icons'   , $icons );
        

        foreach( glob( TPL_ROOT . '/core/images/groups/images/{*.jpg,*.JPG,*.png,*.PNG,*.gif,*.GIF}', GLOB_BRACE) as $file )
        {
            $images[] = preg_replace( '#^(.*)/#', '', $file);   
        }
        $tpl->assign( 'images'   , $images );
               
        /**
        * @desc Group already stored?
        */
        if( !empty( $submit ) )
        {
            if ( !empty( $info['name'] ) )
            {
                $stmt = $db->prepare( 'SELECT * FROM ' . DB_PREFIX . 'groups WHERE group_id != ? AND name = ?' );
                $stmt->execute( array( $info['group_id'], $info['name'] ) );
                $group_already = $stmt->fetch(PDO::FETCH_ASSOC);
                if ( is_array( $group_already ) )
                {
                    $err['name_already'] = 1;        
                }
            }
        }
        else
        {
            /**
            * @desc Select the group
            */
            $stmt = $db->prepare( 'SELECT * FROM ' . DB_PREFIX . 'groups WHERE group_id = ?' );
            $stmt->execute( array( $id ) );
            $info = $stmt->fetch(PDO::FETCH_ASSOC);
        
            /**
            * @desc Select the permissions of group
            */
            $info['rights'] = array();
            $stmt2 = $db->prepare('SELECT tr.name, ug.right_id 
                                   FROM ' . DB_PREFIX . 'group_rights ug,
                                        ' . DB_PREFIX . 'rights tr
                                   WHERE tr.right_id = ug.right_id
                                   AND ug.group_id = ?');
                                       
            $stmt2->execute(  array( $id ) );
            $rights_of_group = $stmt2->fetchAll(PDO::FETCH_ASSOC);
            foreach( $rights_of_group as $r_temp )
            {
                $info['rights'][$r_temp['right_id']] = $r_temp;
            }
            
            /**
            * @desc Select the areas and asigning the rights
            */
            $info['areas'] = array();
            $stmt3 = $db->prepare( 'SELECT * FROM ' . DB_PREFIX . 'areas ORDER BY name ASC' );
            $stmt3->execute();
            $areas_result = $stmt3->fetchAll(PDO::FETCH_ASSOC);
            foreach( $areas_result as $area )
            {
                $stmt4 = $db->prepare( 'SELECT * FROM ' . DB_PREFIX . 'rights WHERE area_id = ? ORDER BY name ASC' );
                $stmt4->execute( array( $area['area_id'] ) );
                $rights_result = $stmt4->fetchAll(PDO::FETCH_ASSOC);
                foreach( $rights_result as $rights )
                {
                    $info['areas'][$area['name']][$rights['name']]['right_id']       = $rights['right_id'];
                    $info['areas'][$area['name']][$rights['name']]['name']           = $rights['name'];
                    $info['areas'][$area['name']][$rights['name']]['description']    = $rights['description'];
                }
            }
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
            $sets =  'sortorder = ?, name = ?, description = ?, icon = ?, image = ?, color = ?';
            $stmt = $db->prepare( 'UPDATE ' . DB_PREFIX . 'groups SET ' . $sets . ' WHERE group_id = ?' );
            $stmt->execute( array ( $info['sortorder'],
                                    $info['name'],
                                    $info['description'], 
                                    $info['icon'],
                                    $info['image'],
                                    $info['color'],
                                    $info['group_id'] ) );
                                    
            /**
            * @desc Drop & Insert the rights
            */
            $stmt = $db->prepare( 'DELETE FROM ' . DB_PREFIX . 'group_rights WHERE group_id = ?' );
            $stmt->execute( array ( $info['group_id'] ) );
            
            $sets =  'right_id = ?, group_id = ?';
            $stmt = $db->prepare( 'INSERT ' . DB_PREFIX . 'group_rights SET ' . $sets );
            $info['rights'] = array_unique( $info['rights'] );
            foreach( $info['rights'] as $right_id )
            {
                $stmt->execute( array ( $right_id, $info['group_id'] ) );
            }
            
            /**
            * @desc Redirect...
            */
            $functions->redirect( 'index.php?mod=admin&sub=groups&action=show', 'metatag|newsite', 3, $lang->t( 'The group has been edited.' ), 'admin' );
    
        }
        
        /**
        * @desc Assign & show template
        */
        $tpl->assign( 'err'         , $err );
        $tpl->assign( 'info'        , $info );
        $this->output .= $tpl->fetch('admin/groups/edit.tpl');
    }
      
    /**
    * @desc Delete the groups list
    */
    function delete()
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
            $functions->redirect( 'index.php?mod=admin&sub=groups&action=show_all', 'metatag|newsite', 3, $lang->t( 'No groups selected to delete! Aborted... ' ), 'admin' );
        }
        
        /**
        * @desc Abort...
        */
        if ( isset( $_POST['abort'] ) )
        {
            $functions->redirect( 'index.php?mod=admin&sub=groups&action=show_all' );
        }
        
        /**
        * @desc Select from DB
        */
        $stmt = $db->prepare( 'SELECT group_id, name FROM ' . DB_PREFIX . 'groups' );
        $stmt->execute();
        $all_groups = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach( $all_groups as $key => $value )
        {
            if( in_array( $value['group_id'], $delete  ) )
            {
                $names .= '<br /><b>' .  $value['name'] . '</b>';
            }
        }       
        
        
        /**
        * @desc Delete Groups
        */
        foreach( $all_groups as $key => $value )
        {
            if ( count ( $delete ) > 0 )
            {
                if ( in_array( $value['group_id'], $ids ) )
                {
                    $d = in_array( $value['group_id'], $delete  ) ? 1 : 0;
                    if ( !isset ( $_POST['confirm'] ) )
                    {
                        $functions->redirect( 'index.php?mod=admin&sub=groups&action=delete&ids=' . urlencode(serialize($ids)) . '&delete=' . urlencode(serialize($delete)), 'confirm', 3, $lang->t( 'You have selected the following group(s) to delete: ' . $names ), 'admin' );
                    }
                    else
                    {
                        if ( $d == 1 )
                        {
                            $stmt = $db->prepare( 'DELETE FROM ' . DB_PREFIX . 'groups WHERE group_id = ?' );
                            $stmt->execute( array($value['group_id']) );
                        }
                    }
                }
            }
        }
        
        $functions->redirect( 'index.php?mod=admin&sub=groups&action=show_all', 'metatag|newsite', 3, $lang->t( 'The groups have been delete.' ), 'admin' );
        
    }
   
    /**
    * @desc Show all members inherent in a group
    */
    function show_group_members($group_id)
    {
        global $db, $tpl, $error, $lang;

        $stmt = $db->prepare( 'SELECT * FROM ' . DB_PREFIX . 'groups WHERE ?');
        $stmt->execute( array( $group_id ) );
        $stmt->closeCursor();

        $groupsdata = $stmt->fetchAll(PDO::FETCH_NAMED);
        
        if ( is_array( $groupsdata ) )
        {
            $tpl->assign('groupsdata', $groupsdata);
        }
        else
        {
            $this->output .= 'There was an error while acquiring the groups-data.';
        }
       
        $stmt = $db->prepare( 'SELECT * FROM ' . DB_PREFIX . 'groups WHERE ?');
        $stmt->execute( array( $group_id ) );
        $stmt->closeCursor();
      
        $this->output .= $tpl->fetch('admin/groups/show_group_members.tpl');
    }
    
}
?>