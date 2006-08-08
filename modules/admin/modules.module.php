<?php
/**
* Admin Configs Module Handler Class
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

/**
* @desc Admin Module - Config Class
*/
class module_admin_modules
{
    public $output          = '';
    public $mod_page_title  = '';
    public $additional_head = '';
    private $used = array();
    
    /**
    * @desc First function to run - switches between $_REQUEST['action'] Vars to the functions
    * @desc Loading necessary language files
    */

    function auto_run()
    {
        global $lang;
        
        $this->mod_page_title = $lang->t('Admin Control Panel - Modules' );
        
        switch ($_REQUEST['action'])
        {
            case 'show_all':
                $this->mod_page_title = $lang->t( 'Show all modules' );
                $this->show_all();
                break;
                
            case 'install_new':
                $this->mod_page_title = $lang->t( 'Install new modules' );
                $this->install_new();
                break;
                
            case 'create_new':
                $this->mod_page_title = $lang->t( 'Create a new module' );
                $this->create_new();
                break;
                
            case 'export':
                $this->mod_page_title = $lang->t( 'Export a module' );
                $this->export();
                break;
                
            case 'import':
                $this->mod_page_title = $lang->t( 'Import a module' );
                $this->import();
                break;

            case 'update':
                $this->update();
                break;
                
            case 'add_to_whitelist':
                $this->add_to_whitelist();
                break;
                                
            case 'chmod':
                $this->chmod();
                break;
                

            default:
                $this->mod_page_title = $lang->t( 'Show all modules' );
                $this->show_all();
            break;
        }
        
        return array( 'OUTPUT'          => $this->output,
                      'MOD_PAGE_TITLE'  => $this->mod_page_title,
                      'ADDITIONAL_HEAD' => $this->additional_head );
    }
    
    /**
    * @desc Show all modules
    */

    function show_all()
    {
        global $cfg, $db, $tpl, $error, $lang;

        $dir_handler = opendir( MOD_ROOT );
        
        while( false !== ($content = readdir($dir_handler)) )
        {
            if ( $content != '.' && $content != '..' && $content != '.svn' )
            {
                if ( is_dir( MOD_ROOT . '/' . $content ) )
                {
                    $stmt = $db->prepare( 'SELECT * FROM ' . DB_PREFIX . 'modules WHERE folder_name = ?' );
                    $stmt->execute( array( $content ) );
                    $res = $stmt->fetch();
                    
                    if ( is_array( $res ) )
                    {
                        if ( $res['core'] == 0 )
                        {
                            $container['whitelisted']['normal'][$res['title']] = $res;
                        }
                        else
                        {
                            $container['whitelisted']['core'][$res['title']] = $res;
                        }
                    }
                    else
                    {
                        $x++;
                        $container['not_in_whitelist'][$x]['folder'] = '/' . $cfg->mod_folder . '/' . $content;
                        $container['not_in_whitelist'][$x]['folder_name'] = $content;
                        require_once( MOD_ROOT . '/' . $content . '/' . $content . '.config.php' );
                        $container['not_in_whitelist'][$x] = array_merge( $container['not_in_whitelist'][$x], $info );

                        if ( !file_exists( MOD_ROOT . '/' . $content . '/' . $content . '.config.php' ) )
                        {
                            $container['not_in_whitelist'][$x]['no_module_config'] = 1;
                        }
                    }
                }
            }
        }
        ksort($container);
        closedir($dir_handler);
                
        $tpl->assign('content', $container);
        $this->output .= $tpl->fetch('admin/modules/show_all.tpl');
    }

    /**
    * @desc Install new modules
    */

    function install_new()
    {
        global $cfg, $db, $tpl, $error, $lang, $functions;

        $err = array();
        
        if ( !is_writeable( MOD_ROOT ) )
        {
            $err['mod_folder_not_writeable'] = 1;
        }
        
        // TODO FTP
        
        $tpl->assign('err', $err);
        $tpl->assign('chmod_redirect_url', '/index.php?mod=admin&sub=modules&action=install_new' );
        $tpl->assign('chmod_tpl', $tpl->fetch('admin/modules/chmod.tpl') );
        $this->output .= $tpl->fetch('admin/modules/install_new.tpl');   
    }

    /**
    * @desc Create a new module
    */

    function create_new()
    {
        global $cfg, $db, $tpl, $input, $error, $lang, $functions;

        $err = array();
        
        $submit         = $_POST['submit'];
        $name           = $_POST['name'];
        $description    = $_POST['description'];
        $license        = $_POST['license'];
        $copyright      = $_POST['copyright'];
        $title          = $_POST['title'];
        $author         = $_POST['author'];
        $homepage       = $_POST['homepage'];
        $enabled        = (int) $_POST['enabled'];
        $core           = (int) $_POST['core'];
        $image_name     = 'module_' . $name . '.jpg';
        
        if ( !is_writeable( MOD_ROOT ) )
        {
            $err['mod_folder_not_writeable'] = 1;
        }
        
        if ( (  empty( $name ) OR
                empty( $description ) OR
                empty( $license ) OR
                empty( $copyright ) OR
                empty( $title ) OR
                empty( $author ) ) AND !empty ( $submit ) )
        {
            $err['fill_form'] = 1;
        }
        
        if (  ( !$input->check( $name       , 'is_abc|is_int|is_custom', '_' ) OR
                !$input->check( $description, 'is_abc|is_int|is_custom', '_\s' ) OR
                !$input->check( $license    , 'is_abc|is_int|is_custom', '_\s' ) OR
                !$input->check( $copyright  , 'is_abc|is_int|is_custom', '_\s' ) OR
                !$input->check( $title      , 'is_abc|is_int|is_custom', '_\s' ) OR
                !$input->check( $author     , 'is_abc|is_int|is_custom', '_\s' ) ) AND
                !empty ( $submit ) AND !$err['fill_form'] )
        {
            $err['no_special_chars'] = 1;
        }
             
        if ( !$input->check( $homepage, 'is_url' ) AND !empty( $homepage ) )
        {
            $err['give_correct_url'] = 1;
        }
        
        if ( count ( $err ) == 0 AND !empty( $submit ) )
        {
            $tpl->assign( 'name'        , $name );
            $tpl->assign( 'description' , $description );
            $tpl->assign( 'license'     , $license );
            $tpl->assign( 'copyright'   , $copyright );
            $tpl->assign( 'title'       , $title );
            $tpl->assign( 'author'      , $author );
            $tpl->assign( 'homepage'    , $homepage );
            $tpl->assign( 'class_name'  , 'module_' . $name );
            $tpl->assign( 'timestamp'   , time() );
            $tpl->assign( 'file_name'   , $name . '.class.php' );
            $tpl->assign( 'folder_name' , $name );
            $tpl->assign( 'image_name'  , $image_name );
            $tpl->assign( 'version'     , (float) 0.1 );
            $tpl->assign( 'cs_version'  , $cfg->version );
            $tpl->assign( 'core'        , $core );
            
            $tpl->register_outputfilter( array ( &$functions, 'remove_tpl_comments' ) );
            
            $mod_class = trim ( $tpl->fetch( 'admin/modules/empty_module.tpl' ) );
            $cfg_class = trim ( $tpl->fetch( 'admin/modules/empty_mod_cfg.tpl' ) );
            
            $tpl->unregister_outputfilter( 'remove_tpl_comments' );
            
            $stmt = $db->prepare( 'SELECT * FROM ' . DB_PREFIX . 'modules WHERE name = ?' );
            $stmt->execute( array( $name ) );
            $res = $stmt->fetch();
            
            if ( file_exists( MOD_ROOT . '/' . $name ) OR is_array($res) )
            {
                $err['mod_already_exist'] = 1;
            }
            else
            {
                if ( mkdir ( MOD_ROOT . '/' . $name, 0755 ) )
                {
                    file_put_contents ( MOD_ROOT . '/' . $name . '/' . $name . '.class.php', $mod_class );
                    file_put_contents ( MOD_ROOT . '/' . $name . '/' . $name . '.config.php', $cfg_class );
                    
                    $qry  = 'INSERT INTO `' . DB_PREFIX . 'modules`';
                    $qry .= '(`author`, `homepage`, `license`, `copyright`, `name`, `title`, `description`, `class_name`, `file_name`, `folder_name`, `enabled`, `image_name`, `version`, `cs_version`, `core`)';
                    $qry .= " VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
                    
                    $stmt = $db->prepare( $qry );
                    $stmt->execute( array ( $author,
                                            $homepage,
                                            $license,
                                            $copyright,
                                            $name,
                                            $title,
                                            $description,
                                            'module_' . $name,
                                            $name . '.class.php',
                                            $name,
                                            $enabled,
                                            $image_name,
                                            (float) 0.1,
                                            $cfg->version,
                                            $core ) );
                                            
                    $functions->redirect( '/index.php?mod=admin&sub=modules&action=show_all', 'metatag|newsite', 5, $lang->t( 'The module was successfully created...' ), 'admin' );
                }
                else
                {
                    $functions->redirect( '/index.php?mod=admin&sub=modules&action=create_new', 'metatag|newsite', 5, $lang->t( 'Could not create the necessary folders!' ), 'admin' );
                }
            }
        }
        
        $tpl->assign('err', $err);
        $tpl->assign('chmod_redirect_url', '/index.php?mod=admin&sub=modules&action=create_new' );
        $tpl->assign('chmod_tpl', $tpl->fetch('admin/modules/chmod.tpl') );
        $this->output .= $tpl->fetch('admin/modules/create_new.tpl');   
    }
    
    /**
    * @desc Export Module
    */

    function export()
    {
        global $functions, $cfg, $db, $tpl;
        
        $submit     = $_POST['submit'];
        $name       = $_POST['name'];
        $menu_ids   = $_POST['menu_ids'];
        
        $exported_menu  = array();
        $needed_ids     = array();
        $err            = array();
        $tared_files    = array();
        
        if ( !is_writeable( UPLOAD_ROOT ) )
        {
            $err['upload_folder_not_writeable'] = 1;
        }
                
        if ( count($err) == 0 AND !empty($submit) )
        {
            $stmt = $db->prepare( 'SELECT * FROM ' . DB_PREFIX . 'modules WHERE name = ?' );
            $stmt->execute( array( $name ) );
            $res = $stmt->fetch();
            
            if ( is_array ( $res ) )
            {
                foreach ( $menu_ids as $key => $value )
                {
                    $needed_ids = split( ',', $value );
                    
                    foreach ( $needed_ids as $key => $value )
                    {
                        $stmt = $db->prepare( 'SELECT * FROM ' . DB_PREFIX . 'adminmenu WHERE id = ?' );
                        $stmt->execute( array( $value ) );
                        $result = $stmt->fetch(PDO::FETCH_ASSOC);
                        if ( !array_key_exists( $value, $exported_menu ) )
                        {
                            $exported_menu[$value] = $result;
                        }
                    }
                }
                $res['admin_menu'] = $exported_menu;

                $info = serialize($res);
                
                file_put_contents( UPLOAD_ROOT . '/modules/temp/mod_info.php', $info );
                
                $tared_files['info'] = UPLOAD_ROOT . '/modules/temp/mod_info.php';
                $tared_files['mod'] = MOD_ROOT . '/' . $name . '/';
                
                require( CORE_ROOT . '/tar.class.php' );
                $tar = new Archive_Tar( UPLOAD_ROOT . '/modules/export/' . $name . '.tar' );
                
                if ( $tar->createModify( $tared_files['mod'], '', MOD_ROOT ) )
                {
                    if ( $tar->addModify( $tared_files['info'], '', UPLOAD_ROOT . '/modules/temp/' ) )
                    {
                        $functions->redirect( '/' . $cfg->upload_folder . '/modules/export/' . $name . '.tar' );
                    }
                }
            }
        }

        $dir_handler = opendir( MOD_ROOT );
        
        while( false !== ($content = readdir($dir_handler)) )
        {
            if ( !preg_match('/^\.(.*)$/', $content) )
            {
                if ( is_dir( MOD_ROOT . '/' . $content ) )
                {
                    $stmt = $db->prepare( 'SELECT * FROM ' . DB_PREFIX . 'modules WHERE folder_name = ?' );
                    $stmt->execute( array( $content ) );
                    $res = $stmt->fetch();
                    
                    if ( is_array( $res ) )
                    {
                        $container['whitelisted'][$res['title']] = $res;
                    }
                }
            }
        }
        closedir( $dir_handler );
        ksort($container);
        
        $tpl->assign('err', $err);
        $tpl->assign('content', $container);
        $tpl->assign('menu_tree', $tpl->fetch('admin/modules/menu_tree.tpl') );
        $tpl->assign('chmod_redirect_url', '/index.php?mod=admin&sub=modules&action=export' );
        $tpl->assign('chmod_tpl', $tpl->fetch('admin/modules/chmod.tpl') );
        $this->output .= $tpl->fetch('admin/modules/export.tpl');
    }

    /**
    * @desc Import Module
    */

    function import()
    {
        global $tpl, $db, $input, $functions, $lang;
        
        $functions->delete_dir_content( UPLOAD_ROOT . '/modules/temp/' );
        
        set_time_limit(0);
        
        $submit = $_POST['submit'];
        
        $err    = array();
        $dirs   = array();
        $used   = array();
        
        if ( !is_writeable( UPLOAD_ROOT ) )
        {
            $err['upload_folder_not_writeable'] = 1;
        }

        if ( !is_writeable( MOD_ROOT ) )
        {
            $err['mod_folder_not_writeable'] = 1;
        }
        
        if ( count ( $err ) == 0 AND !empty( $submit ) )
        {
            if ( !preg_match("/\.(tar)$/i", $_FILES['file']['name']) )
            {
                $err['wrong_filetype'] = 1;
            }
            
            if ( !is_uploaded_file($_FILES['file']['tmp_name']) )
            {
                $err['no_correct_upload'] = 1;
            }
            
            if ( count ($err) == 0 )
            {
                if ( move_uploaded_file( $_FILES['file']['tmp_name'], UPLOAD_ROOT . '/modules/import/' . $_FILES['file']['name'] ) )
                {
                    require( CORE_ROOT . '/tar.class.php' );
                    $tar = new Archive_Tar( UPLOAD_ROOT . '/modules/import/' . $_FILES['file']['name'] );
                    
                    $tar->extract( UPLOAD_ROOT . '/modules/temp/' );
                    
                    $handler = opendir( UPLOAD_ROOT . '/modules/temp/' );
                    while( false !== ($dh = readdir($handler)) )
                    {
                        if ( $dh != '.' && $dh != '..' && $dh != '.svn' && is_dir( UPLOAD_ROOT . '/modules/temp/' . $dh ) )
                        {
                            $dirs[] = $dh;
                        }
                    }
                    closedir($handler);
                    
                    foreach( $dirs as $value )
                    {
                        $functions->dir_copy( UPLOAD_ROOT . '/modules/temp/' . $value . '/', MOD_ROOT . '/' . $value . '/', true, '/index.php?mod=admin&sub=admin_modules&action=import' );
                        
                        $info = unserialize( file_get_contents( UPLOAD_ROOT . '/modules/temp/mod_info.php' ) );
                        
                        $stmt = $db->prepare( 'DELETE FROM ' . DB_PREFIX . 'modules WHERE name = ?' );
                        $stmt->execute( array ( $info['name'] ) );
                        
                        $stmt = $db->prepare( 'INSERT INTO `' . DB_PREFIX . 'modules`(`author`, `homepage`, `license`, `copyright`, `name`, `title`, `description`, `class_name`, `file_name`, `folder_name`, `enabled`, `image_name`, `version`, `cs_version`, `core`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)' );
                        $stmt->execute( array(  $info['author'],
                                                $info['homepage'],
                                                $info['license'],
                                                $info['copyright'],
                                                $info['name'],
                                                $info['title'],
                                                $info['description'],
                                                $info['class_name'],
                                                $info['file_name'],
                                                $info['folder_name'],
                                                0,
                                                $info['image_name'],
                                                $info['version'],
                                                $info['cs_version'],
                                                $info['core'] ) );
                                                
                        if ( !empty( $info['admin_menu'] ) )
                        {
                            $info['admin_menu'] = $this->build_menu( $info['admin_menu'] );
                            
                            $stmt = $db->prepare( 'INSERT INTO ' . DB_PREFIX . 'adminmenu (id, parent, type, text, href, title, target, `order`) VALUES (?,?,?,?,?,?,?,?)' );
                            foreach( $info['admin_menu'] as $item )
                            {
                                $stmt->execute( array ( $item['id'],
                                                        $item['parent'],
                                                        $item['type'],
                                                        $item['text'],
                                                        $item['href'],
                                                        $item['title'],
                                                        $item['target'],
                                                        $item['order'] ) );
                            }
                        }
                        
                        $functions->redirect( '/index.php?mod=admin', 'metatag|newsite', 5, $lang->t( 'Module installed successfully.' ), 'admin' );
                    }

                }
                else
                {
                    $functions->redirect( '/index.php?mod=admin', 'metatag|newsite', 5, $lang->t( 'The file could not be moved to the upload directory.' ), 'admin' );
                }
            }
        }
        
        $functions->delete_dir_content( UPLOAD_ROOT . '/modules/temp/' );
                        
        $tpl->assign('err', $err );
        $tpl->assign('chmod_redirect_url', '/index.php?mod=admin&sub=modules&action=import' );
        $tpl->assign('chmod_tpl', $tpl->fetch('admin/modules/chmod.tpl') );
        $this->output .= $tpl->fetch('admin/modules/import.tpl');
    }
    
    /**
    * @desc Build a new folder
    */
 
    function build_folder( $old_id = 0, $new_id = 0, $parent = 0 )
    {
        global $db;
        
        $number_in = true;
        while ( $number_in )
        {
            $new_id = rand(10,250);
            $stmt = $db->prepare( 'SELECT id FROM ' . DB_PREFIX . 'adminmenu WHERE id = ?' );
            $stmt->execute( array( $new_id ) );
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ( is_array ( $result ) OR in_array( $new_id, $this->used ) )
            {
                $number_in = true;
            }
            else
            {
                $number_in = false;
                $this->used[] = $new_id;
            }
        }
        $this->return_array[$new_id] = $this->folder_array[$old_id];
        $this->return_array[$new_id]['id'] = $new_id;
        $this->return_array[$new_id]['parent'] = $parent;

        foreach ( $this->folder_array as $key => $value )
        {            
            if ( $value['parent'] == $old_id )
            {
                if ( $value['type'] == 'folder' )
                {
                    $this->build_folder( $key, 0, $new_id );
                }
                else
                {
                    $new_item_id = $new_id;
                    $number_in = true;
                    while ( $number_in )
                    {
                        $new_item_id++;
                        $stmt = $db->prepare( 'SELECT id FROM ' . DB_PREFIX . 'adminmenu WHERE id = ?' );
                        $stmt->execute( array( $new_item_id ) );
                        $result = $stmt->fetch(PDO::FETCH_ASSOC);
                        if ( is_array ( $result ) OR in_array( $new_item_id, $this->used ) )
                        {
                            $number_in = true;
                        }
                        else
                        {
                            $number_in = false;
                            $this->used[] = $new_item_id;
                        }
                    }                                      
                            
                    $this->return_array[$new_item_id] = $this->folder_array[$key];
                    $this->return_array[$new_item_id]['id'] = $new_item_id;
                    unset ( $this->folder_array[$key] );
                    $this->return_array[$new_item_id]['parent'] = $new_id;
                }                                    
            }
        }
        
        unset( $this->folder_array[$old_id] );
 
        return array_merge( $this->return_array, $this->folder_array);
    }
    
    /**
    * @desc Build a menu array (recursively)
    */

    function build_menu( $menu_array )
    {
        global $db;
        
        foreach ( $menu_array as $key => $value )
        {
            if ( $value['type'] == 'folder' )
            {
                $stmt = $db->prepare( 'SELECT id, text, href, title FROM ' . DB_PREFIX . 'adminmenu WHERE id = ?' );
                $stmt->execute( array( $key ) );
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ( is_array ( $result ) )
                {
                    if ( $result['text'] != $value['text'] OR $result['href'] != $value['href'] OR $result['title'] != $value['title'])
                    {                        
                        $this->folder_array = $menu_array;
                        $menu_array = $this->build_folder( $key, 0, $value['parent'] );
                    }
                    else
                    {
                        unset( $menu_array[$key] );
                    }
                }
            }
        }
        
        foreach ( $menu_array as $key => $value )
        {
            if ( $value['type'] == 'item' )
            {
                $stmt = $db->prepare( 'SELECT id, text, href, title FROM ' . DB_PREFIX . 'adminmenu WHERE id = ?' );
                $stmt->execute( array( $key ) );
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ( is_array ( $result ) )
                {
                    if ( $result['text'] != $value['text'] OR $result['href'] != $value['href'] OR $result['title'] != $value['title'])
                    {                        
                        $number_in = true;
                        while ( $number_in )
                        {
                            $new_id = rand(10,250);
                            $stmt = $db->prepare( 'SELECT id FROM ' . DB_PREFIX . 'adminmenu WHERE id = ?' );
                            $stmt->execute( array( $new_id ) );
                            $result = $stmt->fetch(PDO::FETCH_ASSOC);
                            if ( is_array ( $result ) OR in_array( $new_id, $this->used ) )
                            {
                                $number_in = true;
                            }
                            else
                            {
                                $number_in = false;
                                $this->used[] = $new_id;
                            }
                        }
                        $menu_array[$new_id] = $menu_array[$key];
                        $menu_array[$new_id]['id'] = $new_id;
                        $menu_array[$new_id]['parent'] = $value['parent'];
                        unset( $menu_array[$key] );
                    }
                    else
                    {
                        unset( $menu_array[$key] );
                    }
                }
            }
        }
        return $menu_array;
    }
    
    /**
    * @desc Update the module list
    */

    function update()
    {
        global $db, $functions, $input, $lang;
        
        $submit     = $_POST['submit'];
        $info       = $_POST['info'];
        $confirm    = $_POST['confirm'];
        $ids        = isset($_POST['ids']) ? $_POST['ids'] : array();
        $ids        = isset($_POST['confirm']) ? unserialize(urldecode($_GET['ids'])) : $ids;
        $delete     = isset($_POST['delete']) ? $_POST['delete'] : array();
        $delete     = isset($_POST['confirm']) ? unserialize(urldecode($_GET['delete'])) : $delete;
        $enabled    = isset($_POST['enabled']) ? $_POST['enabled'] : array();
        $enabled    = isset($_POST['confirm']) ? unserialize(urldecode($_GET['enabled'])) : $enabled;
        
        $sets = '';
        
        if ( isset( $_POST['abort'] ) )
        {
            $functions->redirect( '/index.php?mod=admin&sub=modules&action=show_all' );
        }

        $stmt = $db->prepare( 'SELECT module_id FROM ' . DB_PREFIX . 'modules' );
        $stmt->execute();
        $all_modules = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if ( empty($confirm) )
        {    
            foreach( $all_modules as $key => $value )
            {
                if ( in_array( $value['module_id'], $ids ) )
                {
                    
                    $e = in_array( $value['module_id'], $enabled  ) ? 1 : 0;
                    $sets = 'author = ?, homepage = ?, license = ?, copyright = ?, folder_name = ?,';
                    $sets .= 'class_name = ?, file_name = ?, description = ?, name = ?, title = ?, image_name = ?, version = ?, cs_version = ?, enabled = ?';
                    $stmt = $db->prepare( 'UPDATE ' . DB_PREFIX . 'modules SET ' . $sets . ' WHERE module_id = ?' );
                    $stmt->execute( array(  $info[$value['module_id']]['author'],
                                            $info[$value['module_id']]['homepage'],
                                            $info[$value['module_id']]['license'],
                                            $info[$value['module_id']]['copyright'],
                                            $info[$value['module_id']]['folder_name'],
                                            $info[$value['module_id']]['class_name'],
                                            $info[$value['module_id']]['file_name'],
                                            $info[$value['module_id']]['description'],
                                            $info[$value['module_id']]['name'],
                                            $info[$value['module_id']]['title'],
                                            $info[$value['module_id']]['image_name'],
                                            $info[$value['module_id']]['version'],
                                            $cfg->version,
                                            $e,
                                            $value['module_id']) );
                }
            }
        }

        foreach( $all_modules as $key => $value )
        {
            if ( count ( $delete ) > 0 )
            {
                if ( in_array( $value['module_id'], $ids ) )
                {
                    $d = in_array( $value['module_id'], $delete  ) ? 1 : 0;
                    if ( !isset ( $_POST['confirm'] ) )
                    {
                        $functions->redirect( '/index.php?mod=admin&sub=modules&action=update&ids=' . urlencode(serialize($ids)) . '&delete=' . urlencode(serialize($delete)) . '&enabled=' . urlencode(serialize($enabled)), 'confirm', 3, $lang->t( 'Do you really want to delete the module(s)?' ), 'admin' );
                    }
                    else
                    {
                        if ( $d == 1 )
                        {
                            $stmt = $db->prepare( 'DELETE FROM ' . DB_PREFIX . 'modules WHERE module_id = ?' );
                            $stmt->execute( array($value['module_id']) );
                        }
                    }
                }
            }
        }
        
        $functions->redirect( '/index.php?mod=admin&sub=modules&action=show_all', 'metatag|newsite', 3, $lang->t( 'The modules have been updated.' ), 'admin' );
        
    }

    /**
    * @desc Add a module to the DBs whitelist
    */

    function add_to_whitelist()
    {
        global $db, $cfg, $functions, $lang;
        
        $info_array = $_POST['info'];
        $x = 0;
        
        foreach ( $info_array as $info )
        {
            if ( $info['add'] == 1 )
            {
                $stmt = $db->prepare( 'INSERT INTO `' . DB_PREFIX . 'modules`(`author`, `homepage`, `license`, `copyright`, `name`, `title`, `description`, `class_name`, `file_name`, `folder_name`, `enabled`, `image_name`, `version`, `cs_version`, `core`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)' );
                $stmt->execute( array(  $info['author'],
                                        $info['homepage'],
                                        $info['license'],
                                        $info['copyright'],
                                        $info['name'],
                                        $info['title'],
                                        $info['description'],
                                        $info['class_name'],
                                        $info['file_name'],
                                        $info['folder_name'],
                                        $info['enabled'],
                                        $info['image_name'],
                                        $info['version'],
                                        $cfg->version,
                                        $info['core'] ) );
                $x++;
            }
        }
        if ( $x > 0 )
        {
            $functions->redirect( '/index.php?mod=admin&sub=modules&action=show_all', 'metatag|newsite', 3, $lang->t( 'The module(s) have been stored into the whitelist.' ), 'admin' );
        }
        else
        {
            $functions->redirect( '/index.php?mod=admin&sub=modules&action=show_all', 'metatag|newsite', 3, $lang->t( 'No module(s) have been stored into the whitelist! Please use the "Add" checkbox...' ), 'admin' );
        }
    }
    
    /**
    * @desc Try a chmod
    */

    function chmod()
    {
        global $functions, $input, $lang;

        $type = $_POST['type'];
        $redirect_url = urldecode($_POST['chmod_redirect_url']);
        
        if ( $input->check( $type, 'is_int|is_abc' ) )
        {
            if ( $type == 'modules' )
            {
                if ( !$functions->chmod( MOD_ROOT, '755', 1 ) )
                {
                    $functions->redirect( $redirect_url, 'metatag|newsite', 3, $lang->t( 'The permissions could not be set.' ) );
                }
                else
                {
                    $functions->redirect( $redirect_url, 'metatag|newsite', 3, $lang->t( 'Permissions set to: ' . '755') );
                }
            }
            
            if ( $type == 'uploads' )
            {
                if ( !$functions->chmod( UPLOAD_ROOT, '755', 1 ) )
                {
                    $functions->redirect( $redirect_url, 'metatag|newsite', 3, $lang->t( 'The permissions could not be set.' ) );
                }
                else
                {
                    $functions->redirect( $redirect_url, 'metatag|newsite', 3, $lang->t( 'Permissions set to: ' . '755') );
                }
            }
        }
        else
        {
            $functions->redirect( '/index.php?mod=admin', 'metatag|newsite', 5, $lang->t( 'Wrong type given as chmod.' ), 'admin' );
        }
    }
}
?>