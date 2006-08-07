<?php
/**
* Index Modul Handler Class
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


class module_admin
{
    public $output     = '';
    public $mod_page_title     = '';
    public $additional_head = '';
    
    //----------------------------------------------------------------
    // First function to run - switches between $_REQUEST['action'] Vars to the functions
    // Loading necessary language files
    //----------------------------------------------------------------
    function auto_run()
    {
        global $lang;
        
        $this->mod_page_title = $lang->t('Admin Control Panel' );
        
        switch ($_REQUEST['action'])
        {
        case 'show':
            $this->show();
            break;
            default:
            $this->show();
            break;
        }
        
        return array( 'OUTPUT'          => $this->output,
                      'MOD_PAGE_TITLE'  => $this->mod_page_title,
                      'ADDITIONAL_HEAD' => $this->additional_head );
    }
    
    //----------------------------------------------------------------
    // Show the entracne - welcome message etc.
    //----------------------------------------------------------------
    function show()
    {
        global $db, $tpl, $error, $lang;
        
        $row    = 0;
        $col    = 0;
        $images = array();

        $stmt = $db->prepare( 'SELECT * FROM ' . DB_PREFIX . 'admin_shortcuts ORDER BY `order` ASC, title ASC' );
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if ( is_array ( $result ) )
        {
            foreach( $result as $data )
            {
                $col++;
                $images[$row][$col] = $data;
                
                if ( $col == 4 )
                {
                    $row = $row+1;
                    $col = 0;
                }
            }
        }
        
        /*
        $files = array( 'console', 'downloads', 'articles', 'links', 'calendar', 'time', 'email', 'shoutbox', 'help', 'security', 'gallery', 'system', 'replays', 'news', 'settings', 'users', 'backup', 'templates' );
        $stmt = $db->prepare( "INSERT INTO cs_admin_shortcuts ( href, title, file_name ) VALUES ( ?, ?, ? )" );
        foreach( $files as $key )
        {
            $stmt->execute( array( '/index.php?mod=admin&sub='.$key, $key, $key.'.png' ) );
        }
        */
        
        $tpl->assign( 'shortcuts', $images );
        $this->output .= $tpl->fetch('admin/welcome.tpl');
        $this->output .= $tpl->fetch('admin/shortcuts.tpl');
    }
}
?>