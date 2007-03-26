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
    public $output              = '';
    public $additional_head     = '';
    public $suppress_wrapper    = '';

    //----------------------------------------------------------------
    // First function to run - switches between $_REQUEST['action'] Vars to the functions
    // Loading necessary language files
    //----------------------------------------------------------------
    function auto_run()
    {
        global $lang, $trail;

        // Set Pagetitle and Breadcrumbs
        $trail->addStep($lang->t('Admin'), '/index.php?mod=admin');
        $trail->addStep($lang->t('Groups'), '/index.php?mod=admin&sub=groups');

        switch ($_REQUEST['action'])
        {   
            // direct actions on groups
            default:
            case 'show':
                $trail->addStep($lang->t('Show'), '/index.php?mod=admin&sub=groups&action=show');
                $this->show_groups();
                break;

            case 'create':
                $trail->addStep($lang->t('Create a new group'), '/index.php?mod=admin&sub=groups&action=create');
                $this->create_group();
                break;

            case 'edit':
                $trail->addStep($lang->t('Edit a group'), '/index.php?mod=admin&sub=groups&action=edit');
                $this->edit_group();
                break;

            case 'delete':
                $this->delete_group();
                break;
            
            // indirect actions related to groups/membership operations
            case 'members':
                $trail->addStep($lang->t('Show Group and its Members'), '/index.php?mod=admin&sub=groups&action=members');
                $this->show_group_members($group_id);
                break;
            
            case 'add_members':
                $trail->addStep($lang->t('Add members'), '/index.php?mod=admin&sub=groups&action=add_members');
                $this->add_members();
                break;

         }

        return array( 'OUTPUT'          => $this->output,
                      'ADDITIONAL_HEAD' => $this->additional_head,
                      'SUPPRESS_WRAPPER'=> $this->suppress_wrapper );
    }

    /**
    * @desc Show all groups
    */
    /**
     * Sets the color of the eyes.
     *
     * @param  string $eyeColor Color of the eyes
     * @return boolean
     */
    function show_groups()
    {
        global $db, $tpl, $error, $lang;

        // init
        $icons  = array();
        $groups = array();

        // Extrapolate icons from dir
        $icons = glob( ROOT_TPL . '/core/images/groups/{*.jpg,*.JPG,*.png,*.PNG,*.gif,*.GIF}', GLOB_BRACE);

        // Get the DB result sets (get the usergroups)
        $stmt = $db->prepare( 'SELECT * FROM ' . DB_PREFIX . 'groups ORDER BY sortorder ASC' );
        $stmt->execute();
        $groups = $stmt->fetchAll(PDO::FETCH_NAMED);

        // Run thorugh all the groups
        $runtimes = count($groups);
        for ($i=0; $i<=$runtimes-1; $i++)
        {
            // Get the members of the group
            $stmt2 = $db->prepare('SELECT cs.nick, cs.user_id
                                   FROM ' . DB_PREFIX . 'user_groups cu,
                                        ' . DB_PREFIX . 'users cs
                                   WHERE cs.user_id = cu.user_id
                                   AND cu.group_id = ?');

            $stmt2->execute( array ( $groups[$i]['group_id'] ));
            $user_in_group = $stmt2->fetchAll(PDO::FETCH_NAMED);

            // Assign to global tpl var
            $groups[$i]['users'] = $user_in_group;
        }

        // Assing to template & output
        $tpl->assign( 'icons'   , $icons );
        $tpl->assign( 'groups'  , $groups );

        $this->output .= $tpl->fetch('admin/groups/show.tpl');
    }

    /**
    * @desc Creates a new group
    */
    function create_group()
    {
        global $db, $tpl, $error, $lang, $functions, $input;

        // Init
        $submit     = $_POST['submit'];
        $info       = $_POST['info'];
        $sets       = '';
        
        // Icons & Images
        $icons  = array();
        foreach( glob( ROOT_TPL . '/core/images/groups/icons/{*.jpg,*.JPG,*.png,*.PNG,*.gif,*.GIF}', GLOB_BRACE) as $file )
        {
            $icons[] = preg_replace( '#^(.*)/#', '', $file);
        }
        $tpl->assign( 'icons'   , $icons );


        $images  = array();
        foreach( glob( ROOT_TPL . '/core/images/groups/images/{*.jpg,*.JPG,*.png,*.PNG,*.gif,*.GIF}', GLOB_BRACE) as $file )
        {
            $images[] = preg_replace( '#^(.*)/#', '', $file);
        }
        $tpl->assign( 'images'   , $images );

        // Select the areas and assing the rights
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
            // Group already stored?
            $stmt = $db->prepare( 'SELECT * FROM ' . DB_PREFIX . 'groups WHERE name = ?' );
            $stmt->execute( array( $info['name'] ) );
            $group = $stmt->fetch(PDO::FETCH_ASSOC);
            if ( is_array( $group ) )
            {
                $err['name_already'] = 1;
            }

            // Form filled?
            if( empty($info['name']) OR
                empty($info['description']))
            {
                $err['fill_form'] = 1;
            }
        }

        // No errors - procceed to edit
        if ( !empty($submit) AND count($err) == 0 )
        {
            // Update the DB
            $sets =  'sortorder = ?, name = ?, description = ?, icon = ?, image = ?, color = ?';
            $stmt = $db->prepare( 'INSERT ' . DB_PREFIX . 'groups SET ' . $sets );
            $stmt->execute( array ( $info['sortorder'],
                                    $info['name'],
                                    $info['description'],
                                    $info['icon'],
                                    $info['image'],
                                    $info['color'] ) );


            // Get the id
            $stmt = $db->prepare( 'SELECT group_id FROM ' . DB_PREFIX . 'groups WHERE name = ?' );
            $stmt->execute( array( $info['name'] ) );
            $id_array = $stmt->fetch(PDO::FETCH_ASSOC);

            // Insert the rights
            $sets =  'right_id = ?, group_id = ?';
            $stmt = $db->prepare( 'INSERT ' . DB_PREFIX . 'group_rights SET ' . $sets );
            $info['rights'] = array_unique( $info['rights'] );
            foreach( $info['rights'] as $right_id )
            {
                $stmt->execute( array ( $right_id, $id_array['group_id'] ) );
            }

            // Redirect...
            $functions->redirect( 'index.php?mod=admin&sub=groups&action=show_groups', 'metatag|newsite', 3, $lang->t( 'The group has been created.' ), 'admin' );
        }

        // Assign & Show template
        $tpl->assign( 'areas'       , $areas );
        $tpl->assign( 'err'         , $err );
        $this->output .= $tpl->fetch('admin/groups/create.tpl');
    }

    /**
    * @desc Add/Remove members
    */
    function add_members()
    {
        global $db, $tpl, $error, $lang, $functions, $input;

        // Incoming Vars
        $submit   = $_POST['submit'];
        $group_id = $_GET['id'];
        $users    = !empty($_POST['PickList']) ? $_POST['PickList'] : array();

        if( !empty( $submit ) )
        {
            $stmt = $db->prepare( 'DELETE FROM ' . DB_PREFIX . 'user_groups WHERE group_id = ?' );
            $stmt->execute( array( $group_id ) );

            $stmt = $db->prepare( 'INSERT ' . DB_PREFIX . 'user_groups SET user_id = ?, group_id = ?' );
            foreach( $users as $id )
            {
                $stmt->execute( array ( $id, $group_id ) );
            }
            $functions->redirect( 'index.php?mod=admin&sub=groups&action=show_groups', 'metatag|newsite', 3, $lang->t( 'The Groupmembers have been set.' ), 'admin' );
        }
        else
        {
            // Get the members of the group
            $stmt2 = $db->prepare('SELECT u.nick, u.user_id
                                   FROM ' . DB_PREFIX . 'users u,
                                        ' . DB_PREFIX . 'user_groups ug
                                   WHERE u.user_id = ug.user_id
                                   AND ug.group_id = ? ORDER BY nick ASC');
            $stmt2->execute( array ( $group_id ));
            $info['users_in_group'] = $stmt2->fetchAll(PDO::FETCH_NAMED);

            // Get the members not in the group
            $stmt2 = $db->prepare('SELECT user_id, nick FROM ' . DB_PREFIX . 'users WHERE user_id
                                   NOT IN( SELECT user_id FROM ' . DB_PREFIX . 'user_groups WHERE group_id = ? ) ORDER BY nick ASC');
            $stmt2->execute( array ( $group_id ));
            $info['users_not_in_group'] = $stmt2->fetchAll(PDO::FETCH_NAMED);
            
            // Get Informations about $group_id
            $stmt3 = $db->prepare('SELECT g.*
                                   FROM ' . DB_PREFIX . 'groups g
                                   WHERE g.group_id = ?');
            $stmt3->execute( array ( $group_id ));
            $info['group'] = $stmt3->fetch(PDO::FETCH_NAMED);
       }

        $tpl->assign('info', $info);
        $this->output .= $tpl->fetch( 'admin/groups/add_members.tpl' );
    }

    /**
    * @desc Edit a group
    */
    function edit_group()
    {
        global $db, $tpl, $error, $lang, $functions, $input;

        // Init
        $submit     = $_POST['submit'];
        $info       = $_POST['info'];
        $id         = isset($_GET['id']) ? (int)$_GET['id'] : (int)$_POST['info']['id'];
        $sets       = '';
        $images     = array();
        $icons      = array();

        // Get the images
        foreach( glob( ROOT_TPL . '/core/images/groups/icons/{*.jpg,*.JPG,*.png,*.PNG,*.gif,*.GIF}', GLOB_BRACE) as $file )
        {
            $icons[] = preg_replace( '#^(.*)/#', '', $file);
        }
        $tpl->assign( 'icons'   , $icons );


        foreach( glob( ROOT_TPL . '/core/images/groups/images/{*.jpg,*.JPG,*.png,*.PNG,*.gif,*.GIF}', GLOB_BRACE) as $file )
        {
            $images[] = preg_replace( '#^(.*)/#', '', $file);
        }
        $tpl->assign( 'images'   , $images );

        // Group already stored?
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
            // Select the group
            $stmt = $db->prepare( 'SELECT * FROM ' . DB_PREFIX . 'groups WHERE group_id = ?' );
            $stmt->execute( array( $id ) );
            $info = $stmt->fetch(PDO::FETCH_ASSOC);

            // Select the permissions of group
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

            // Select the areas and asigning the rights
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

        // Form filled?
        if( ( empty($info['name'] ) OR
              empty($info['description']) ) AND !empty($submit) )
        {
            $err['fill_form'] = 1;
        }

        if ( !empty($submit) AND count($err) == 0 )
        {
            // Update the DB
            $sets =  'sortorder = ?, name = ?, description = ?, icon = ?, image = ?, color = ?';
            $stmt = $db->prepare( 'UPDATE ' . DB_PREFIX . 'groups SET ' . $sets . ' WHERE group_id = ?' );
            $stmt->execute( array ( $info['sortorder'],
                                    $info['name'],
                                    $info['description'],
                                    $info['icon'],
                                    $info['image'],
                                    $info['color'],
                                    $info['group_id'] ) );

            // Drop & Insert the rights
            $stmt = $db->prepare( 'DELETE FROM ' . DB_PREFIX . 'group_rights WHERE group_id = ?' );
            $stmt->execute( array ( $info['group_id'] ) );

            $sets =  'right_id = ?, group_id = ?';
            $stmt = $db->prepare( 'INSERT ' . DB_PREFIX . 'group_rights SET ' . $sets );
            $info['rights'] = array_unique( $info['rights'] );
            foreach( $info['rights'] as $right_id )
            {
                $stmt->execute( array ( $right_id, $info['group_id'] ) );
            }

            // Redirect to main
            $functions->redirect( 'index.php?mod=admin&sub=groups&action=show_groups', 'metatag|newsite', 3, $lang->t( 'The group has been edited.' ), 'admin' );

        }

        // Assign & show template
        $tpl->assign( 'err'         , $err );
        $tpl->assign( 'info'        , $info );
        $this->output .= $tpl->fetch('admin/groups/edit.tpl');
    }

    /**
    * @desc Delete the groups list
    */
    function delete_group()
    {
        global $db, $functions, $input, $lang;

        // Init
        $submit     = $_POST['submit'];
        $confirm    = $_POST['confirm'];
        $ids        = isset($_POST['ids'])      ? $_POST['ids'] : array();
        $ids        = isset($_POST['confirm'])  ? unserialize(urldecode($_GET['ids'])) : $ids;
        $delete     = isset($_POST['delete'])   ? $_POST['delete'] : array();
        $delete     = isset($_POST['confirm'])  ? unserialize(urldecode($_GET['delete'])) : $delete;

        // Check, if there is a delete request
        if ( count($delete) < 1 )
        {
            $functions->redirect( 'index.php?mod=admin&sub=groups&action=show_groups', 'metatag|newsite', 3, $lang->t( 'No groups selected to delete! Aborted... ' ), 'admin' );
        }

        // Abort...
        if ( isset( $_POST['abort'] ) )
        {
            $functions->redirect( 'index.php?mod=admin&sub=groups&action=show_groups' );
        }

        // Select from DB
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

        // Delete Groups
        // note by vain
        // @todo: groupnumber to delete = query count, cause each one is a delete-query
        // @see: users-admin module how to edit the query to delete all with one!!
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

        // Redirect to main
        $functions->redirect( 'index.php?mod=admin&sub=groups&action=show_groups', 'metatag|newsite', 3, $lang->t( 'The groups have been delete.' ), 'admin' );

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