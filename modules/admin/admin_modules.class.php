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
        
        while( $content = readdir($dir_handler) )
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
                        if ( !file_exists( TPL_ROOT . '/' . $content . '/module.config.php' ) )
                        {
                            $container['not_in_whitelist'][$x]['no_module_config'] = 1;
                        }
                    }
                }
            }
        }
        ksort($container);
        
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
        
        require( CORE_ROOT . '/tar.class.php' );
        
        /*
        while ( $content = readdir( MOD_ROOT . '/cpatcha2' ) )
        {
            if ( !preg_match('/^\.(.*)$/', $content) )
            {
            
            }            
        }
        */
        $tar = new Archive_Tar( MOD_ROOT . '/captcha2.tar' );

        $tar->createModify(MOD_ROOT . '/captcha2/', '', MOD_ROOT);
        //$tar->create(  );
        //$tar->extract( MOD_ROOT );
        
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
                    file_put_contents ( MOD_ROOT . '/' . $name . '/module.config.php', $cfg_class );
                    
                    $qry  = 'INSERT INTO `' . DB_PREFIX . 'modules`';
                    $qry .= '(`name`, `title`, `description`, `class_name`, `file_name`, `folder_name`, `enabled`, `image_name`)';
                    $qry .= " VALUES (?,?,?,?,?,?,?,?)";
                    
                    $stmt = $db->prepare( $qry );
                    $stmt->execute( array ( $name,
                                            $title,
                                            $description,
                                            'module_' . $name,
                                            $name . '.class.php',
                                            $name,
                                            $enabled,
                                            'module_' . $name . '.jpg' ) );
                                            
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
                $dump  = 'INSERT INTO `{DB_PREFIX}_modules`';
                $dump .= '(`name`, `title`, `description`, `class_name`, `file_name`, `folder_name`, `enabled`, `image_name`)';
                $dump .= " VALUES ('$res[name]', '$res[title]', '$res[description]', '$res[class_name]', '$res[file_name]', '$res[folder_name]', 0, '$res[image_name]')";
                
                file_put_contents( UPLOAD_ROOT . '/modules/temp/sql_dump.txt', $dump );
                
                $tared_files['dump'] = UPLOAD_ROOT . '/modules/temp/sql_dump.txt';
                $tared_files['mod'] = MOD_ROOT . '/' . $name . '/';
                
                require( CORE_ROOT . '/tar.class.php' );
                $tar = new Archive_Tar( UPLOAD_ROOT . '/modules/export/' . $name . '.tar' );
                
                if ( $tar->createModify( $tared_files['mod'], '', MOD_ROOT ) )
                {
                    if ( $tar->addModify( $tared_files['dump'], '', UPLOAD_ROOT . '/modules/temp/' ) )
                    {
                        $functions->redirect( '/' . $cfg->upload_folder . '/modules/export/' . $name . '.tar' );
                    }
                }
            }
        }

        $dir_handler = opendir( MOD_ROOT );
        
        while( $content = readdir($dir_handler) )
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
        ksort($container);
        
        $tpl->assign('err', $err);
        $tpl->assign('content', $container);
        $tpl->assign('chmod_tpl', $tpl->fetch('admin/modules/chmod.tpl') );
        $this->output .= $tpl->fetch('admin/modules/export.tpl');
    }

    //----------------------------------------------------------------
    // Export Module
    //----------------------------------------------------------------
    function import()
    {
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
                        
        $tpl->assign('chmod_tpl', $tpl->fetch('admin/modules/chmod.tpl') );
        $this->output .= $tpl->fetch('admin/modules/import.tpl');
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