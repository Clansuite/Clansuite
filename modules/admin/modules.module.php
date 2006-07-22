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
class module_admin_modules
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
    
    //----------------------------------------------------------------
    // Show all modules
    //----------------------------------------------------------------
    function show_all()
    {
        global $cfg, $db, $tpl, $error, $lang;

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
                    else
                    {
                        $x++;
                        $container['not_in_whitelist'][$x]['folder'] = '/' . $cfg->mod_folder . '/' . $content;
                        $container['not_in_whitelist'][$x]['folder_name'] = $content;
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

    //----------------------------------------------------------------
    // Install new modules
    //----------------------------------------------------------------
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
        $tpl->assign('chmod_tpl', $tpl->fetch('admin/modules/chmod.tpl') );
        $this->output .= $tpl->fetch('admin/modules/install_new.tpl');   
    }

    //----------------------------------------------------------------
    // Create a new module
    //----------------------------------------------------------------
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
        
        if ( !is_writeable( MOD_ROOT ) )
        {
            $err['mod_folder_not_writeable'] = 1;
        }
        
        if ( (  empty( $name ) OR
                empty( $description ) OR
                empty( $license ) OR
                empty( $copyright ) OR
                empty( $title ) OR
                empty( $author ) OR
                empty( $homepage ) ) AND !empty ( $submit ) )
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
                if ( mkdir ( MOD_ROOT . '/' . $name, 755 ) )
                {
                    file_put_contents ( MOD_ROOT . '/' . $name . '/' . $name . '.class.php', $mod_class );
                    file_put_contents ( MOD_ROOT . '/' . $name . '/' . $name . '.config.php', $cfg_class );
                    
                    $qry  = 'INSERT INTO `' . DB_PREFIX . 'modules`';
                    $qry .= '(`name`, `title`, `description`, `class_name`, `file_name`, `folder_name`, `enabled`, `image_name`, `version`, `cs_version`)';
                    $qry .= " VALUES (?,?,?,?,?,?,?,?)";
                    
                    $stmt = $db->prepare( $qry );
                    $stmt->execute( array ( $name,
                                            $title,
                                            $description,
                                            'module_' . $name,
                                            $name . '.class.php',
                                            $name,
                                            $enabled,
                                            'module_' . $name . '.jpg',
                                            (float) 0.1,
                                            $cfg->version ) );
                                            
                    $functions->redirect( '/index.php?mod=admin', 'metatag|newsite', 5, $lang->t( 'The module was successfully created...' ) );
                }
                else
                {
                    $functions->redirect( '/index.php?mod=admin', 'metatag|newsite', 5, $lang->t( 'Could not create the necessary folders!' ) );
                }
            }
        }
        
        $tpl->assign('err', $err);
        $tpl->assign('chmod_tpl', $tpl->fetch('admin/modules/chmod.tpl') );
        $this->output .= $tpl->fetch('admin/modules/create_new.tpl');   
    }
    
    //----------------------------------------------------------------
    // Export Module
    //----------------------------------------------------------------
    function export()
    {
        global $functions, $cfg, $db, $tpl;
        
        $submit = $_POST['submit'];
        $name   = $_POST['name'];

        $err = array();
        
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
        $tpl->assign('chmod_tpl', $tpl->fetch('admin/modules/chmod.tpl') );
        $this->output .= $tpl->fetch('admin/modules/export.tpl');
    }

    //----------------------------------------------------------------
    // Import Module
    //----------------------------------------------------------------
    function import()
    {
        global $tpl, $db, $input, $functions, $lang;
        
        $functions->delete_dir_content( UPLOAD_ROOT . '/modules/temp/' );
        
        set_time_limit(0);
        
        $submit = $_POST['submit'];
        
        $err = array();
        
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
                        
                        $stmt = $db->prepare( 'INSERT INTO `' . DB_PREFIX . 'modules`(`name`, `title`, `description`, `class_name`, `file_name`, `folder_name`, `enabled`, `image_name`, `version`, `cs_version`) VALUES (?,?,?,?,?,?,?,?,?,?)' );
                        $stmt->execute( array(  $info['name'],
                                                $info['title'],
                                                $info['description'],
                                                $info['class_name'],
                                                $info['file_name'],
                                                $info['folder_name'],
                                                0,
                                                $info['image_name'],
                                                $info['version'],
                                                $info['cs_version'] ) );
                        
                        $functions->redirect( '/index.php?mod=admin', 'metatag|newsite', 5, $lang->t( 'Module installed successfully.' ) );
                    }

                }
                else
                {
                    $functions->redirect( '/index.php?mod=admin', 'metatag|newsite', 5, $lang->t( 'The file could not be moved to the upload directory.' ) );
                }
            }
        }
        
        $functions->delete_dir_content( UPLOAD_ROOT . '/modules/temp/' );
                        
        $tpl->assign('err', $err );
        $tpl->assign('chmod_tpl', $tpl->fetch('admin/modules/chmod.tpl') );
        $this->output .= $tpl->fetch('admin/modules/import.tpl');
    }

    //----------------------------------------------------------------
    // Update the module list
    //----------------------------------------------------------------
    function update()
    {
        global $db, $functions, $input, $lang;
        
        $submit = $_POST['submit'];
        $delete = isset($_POST['delete']) ? $_POST['delete'] : array();
        $delete = isset($_POST['confirm']) ? unserialize(urldecode($_GET['delete'])) : $delete;
        $enabled = isset($_POST['enabled']) ? $_POST['enabled'] : array();
        $enabled = isset($_POST['confirm']) ? unserialize(urldecode($_GET['enabled'])) : $enabled;
        
        if ( isset( $_POST['abort'] ) )
        {
            $functions->redirect( '/index.php?mod=admin&sub=modules&action=show_all' );
        }
        
        $stmt = $db->prepare( 'SELECT module_id FROM ' . DB_PREFIX . 'modules' );
        $stmt->execute();
        $all_modules = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach( $all_modules as $key => $value )
        {
            $e = in_array( $value['module_id'], $enabled  ) ? 1 : 0;
            $stmt = $db->prepare( 'UPDATE ' . DB_PREFIX . 'modules SET enabled = ? WHERE module_id = ?' );
            $stmt->execute( array($e, $value['module_id']) );
        }

        foreach( $all_modules as $key => $value )
        {
            if ( count ( $delete ) > 0 )
            {
                $d = in_array( $value['module_id'], $delete  ) ? 1 : 0;
                if ( !isset ( $_POST['confirm'] ) )
                {
                    $functions->redirect( '/index.php?mod=admin&sub=modules&action=update&delete=' . urlencode(serialize($delete)) . '&enabled=' . urlencode(serialize($enabled)), 'confirm', 3, $lang->t( 'Do you really want to delete the module(s)?' ) );
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
        
        $functions->redirect( '/index.php?mod=admin&sub=modules&action=show_all', 'metatag|newsite', 3, $lang->t( 'The modules have been updated.' ) );
        
    }

    //----------------------------------------------------------------
    // Add a module to the DBs whitelist
    //----------------------------------------------------------------
    function add_to_whitelist()
    {
        global $db, $cfg, $functions, $lang;
        
        $info_array = $_POST['info'];
        
        foreach ( $info_array as $info )
        {
            if ( $info['add'] == 1 )
            {
                $stmt = $db->prepare( 'INSERT INTO `' . DB_PREFIX . 'modules`(`name`, `title`, `description`, `class_name`, `file_name`, `folder_name`, `enabled`, `image_name`, `version`, `cs_version`) VALUES (?,?,?,?,?,?,?,?,?,?)' );
                $stmt->execute( array(  $info['name'],
                                        $info['title'],
                                        $info['description'],
                                        $info['class_name'],
                                        $info['file_name'],
                                        $info['folder_name'],
                                        $info['enabled'],
                                        $info['image_name'],
                                        $info['version'],
                                        $cfg->version ) );
            }
        }
        
        $functions->redirect( '/index.php?mod=admin&sub=modules&action=show_all', 'metatag|newsite', 3, $lang->t( 'The module(s) have been stored into the whitelist.' ) );
    }
    
    //----------------------------------------------------------------
    // Try a chmod
    //----------------------------------------------------------------
    function chmod()
    {
        global $functions, $input, $lang;

        $type = $_GET['type'];
        
        if ( $input->check( $type, 'is_int|is_abc' ) )
        {
            if ( $type == 'modules' )
            {
                $functions->chmod( MOD_ROOT, '0755', '/index.php?mod=admin&sub=admin_modules&action=install_new' );
            }
            
            if ( $type == 'uploads' )
            {
                $functions->chmod( UPLOAD_ROOT, '0755', '/index.php?mod=admin&sub=admin_modules&action=install_new' );
            }
        }
        else
        {
            $functions->redirect( '/index.php?mod=admin', 'metatag|newsite', 5, $lang->t( 'Wrong type given as chmod.' ) );
        }
    }
}
?>