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
    
  
    
}
?>