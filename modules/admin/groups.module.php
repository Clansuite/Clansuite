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
-- Tabellenstruktur f�r Tabelle `cs_usergroups`

CREATE TABLE `cs_usergroups` (
  `group_id` int(5) unsigned NOT NULL auto_increment,
  `pos` tinyint(4) unsigned NOT NULL default '1',
  `name` varchar(75) default NULL,
  `desc` varchar(255) default NULL,
  `icon` varchar(255) default NULL,
  `colour` varchar(10) NOT NULL,
  `posts` tinyint(5) default NULL,
  PRIMARY KEY  (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Daten f�r Tabelle `cs_usergroups`

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
        
        $this->mod_page_title = $lang->t('Control Center - Administration of Groups' );
        
        switch ($_REQUEST['action'])
        {
            case 'show':
                $this->mod_page_title = $lang->t( 'Show Groups' );
                $this->show_groups();
                break;
            
            case 'add_group':
                $this->mod_page_title = $lang->t( 'Add new Group' );
                $this->add_group();
                break;
          
            case 'edit':
                $this->mod_page_title = $lang->t( 'Edit Group' );
                $this->edit_group();
                break;
                
            case 'delete':
                $this->delete();
                break;
                
            case 'members':
                $this->mod_page_title = $lang->t( 'Show Group and its Members' );
                $this->show_group_members($group_id);
                break;
         
            default:
                $this->mod_page_title = $lang->t( 'Show Groups' );
                $this->show_groups();
            break;
        }
        
        return array( 'OUTPUT'          => $this->output,
                      'MOD_PAGE_TITLE'  => $this->mod_page_title,
                      'ADDITIONAL_HEAD' => $this->additional_head,
                      'SUPPRESS_WRAPPER'=> $this->suppress_wrapper );
    }
    
    //----------------------------------------------------------------
    // Show all groups
    //----------------------------------------------------------------
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
        */
        $stmt = $db->prepare( 'SELECT group_id,name,description,pos,icon,image,color FROM ' . DB_PREFIX . 'groups' );
        $stmt->execute();
        $groups = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        /**
        * @desc Assing to template & output
        */
        $tpl->assign( 'icons'   , $icons );
        $tpl->assign( 'groups'  , $groups );
        
        $this->output .= $tpl->fetch('admin/groups/show.tpl');
    }
    
    //----------------------------------------------------------------
    // Add a new usergroups based on rights
    //----------------------------------------------------------------
    function add_group()
    {
        global $db, $tpl, $error, $lang, $functions, $input;
        
        // Inputs to Vars
        $right_group_name = $_POST['right_group_name'];
        $colour = $_POST['colour'];
        $icon = $_POST['icon'];
        $description = $_POST['desc'];
              
        // Db Insert
		$stmt = $db->prepare('INSERT INTO '.DB_PREFIX.'groups (name, icon, colour, description, posts) VALUES (?,?,?,?,NULL)');
		$stmt->execute( array( $right_group_name, $icon, $colour, $description  ) );	
      
        $functions->redirect( 'index.php?mod=admin&sub=groups&action=show', 'metatag|newsite', 1, $lang->t( 'Group was created.' ), 'admin' );
       
    }
    
     //----------------------------------------------------------------
    // Edit group
    //----------------------------------------------------------------
    function edit_group()
    {
        global $db, $tpl, $error, $lang, $functions, $input;
       
        // init, get, assign ICONS!
        $icons  = array();    
        $icons = glob( TPL_ROOT . '/core/images/groups/{*.jpg,*.JPG,*.png,*.PNG,*.gif,*.GIF}', GLOB_BRACE);
        $tpl->assign( 'icons'   , $icons );
        
        // init, get, assign IMAGES!
        $images  = array();    
        $images = glob( TPL_ROOT . '/core/images/groups/{*.jpg,*.JPG,*.png,*.PNG,*.gif,*.GIF}', GLOB_BRACE);
        $tpl->assign( 'images'   , $images );
        
        // $_POST EINGANG 
        $submit     = $_POST['submit'];
        $info       = $_POST['info'];
        $id         = $_GET['id'];
                
        $sets = '';
               
        // DB - SELECT       
        $stmt = $db->prepare( 'SELECT * FROM ' . DB_PREFIX . 'groups WHERE group_id = ?' );
        $stmt->execute( array( $id ) );
        $edit_group = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // SHOW EDIT 
        
        if ( ! isset ( $_POST['submit'] ) )
        {
        
        $tpl->assign('editgroup', $edit_group);

        $this->output .= $tpl->fetch('admin/groups/edit.tpl');
       
        }
        
        // SUBMIT INPUT
        
        else
        {     
         //debug input array
        print_r($info);
        
        
        // Db Update
        $sets =  'pos = ?, name = ?, description = ?, icon = ?, image = ?, color = ?';
        $stmt = $db->prepare( 'UPDATE ' . DB_PREFIX . 'groups SET ' . $sets . ' WHERE group_id = ?' );
        $stmt->execute( array ( $info['pos'],
                                $info['name'],
                                $info['description'], 
                                $info['icon'],
                                $info['image'],
                                $info['color'],
                                $info['group_id'] ) );
        
        // Redirect to Show
        $functions->redirect( 'index.php?mod=admin&sub=groups&action=show', 'metatag|newsite', 3, $lang->t( 'The group was edited.' ), 'admin' );
    
        }
    
    }
      
    /**
    * @desc Delete the groups list
    */
    function delete()
    {
        global $db, $functions, $input, $lang;
        
        // $_POST EINGANG        
        $submit     = $_POST['submit'];
        $del        = $_POST['delete'];
        $confirm    = $_POST['confirm'];
        $ids        = isset($_POST['ids'])      ? $_POST['ids'] : array();
        $ids        = isset($_POST['confirm'])  ? unserialize(urldecode($_GET['ids'])) : $ids;
        $delete     = isset($_POST['delete'])   ? $_POST['delete'] : array();
        $delete     = isset($_POST['confirm'])  ? unserialize(urldecode($_GET['delete'])) : $delete;
        
        if ( count($delete) < 1 )
        { 
            $functions->redirect( 'index.php?mod=admin&sub=groups&action=show_all', 'metatag|newsite', 3, $lang->t( 'No groups selected to delete! Aborted... ' ), 'admin' );
        }
        
        // Abbruchm�glichkeit innerhalb der Confirm-Abfrage
        if ( isset( $_POST['abort'] ) )
        {
            $functions->redirect( 'index.php?mod=admin&sub=groups&action=show_all' );
        }
        
        // DB - SELECT       
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
        // Gruppen anhand der IDs l�schen
        foreach( $all_groups as $key => $value )
        {
            if ( count ( $delete ) > 0 )
            {
                if ( in_array( $value['group_id'], $ids ) )
                {
                    $d = in_array( $value['group_id'], $delete  ) ? 1 : 0;
                    if ( !isset ( $_POST['confirm'] ) )
                    {
                        $functions->redirect( 'index.php?mod=admin&sub=groups&action=update&ids=' . urlencode(serialize($ids)) . '&delete=' . urlencode(serialize($delete)), 'confirm', 3, $lang->t( 'You have selected the following group(s) to delete: ' . $names ), 'admin' );
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
            } // close if
        } // close foreach
        
        $functions->redirect( 'index.php?mod=admin&sub=groups&action=show_all', 'metatag|newsite', 3, $lang->t( 'The groups have been delete.' ), 'admin' );
        
    }
   
   
    //----------------------------------------------------------------
    // Show usergroup with its members
    //----------------------------------------------------------------
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