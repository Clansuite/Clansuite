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
  `pos` tinyint(4) unsigned NOT NULL default '1',
  `name` varchar(75) default NULL,
  `desc` varchar(255) default NULL,
  `icon` varchar(255) default NULL,
  `colour` varchar(10) NOT NULL,
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
            
            case 'add_right_group':
                $this->mod_page_title = $lang->t( 'Add new Group based on Rights' );
                $this->add_right_group();
                break;
                
            case 'add_post_group':
                $this->mod_page_title = $lang->t( 'Add new Group based on Posts' );
                $this->add_post_group();
                break;
          
            case 'edit':
                $this->mod_page_title = $lang->t( 'Edit Group' );
                $this->edit();
                break;
                
            case 'update':
                $this->update();
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
                      'ADDITIONAL_HEAD' => $this->additional_head );
    }
    
    //----------------------------------------------------------------
    // Show all groups
    //----------------------------------------------------------------
    function show_groups()
    {
        global $db, $tpl, $error, $lang;

        // Benutzergruppen - Icons laden
        
        $icons = array();
        
        $dir_handler = opendir( TPL_ROOT . '/core/images/groups/' );
        
        while( false !== ($file = readdir($dir_handler)) )
        {
            if ( $file != '.' && $file != '..' && $file != '.svn' )
            {
                $icons[] = $file;
            }
        }
        closedir($dir_handler);
        
        $tpl->assign( 'icons', $icons );
        
        // Ausgabe der Benutzergruppen basierend auf Rechten
        
        $stmt = $db->prepare( 'SELECT * FROM ' . DB_PREFIX . 'usergroups WHERE (posts IS NULL) ORDER BY pos ASC' );
        $stmt->execute();
        $right_based_groups = $stmt->fetchAll(PDO::FETCH_NAMED);
                        
        if ( is_array( $right_based_groups ) )
        {
            $tpl->assign('right_based_groups', $right_based_groups);
        }
        else
        {
        $this->output .= 'There was an error while acquiring the right_based_groups-data.';
        }
        
        // Ausgabe der Benutzergruppen basierend auf der Anzahl der Posts
        
        $stmt = $db->prepare( 'SELECT * FROM ' . DB_PREFIX . 'usergroups WHERE posts > 0 ORDER BY pos, posts ASC' );
        $stmt->execute();
        $post_based_groups = $stmt->fetchAll(PDO::FETCH_NAMED);
       
        if ( is_array( $post_based_groups ) )
        {
            $tpl->assign('post_based_groups', $post_based_groups);
        }
        else
        {
        $this->output .= 'There was an error while acquiring the post_based_groups.';
        }
       
        $this->output .= $tpl->fetch('admin/groups/show.tpl');
    }
    
    //----------------------------------------------------------------
    // Add a new usergroups based on rights
    //----------------------------------------------------------------
    function add_right_group()
    {
        global $db, $tpl, $error, $lang, $functions, $input;
        
        // Inputs to Vars
        $right_group_name = $_POST['right_group_name'];
        $posts = $_POST['posts'];
        $desc = $_POST['desc'];
        $icon = $_POST['icon'];
        
        // 
        
        
        // Db Insert
		$stmt = $db->prepare('INSERT INTO '.DB_PREFIX.'usergroups (name, desc, icon, posts ) 
		                      VALUES (?,?,?,?)');
		
		$stmt->execute(array($right_group_name, $desc, $icon, $posts));	
  
         
        $functions->redirect( 'index.php?mod=admin&sub=groups&action=show', 'metatag|newsite', 2, $lang->t( 'Group was created.' ), 'admin' );
       
    }
    
    //----------------------------------------------------------------
    // Add a new usergroups based on posts
    //----------------------------------------------------------------
    function add_post_group()
    {
        global $db, $tpl, $error, $lang, $functions, $input;
       
        $post_group_name = $_POST['post_group_name'];
        $posts = $_POST['posts'];
        $icon = $_POST['icon'];
          
        // Db Insert
		$stmt = $db->prepare('INSERT INTO ' . DB_PREFIX . 'usergroups ( name, icon, posts ) 
		                      VALUES (?, ?, ?)');
		
		$stmt->execute(array($post_group_name, $icon, $posts));		
            
        $functions->redirect( 'index.php?mod=admin&sub=groups&action=show', 'metatag|newsite', 2, $lang->t( 'Group was created.' ), 'admin' );
        
    }
    
    //----------------------------------------------------------------
    // Edit usergroup
    //----------------------------------------------------------------
    function edit()
    {
        global $db, $tpl, $error, $lang, $functions, $input;
        
      

        $this->output .= $tpl->fetch('admin/groups/edit.tpl');
    }
   
    /**
    * @desc Update the groups list
    */
    function update()
    {
        global $db, $functions, $input, $lang;
        
        // $_POST EINGANG        
        $submit     = $_POST['xsubmit'];
        $del        = $_POST['xdelete'];
        $confirm    = $_POST['confirm'];
        $ids        = isset($_POST['ids'])      ? $_POST['ids'] : array();
        $ids        = isset($_POST['confirm'])  ? unserialize(urldecode($_GET['ids'])) : $ids;
        $delete     = isset($_POST['delete'])   ? $_POST['delete'] : array();
        $delete     = isset($_POST['confirm'])  ? unserialize(urldecode($_GET['delete'])) : $delete;
        
        /* Nichts selected ?
        if ( empty($ids) )
        { 
            $functions->redirect( 'index.php?mod=admin&sub=groups&action=show_all', 'metatag|newsite', 3, $lang->t( 'No Group selected to delete! Returning... ' ), 'admin' );
        }        
        */
        
        // Abbruchmöglichkeit innerhalb der Confirm-Abfrage
        if ( isset( $_POST['abort'] ) )
        {
            $functions->redirect( 'index.php?mod=admin&sub=groups&action=show_all' );
        }
        
        // DB - SELECT       
        $stmt = $db->prepare( 'SELECT group_id, name FROM ' . DB_PREFIX . 'usergroups' );
        $stmt->execute();
        $all_groups = $stmt->fetchAll(PDO::FETCH_ASSOC);
               
        // Gruppen anhand der IDs löschen
        foreach( $all_groups as $key => $value )
        {
            if ( count ( $delete ) > 0 )
            {
                if ( in_array( $value['group_id'], $ids ) )
                {
                    $d = in_array( $value['group_id'], $delete  ) ? 1 : 0;
                    if ( !isset ( $_POST['confirm'] ) )
                    {
                        $functions->redirect( 'index.php?mod=admin&sub=groups&action=update&ids=' . urlencode(serialize($ids)) . '&delete=' . urlencode(serialize($delete)), 'confirm', 3, $lang->t( 'You have selected the following group(s) to delete: ' . $value['name'] . '?'), 'admin' );
                    }
                    else
                    {
                        if ( $d == 1 )
                        {
                            $stmt = $db->prepare( 'DELETE FROM ' . DB_PREFIX . 'usergroups WHERE group_id = ?' );
                            $stmt->execute( array($value['group_id']) );
                        }
                    }
                }
            } // close if
        } // close foreach
        
        $functions->redirect( 'index.php?mod=admin&sub=groups&action=show_all', 'metatag|newsite', 3, $lang->t( 'The groups have been updated.' ), 'admin' );
        
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
       
        $stmt = $db->prepare( 'SELECT * FROM ' . DB_PREFIX . 'usergroups WHERE ?');
        $stmt->execute( array( $group_id ) );
        $stmt->closeCursor();
      
        $this->output .= $tpl->fetch('admin/groups/show_group_members.tpl');
    }
    
}
?>