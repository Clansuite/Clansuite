<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-Andre Koch � 2005 - onwards
    * http://www.clansuite.com/
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
    *
    *    You should have received a copy of the GNU General Public License
    *    along with this program; if not, write to the Free Software
    *    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
    *
    * @license    GNU/GPL, see COPYING.txt
    *
    * @author     Jens-Andre Koch <vain@clansuite.com>
    * @copyright  Copyleft: All rights reserved. Jens-Andre Koch (2005-onwards)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    * @since      File available since Release 0.2
    *
    * @version    SVN: $Id$
    */

//Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}

/**
 * Module:       Admin
 * Submodule:    Menueditor
 *
 * @author     Florian Wolf <xsign.dll@clansuite.com>
 * @author     Jens-Andre Koch <vain@clansuite.com>
 * @copyright  Copyleft: All rights reserved. Jens-Andre Koch (2005-onwards)
 *
 * @package clansuite
 * @subpackage module_admin
 * @category modules
 */
class module_admin_menueditor extends ModuleController implements Clansuite_Module_Interface
{
    /**
     * Module_Admin_Menueditor -> Execute
     */
    public function execute(httprequest $request, httpresponse $response)
    {
        # proceed to the requested action
        $this->processActionController($request);
    }

    /**
    * @desc First function to run - switches between $_REQUEST['action'] Vars to the functions
    * @desc Loading necessary language files
    */

    function auto_run()
    {
        global $lang, $trail;

        $params = func_get_args();

        switch ($_REQUEST['action'])
        {
            case 'update':
                $this->update();
                break;

            case 'restore':
                $this->restore();
                break;

            case 'get_adminmenu_div':
                $this->output .= call_user_func_array( array( $this, 'get_adminmenu_div' ), $params );
                break;

            case 'get_html_div':
                $this->output .= call_user_func_array( array( $this, 'get_html_div' ), $params );
                break;

            case 'get_export_div':
                $this->output .= call_user_func_array( array( $this, 'get_export_div' ), $params );
                break;

        }

        return array( 'OUTPUT'          => $this->output,
                      'ADDITIONAL_HEAD' => $this->additional_head,
                      'SUPPRESS_WRAPPER'=> $this->suppress_wrapper );
    }

    /**
     * Shows the Admin Menu Editor
     */
    public function action_menueditor_show()
    {
        // Set Pagetitle and Breadcrumbs
        trail::addStep( _('Show'), '/index.php?mod=admin&amp;sub=menueditor&amp;action=show');

        // Setup Icons Array
        $icons = array();

        // Get Icons from Directory
        $dir_handler = opendir( ROOT_THEMES . '/core/images/icons/' );

        while( false !== ($file = readdir($dir_handler)) )
        {
            if (substr($file,0,1) != '.')
            {
                $icons[] = $file;
            }
        }

        closedir($dir_handler);

        // Assign ICONS to View
        $this->getView()->assign('icons', $icons );
        // Set Layout Template
        $this->getView()->setLayoutTemplate('admin/index.tpl');
        // specifiy the template manually
        $this->setTemplate('admin/adminmenu/menueditor.tpl');
        // Prepare the Output
        $this->prepareOutput();
    }

    /**
     * Update the menu in DB and create a backup
     */
    public function action_menueditor_update()
    {
        // Incoming Variables
        $menu = $_POST['container'];

        // Clear Backup Table
        // @todo: REPLACE ??
        $stmt = $db->prepare( 'TRUNCATE TABLE ' . DB_PREFIX . 'adminmenu_backup' );
        $stmt->execute();

        // Insert Into Backup Table
        $stmt = $db->prepare( 'INSERT INTO '. DB_PREFIX . 'adminmenu_backup SELECT `id`, `parent`, `type`, `text`, `href`, `title`, `target`, `order`, `icon`, `right_to_view` FROM '. DB_PREFIX . 'adminmenu' );
        $stmt->execute();

        // Clear Original Adminmenu Table
        $stmt = $db->prepare( 'TRUNCATE TABLE ' . DB_PREFIX . 'adminmenu' );
        $stmt->execute();

        foreach ( $menu as $key => $value )
        {
            $id = str_replace( 'tree-', '', $key );
            $id = (int) $id;
            $parent = str_replace( 'tree-', '', $value['parent'] );
            $parent = (int) $parent;

            $value['href'] = preg_replace("/&(?!amp;)/","&amp;", $value['href']);

            $stmt = $db->prepare( 'INSERT INTO ' . DB_PREFIX . 'adminmenu (`id`, `parent`, `type`, `text`, `href`, `title`, `target`, `order`, `icon`, `right_to_view`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)' );
            $stmt->execute( array( $id, $parent, $value['type'], html_entity_decode($value['text']), $value['href'], html_entity_decode($value['title']), $value['target'], $value['order'], $value['icon'], $value['right_to_view'] ) );
        }
        $functions->redirect( 'index.php?mod=admin&sub=menueditor', 'metatag|newsite', 5, $lang->t( 'The menu was successfully updated...' ), 'admin' );
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
            $functions->redirect( 'index.php?mod=admin&sub=menueditor', 'metatag|newsite', 3, $lang->t( 'Aborted. Nothing has been changed.' ), 'admin' );
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

            $stmt = $db->prepare( 'INSERT INTO '. DB_PREFIX . 'adminmenu SELECT `id`, `parent`, `type`, `text`, `href`, `title`, `target`, `order`, `icon`, `right_to_view` FROM '. DB_PREFIX . 'adminmenu_backup' );
            $stmt->execute();

            /**
            * @desc Switch old menu to bck table
            */

            $stmt = $db->prepare( 'TRUNCATE TABLE ' . DB_PREFIX . 'adminmenu_backup' );
            $stmt->execute();

            $stmt = $db->prepare( 'INSERT INTO ' . DB_PREFIX . 'adminmenu_backup (`id`, `parent`, `type`, `text`, `href`, `title`, `target`, `order`, `icon`, `right_to_view`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)' );
            foreach( $result as $data )
            {
                $stmt->execute( $data );
            }

            $functions->redirect( 'index.php?mod=admin&sub=menueditor', 'metatag|newsite', 3, $lang->t( 'Last menu restored...' ), 'admin' );
        }
        else
        {
            $functions->redirect( 'index.php?mod=admin&sub=menueditor&action=restore', 'confirm', 3, $lang->t( 'Do you really want to restore the old menu and delete the current menu?' ), 'admin' );
        }
    }

    /**
     * This function generates html-div based menu lists.
     *
     * Watch out, this is a recursive method!
     *
     * @param $menu
     * @access public
     *
     * @return Returns a HTML String (Div Menu)
     */
    public function get_html_div($menu = '')
    {
        # $result is relevant to the recursion
        $result = !isset( $result ) ? '' : $result;

        if ( empty( $menu ) )
        {
            $menu = $this->fetch_adminmenu(true);
        }

        foreach($menu as $entry)
        {
            /**
            * @desc Init Vars
            */
            $entry['type']          = isset($entry['type'])             ? $entry['type']            : '';
            $entry['content']       = isset($entry['content'])          ? $entry['content']         : '';
            $entry['href']          = isset($entry['href'])             ? $entry['href']            : '';
            $entry['title']         = isset($entry['title'])            ? $entry['title']           : '';
            $entry['target']        = isset($entry['target'])           ? $entry['target']          : '';
            $entry['icon']          = isset($entry['icon'])             ? $entry['icon']            : '';
            $entry['name']          = isset($entry['name'])             ? $entry['name']            : '';
            $entry['right_to_view'] = isset($entry['right_to_view'])    ? $entry['right_to_view']   : '';

            // Set empty image, if no image is given [ IE HACK ]
            if ( $entry['icon'] == '' )
            {
                $entry['icon'] = 'empty.png';
            }

            /**
            * @desc Build Menu
            */
            if ( $entry['href'] == '' && isset( $entry['href'] ) )
            {
                $entry['href'] = 'javascript:void(0)';
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
                     $result .= '<img alt="Image of Folder" class="pic" src="' . WWW_ROOT_THEMES_CORE .'/images/icons/' . $entry['icon'] . '" border="0" width="16" height="16" />';

                     if( $entry['icon'] != 'empty.png' )
                     {
                         $result .= '<span class="element">' . htmlspecialchars(_($entry['name'])) . '</span>';
                     }
                     else
                     {
                         $result .= htmlspecialchars(_($entry['name']));
                     }
                     $result .= '<img alt="dots" class="nubs_pic" src="' . WWW_ROOT_THEMES_CORE . '/images/adminmenu/nubs.gif" /></a>';
                 }
                 else
                 {
                     $result .= '<a class="item" href="'.$entry['href'];
                     $result .= '" title="'.htmlspecialchars($entry['title']) . '" target="'.htmlspecialchars($entry['target']) . '">';
                     $result .= '<img alt="icon" class="pic" src="' . WWW_ROOT_THEMES_CORE .'/images/icons/' . $entry['icon'] . '" border="0" width="16" height="16" />';
                     $result .= htmlspecialchars(_($entry['name']));
                     $result .= '<img alt="arrow" class="arrow" src="' . WWW_ROOT_THEMES_CORE . '/images/adminmenu/arrow1.gif" width="4" height="7" />';
                     $result .= '</a>';
                 }
             }

             if ( $entry['type'] != 'folder' && isset($entry['type']) )
            {
                $result .= "\n\t";
                $result .= '<a class="item" href="'.$entry['href'];
                $result .= '" title="'.htmlspecialchars($entry['title']) . '" target="' . htmlspecialchars($entry['target']) . '">';
                $result .= '<img alt="Item" class="pic" src="' . WWW_ROOT_THEMES_CORE .'/images/icons/' . $entry['icon'] . '" border="0" width="16" height="16" />';
                $result .= htmlspecialchars(_($entry['name']));
                $result .= '</a>';
            }


         	if ( is_array($entry['content']) )
        	{
                $result .= "\n\t<div class=\"section\">";
                # recursion
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
     * This function generates html-div based menu lists - for menu editor
     *
     * @param $menu String
     */
    public function get_adminmenu_div( $menu = '' )
    {
        #echo 'get adminmenu div called'; exit;

        $result = '';

        if ( empty( $menu ) )
        {
            $menu = $this->fetch_adminmenu(false);
        }

        foreach($menu as $entry)
        {
            /**
             * Init Variables
             */
            $entry['type']              = isset($entry['type'])             ? $entry['type']            : '';
            $entry['content']           = isset($entry['content'])          ? $entry['content']         : '';
            $entry['href']              = isset($entry['href'])             ? $entry['href']            : '';
            $entry['title']             = isset($entry['title'])            ? $entry['title']           : '';
            $entry['target']            = isset($entry['target'])           ? $entry['target']          : '';
            $entry['icon']              = isset($entry['icon'])             ? $entry['icon']            : '';
            $entry['name']              = isset($entry['name'])             ? $entry['name']            : '';
            $entry['right_to_view']     = isset($entry['right_to_view'])    ? $entry['right_to_view']   : '';

            /**
             *  Build Menu
             */
            if ( $entry['type'] == 'folder')
            {
                $result .= "<div class=\"folder\">";
                $result .= '<a href="'.$entry['href'];
                $result .= '" title="'.htmlspecialchars($entry['title']) . '" target="'.htmlspecialchars($entry['target']) . '___' . $entry['icon'] . '___' . htmlspecialchars($entry['right_to_view']) . '">';
                $result .= htmlspecialchars( _($entry['name'])) . '</a>';
            }

            # it's an item
            if ( $entry['type'] == 'item')
            {
                $result .= "\t<div class=\"doc\">";
            }

            # it's an content array, call recursive !!
        	if ( is_array($entry['content']) )
        	{
        	   $result .= $this->get_adminmenu_div($entry['content']);
        	}
        	else
        	{
        	    # it's not an content array, it's an folder
                if ( $entry['type'] != 'folder' )
                {
                    $result .= '<a href="'.$entry['href'];
                    $result .= '" title="'.htmlspecialchars($entry['title']) . '" target="'.htmlspecialchars($entry['target']) . '___' . $entry['icon'] . '___' . htmlspecialchars($entry['right_to_view']) . '">';
                    $result .= htmlspecialchars( _($entry['name'])) . '</a>';
                }
        	}

            # it was an item, close it
        	if ( $entry['type'] == 'item')
            {
                $result .= "</div>\n";
            }

            # it was an folder, close it
            # @todo: combine both if's with OR?
            if ( $entry['type'] == 'folder')
            {
                $result .= "</div>\n";
            }
        }

        return $result;
    }


    /**
     * This function generates html-div based menu lists - for menu editor
     *
     * @param $menu default empty
     * @param int $level Integer Value determining the Nesting Level default empty
     * @param string $module Name of the Module default empty
     */
    function get_export_div( $menu = '', $level = '', $module = '' )
    {
        global $lang, $cfg, $perms;

        $result  = '';
        $jscript = '';

        if ( !is_array($menu) )
        {
            $menu = $this->fetch_adminmenu(false);
        }

        foreach($menu as $entry)
        {
            /**
            * @desc Init Vars
            */
            $entry['type']              = isset($entry['type'])             ? $entry['type']            : '';
            $entry['content']           = isset($entry['content'])          ? $entry['content']         : '';
            $entry['href']              = isset($entry['href'])             ? $entry['href']            : '';
            $entry['title']             = isset($entry['title'])            ? $entry['title']           : '';
            $entry['target']            = isset($entry['target'])           ? $entry['target']          : '';
            $entry['icon']              = isset($entry['icon'])             ? $entry['icon']            : '';
            $entry['name']              = isset($entry['name'])             ? $entry['name']            : '';
            $entry['right_to_view']     = isset($entry['right_to_view'])    ? $entry['right_to_view']   : '';

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
                $result .= "\t<div class=\"folder\">";

                if ( $entry['icon'] == '' )
                {
                    $result .= '<img id="node-'.$module.'_dir_'.$entry['id'].'" src="'. WWW_ROOT . '/' . $cfg->tpl_folder . '/core/admin/adminmenu/images/tree-node.gif" width="18" height="18" border="0" onClick="node_click(\''.$module.'_dir_'.$entry['id'].'\')" />';
                    $result .= '<input id="'.$module.'_dir_'.$entry['id'].'" type="checkbox" onclick="javascript:checker(\''.$jscript.'\',\''.$module.'_dir_'.$entry['id'].'\');" name="menu_ids['.$module.'][]" value="'.$entry['id'].'">';
                    $result .= '<img src="'. WWW_ROOT . '/' . $cfg->tpl_folder . '/core/admin/adminmenu/images/tree-folder.gif" width="18" height="18" border="0" />';
                }
                else
                {
                    $result .= '';
                    $result .= '<img id="node-'.$module.'_dir_'.$entry['id'].'" src="'. WWW_ROOT . '/' . $cfg->tpl_folder . '/core/admin/adminmenu/images/tree-node.gif" width="18" height="18" border="0" onClick="node_click(\''.$module.'_dir_'.$entry['id'].'\')" />';
                    $result .= '<input id="'.$module.'_dir_'.$entry['id'].'" type="checkbox" onclick="javascript:checker(\''.$jscript.'\',\''.$module.'_dir_'.$entry['id'].'\');" name="menu_ids['.$module.'][]" value="'.$entry['id'].'">';
                    $result .= '<img class="pic" src="' . WWW_ROOT . '/' . $cfg->tpl_folder . '/core/images/icons/' . $entry['icon'] . '" border="0" width="16" height="16" />';
                }
                $result .= '<span class="text" style="padding-left: 5px;">'.$entry['name'].'</span>';

                $result .= '<div id="section-'.$module.'_dir_'.$entry['id'].'" class="section" style="display: none">';
                $jscript = '';
            }

            if ( $entry['type'] == 'item')
            {
                $result .= "\t<div class=\"doc\">";
                if ( $entry['icon'] == '' )
                {
                    $result .= '<img src="'. WWW_ROOT . '/' . $cfg->tpl_folder . '/core/admin/adminmenu/images/tree-leaf.gif" width="18" height="18" border="0" />';
                    $result .= '<input id="'.$module.'_dir_'.$entry['id'].'" type="checkbox" onclick="javascript:checker(\''.$jscript.'\',\''.$module.'_dir_'.$entry['id'].'\');" name="menu_ids['.$module.'][]" value="' . $level . $entry['id'] . '">';
                    $result .= '<img class="pic" src="' . WWW_ROOT . '/' . $cfg->tpl_folder . '/core/images/icons/empty.png" border="0" width="16" height="16" />';
                }
                else
                {
                    $result .= "<img src='". WWW_ROOT . '/' . $cfg->tpl_folder . '/core/admin/adminmenu/images/tree-leaf.gif" width="18" height="18" border="0" />';
                    $result .= '<input id="'.$module.'_dir_'.$entry['id'].'" type="checkbox" onclick="javascript:checker(\''.$jscript.'\',\''.$module.'_dir_'.$entry['id'].'\');" name="menu_ids['.$module.'][]" value="' . $level . $entry['id'] . '">';
                    $result .= '<img class="pic" src="' . WWW_ROOT . '/' . $cfg->tpl_folder . '/core/images/icons/' . $entry['icon'] . '" border="0" width="16" height="16" />';
                }
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

                    $result .= '<span class="text" style="padding-left: 5px;">'.$entry['name'].'</span>';
                    $result .= '<div class="section" style="display: block">';
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
            $result .= "</div>\n";
        }

        return $result;
    }

    /**
     * Read the Menu from the Database
     *
     * @param $perm_check = true
     * @param  &$result = ''
     * @param  $parent = 0
     * @param  $level = 0
     * @access public
     *
     * @return Returns array_values of the Menu read from Database.
     *
     * @todo Permissions
     */
    public function fetch_adminmenu( $perm_check = true, &$result = '', $parent = 0, $level = 0 )
    {
       if ( empty($result) )
        {
            # Load DBAL
            parent::getInjector()->instantiate('clansuite_doctrine')->doctrine_initialize();

            # Load Models
            Doctrine::loadModels(ROOT . '/myrecords/', Doctrine::MODEL_LOADING_CONSERVATIVE);

            # Issue Doctrine_Query
            $result = Doctrine_Query::create()
                                    ->select('m.*')
                                    ->from('CsAdminmenu m')
                                    ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
                                    #->setHydrationMode(Doctrine::HYDRATE_NONE)
                                    ->orderby('m.order ASC, m.parent ASC')
                                    ->execute();
            #var_dump($result);
            #$result = $stmt->fetchAll( PDO::FETCH_ASSOC );
        }
        $output = array();
        $rows = count($result);

        for($i = 0; $i < $rows; $i++)
        {
            if($result[$i]['parent'] == $parent)
            {
                #if ( $perms->check( $result[$i]['right_to_view'] , 'no_redirect' )
                #      OR $result[$i]['right_to_view'] == ''
                #      OR $perm_check == false )
                #{
                    $output[$result[$i]['id']] = array(
                                                        'name'          => $result[$i]['text'],
                                                        'level'         => $level,
                                                        'type'          => $result[$i]['type'],
                                                        'parent'        => $result[$i]['parent'],
                                                        'id'            => $result[$i]['id'],
                                                        'href'          => $result[$i]['href'],
                                                        'title'         => $result[$i]['title'],
                                                        'target'        => $result[$i]['target'],
                                                        'order'         => $result[$i]['order'],
                                                        'icon'          => $result[$i]['icon'],
                                                        'right_to_view' => $result[$i]['right_to_view']
                                                    );

                    $output[$result[$i]['id']]['content'] = $this->fetch_adminmenu($perm_check, $result, $result[$i]['id'], $level + 1);

                    if( count($output[$result[$i]['id']]['content']) == 0)
                        unset($output[$result[$i]['id']]['content']);
                    else
                        $output[$result[$i]['id']]['expanded'] = true;
                #}
            }
        }
        return array_values($output);
    }
}
?>
