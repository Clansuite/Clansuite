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
            case 'show':
                $this->mod_page_title = $lang->t( 'Show Permissions' );
                $this->show_permissions();
                break;
                
            case 'delete':
                $this->mod_page_title = $lang->t( 'Delete Permissions' );
                $this->delete_permissions();
                break;
      
            default:
                $this->mod_page_title = $lang->t( 'Show Permissions' );
                $this->show_permissions();
            break;
        }
        
        return array( 'OUTPUT'          => $this->output,
                      'MOD_PAGE_TITLE'  => $this->mod_page_title,
                      'ADDITIONAL_HEAD' => $this->additional_head,
                      'SUPPRESS_WRAPPER'=> $this->suppress_wrapper );
    }
    
    //----------------------------------------------------------------
    // Show all permissions
    //----------------------------------------------------------------
    function show_permissions()
    {
        global $db, $tpl, $error, $lang;

        // Ausgabe der Benutzergruppen basierend auf Rechten
        $stmt = $db->prepare( 'SELECT * FROM ' . DB_PREFIX . 'rights ORDER BY name ASC' );
        $stmt->execute();
        $permissions_data = $stmt->fetchAll(PDO::FETCH_NAMED);
        
        if ( is_array( $permissions_data ) )
        {
            $tpl->assign('permissions_data', $permissions_data);
        }
        else
        {
        $this->output .= 'There was an error while acquiring the usergroups-data.';
        }
        
       
        $this->output .= $tpl->fetch('admin/permissions/show.tpl');
    }
    
  /**
    * @desc Delete permissions
    */
    function delete_permissions()
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
            $functions->redirect( 'index.php?mod=admin&sub=permissions&action=show', 'metatag|newsite', 3, $lang->t( 'No permissions selected to delete! Aborted... ' ), 'admin' );
        }
        
        // Abbruchmöglichkeit innerhalb der Confirm-Abfrage
        if ( isset( $_POST['abort'] ) )
        {
            $functions->redirect( 'index.php?mod=admin&sub=permissions&action=show' );
        }
        
        // DB - SELECT       
        $stmt = $db->prepare( 'SELECT right_id, name FROM ' . DB_PREFIX . 'rights' );
        $stmt->execute();
        $all_permissions = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach( $all_permissions as $key => $value )
        {
            if( in_array( $value['right_id'], $delete  ) )
            {
                $names .= '<br /><b>' .  $value['name'] . '</b>';
            }
        }       
        // Permissions anhand der IDs loeschen
        foreach( $all_permissions as $key => $value )
        {
            if ( count ( $delete ) > 0 )
            {
                if ( in_array( $value['right_id'], $ids ) )
                {
                    $d = in_array( $value['right_id'], $delete  ) ? 1 : 0;
                    if ( !isset ( $_POST['confirm'] ) )
                    {
                        $functions->redirect( 'index.php?mod=admin&sub=permissions&action=delete&ids=' . urlencode(serialize($ids)) . '&delete=' . urlencode(serialize($delete)), 'confirm', 3, $lang->t( 'You have selected the following permission(s) to be deleted: ' . $names ), 'admin' );
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
            } // close if
        } // close foreach
        
        $functions->redirect( 'index.php?mod=admin&sub=permissions&action=show', 'metatag|newsite', 3, $lang->t( 'The permissions have been deleted.' ), 'admin' );
        
    }
    
}
?>