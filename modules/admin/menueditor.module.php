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

/**
* @desc Security Handler
*/
if (!defined('IN_CS'))
{
    die('You are not allowed to view this page statically.' );
}


class module_admin_menueditor
{
    public $output     = '';
    public $mod_page_title     = '';
    public $additional_head = '';
    
    /**
    * @desc First function to run - switches between $_REQUEST['action'] Vars to the functions
    * @desc Loading necessary language files
    */

    function auto_run()
    {
        global $lang;
        
        $this->mod_page_title = $lang->t('Admin Control Panel :: Menu Editor' );
        
        switch ($_REQUEST['action'])
        {
            case 'show':
                $this->show();
                break;

            case 'update':
                $this->update();
                break;
                
            case 'restore':
                $this->restore();
                break;                
                                
            default:
                $this->show();
                break;
        }
        
        return array( 'OUTPUT'          => $this->output,
                      'MOD_PAGE_TITLE'  => $this->mod_page_title,
                      'ADDITIONAL_HEAD' => $this->additional_head );
    }
    
    /**
    * @desc Show the entrance - welcome message etc.
    */

    function show()
    {
        global $tpl, $error, $lang;
        
        $this->additional_head = '';
        
        $icons = array();
        
        $dir_handler = opendir( TPL_ROOT . '/core/images/icons/' );
        
        while( false !== ($file = readdir($dir_handler)) )
        {
            if ( $file != '.' && $file != '..' && $file != '.svn' )
            {
                $icons[] = $file;
            }
        }
        closedir($dir_handler);
        
        $tpl->assign( 'icons', $icons );

        $this->output .= $tpl->fetch('admin/adminmenu/menueditor.tpl');
    }
    
    /**
    * @desc Update the menu in DB and create a backup
    */

    function update()
    {
        global $db, $tpl, $error, $lang, $functions;
        
        $menu = $_POST['container'];
        
        $stmt = $db->prepare( 'TRUNCATE TABLE ' . DB_PREFIX . 'adminmenu_old' );
        $stmt->execute();
        
        $stmt = $db->prepare( 'INSERT INTO '. DB_PREFIX . 'adminmenu_old SELECT id, parent, type, text, href, title, target, `order`, icon FROM '. DB_PREFIX . 'adminmenu' );
        $stmt->execute();
        
        $stmt = $db->prepare( 'TRUNCATE TABLE ' . DB_PREFIX . 'adminmenu' );
        $stmt->execute();
                
        foreach ( $menu as $key => $value )
        {
            $id = str_replace( 'tree-', '', $key );
            $id = (int) $id;
            $parent = str_replace( 'tree-', '', $value['parent'] );
            $parent = (int) $parent;

            $stmt = $db->prepare( 'INSERT INTO ' . DB_PREFIX . 'adminmenu (id, parent, type, text, href, title, target, `order`, icon) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)' );
            $stmt->execute( array( $id, $parent, $value['type'], html_entity_decode($value['text']), $value['href'], html_entity_decode($value['title']), $value['target'], $value['order'], $value['icon'] ) );
        }
        $functions->redirect( '/index.php?mod=admin&sub=menueditor', 'metatag|newsite', 5, $lang->t( 'The menu was successfully updated...' ), 'admin' );
    }
    
    /**
    * @desc Restore the old menu
    */

    function restore()
    {
        global $db, $tpl, $error, $lang, $functions;
        
        $confirm = $_POST['confirm'];
        $abort   = $_POST['abort'];
        
        if ( !empty($abort) )
        {
            $functions->redirect( '/index.php?mod=admin&sub=menueditor', 'metatag|newsite', 3, $lang->t( 'Aborted. Nothing has been changed.' ), 'admin' );
        }
        
        if ( !empty($confirm) )
        {
            /**
            * @desc Get content of current menu
            */

            $stmt = $db->prepare( 'SELECT * FROM ' . DB_PREFIX .'adminmenu' );
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_NUM);
            
            /**
            * @desc Switch old menu to new table
            */

            $stmt = $db->prepare( 'TRUNCATE TABLE ' . DB_PREFIX . 'adminmenu' );
            $stmt->execute();
            
            $stmt = $db->prepare( 'INSERT INTO '. DB_PREFIX . 'adminmenu SELECT id, parent, type, text, href, title, target, `order`, icon FROM '. DB_PREFIX . 'adminmenu_old' );
            $stmt->execute();

            /**
            * @desc Switch old menu to bck table
            */

            $stmt = $db->prepare( 'TRUNCATE TABLE ' . DB_PREFIX . 'adminmenu_old' );
            $stmt->execute();
            
            $stmt = $db->prepare( 'INSERT INTO ' . DB_PREFIX . 'adminmenu_old (id, parent, type, text, href, title, target, `order`, icon) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)' );
            foreach( $result as $data )
            {
                $stmt->execute( $data );
            }
        
            $functions->redirect( '/index.php?mod=admin&sub=menueditor', 'metatag|newsite', 3, $lang->t( 'Last menu restored...' ), 'admin' );
        }
        else
        {
            $functions->redirect( '/index.php?mod=admin&sub=menueditor&action=restore', 'confirm', 3, $lang->t( 'Do you really want to restore the old menu and delete the current menu?' ), 'admin' );
        }
    }
    
    /**
    * @desc This function generates html-div based menu lists
    */

    function get_html_div($menu = '')
    {
        global $lang, $cfg;
        
        $result = !isset( $result ) ? '' : $result;
        
        if ( empty( $menu ) )
        {
            $menu = $this->build_editormenu();
        }
                
        foreach($menu as $entry)
        {
            /**
            * @desc Init Vars
            */
            $entry['type']      = isset($entry['type'])     ? $entry['type']    : '';
            $entry['content']   = isset($entry['content'])  ? $entry['content'] : '';
            $entry['href']      = isset($entry['href'])     ? $entry['href']    : '';
            $entry['title']     = isset($entry['title'])    ? $entry['title']   : '';
            $entry['target']    = isset($entry['target'])   ? $entry['target']  : '';
            $entry['icon']      = isset($entry['icon'])     ? $entry['icon']    : '';
            $entry['name']      = isset($entry['name'])     ? $entry['name']    : '';
            
            if ( $entry['icon'] == '' )
            {
                $entry['icon'] = 'empty.png';
            }                            
            
            /**
            * @desc Build Menu
            */
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
            
            ################# Start ####################
                           
             if ( $entry['type'] == 'folder')
            {
            
                 $result .= "\n\t";
                
                 if ( $entry['parent'] == 0) 
                 { 
                     $result .= '<td>';
                     $result .= "\n\t";
                     $result .= '<a class="button" href="'.$entry['href'];
                     $result .= '" title="'.htmlspecialchars($entry['title']) . '" target="'.htmlspecialchars($entry['target']) . '">';
                     $result .= '<img class="pic" src="' . WWW_ROOT . '/' . $cfg->tpl_folder . '/core/images/icons/' . $entry['icon'] . '" border="0" width="16" height="16">';
                     $result .= htmlspecialchars($lang->t($entry['name']));
                     $result .= '</a>';
                 }
                 else
                 {
                     $result .= '<a class="item" href="'.$entry['href'];
                     $result .= '" title="'.htmlspecialchars($entry['title']) . '" target="'.htmlspecialchars($entry['target']) . '">';
                     $result .= '<img class="pic" src="' . WWW_ROOT . '/' . $cfg->tpl_folder . '/core/images/icons/' . $entry['icon'] . '" border="0" width="16" height="16">';
                     $result .= htmlspecialchars($lang->t($entry['name']));
                     $result .= '<img class="arrow" src="';
                     $result .= WWW_ROOT . '/' . $cfg->tpl_folder . '/core/images/adminmenu/arrow1.gif" width="4" height="7" alt="" />';
                     $result .= '</a>';
                 }
             }
             
             if ( $entry['type'] != 'folder' )
            {
                $result .= "\n\t";
                $result .= '<a class="item" href="'.$entry['href'];
                $result .= '" title="'.htmlspecialchars($entry['title']) . '" target="'.htmlspecialchars($entry['target']) . '">';
                $result .= '<img  class="pic" src="' . WWW_ROOT . '/' . $cfg->tpl_folder . '/core/images/icons/' . $entry['icon'] . '" border="0" width="16" height="16">';
                $result .= htmlspecialchars($lang->t($entry['name']));
                $result .= '</a>';
            }
                  
                                                  
         	if ( is_array($entry['content']) )
        	{    	  
            	$result .= "\n\t<div class=\"section\">";  
              	$result .= $this->get_html_div($entry['content']);
              	$result .= "\t</div>\n";
           	}
          
                            
            if ( $entry['parent'] == 0) 
            { 
                $result .= "\n\t</td>"; 
            }
                    
        }
        return $result;
    }

    /**
    * @desc This function generates html-div based menu lists - for menu editor
    */

    function get_adminmenu_div( $menu = '' )
    {
        global $lang, $cfg;
        
        $result = '';
        
        if ( empty( $menu ) )
        {
            $menu = $this->build_editormenu();
        }

        foreach($menu as $entry)
        {
            /**
            * @desc Init Vars
            */
            $entry['type']      = isset($entry['type'])     ? $entry['type']    : '';
            $entry['content']   = isset($entry['content'])  ? $entry['content'] : '';
            $entry['href']      = isset($entry['href'])     ? $entry['href']    : '';
            $entry['title']     = isset($entry['title'])    ? $entry['title']   : '';
            $entry['target']    = isset($entry['target'])   ? $entry['target']  : '';
            $entry['icon']      = isset($entry['icon'])     ? $entry['icon']    : '';
            $entry['name']      = isset($entry['name'])     ? $entry['name']    : '';                            
            
            /**
            * @desc Build Menu
            */
            if ( $entry['type'] == 'folder')
            {
                $result .= "<div class=\"folder\">";
                $result .= '<a href="'.$entry['href'];
                $result .= '" title="'.htmlspecialchars($entry['title']) . '" id="' . $entry['icon'] . '" target="'.htmlspecialchars($entry['target']) . '">';
                $result .= htmlspecialchars($lang->t($entry['name'])) . '</a>';
            }
                                  
            if ( $entry['type'] == 'item')
            {
                $result .= "\t<div class=\"doc\">";
            }
            
        	if ( is_array($entry['content']) )
        	{
        	   $result .= $this->get_adminmenu_div($entry['content']);
        	}
        	else
        	{
                if ( $entry['type'] != 'folder' )
                {
                    $result .= '<a href="'.$entry['href'];
                    $result .= '" title="'.htmlspecialchars($entry['title']) . '" id="' . $entry['icon'] . '" target="'.htmlspecialchars($entry['target']) . '">';
                    $result .= htmlspecialchars($lang->t($entry['name'])) . '</a>';
                }
        	}
                                	
        	if ( $entry['type'] == 'item')
            {
                $result .= "</div>\n";
            }
        	
            if ( $entry['type'] == 'folder')
            {
                $result .= "</div>\n";
            }
        }
        
        return $result;
    }
    
    
    /**
    * @desc This function generates html-div based menu lists - for menu editor
    */

    function get_export_div( $menu = '', $level = '', $module = '' )
    {
        global $lang, $cfg;
               
        $result = '';
        
        if ( empty( $menu ) )
        {
            $menu = $this->build_editormenu();
        }
        
        foreach($menu as $entry)
        {
            /**
            * @desc Init Vars
            */
            $entry['type']      = isset($entry['type'])     ? $entry['type']    : '';
            $entry['content']   = isset($entry['content'])  ? $entry['content'] : '';
            $entry['href']      = isset($entry['href'])     ? $entry['href']    : '';
            $entry['title']     = isset($entry['title'])    ? $entry['title']   : '';
            $entry['target']    = isset($entry['target'])   ? $entry['target']  : '';
            $entry['icon']      = isset($entry['icon'])     ? $entry['icon']    : '';
            $entry['name']      = isset($entry['name'])     ? $entry['name']    : '';                            
            
            /**
            * @desc Build Menu
            */                            
            if ( $entry['type'] == 'folder')
            {
                $values = split( ',', $level );
                foreach( $values as $key )
                {
                    if ( $key != '' )
                    {
                        $jscript .= $module.'_dir_'.$key.',';
                    }
                }
                $jscript = preg_replace("/,$/", '', $jscript);
                $result .= "<div class=\"folder\">";
                $result .= "<img src='". WWW_ROOT . '/' . $cfg->tpl_folder . "/core/admin/adminmenu/images/tree-folder.gif' width='18' height='18' border='0'>";
                $result .= '<span class="text">'.$entry['name'];
                $result .= '<input id="'.$module.'_dir_'.$entry['id'].'" type="checkbox" onclick="javascript:checker(\''.$jscript.'\',\''.$module.'_dir_'.$entry['id'].'\');" name="menu_ids[]" value="'.$entry['id'].'"></span>';
                $jscript = '';
            }
                                  
            if ( $entry['type'] == 'item')
            {
                $result .= "\t<div class=\"doc\">";
                $result .= "<img src='". WWW_ROOT . '/' . $cfg->tpl_folder . "/core/admin/adminmenu/images/tree-leaf.gif' width='18' height='18' border='0'>";
            }
            
        	if ( is_array($entry['content']) )
        	{
        	   $result .= $this->get_export_div($entry['content'], $level . $entry['id'] . ',', $module);
        	}
        	else
        	{
                if ( $entry['type'] != 'folder' )
                {
                    $values = split( ',', $level );
                    foreach( $values as $key )
                    {
                        if ( $key != '' )
                        {
                            $jscript .= $module.'_dir_'.$key.',';
                        }
                    }
                    $jscript = preg_replace("/,$/", '', $jscript);

                    $result .= '<span class="text">'.$entry['name'];
                    $result .= '<input id="'.$module.'_dir_'.$entry['id'].'" type="checkbox" onclick="javascript:checker(\''.$jscript.'\',\''.$module.'_dir_'.$entry['id'].'\');" name="menu_ids[]" value="' . $level . $entry['id'] . '"></span>';
                    $jscript = '';
                }
        	}
                                	
        	if ( $entry['type'] == 'item')
            {
                $result .= "</div>\n";
            }
        	
            if ( $entry['type'] == 'folder')
            {
                $result .= "</div>\n";
            }
        }
        
        return $result;
    }
        
    /**
    * @desc Read menu from DB
    */

    function build_editormenu( &$result = '', $parent = 0, $level = 0)
    {
        global $db;
    
        if ( empty($result) )
        {        
            $stmt = $db->prepare('SELECT *
                                  FROM ' . DB_PREFIX .'adminmenu
                                  ORDER BY `order` ASC, parent ASC');
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
                                                    'name'   => $result[$i]['text'],
                                                    'level'  => $level,
                                                    'type'   => $result[$i]['type'],
                                                    'parent' => $result[$i]['parent'],
                                                    'id'     => $result[$i]['id'],
                                                    'href'   => $result[$i]['href'],
                                                    'title'  => $result[$i]['title'],
                                                    'target' => $result[$i]['target'],
                                                    'order'  => $result[$i]['order'],
                                                    'icon'   => $result[$i]['icon'],
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