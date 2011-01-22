<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-André Koch © 2005 - onwards
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
    * @license    GNU/GPL v2 or (at your option) any later version, see "/doc/LICENSE".
    * @author     Jens-André Koch <vain@clansuite.com>
    * @copyright  Jens-André Koch (2005 - onwards)
    * @link       http://www.clansuite.com
    *
    * @version    SVN: $Id: menueditor.module.php 2248 2008-07-12 01:48:54Z vain $
    */

# Security Handler
if (defined('IN_CS') === false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

/**
 * Clansuite_Module_Menu_Admin
 *
 * @category    Clansuite
 * @package     Modules
 * @subpackage  Menu
 */
class Clansuite_Module_Menu_Admin extends Clansuite_Module_Controller
{
    public function initializeModule()
    {
        parent::initModel('menu');
    }

    /**
     * Shows the Admin Menu Editor
     */
    public function action_admin_show()
    {
        # Assign Icon Filenames to View
        $this->getView()->assign('icons', self::fetchIcons());

        $this->display(array('content_template' => 'action_admin_menueditor.tpl'));
    }

    /**
     * Shows the Editor for the Adminmenu
     */
    public function action_admin_menueditor()
    {
        # Assign Icon Filenames to View
        $this->getView()->assign('icons', self::fetchIcons());

        $this->display();
    }

    /**
     * Fetches all icon filenames.
     *
     * @return array Array with Icon filenames
     */
    private static function fetchIcons()
    {
        # Setup Icons Array
        $icons = array();

        # Get Icons from Directory
        $dir_handler = opendir( ROOT_THEMES_CORE . 'images/icons/' );

        while( false !== ($filename = readdir($dir_handler)) )
        {
            if (is_file($filename) && $filename != '.' && $filename != '..')
            {
                $icons[] = $filename;
            }
        }

        closedir($dir_handler);

        return $icons;
    }

    private function getTree($model, $rootId = null)
    {
        $tree = Doctrine_Core::getTable($model)->getTree();

        $options = array();

        if(is_null($rootId))
        {
            $options['root_id'] = 1;
        }
        else
        {
            $options['root_id'] = $rootId;
        }

        return $tree->fetchTree($options);
    }

    private function getRoots($model)
    {
        $tree = Doctrine_Core::getTable($model)->getTree();
        return $tree->fetchRoots();
    }

    public function action_admin_modulemenu_edit()
    {
        # get modulename
        $modulename = $this->request->getParameter('module');

        $modulenavigation_file = ROOT_MOD. $modulename . DS . $modulename . '.menu.php';

        # read module menu file
        if( is_file($modulenavigation_file) )
        {
            # this includes the file, which contains a php array name $modulenavigation
            include $modulenavigation_file;

            # convert URLs by callback
            $modulenavigation = array_map("convertURLs", $modulenavigation);

            $view->assign('modulenavigation', $modulenavigation);
            # The file is located in clansuite/themes/core/view/smarty/modulenavigation-generic.tpl
            return $smarty->fetch('modulenavigation-generic.tpl');
        }
        else
        {
            $smarty->assign('modulename', $modulename);
            $errormessage = $smarty->fetch('modulenavigation_not_found.tpl');
            trigger_error($errormessage);
        }

        # assign the html of the form to the view
        $this->getView()->assign('form', $form->render());

        # display
        $this->display();
    }

    public function action_admin_modulemenueditor()
    {
        $modulenames = Clansuite_ModuleInfoController::getModuleNames(false, true);

        # create a new form
        $form = new Clansuite_Form('module_select_dropdown_form', 'post', '/menu/admin/modulemenu_edit');

        # select dropdown for modules
        $form->addElement('select')->setName('menu_select_form[modulename]')->setLabel(_('Module'))
             ->setOptions($modulenames);

        # add the buttonbar
        $form->addElement('buttonbar')->getButton('cancelbutton')->cancelURL = 'index.php?mod=menu&sub=admin';

        # assign the html of the form to the view
        $this->getView()->assign('module_select_dropdown_form', $form->render());
        $this->display();
    }

    public function action_admin_menueditor2()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Breadcrumb::add( _('Adminmenu Editor II'), '/menu/admin/menueditor2');

        $model = 'CsAdminmenuShortcuts';
        $treeObject = Doctrine::getTable($model)->getTree();
        $rootColumnName = $treeObject->getAttribute('rootColumnName');

        $lastLevel = 0;
        $html = '';

        # open unordered list tag
        $html .= "\n\t<ul>\n";

        # retrieve the roots
        foreach($treeObject->fetchRoots() as $root)
        {
            $options = array('root_id' => $root->$rootColumnName);

            # start iterating the tree beginning with a specific root_id
            foreach($treeObject->fetchTree($options) as $node)
            {
                # we are on the item of the same level, closing <li> tag before printing item
                if($lastLevel > 0 and $node['level'] == $lastLevel)
                {
                    $html .= "</li>\n";
                }

                # we are printing a next-level item, starting a new <ul>
                if($node['level'] > $lastLevel)
                {
                    $html .= "\t\n<ul>\n";
                }
                # we are going back one or more levels, closing tags
                elseif($node['level'] < $lastLevel)
                {
                    $html .= str_repeat('</ul></li>', $lastLevel - $node['level']);
                }

                # Print the NODE
                $html .= '<li id="node_' . $node['id'] . '">';
                $html .= '<a href="#">Name: ' . $node['name'] . ' ID: ' . $node['id'] . '</a>';
                #$html .= '</li>';

                # Refreshing last level of the item
                $lastLevel = $node['level'];
            }
        }

        # close unordered list tag
        $html .= "\n</ul>";
        $html .= "\n</div>";

        #Clansuite_Debug::printR($html);

        # assign the html of the tree to the view
        $view = $this->getView();
        $view->assign('modelname', $model);
        $view->assign('tree', $html);

        $this->display();
    }

    /**
     * Update the Adminmenu
     *
     * 1) clear adminmenu_backup
     * 2) insert data from adminmenu table into the admin_backup table
     * 3) clear adminmenu
     * 4) insert menu into adminmenu table
     */
    public function action_admin_update()
    {
        # fetch the container variable from post
        $menu = $this->request->getParameter('container','POST');

        # Check if we have some $menu values to insert
        if(count($menu) == 0)
        {
            # if not, tell the user about it and stop here
            throw new Clansuite_Exception('No Menu Items defined for Action: '.__FUNCTION__);
        }

        /**
         * Clear the Backup Table of the adminmenu (truncate table)
         */
        Doctrine::getTable('CsAdminmenuBackup')->createQuery()->delete()->execute();

        /**
         * Insert the adminmenu Into the Backup Table
         *
         * @todo how to clone a table? with Doctrine clone / copy()?
         */
        # Get PDO Object from Doctrine
        $pdo = Doctrine_Manager::connection()->getDbh();
        # prepare stmt
        $stmt2 = $pdo->prepare('INSERT INTO '. DB_PREFIX . 'adminmenu_backup
                                SELECT `id`, `parent`, `type`, `text`, `href`, `title`, `target`, `sortorder`, `icon`, `permission`
                                FROM '. DB_PREFIX . 'adminmenu' );
        # execute
        $stmt2->execute();

        /**
         * Clear Original Adminmenu Table (truncate table)
         */
        Doctrine::getTable('CsAdminmenu')->createQuery()->delete()->execute();

        /**
         * Insert the new values for the Adminmenu via Doctrine ActiveRecord
         */
        # now loop over all menu values and prepare the temporary array to insert later
        foreach ( $menu as $key => $value )
        {
            #Clansuite_Debug::printR($value);

            # fetch activerecord of the adminmenu
            $adminmenu = new \Entities\CsAdminmenu;

            # setup the new menuelement
            $adminmenu['id']            = (int) str_replace( 'tree-', '', $key );
            $adminmenu['parent']        = (int) str_replace( 'tree-', '', $value['parent'] );
            $adminmenu['type']          = $value['type'];
            $adminmenu['text']          = html_entity_decode($value['text']);
            $adminmenu['href']          = preg_replace("/&(?!amp;)/","&amp;", $value['href']);
            $adminmenu['title']         = html_entity_decode($value['title']);
            $adminmenu['target']        = (string) $value['target'];
            $adminmenu['sortorder']     = (int) $value['sortorder'];
            $adminmenu['icon']          = (string) $value['icon'];
            $adminmenu['permission']    = (string) $value['permission'];

            # save it
            $em->persist($adminmenu);
            $em->flush();        }

        # message the user

        # redirect back to the menu manager
        #$this->redirect('index.php?mod=menu/admin', 1, 202, _('success#Menu successfully updated.'));
        $this->redirect('/menu/admin', 1, 202, _('success#Menu successfully updated.'));
    }

    /**
     * Restore the old menu
     */
    function restore()
    {
        $confirm = $_POST['confirm'];
        $abort   = $_POST['abort'];

        if ( !empty($abort) )
        {
            # tell the user, that the last action changed nothing on the menu
            $this->addFlashMessage('Menuediting was cancelled. Nothing has been changed.');

            # redirect to menu manager
            $this->redirect('index.php?mod=menu/admin');
        }

        if ( !empty($confirm) )
        {
            /**
             *  Get content of current menu
             */

            // Get PDO Object from Doctrine
            $pdo = Doctrine_Manager::connection()->getDbh();
            # 1) get adminmenu table as variable $result
            $stmt1 = $pdo->prepare( 'SELECT * FROM ' . DB_PREFIX .'adminmenu' );
            $stmt1->execute();
            $result = $stmt1->fetchAll(PDO::FETCH_NUM);

            # 2) empty adminmenu table
            $stmt2 = $pdo->prepare( 'TRUNCATE TABLE ' . DB_PREFIX . 'adminmenu' );
            $stmt2->execute();

            # 3) insert into adminmenu the adminmenu_backup entries
            $stmt3 = $pdo->prepare( 'INSERT INTO '. DB_PREFIX . 'adminmenu SELECT `id`, `parent`, `type`, `text`, `href`, `title`, `target`, `sortorder`, `icon`, `permission` FROM '. DB_PREFIX . 'adminmenu_backup' );
            $stmt3->execute();

            # 4) empty adminmenu_backup table
            $stmt4 = $pdo->prepare( 'TRUNCATE TABLE ' . DB_PREFIX . 'adminmenu_backup' );
            $stmt4->execute();

            # 5) insert the former adminmenu into the adminmenu_backup table
            $stmt5 = $pdo->prepare( 'INSERT INTO ' . DB_PREFIX . 'adminmenu_backup (`id`, `parent`, `type`, `text`, `href`, `title`, `target`, `sortorder`, `icon`, `permission`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)' );
            foreach( $result as $data )
            {
                $stmt5->execute( $data );
            }

            # tell the user, that the last menu was restored on the next screen
            $this->addFlashMessage('The Last Menu was restored.');

            # redirect  to menu manager
            $this->redirect('index.php?mod=menu/admin');
        }
        else
        {
            # confirm?
            #'confirm', 3, _( 'Do you really want to restore the old menu and delete the current menu?' ), 'admin' );

            # redirect to menu restore
            $this->redirect('index.php?mod=menu/admin/restore');
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
        if(false === isset($result))
        {
            $result = '';
        }

        if($menu == '')
        {
            $menu = $this->fetch_adminmenu(true);
        }

        foreach($menu as $entry)
        {
            /**
             * Init Vars
             */
            $entry['type']          = isset($entry['type'])             ? $entry['type']            : '';
            $entry['content']       = isset($entry['content'])          ? $entry['content']         : '';
            $entry['href']          = isset($entry['href'])             ? $entry['href']            : '';
            $entry['title']         = isset($entry['title'])            ? $entry['title']           : '';
            $entry['target']        = isset($entry['target'])           ? $entry['target']          : '';
            $entry['icon']          = isset($entry['icon'])             ? $entry['icon']            : '';
            $entry['name']          = isset($entry['name'])             ? $entry['name']            : '';
            $entry['permission']    = isset($entry['permission'])       ? $entry['permission']      : '';

            # Set empty image, if no image is given [ IE HACK ]
            if ( $entry['icon'] == '' )
            {
                $entry['icon'] = 'empty.png';
            }

            /**
             *  Build Menu Start
             */

            # Toplevel
            if ( $entry['type'] == 'folder')
            {
                 $result .= "\n\t";

                 # we are at the toplevel, there are no parents
                 if ( $entry['parent'] == 0)
                 {
                     $result .= '<td>';
                     $result .= "\n\t";

                     /**
                      * if we have an toplevel-menu-item with link, we have to present an anchor href
                      * (this implies that there are no submenuitems - you won't reach them)
                      * else present the toplevel-menu-item as an clickable div.
                      * this was formerly an <a href="javascript:void(0);"></a>
                      * but u know: avoid the void!
                      *
                      * this conditional handles the opening of the tag.
                      * the tag is closed with the same conditional check some lines below.
                      */
                     # it's an toplevel-menu-item WITHOUT link and we have to open the div container
                     if ( $entry['href'] == '' )
                     {
                        $result .= '<span class="button" onclick="aFunction(); return false;">';

                     }
                     else # it's an toplevel-menu-item WITH link and we have to open the anchor href
                     {
                        $result .= '<a class="button" href="'.$entry['href'].'" title="'.htmlspecialchars($entry['title']) . '" target="'.htmlspecialchars($entry['target']) . '">';
                     }

                     /**
                      * Image
                      */
                     $result .= '<img alt="Image of Folder" class="pic" src="' . WWW_ROOT_THEMES_CORE .'images/icons/' . $entry['icon'] . '" border="0" width="16" height="16" />';

                     # if the icon empty is used, we do not need to put the name in an html span element
                     # @todo because of what, we have to do this? is this an IE FIX with span element?
                     if( $entry['icon'] == 'empty.png' )
                     {
                        $result .= htmlspecialchars(_($entry['name']));
                     }
                     else # we are not using empty.png, put the entryname in span
                     {
                        $result .= '<span class="element">' . htmlspecialchars(_($entry['name'])) . '</span>';
                     }

                     /**
                      * Add Seperator Dots between Toplevel Menu Items
                      */
                     $result .= '<img alt="dots" class="nubs_pic" src="' . WWW_ROOT . 'modules/menu/images/nubs.gif" />';

                     /**
                      * Close Anchor or Div Element of Toplevel Item
                      */
                     #  it's an toplevel-menu-item WITHOUT link and we have to close the div container
                     if ( $entry['href'] == '' )
                     {
                        $result .= '</span>';
                     }
                     else # it's an toplevel-menu-item WITH link, we have to close the anchor href
                     {
                        $result .= '</a>';
                     }

                 }
                 else
                 {
                     $result .= '<a class="item" href="'.$entry['href'];
                     $result .= '" title="'.htmlspecialchars($entry['title']) . '" target="'.htmlspecialchars($entry['target']) . '">';
                     $result .= '<img alt="icon" class="pic" src="' . WWW_ROOT_THEMES_CORE .'images/icons/' . $entry['icon'] . '" border="0" width="16" height="16" />';
                     $result .= htmlspecialchars(_($entry['name']));
                     $result .= '<img alt="arrow" class="arrow" src="' . WWW_ROOT . 'modules/menu/images/arrow1.gif" width="4" height="7" />';
                     $result .= '</a>';
                 }
             }

             if ( $entry['type'] != 'folder' && isset($entry['type']) )
            {
                $result .= "\n\t";
                $result .= '<a class="item" href="'.$entry['href'];
                $result .= '" title="'.htmlspecialchars($entry['title']) . '" target="' . htmlspecialchars($entry['target']) . '">';
                $result .= '<img alt="Item" class="pic" src="' . WWW_ROOT_THEMES_CORE .'images/icons/' . $entry['icon'] . '" border="0" width="16" height="16" />';
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


            if ( isset($entry['parent']) && $entry['parent'] == 0 )
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
            $entry['permission']        = isset($entry['permission'])       ? $entry['permission']       : '';

            /**
             *  Build Menu
             */
            if ( $entry['type'] == 'folder')
            {
                $result .= "<div class=\"folder\">";
                $result .= '<a href="'.$entry['href'];
                $result .= '" title="'.htmlspecialchars($entry['title']) . '" target="'.htmlspecialchars($entry['target']) . '___' . $entry['icon'] . '___' . htmlspecialchars($entry['permission']) . '">';
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
                    $result .= '" title="'.htmlspecialchars($entry['title']) . '" target="'.htmlspecialchars($entry['target']) . '___' . $entry['icon'] . '___' . htmlspecialchars($entry['permission']) . '">';
                    $result .= htmlspecialchars( _($entry['name'])) . '</a>';
                }
            }

            # it was an item, close it
            if ( $entry['type'] == 'item')
            {
                $result .= "</div>\n";
            }

            # it was an folder, close it
            # @todo combine both if's with OR?
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
     * @todo this is not used. marked as deprecated.
     */
    function get_export_div( $menu = '', $level = '', $module = '' )
    {

        $result  = '';
        $jscript = '';

        if ( !is_array($menu) )
        {
            $menu = $this->fetch_adminmenu(false);
        }

        foreach($menu as $entry)
        {
            #  Init Vars

            $entry['type']              = isset($entry['type'])             ? $entry['type']            : '';
            $entry['content']           = isset($entry['content'])          ? $entry['content']         : '';
            $entry['href']              = isset($entry['href'])             ? $entry['href']            : '';
            $entry['title']             = isset($entry['title'])            ? $entry['title']           : '';
            $entry['target']            = isset($entry['target'])           ? $entry['target']          : '';
            $entry['icon']              = isset($entry['icon'])             ? $entry['icon']            : '';
            $entry['name']              = isset($entry['name'])             ? $entry['name']            : '';
            $entry['permission']        = isset($entry['permission'])       ? $entry['permission']      : '';

            #  Build Menu

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
                    $result .= '<img id="node-'.$module.'_dir_'.$entry['id'].'" src="'. WWW_ROOT . '' . $cfg->tpl_folder . '/core/admin/adminmenu/images/tree-node.gif" width="18" height="18" border="0" onClick="node_click(\''.$module.'_dir_'.$entry['id'].'\')" />';
                    $result .= '<input id="'.$module.'_dir_'.$entry['id'].'" type="checkbox" onclick="javascript:checker(\''.$jscript.'\',\''.$module.'_dir_'.$entry['id'].'\');" name="menu_ids['.$module.'][]" value="'.$entry['id'].'">';
                    $result .= '<img src="'. WWW_ROOT . '' . $cfg->tpl_folder . '/core/admin/adminmenu/images/tree-folder.gif" width="18" height="18" border="0" />';
                }
                else
                {
                    $result .= '';
                    $result .= '<img id="node-'.$module.'_dir_'.$entry['id'].'" src="'. WWW_ROOT . '' . $cfg->tpl_folder . '/core/admin/adminmenu/images/tree-node.gif" width="18" height="18" border="0" onClick="node_click(\''.$module.'_dir_'.$entry['id'].'\')" />';
                    $result .= '<input id="'.$module.'_dir_'.$entry['id'].'" type="checkbox" onclick="javascript:checker(\''.$jscript.'\',\''.$module.'_dir_'.$entry['id'].'\');" name="menu_ids['.$module.'][]" value="'.$entry['id'].'">';
                    $result .= '<img class="pic" src="' . WWW_ROOT . '' . $cfg->tpl_folder . '/core/images/icons/' . $entry['icon'] . '" border="0" width="16" height="16" />';
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
                    $result .= '<img src="'. WWW_ROOT . '' . $cfg->tpl_folder . '/core/admin/adminmenu/images/tree-leaf.gif" width="18" height="18" border="0" />';
                    $result .= '<input id="'.$module.'_dir_'.$entry['id'].'" type="checkbox" onclick="javascript:checker(\''.$jscript.'\',\''.$module.'_dir_'.$entry['id'].'\');" name="menu_ids['.$module.'][]" value="' . $level . $entry['id'] . '">';
                    $result .= '<img class="pic" src="' . WWW_ROOT . '' . $cfg->tpl_folder . '/core/images/icons/empty.png" border="0" width="16" height="16" />';
                }
                else
                {
                    $result .= "<img src='". WWW_ROOT . '' . $cfg->tpl_folder . '/core/admin/adminmenu/images/tree-leaf.gif" width="18" height="18" border="0" />';
                    $result .= '<input id="'.$module.'_dir_'.$entry['id'].'" type="checkbox" onclick="javascript:checker(\''.$jscript.'\',\''.$module.'_dir_'.$entry['id'].'\');" name="menu_ids['.$module.'][]" value="' . $level . $entry['id'] . '">';
                    $result .= '<img class="pic" src="' . WWW_ROOT . '' . $cfg->tpl_folder . '/core/images/icons/' . $entry['icon'] . '" border="0" width="16" height="16" />';
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
     * @param  $perm_check = true
     * @param  $result = ''
     * @param  $parent = 0
     * @param  $level = 0
     * @access public
     *
     * @return Returns array_values of the Menu read from Database.
     *
     * @todo Permissions
     */
    public function fetch_adminmenu( $perm_check = true, $result = '', $parent = 0, $level = 0 )
    {
        # this is a recursive funtion, if this is the first call to it, fetch the menu
        if(empty($result))
        {
            $query = $this->doctrine_em->createQuery('
                SELECT m
                FROM \Entities\Adminmenu m
                ORDER BY m.sortorder ASC, m.parent ASC');
            $result = $query->getArrayResult();
        }

        # initialize the output array
        $output = array();

        # count the menurows
        $menuitems = count($result);

        for($i = 0; $i < $menuitems; $i++)
        {
            if($result[$i]['parent'] == $parent)
            {
               # @todo - permission check?
               $output[$result[$i]['id']] = array(
                                                    'name'          => $result[$i]['text'],
                                                    'level'         => $level,
                                                    'type'          => $result[$i]['type'],
                                                    'parent'        => $result[$i]['parent'],
                                                    'id'            => $result[$i]['id'],
                                                    'href'          => $result[$i]['href'],
                                                    'title'         => $result[$i]['title'],
                                                    'target'        => $result[$i]['target'],
                                                    'sortorder'     => $result[$i]['sortorder'],
                                                    'icon'          => $result[$i]['icon'],
                                                    'permission'    => $result[$i]['permission']
                                                );

                $output[$result[$i]['id']]['content'] = $this->fetch_adminmenu($perm_check, $result, $result[$i]['id'], $level + 1);

                if( count($output[$result[$i]['id']]['content']) == 0)
                {
                    unset($output[$result[$i]['id']]['content']);
                }
                else
                {
                    $output[$result[$i]['id']]['expanded'] = true;
                }
            }
        }
        return array_values($output);
    }
}
?>
