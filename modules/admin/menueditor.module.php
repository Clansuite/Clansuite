<?php
/**
* Menueditor Admin Class
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


class module_admin_menueditor
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
        
        $this->mod_page_title = $lang->t('Admin Control Panel :: Menueditor' );
        
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
    // Show the entrance - welcome message etc.
    //----------------------------------------------------------------
    function show()
    {
        global $tpl, $error, $lang;
        
        $this->additional_head = '';
        
        
        $this->output .= $tpl->fetch('admin/adminmenu/menueditor.tpl');
    }
    
    //----------------------------------------------------------------
    // This function generates html-div based menu lists
    //----------------------------------------------------------------
    function get_html_div($menu = '')
    {
        if ( empty( $menu ) )
        {
            $menu = $this->build_editormenu();
        }
                
        foreach($menu as $entry)
        {
        	if ($entry['href'] == '' )
            {
                $entry['href'] = 'javascript:void(0)';
            }
            else
            {
                $c = parse_url($$entry['href']);
                if( !array_key_exists('host', $c) )
                {
                    $entry['href'] = WWW_ROOT . $entry['href'];
                }
            }
            
            ################# Start ####################
                           
             if (htmlentities($entry['type']) == 'folder')
            {
            
                 $result .= "\n\t";
                
                 if (htmlentities($entry['parent']) == '0') 
                 { 
                 $result .= "<td>";
                 $result .= "\n\t";          
                 $result .= '<a class="button" href="'.$entry['href'];
                 $result .= '">'.htmlentities($entry['name']);
                 $result .= "</a>";
                 }
                 else
                 {
                 $result .= '<a class="item" href="'.$entry['href'];
                 $result .= '">'.htmlentities($entry['name']);
                 $result .= '<img class="arrow" src="';
                 $result .= WWW_ROOT . '/templates/core/images/adminmenu/arrow1.gif" width="4" height="7" alt="" />';
                 $result .= '</a>';
                 }
             }
             
             if ( htmlentities($entry['type']) != 'folder' )
                {
                    $result .= "\n\t";
                    $result .= '<a class="item" href="'.$entry['href'];
                    $result .= '">'.htmlentities($entry['name']) . '</a>';
                }
                  
                                                  
         	if ( is_array($entry['content']) )
        	{    	  
        	$result .= "\n\t<div class=\"section\">";  
          	$result .= $this->get_html_div($entry['content']);
          	$result .= "\t</div>\n";
           	}
          
                            
            if (htmlentities($entry['parent']) == '0') 
            { 
            $result .= "\n\t</td>"; 
            }
                    
        }
        return $result;
    }

    //----------------------------------------------------------------
    // This function generates html-div based menu lists - for menu editor
    //----------------------------------------------------------------
    function get_adminmenu_div( $menu = '' )
    {
        if ( empty( $menu ) )
        {
            $menu = $this->build_editormenu();
        }

        foreach($menu as $entry)
        {

        	
            if ($entry['href'] == '' )
            {
                $entry['href'] = 'javascript:void(0)';
            }
            else
            {
                $c = parse_url($entry['href']);
                if( !array_key_exists('host', $c) )
                {
                    $entry['href'] = WWW_ROOT . $entry['href'];
                }
            }
                            
            if (htmlentities($entry['type']) == 'folder')
            {
                $result .= "<div class=\"folder\">$entry[name]\n";
            }
                                  
            if (htmlentities($entry['type']) == 'item')
            {
                $result .= "\t<div class=\"doc\">";
            }
            
        	if ( is_array($entry['content']) )
        	{
        	   $result .= $this->get_adminmenu_div($entry['content']);
        	}
        	else
        	{
                if ( htmlentities($entry['type']) != 'folder' )
                {
                    $result .= '<a href="'.$entry['href'];
                    $result .= '">'.htmlentities($entry['name']) . '</a>';
                }
        	}
                                	
        	if (htmlentities($entry['type']) == 'item')
            {
                $result .= "</div>\n";
            }
        	
            if (htmlentities($entry['type']) == 'folder')
            {
                $result .= "</div>\n";
            }
        }
        
        return $result;
    }
    
    //----------------------------------------------------------------
    // Read menu from DB
    //----------------------------------------------------------------
    function build_editormenu( &$result = '', $parent = 0, $level = 0)
    {
        global $db;
    
        if ( empty($result) )
        {        
            $stmt = $db->prepare('SELECT *
                                  FROM ' . DB_PREFIX .'adminmenu
                                  ORDER BY id ASC, parent ASC');
            $stmt->execute();

            $result = $stmt->fetchAll( PDO::FETCH_ASSOC );
        }
        $output = array();
        $rows = count($result);
        for($i = 0; $i < $rows; $i++)
        {
            if($result[$i]['parent'] == $parent)
            {
                $output[$result[$i]['id']] = array(
                    'name' => $result[$i]['title'],
                    'level' => $level,
                    'type' => $result[$i]['type'],
                    'parent' => $result[$i]['parent'],
                    'id' => $result[$i]['id'],
                    'href' => $result[$i]['href']
                    );
                $output[$result[$i]['id']]['content'] = $this->build_editormenu($result, $result[$i]['id'], $level + 1);
                if (count($output[$result[$i]['id']]['content']) == 0)
                    unset($output[$result[$i]['id']]['content']);
                else
                    $output[$result[$i]['id']]['expanded'] = true;
            }
        }
        return array_values($output);
    }
}
?>