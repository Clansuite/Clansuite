<?php
/**
* Module Handler Class
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

// Security Handler
if (!defined('IN_CS')){ die('You are not allowed to view this page.'); }

/**
 * Start of Module Admin Modules (Modules Administration)
 * @package module_admin
 * @subpackage modules
 */
class module_admin_modules
{
    public $output          = '';
    public $additional_head = '';
    public $suppress_wrapper= '';

    // import
    private $used = array();

    /**
    * First function to run - switches between $_REQUEST['action'] Vars to the functions
    *
    * @global $lang
    * @global $trail
    * @return array( 'OUTPUT', 'ADDITIONAL_HEAD', 'SUPPRESS_WRAPPER' )
    */

    function auto_run()
    {
        global $lang, $trail;

        // Set Pagetitle and Breadcrumbs
        $trail->addStep($lang->t('Admin'), '/index.php?mod=admin');
        $trail->addStep($lang->t('Modules'), '/index.php?mod=admin&amp;sub=modules');

        switch ($_REQUEST['action'])
        {
            default:
            case 'show_all':
                $trail->addStep($lang->t('Show and edit all modules'), '/index.php?mod=admin&amp;sub=modules&amp;action=show_all');
                $this->show_all();
                break;

            // AJAX
            case 'ajaxupdate_onoffswitch':
                $this->ajaxupdate_onoffswitch();
                break;

            // AJAX
            case 'ajaxupdate_modules':
                $this->ajaxupdate_modules();
                break;

            // AJAX
            case 'ajaxupdate_submodules':
                $this->ajaxupdate_submodules();
                break;

            case 'install_new':
                $trail->addStep($lang->t('Install new modules'), '/index.php?mod=admin&amp;sub=modules&amp;action=install_new');
                $this->install_new();
                break;

            case 'create_new':
                $trail->addStep($lang->t('Create a new module'), '/index.php?mod=admin&amp;sub=modules&amp;action=create_new');
                $this->create_new();
                break;

            case 'export':
                $trail->addStep($lang->t('Export a module'), '/index.php?mod=admin&amp;sub=modules&amp;action=export');
                $this->export();
                break;

            case 'import':
                $trail->addStep($lang->t('Import a module'), '/index.php?mod=admin&amp;sub=modules&amp;action=import');
                $this->import();
                break;

            case 'update':
                $this->update();
                break;

            case 'uninstall':
                $this->uninstall();
                break;

            case 'get_folder_tree':
                $this->output = $this->build_folder_tree( ROOT );
                $this->suppress_wrapper = 1;
                break;

            case 'add_to_db':
                $this->add_to_db();
                break;

            case 'chmod':
                $this->chmod();
                break;

        }

        return array( 'OUTPUT'          => $this->output,
                      'ADDITIONAL_HEAD' => $this->additional_head,
                      'SUPPRESS_WRAPPER'=> $this->suppress_wrapper );
    }

    /**
    * @desc Show all modules
    * a. durchlaeuft alle modules verzeichnisse
    * b. erstellt fuer alle in der datenbank eingetragenen module inklusive ihrer submodule
    * c. ein array
    * d. anderenfalls:
    */
    function show_all()
    {
        global $cfg, $db, $tpl, $error, $lang;

        // for all Moduldirectories
        $dir_handler = opendir( ROOT_MOD );

        while( false !== ($content = readdir($dir_handler)) )
        {
            if ( $content != '.' && $content != '..' && $content != '.svn' )
            {
                if ( is_dir( ROOT_MOD . '/' . $content ) )
                {
                    $stmt = $db->prepare( 'SELECT * FROM ' . DB_PREFIX . 'modules WHERE folder_name = ?' );
                    $stmt->execute( array( $content ) );
                    $res = $stmt->fetch( PDO::FETCH_NAMED );

                    // get submodule infos and assign to $res['module_id']['subs'] = array[submodule_id]
                    $stmt = $db->prepare( 'SELECT s.name, s.file_name, s.class_name, s.submodule_id
                                           FROM ' . DB_PREFIX . 'mod_rel_sub r,
                                                ' . DB_PREFIX . 'submodules s
                                           WHERE r.submodule_id =  s.submodule_id
                                           AND   r.module_id = ?');
                    $stmt->execute( array( $res['module_id'] ) );
                    $submodules = $stmt->fetchALL(PDO::FETCH_NAMED|PDO::FETCH_GROUP);

                    foreach($submodules as $submodul => $v)
                    {
                        $res['subs'][$submodul] = $v[0];
                    }

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

                        if ( !file_exists( ROOT_MOD . '/' . $content . '/' . $content . '.config.php' ) )
                        {
                            $container['not_in_whitelist'][$x]['no_module_config'] = 1;
                        }
                        else
                        {
                            require_once( ROOT_MOD . '/' . $content . '/' . $content . '.config.php' );
                            $container['not_in_whitelist'][$x] = array_merge( $container['not_in_whitelist'][$x], $info );
                        }
                    }
                }
            }
        }
        ksort($container);

        #var_dump($container);

        closedir($dir_handler);

        $container['generals'] = array( 'Title'         => 'title',
                                        'Description'   => 'description',
                                        'Author'        => 'author',
                                        'Homepage'      => 'homepage' );

        $container['more'] = array( 'Name'          => 'name',
                                    'Version'       => 'version',
                                    'License'       => 'license',
                                    'Copyright'     => 'copyright',
                                    'Foldername'    => 'folder_name',
                                    'Imagename'     => 'image_name',
                                    'Classname'     => 'class_name',
                                    'Filename'      => 'file_name' );

        $tpl->assign('content', $container);
        $this->output .= $tpl->fetch('admin/modules/show_all.tpl');
    }

    function ajaxupdate_onoffswitch()
    {
      global $cfg, $db, $tpl, $error, $lang, $functions, $security, $input;

        /**
        * @desc Incoming vars
        * urldecode, check if only int, assign as $value
        */
        $value = $input->check(urldecode($_POST['value']), 'is_int');

        //set module activ inactiv
        $stmt = $db->prepare( 'UPDATE ' . DB_PREFIX . 'modules
                               SET enabled = ? WHERE module_id = ? AND name = ?' );
        $stmt->execute( array(  $value, $modules_id, $modules_name ) );

         // return value
        $this->output .= $value;

        // suppress mainframe
        $this->suppress_wrapper = true;

    }

    /**
    * @desc Ajax Update Function called from show_all.tpl
    * $_POST
    * &table=table_for_modules_8&value=Filebrowser123&cell=8_filebrowser_title&_=
    */
    function ajaxupdate_modules()
    {
        global $cfg, $db, $tpl, $error, $lang, $functions, $security, $input;

        /**
        * @desc Incoming vars
        */
        $value = urldecode($_POST['value']);
        $cell_string = urldecode($_POST['cell']);

        // Pregmatch $cell_string
        $pattern = '!([0-9]+)_(.+)_(.+)!is';
        if(preg_match($pattern, $cell_string))
        {
            $result = preg_match($pattern, $cell_string, $subpattern);

            // split $cell_string into into vars
            $modules_id      = $subpattern[1];
            $modules_name    = $subpattern[2];
            $modules_dbfield = $subpattern[3];

            // whitelist for $modules_dbfields
            $whitelist = array( 'name',
                                'author',
                                'homepage',
                                'license',
                                'copyright',
                                'title',
                                'description',
                                'class_name',
                                'file_name',
                                'folder_name',
                                'enabled',
                                'image_name',
                                'version',
                                'cs_version',
                                'core');

            // check if $modules_dbfield exists in $whitelist
            if( in_array($modules_dbfield, $whitelist) )
            {
                // if yes, update that field in db
                $stmt = $db->prepare( 'UPDATE ' . DB_PREFIX . 'modules SET ' . $modules_dbfield . ' = ?
                                                                       WHERE module_id = ? AND name = ?' );
                $stmt->execute( array(  $value, $modules_id, $modules_name ) );
            }
            else
            {
                $security->intruder_alert();
            }
        }

        // return value
        $this->output .= $value;

        // suppress mainframe
        $this->suppress_wrapper = true;
    }

    /**
    * @desc Ajax Update Function for submodules
    */
    function ajaxupdate_submodules(){
        global $cfg, $db, $tpl, $error, $lang, $functions, $security, $input;


        $this->output .= $_POST['value'];

        // suppress mainframe
        $this->suppress_wrapper = true;
    }


    /**
    * @desc Install new modules
    */

    function install_new()
    {
        global $cfg, $db, $tpl, $error, $lang, $functions;

        $err = array();

        if ( !is_writeable( ROOT_MOD ) )
        {
            $err['mod_folder_not_writeable'] = 1;
        }

        // TODO FTP

        $tpl->assign('err', $err);
        $tpl->assign('chmod_redirect_url', 'index.php?mod=admin&sub=modules&action=install_new' );
        $tpl->assign('chmod_tpl', $tpl->fetch('admin/modules/chmod.tpl') );
        $this->output .= $tpl->fetch('admin/modules/install_new.tpl');
    }

    /**
    * @desc Build folder tree
    */
    function build_folder_tree( $path, $x = 0 )
    {
        global $cfg;

        $result  = '';

        $file_count = 0;

        foreach( glob( $path . '/*', GLOB_BRACE) as $file )
        {
            if($file != '.' && $file != '..' && $file != '.svn')
            {
                if ( is_dir( $file ) )
                {
                    $result .= "\t<div class='folder' id='folder-{name}-$x'>\n";
                    $result .= '<img alt="Tree Node" src="'. WWW_ROOT . '/' . $cfg->tpl_folder . '/core/admin/adminmenu/images/tree-node.gif" width="18" height="18" border="0" id="node-{name}-'. $file . $x .'" onclick="javascript: node_click(\'{name}-'. $file . $x .'\')" />';
                    $result .= '<img alt="Tree Folder" src="'. WWW_ROOT . '/' . $cfg->tpl_folder . '/core/admin/adminmenu/images/tree-folder.gif" width="18" height="18" border="0" />';
                    $result .= preg_replace( '#^(.*)/#', '', $file);
                    $result .= '<div class="section" id="section-{name}-'. $file . $x .'" style="display: none">';
                    $x++;
                    $result .= $this->build_folder_tree( $file, $x );
                    $result .= '</div>';

                }
                else
                {
                    $result .= "\t<div class=\"doc\">\n";
                    $file_count++;
                    $result .= '<img alt="Tree Leaf" src="'. WWW_ROOT . '/' . $cfg->tpl_folder . '/core/admin/adminmenu/images/tree-leaf.gif" width="18" height="18" border="0" />';
                    $result .= '<img alt="Tree Doc" class="pic" src="' . WWW_ROOT . '/' . $cfg->tpl_folder . '/core/admin/adminmenu/images/tree-doc.gif" border="0" width="16" height="16" />';
                    $result .= '<span class="text"><input type="checkbox" value="" style="position: relative; top: 2px"/>';
                    $result .= preg_replace( '#^(.*)/#', '', $file);
                    $result .= '</span>';
                    $result .= '</div>';
                }
            }
        }
        if( $file_count < 0 )
        {
            $result .= "</div>\n";
        }
        $result .= "</div>\n";
        return $result;
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
        $db_table       = $_POST['db_table'];
        $db_cols        = $_POST['db_cols'];
        $submodule      = $_POST['submodule'];  // Checkbox 1 / 0
        $module_id      = $_POST['module_id'];
        $enabled        = (int) $_POST['enabled'];
        $core           = (int) $_POST['core'];
        $image_name     = 'module_' . $name . '.jpg';

        /**
        * @desc Folder's writeable?
        */
        if ( !is_writeable( ROOT_MOD ) )
        {
            $err['mod_folder_not_writeable'] = 1;
        }

        /**
        * @desc Form filled?
        */
        if ( (  empty( $name ) OR
                empty( $description ) OR
                empty( $license ) OR
                empty( $copyright ) OR
                empty( $title ) OR
                empty( $author ) ) AND !empty ( $submit ) )
        {
            $err['fill_form'] = 1;
        }

        /**
        * @desc Permitted chars
        */
        if (  ( !$input->check( $name       , 'is_abc|is_int|is_custom', '_' ) OR
                !$input->check( $license    , 'is_abc|is_int|is_custom', '_\s' ) OR
                !$input->check( $copyright  , 'is_abc|is_int|is_custom', '_\s' ) OR
                !$input->check( $title      , 'is_abc|is_int|is_custom', '_\s' ) ) AND
                !empty ( $submit ) AND !$err['fill_form'] )
        {
            $err['no_special_chars'] = 1;
        }

        /**
        * @desc URL Check
        */
        if ( !$input->check( $homepage, 'is_url' ) AND !empty( $homepage ) )
        {
            $err['give_correct_url'] = 1;
        }

        /**
        * @desc Check "CREATE" SQL
        */
        if( !empty($submit) AND $db_table != '' )
        {
            $create_qry = 'CREATE TABLE `' . $db_table . '` (';
            foreach( $db_cols as $values )
            {
                /**
                * @desc No Special Chars in the names...
                */
                if (  ( !$input->check( $values['name']     , 'is_abc|is_int|is_custom', '_' ) OR
                        !$input->check( $db_table           , 'is_abc|is_int|is_custom', '_' ) ) AND
                        !empty ( $submit ) )
                {
                    $err['sql_no_special_chars'] = 1;
                }

                /**
                * @desc Length as INT
                */
                if (  ( !$input->check( $values['length']   , 'is_int' ) ) AND
                        !empty ( $submit ) )
                {
                    $err['sql_int_length'] = 1;
                }

                $length = !empty($values['length']) ? '(' . $values['length'] . ')' : '';

                $create_qry .= '`' . $values['name'] . '` ' . $values['type'] . $length . ' NOT NULL ' . $values['extra'] . ' ,' . $values['keys'] . '(`' . $values['name'] . '`) ,';
            }

            $create_qry = preg_replace("/,$/", '', $create_qry);
            $create_qry .= ') ENGINE = MYISAM ;';

            /**
            * @desc Try the SQL CREATE Statement and give error on false...
            */
            if( count( $err ) == 0 )
            {
                $stmt = $db->prepare($create_qry);
                try
                {
                    $stmt->execute();
                }
                catch( PDOException $e )
                {
                    $err['sql_failure'] = 1;
                    $err['sql_message'] = $e->getMessage();
                }
            }
        }

         /**
        * @desc Get all module names for submodule relations
        * todo: fetch just for assign tpl ?? ->
        */
        $stmt = $db->prepare( 'SELECT module_id,name FROM ' . DB_PREFIX . 'modules' );
        $stmt->execute();
        $modules = $stmt->fetchAll(PDO::FETCH_ASSOC);

        /**
        * @desc Everything's fine - begin creating
        */
        if ( count ( $err ) == 0 AND !empty( $submit ) )
        {
            $tpl->assign( 'name'                , $name );
            $tpl->assign( 'description'         , $description );
            $tpl->assign( 'license'             , $license );
            $tpl->assign( 'copyright'           , $copyright );
            $tpl->assign( 'title'               , $title );
            $tpl->assign( 'author'              , $author );
            $tpl->assign( 'homepage'            , $homepage );
            $tpl->assign( 'timestamp'           , time() );
            $tpl->assign( 'file_name'           , $name . '.module.php' );
            $tpl->assign( 'folder_name'         , $name );
            $tpl->assign( 'image_name'          , $image_name );
            $tpl->assign( 'version'             , (float) 0.1 );
            $tpl->assign( 'cs_version'          , $cfg->version );
            $tpl->assign( 'core'                , $core );
            $tpl->assign( 'subs'                , serialize( array(  'admin' => array( $name . '.admin.php',
                                                                                       'module_' . $name . '_admin' )
                                                                                     ) ) );
            $tpl->assign( 'admin_class_name'    , 'module_' . $name . '_admin' );

            if ( $submodule == 1 )
            {
                /**
                * @desc Create as submodule
                */

                if ( file_exists( ROOT_MOD . '/' . $name . '.module.php' ) )
                {
                    // - todo -
                    // sicherstellen das hauptmodul f�r submodul als file besteht.
                    // eigentlich m�sste das main_module_exists sein oder?

                    // take care, that modul exists as file
                    $err['sub_already_exists'] = 1;
                }
                else
                {
                    $stmt = $db->prepare( 'SELECT name,folder_name,module_id FROM ' . DB_PREFIX . 'modules WHERE module_id = ?' );
                    $stmt->execute( array( $module_id ) );
                    $modul = $stmt->fetch();

                    if ( !is_array($modul) )
                    {
                        // take care, that modul_id exists in db
                        $err['mod_not_existing'] = 1;
                    }
                    else
                    {
                        $tpl->assign( 'class_name'          , 'module_' . $modul['name'] . '_' . $name );

                        $tpl->register_outputfilter( array ( &$functions, 'remove_tpl_comments' ) );

                        $mod_class      = trim ( $tpl->fetch( 'admin/modules/empty_module.tpl' ) );

                        $tpl->unregister_outputfilter( 'remove_tpl_comments' );

                        file_put_contents ( ROOT_MOD . '/' . $modul['folder_name'] . '/' . $name . '.module.php', $mod_class );

                        // insert submodul
                        $stmt = $db->prepare( 'INSERT INTO ' . DB_PREFIX .'submodules SET name = ?, file_name = ?, class_name = ?' );
                        $stmt->execute( array( $name, $name . '.module.php', 'module_' . $modul['name'] . '_' . $name ) );

                        // insert relation of submodul to modul

                        // ?-> 1. INSERT IGNORE, because module_id primary keys are doubled up

                        // ?-> 2. how not to use pdo::last_insert_id, as it's not supported by all pdo drivers
                        //        and using "INSERT IGNORE INTO with SELECT" instead

                        $stmt = $db->prepare( 'INSERT IGNORE INTO ' . DB_PREFIX .'mod_rel_sub SET module_id = ?, submodule_id = ? ');
                        $stmt->execute( array( $modul['module_id'], $db->lastInsertId() ) );

                        $functions->redirect( 'index.php?mod=admin&sub=modules&action=show_all', 'metatag|newsite', 3, $lang->t( 'The submodule has been successfully created...' ), 'admin' );
                    }

                }
            }
            else
            {
                /**
                * @desc Create as normal module
                */
                $tpl->assign( 'class_name'          , 'module_' . $name );

                $tpl->register_outputfilter( array ( &$functions, 'remove_tpl_comments' ) );

                $mod_class      = trim ( $tpl->fetch( 'admin/modules/empty_module.tpl' ) );
                $admin_class    = trim ( $tpl->fetch( 'admin/modules/empty_admin_class.tpl' ) );
                $cfg_class      = trim ( $tpl->fetch( 'admin/modules/empty_mod_cfg.tpl' ) );

                $tpl->unregister_outputfilter( 'remove_tpl_comments' );

                $stmt = $db->prepare( 'SELECT * FROM ' . DB_PREFIX . 'modules WHERE name = ?' );
                $stmt->execute( array( $name ) );
                $res = $stmt->fetch();

                if ( file_exists( ROOT_MOD . '/' . $name ) OR is_array($res) )
                {
                    $err['mod_already_exist'] = 1;
                }
                else
                {
                    if ( mkdir ( ROOT_MOD . '/' . $name, 0755 ) )
                    {
                        file_put_contents ( ROOT_MOD . '/' . $name . '/' . $name . '.admin.php', $admin_class );
                        file_put_contents ( ROOT_MOD . '/' . $name . '/' . $name . '.module.php', $mod_class );
                        file_put_contents ( ROOT_MOD . '/' . $name . '/' . $name . '.config.php', $cfg_class );

                        $qry  = 'INSERT INTO `' . DB_PREFIX . 'modules`';
                        $qry .= ' SET `author`=?, `homepage`=?, `license`=?, `copyright`=?, `name`=?, `title`=?, `description`=?, `class_name`=?, `file_name`=?, `folder_name`=?, `enabled`=?, `image_name`=?, `version`=?, `cs_version`=?, `core`=?';

                        $stmt = $db->prepare( $qry );
                        $stmt->execute( array ( $author,
                                                $homepage,
                                                $license,
                                                $copyright,
                                                $name,
                                                $title,
                                                $description,
                                                'module_' . $name,
                                                $name . '.module.php',
                                                $name,
                                                $enabled,
                                                $image_name,
                                                (float) 0.1,
                                                $cfg->version,
                                                $core) );

                        $module_id = $db->lastInsertId();

                        // insert admin submodul
                        $stmt = $db->prepare( 'INSERT INTO ' . DB_PREFIX .'submodules SET name = ?, file_name = ?, class_name = ?' );
                        $stmt->execute( array( 'admin', $name.'.admin.php', 'module_' . $name . '_admin' ) );

                        // insert relation of submodul to modul

                        // ?-> 1. INSERT IGNORE, because module_id primary keys are doubled up

                        // ?-> 2. how not to use pdo::last_insert_id, as it's not supported by all pdo drivers
                        //        and using "INSERT IGNORE INTO with SELECT" instead

                        $stmt = $db->prepare( 'INSERT IGNORE INTO ' . DB_PREFIX .'mod_rel_sub SET module_id = ?, submodule_id = ? ');
                        $stmt->execute( array( $module_id, $db->lastInsertId() ) );

                                                #serialize( array(  'admin' => array( $name . '.admin.php', 'module_' . $name . '_admin' ) ) )
                        $functions->redirect( 'index.php?mod=admin&sub=modules&action=show_all', 'metatag|newsite', 3, $lang->t( 'The module has been successfully created...' ), 'admin' );
                    }
                    else
                    {
                        $functions->redirect( 'index.php?mod=admin&sub=modules&action=create_new', 'metatag|newsite', 3, $lang->t( 'Could not create the necessary folders!' ), 'admin' );
                    }
                }
            }
        }

        /**
        * @desc Output TPL
        */
        $tpl->assign('modules'              , $modules);
        $tpl->assign('db_prefix'            , DB_PREFIX);
        $tpl->assign('err'                  , $err);
        $tpl->assign('chmod_redirect_url'   , 'index.php?mod=admin&sub=modules&action=create_new' );
        $tpl->assign('chmod_tpl'            , $tpl->fetch('admin/modules/chmod.tpl') );
        $this->output .= $tpl->fetch('admin/modules/create_new.tpl');
    }

    /**
    * @desc The CREATE export
    */
    function sql_create_export($table_name, $metas)
    {
        $h = "CREATE TABLE `" . $table_name . "` (";

        foreach($metas as $key => $value)
        {
            $name = mysql_field_name($fields, $i);
            $flags = mysql_field_flags($fields, $i);
            $len = mysql_field_len($fields, $i);
            $type = mysql_field_type($fields, $i);

            $h .= "`$value[name]` $value[type]($len) $flags,";

            if(strpos($flags, "primary_key")) {
                $pkey = " PRIMARY KEY (`$name`)";
            }
        }

        $h = substr($h, 0, strlen($d) - 1);
        $h .= "$pkey) TYPE=MyISAM;\n\n";
        return($h);
    }

    /**
    * @desc Export Module
    */

    function export()
    {
        global $functions, $cfg, $db, $tpl, $lang;

        $submit       	  = $_POST['submit'];
        $name         	  = $_POST['name'];
        $export_type      = isset($_POST['export_type'])         ? $_POST['export_type'] : '';
        $menu_ids     	  = isset($_POST['menu_ids'])            ? $_POST['menu_ids'] : array();
        $tables       	  = isset($_POST['tables'])              ? $_POST['tables'] : array();
        $files        	  = isset($_POST['files'])               ? $_POST['files'] : array();
        $sql_textarea 	  = isset($_POST['sql_textarea'])        ? $_POST['sql_textarea'] : '';
        $use_sql_textarea = isset($_POST['use_sql_textarea'])    ? $_POST['use_sql_textarea'] : '';
        $details_name	  = isset($_GET['details_name'])         ? $_GET['details_name'] : '';
        $subdetails_id	  = isset($_GET['subdetails_id'])        ? $_GET['subdetails_id'] : '';

        $exported_menu  = array();
        $needed_ids     = array();
        $err            = array();
        $tared_files    = array();

        // Check upload folder
        if ( !is_writeable( ROOT_UPLOAD ) )
        {
            $err['upload_folder_not_writeable'] = 1;
        }

        // Check incoming Vars
        if( !empty($submit) AND empty($export_type) )
        {
            $functions->redirect( 'index.php?mod=admin&sub=modules&action=export', 'metatag|newsite', 3, $lang->t( 'Internal Error: Export type not given.' ), 'admin' );
        }

        /**
        * @desc Everything OK - Export the module...
        */
        if ( count($err) == 0 AND !empty($submit) AND $export_type == 'module')
        {
            $stmt = $db->prepare( 'SELECT * FROM ' . DB_PREFIX . 'modules WHERE name = ?' );
            $stmt->execute( array( $name ) );
            $res = $stmt->fetch(PDO::FETCH_NAMED);

            // get submodule infos and assign to $res['module_id']['subs'] = array[submodule_id]
            $stmt = $db->prepare( 'SELECT s.name, s.file_name, s.class_name, s.submodule_id
                                   FROM ' . DB_PREFIX . 'mod_rel_sub r,
                                        ' . DB_PREFIX . 'submodules s
                                   WHERE r.submodule_id =  s.submodule_id
                                   AND   r.module_id = ?');
            $stmt->execute( array( $res['module_id'] ) );
            $submodules = $stmt->fetchALL(PDO::FETCH_NAMED|PDO::FETCH_GROUP);

            foreach($submodules as $submodul => $v)
            {
             $res['subs'][$submodul] = $v[0];
            }
            #var_dump($res);

            if ( is_array ( $res ) )
            {
                if ( is_array( $menu_ids[$name] ) )
                {
                    foreach ( $menu_ids[$name] as $key => $value )
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
                                if ( $result['icon'] != '' )
                                {
                                    $icons[] = ROOT_TPL . '/core/images/icons/' . $result['icon'];
                                }
                            }
                        }
                    }
                }

                $tpl->assign( 'subs'        , $res['subs'] );
                $tpl->assign( 'name'        , $res['name'] );
                $tpl->assign( 'description' , $res['description'] );
                $tpl->assign( 'license'     , $res['license'] );
                $tpl->assign( 'copyright'   , $res['copyright'] );
                $tpl->assign( 'title'       , $res['title'] );
                $tpl->assign( 'author'      , $res['author'] );
                $tpl->assign( 'homepage'    , $res['homepage'] );
                $tpl->assign( 'class_name'  , $res['class_name'] );
                $tpl->assign( 'timestamp'   , time() );
                $tpl->assign( 'file_name'   , $res['file_name'] );
                $tpl->assign( 'folder_name' , $res['folder_name'] );
                $tpl->assign( 'image_name'  , $res['image_name'] );
                $tpl->assign( 'version'     , $res['version'] );
                $tpl->assign( 'cs_version'  , $cfg->version );
                $tpl->assign( 'core'        , $res['core'] );
                $tpl->assign( 'admin_menu'  , serialize($exported_menu) );

                $tpl->register_outputfilter( array ( &$functions, 'remove_tpl_comments' ) );

                $cfg_class = trim ( $tpl->fetch( 'admin/modules/empty_mod_cfg.tpl' ) );

                $tpl->unregister_outputfilter( 'remove_tpl_comments' );

                /**
                * @desc Write config
                */
                file_put_contents( ROOT_MOD . '/' . $name . '/' . $name . '.config.php', $cfg_class );

                /**
                * @desc Shape CREATE TABLE Statements and write it
                */
                $create_stmts = '';
                if( !empty($tables[$name]) && empty($use_sql_textarea) )
                {
                    foreach( $tables[$name] as $key => $value )
                    {
                        $result = $db->query('SHOW CREATE TABLE '. $value)->fetch(PDO::FETCH_ASSOC);
                        $create_stmts .= str_replace('CREATE TABLE `'.DB_PREFIX, 'CREATE TABLE `<DB_PREFIX>', $result['create table']) . ";\n\n";
                    }
                }
                elseif ( !empty( $use_sql_textarea ) && !empty($sql_textarea) )
                {
                	$create_stmts = $sql_textarea;
                }
                file_put_contents( ROOT_UPLOAD . '/modules/temp/mod_sql.php', serialize($create_stmts) );

                /**
                * @desc Write Mod Info Container
                */
                $container = array( 'name' => $res['name'], 'folder_name' => $res['folder_name'] );
                file_put_contents( ROOT_UPLOAD . '/modules/temp/mod_info.php', serialize($container) );

                $tared_files['mod'] = ROOT_MOD . '/' . $name . '/';

                require( ROOT_CORE . '/tar.class.php' );
                $tar = new Archive_Tar( ROOT_UPLOAD . '/modules/export/' . $name . '.tar' );

                if ( $tar->createModify( $tared_files['mod'], '', ROOT_MOD ) )
                {
                    $tar->addModify(  ROOT_TPL . '/core/images/modules/' . $res['image_name'], 'image', ROOT_TPL . '/core/images/modules/' );
                    $tar->addModify(  $icons, 'icons', ROOT_TPL . '/core/images/icons/' );
                    $tar->addModify(  ROOT_UPLOAD . '/modules/temp/mod_sql.php', '', ROOT_UPLOAD . '/modules/temp/' );
                    $tar->addModify(  ROOT_UPLOAD . '/modules/temp/mod_info.php', '', ROOT_UPLOAD . '/modules/temp/' );
                    if( !empty($files[$name]) )
                    {
                        foreach( $files[$name] as $key => $value )
                        {
                            $tar->addModify( ROOT .'/'. $value, 'files', ROOT );
                        }
                    }
                    $functions->redirect( '/' . $cfg->upload_folder . '/modules/export/' . $name . '.tar' );
                }
            }
        }

        /**
        * @desc Get the exportable modules that are whitelisted
        */
        $dir_handler = opendir( ROOT_MOD );

		if( !empty($details_name) )
		{
	        $stmt = $db->prepare( 'SELECT * FROM ' . DB_PREFIX . 'modules WHERE name = ?' );
	        $stmt->execute( array( $details_name ) );
	        $res = $stmt->fetch();

	        if ( is_array( $res ) )
	        {
	            $container['whitelisted'][$res['title']] = $res;
	        }
		}
		elseif ( !empty($subdetails_id) )
		{

		}
		else
		{
	        while( false !== ($content = readdir($dir_handler)) )
	        {
	            if ( !preg_match('/^\.(.*)$/', $content) )
	            {
	                if ( is_dir( ROOT_MOD . '/' . $content ) )
	                {
	                    $stmt = $db->prepare( 'SELECT `name`,`title`, `module_id` FROM ' . DB_PREFIX . 'modules WHERE folder_name = ?' );
	                    $stmt->execute( array( $content ) );
	                    $res = $stmt->fetch();

	                    if ( is_array( $res ) )
	                    {
	                        $stmt = $db->prepare('SELECT * FROM ' . DB_PREFIX . 'mod_rel_sub AS mrs LEFT JOIN ' . DB_PREFIX . 'submodules AS s ON s.submodule_id = mrs.submodule_id WHERE mrs.module_id = ?');
                            $stmt->execute( array($res['module_id'] ) );
                            $sub_res = $stmt->fetchAll(PDO::FETCH_NAMED);
                            $container['whitelisted'][$res['title']] = $res;
                            $container['whitelisted'][$res['title']]['subs'] = $sub_res;
	                    }
	                }
	            }
	        }
	        closedir( $dir_handler );
	        ksort($container);
	 	}


        /**
        * @desc Get the SQL
        */
        $stmt = $db->prepare( 'SHOW TABLES FROM ' . $cfg->db_name );
        $stmt->execute();
        $tables = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach( $tables as $key => $items )
        {
            $sql_tables[] = $items['tables_in_' . $cfg->db_name];
        }

        /**
        * @desc Assign vars & output
        */
        $tpl->assign('sql_tables'           , $sql_tables );
        $tpl->assign('err'                  , $err);
        $tpl->assign('content'              , $container);
        $tpl->assign('folder_tree'          , $this->build_folder_tree( ROOT ) );
        $tpl->assign('chmod_redirect_url'   , 'index.php?mod=admin&sub=modules&action=export' );
        $tpl->assign('chmod_tpl'            , $tpl->fetch('admin/modules/chmod.tpl') );

        if( !empty( $details_name ) )
        {
        	$this->output .= $tpl->fetch('admin/modules/export_module.tpl');
		}
        elseif ( !empty( $subdetails_id ) )
		{
			$this->output .= $tpl->fetch('admin/modules/export_submodule.tpl');
		}
		else
		{
			$this->output .= $tpl->fetch('admin/modules/export.tpl');
		}
    }

    /**
    * Import Module
    *
    * 1) Extract tar in temp
    * 2) Copy files from temp dirs to correct dirs
    * 3) Remove module with same name
    *	 Delete * from cs_modules where name
    * 4) Insert module infos into cs_modules
    * 5) insert each submodul of this modul into submodules table
    * 6) insert the admin menu
    * 7) Insert the custom SQL Commands
    * 8) Clean up Temp Dir
    * 9) Redirect
    *
    *
    * @global $tpl
    * @global $db
    * @global $input
    * @global $functions
    * @global $lang
    */

    function import()
    {
        global $tpl, $db, $input, $functions, $lang;

        // set time limit for file operations
		set_time_limit(0);

        // fetch POST submit
        $submit = $_POST['submit'];

        // setup arrays
        $err    = array();
        $dirs   = array();
        $used   = array();

        // check if dirs writeable
        if ( !is_writeable( ROOT_UPLOAD ) )
        {
            $err['upload_folder_not_writeable'] = 1;
        }

        if ( !is_writeable( ROOT_MOD ) )
        {
            $err['mod_folder_not_writeable'] = 1;
        }

		// 1) Extract tar in temp
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
                if ( move_uploaded_file( $_FILES['file']['tmp_name'], ROOT_UPLOAD . '/modules/import/' . $_FILES['file']['name'] ) )
                {
                    require( ROOT_CORE . '/tar.class.php' );
                    $tar = new Archive_Tar( ROOT_UPLOAD . '/modules/import/' . $_FILES['file']['name'] );

                    $tar->extract( ROOT_UPLOAD . '/modules/temp/' );

                    $handler = opendir( ROOT_UPLOAD . '/modules/temp/' );
                    while( false !== ($dh = readdir($handler)) )
                    {
                        if ( $dh != '.' && $dh != '..' && $dh != '.svn' && is_dir( ROOT_UPLOAD . '/modules/temp/' . $dh ) )
                        {
                            $dirs[] = $dh;
                        }
                    }
                    closedir($handler);

                    // 2) Copy files from temp dirs to correct dirs
                    foreach( $dirs as $value )
                    {

                        $container = unserialize( file_get_contents( ROOT_UPLOAD . '/modules/temp/mod_info.php' ) );

                        if ( $value == 'icons' )
                        {
                            $functions->dir_copy( ROOT_UPLOAD . '/modules/temp/icons/', ROOT_TPL . '/core/images/icons/', true, 'index.php?mod=admin&sub=admin_modules&action=import' );
                        }

                        if ( $value == 'images' )
                        {
                            $functions->dir_copy( ROOT_UPLOAD . '/modules/temp/images/', ROOT_TPL . '/core/images/modules/', true, 'index.php?mod=admin&sub=admin_modules&action=import' );
                        }

                        if ( $value == $container['folder_name'] )
                        {
                            $functions->dir_copy( ROOT_UPLOAD . '/modules/temp/' . $container['folder_name'] . '/', ROOT_MOD . '/' . $container['folder_name'] . '/', true, 'index.php?mod=admin&sub=admin_modules&action=import' );
                        }

                        if ( file_exists ( ROOT_UPLOAD . '/modules/temp/' . $container['folder_name'] . '/'. $container['name'] . '.config.php' ) )
                        {
                            require( ROOT_UPLOAD . '/modules/temp/' . $container['folder_name'] . '/'. $container['name'] . '.config.php' );
                        }
                        else
                        {
                            $functions->redirect( 'index.php?mod=admin&sub=modules&action=import', 'metatag|newsite', 3, $lang->t( 'There is no modulename.config.php in the package!' ), 'admin' );
                        }

                    }

						// 3) Remove module with same name
                        $stmt = $db->prepare( 'DELETE FROM ' . DB_PREFIX . 'modules WHERE `name` = ?' );
                        $stmt->execute( array ( $info['name'] ) );

						// 4) Insert module infos into cs_modules
                        $stmt = $db->prepare( 'INSERT INTO `' . DB_PREFIX . 'modules`(`author`, `homepage`, `license`, `copyright`, `name`, `title`, `description`, `class_name`, `file_name`, `folder_name`, `enabled`, `image_name`, `module_version`, `clansuite_version`, `core`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)' );
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
                                                $info['module_version'],
                                                $info['clansuite_version'],
                                                $info['core']) );

						// 5) insert each submodul of this modul into submodules table
                        foreach($info['subs'] as $submodul => $v)
                        {
                         $stmt = $db->prepare( 'INSERT INTO `' . DB_PREFIX . 'submodules` SET
                                               name = ?, file_name = ?, class_name = ?' );
                         $stmt->execute( array( $submodul, $v[0], $v[1] ) );
                        }

                       // 6) insert the admin menu
                        $info['admin_menu'] = unserialize( $info['admin_menu'] );
                        if ( !empty( $info['admin_menu'] ) )
                        {
                            $info['admin_menu'] = $this->build_menu( $info['admin_menu'] );

                            $stmt = $db->prepare( 'INSERT INTO ' . DB_PREFIX . 'adminmenu (`id`, `parent`, `type`, `text`, `href`, `title`, `target`, `order`, `icon`, `right_to_view`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)' );
                            foreach( $info['admin_menu'] as $item )
                            {
                                $stmt->execute( array ( $item['id'],
                                                        $item['parent'],
                                                        $item['type'],
                                                        $item['text'],
                                                        $item['href'],
                                                        $item['title'],
                                                        $item['target'],
                                                        $item['order'],
                                                        $item['icon'],
                                                        $item['right_to_view'] ) );
                            }
                        }

						// 7) Insert the custom SQL Commands
						$sql_commands = trim(unserialize( file_get_contents( ROOT_UPLOAD . '/modules/temp/mod_sql.php' ) ) );
                        $db->exec($sql_commands);
                        /*
                        $cmds = preg_split('#;#',$sql_commands);
						foreach( $cmds as $key => $value)
						{
							if( !empty($value) )
							{
								$stmt = $db->prepare( $value );
								$stmt->execute();
							}
						}
						*/

						// Ok, module is installed successfully - so:
				  		// 8) Clean up Temp Dir and
				  		$functions->delete_dir_content( ROOT_UPLOAD . '/modules/temp/' );

						// 9) Redirect in case "successfully installed"
						$functions->redirect( 'index.php?mod=admin', 'metatag|newsite', 3, $lang->t( 'Module installed successfully.' ), 'admin' );


                }
                else
                {
      				// Too Bad, the file could not moved for extraction
                	// 8) Clean up Temp Dir
        			$functions->delete_dir_content( ROOT_UPLOAD . '/modules/temp/' );

                	// 9) Redirect in case "file not moveable"
                    $functions->redirect( 'index.php?mod=admin', 'metatag|newsite', 3, $lang->t( 'The file could not be moved to the upload directory.' ), 'admin' );
                }
            }
        }

        // assign variables for $tpl
        $tpl->assign('err'                  , $err );
        $tpl->assign('chmod_redirect_url'   , 'index.php?mod=admin&sub=modules&action=import' );
        $tpl->assign('chmod_tpl'            , $tpl->fetch('admin/modules/chmod.tpl') );
        // get tpl-file and assign it to output
        $this->output .= $tpl->fetch('admin/modules/import.tpl');
    }

    /**
    * Build a new folder
    *
    * @param integer $old_id old id
    * @param integer $new_id new id
    * @param integer $parent parent id
    *
    * @global $db
    * @return array of merged ($this->return_array, $this->folder_array)
    * @todo: move function into adminmenu class? because it's administration of adminmenu elements
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
    * Build a menu array (recursively)
    *
    *
    * @global $db
    * @return $menu_array array of menudata
    * @todo: move function into adminmenu class?
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
    * @desc Update a module
    */
    function update_module()
    {
        global $db, $functions, $input, $lang, $error, $tpl;

        // 1. checken ob modul existiert
    }

    /**
    * @desc Update a submodule
    */
    function update_submodule()
    {
        global $db, $functions, $input, $lang, $error, $tpl;

        // 2. checken ob submodul existiert

    }


    /**
    * @desc Deprecated -> Update the module list
    */
    function update()
    {
        global $db, $functions, $input, $lang, $error, $tpl;

        $submit     = $_POST['submit'];
        $info       = $_POST['info'];
        $confirm    = $_POST['confirm'];
        $menu_ids   = isset($_POST['menu_ids']) ? $_POST['menu_ids'] : array();
        $ids        = isset($_POST['ids']) ? $_POST['ids'] : array();
        $ids        = isset($_POST['confirm']) ? unserialize(urldecode($_GET['ids'])) : $ids;
        $delete     = isset($_POST['delete']) ? $_POST['delete'] : array();
        $delete     = isset($_POST['confirm']) ? unserialize(urldecode($_GET['delete'])) : $delete;
        $enabled    = isset($_POST['enabled']) ? $_POST['enabled'] : array();
        $enabled    = isset($_POST['confirm']) ? unserialize(urldecode($_GET['enabled'])) : $enabled;

        $sets = '';

        echo '$info <br>';
        var_dump($info);
        echo '$ids <br>';
        var_dump($ids);

        if ( isset( $_POST['abort'] ) )
        {
            $functions->redirect( 'index.php?mod=admin&sub=modules&action=show_all' );
        }

        // get all modules for foreach loop
        $stmt = $db->prepare( 'SELECT module_id FROM ' . DB_PREFIX . 'modules' );
        $stmt->execute();
        $all_modules = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ( empty($confirm) )
        {
            // loop through all modules
            // (not only the ones to be updated, because of delete function)
            foreach( $all_modules as $key => $value )
            {
                // ?? why to check that - what else?
                if ( in_array( $value['module_id'], $ids ) )
                {
                    echo 'module id : '. $value['module_id'] . '<br>';

                    // check if module contains submodules
                    if ( isset( $info[$value['module_id']]['subs'] ) )
                    {
                        echo 'submodules : <br>';
                        var_dump($info[$value['module_id']]['subs']);

                        // loop through all submodules
                        foreach( $info[$value['module_id']]['subs'] as $arr )
                        {
                            // create new array with submodules
                            $subs[$arr['name']] = array( $arr['file'], $arr['class'] );

                            echo "subsrrname : <br>";
                            var_dump($subs[$arr['name']]);

                            /**
                            * @desc Write the subfile if requested
                            */
                            if ( $arr['create_sub'] == 1 )
                            {
                                $tpl->assign( 'name'        , $arr['name'] );
                                $tpl->assign( 'description' , $info[$value['module_id']]['description'] );
                                $tpl->assign( 'license'     , $info[$value['module_id']]['license'] );
                                $tpl->assign( 'copyright'   , $info[$value['module_id']]['copyright'] );
                                $tpl->assign( 'title'       , $info[$value['module_id']]['title'] );
                                $tpl->assign( 'author'      , $info[$value['module_id']]['author'] );
                                $tpl->assign( 'homepage'    , $info[$value['module_id']]['homepage'] );
                                $tpl->assign( 'class_name'  , $arr['class'] );
                                $tpl->assign( 'timestamp'   , time() );
                                $tpl->assign( 'file_name'   , $arr['file'] );
                                $tpl->assign( 'folder_name' , $info[$value['module_id']]['folder_name'] );
                                $tpl->assign( 'image_name'  , $info[$value['module_id']]['image_name'] );
                                $tpl->assign( 'version'     , $info[$value['module_id']]['version'] );
                                $tpl->assign( 'cs_version'  , $cfg->version );

                                $tpl->register_outputfilter( array ( &$functions, 'remove_tpl_comments' ) );

                                $mod_class = trim ( $tpl->fetch( 'admin/modules/empty_module.tpl' ) );

                                $tpl->unregister_outputfilter( 'remove_tpl_comments' );

                                file_put_contents ( ROOT_MOD . '/' . $info[$value['module_id']]['folder_name'] . '/' . $arr['file'], $mod_class );

                            }
                        }
                    }
                    else
                    {
                        // A. there are no submodules in that module
                        $subs = null;

                        /**
                        * @desc Submodule delete -> from relation and submodule-table
                        */
                        $stmt = $db->prepare( 'DELETE rel,subs FROM ' . DB_PREFIX .'mod_rel_sub AS rel,
                                                                    ' . DB_PREFIX . 'submodules AS subs
                                               WHERE rel.module_id = ?
                                               AND rel.submodule_id = subs.submodule_id' );
                        $stmt->execute( array( $value['module_id'] ) );
                    }

                    /**
                    * @desc A. Database Insert of MODULE
                    */
                    $e = in_array( $value['module_id'], $enabled  ) ? 1 : 0;
                    $sets = 'author = ?, homepage = ?, license = ?, copyright = ?,';
                    $sets .= 'folder_name = ?, class_name = ?, file_name = ?, description = ?,';
                    $sets .= 'name = ?, title = ?, image_name = ?, version = ?, cs_version = ?, enabled = ?';
                    $stmt = $db->prepare( 'UPDATE ' . DB_PREFIX . 'modules
                                           SET ' . $sets . '
                                           WHERE module_id = ?' );
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

                    /**
                    * @desc B. Database Insert of SUBMODULE(S)
                    */

                    // if submodules exists
                    if ( !empty($subs) )
                    {
                        echo 'subs to insert:';  var_dump($subs);


                        //TODO: if submodule exists in all_modules [module_id] array
                        //update
                        //else insert
                        //

                        /**
                        * @desc Submodule insert -> into submodule table
                        */
                        $stmt = $db->prepare( 'INSERT INTO ' . DB_PREFIX . 'submodules
                                               SET name = ?, file_name = ?, class_name = ?' );
                        foreach( $subs as $key2 => $value2 )
                        {
                            $stmt->execute( array( $key2, $value2[0], $value2[1]) );
                            $last_ids[] = $db->lastInsertId();
                        }

                        /**
                        * @desc Submodule insert -> into relation table
                        */
                        foreach( $last_ids as $key3 => $value3 )
                        {
                            $stmt = $db->prepare( 'INSERT INTO ' . DB_PREFIX . 'mod_rel_sub
                                                  SET module_id = ?, submodule_id = ?' );
                            $stmt->execute( array( $value['module_id'], $value3 ) );
                        }
                    }
                    else
                    {
                     echo 'subs not empty';
                     var_dump($subs);
                    }
                }
            }
        }

        /**
        * @desc C. Delete MODULES
        */
        foreach( $all_modules as $key => $value )
        {
            if ( count ( $delete ) > 0 )
            {
                if ( in_array( $value['module_id'], $ids ) )
                {
                    $d = in_array( $value['module_id'], $delete  ) ? 1 : 0;
                    if ( !isset ( $_POST['confirm'] ) )
                    {
                        $functions->redirect( 'index.php?mod=admin&sub=modules&action=update&ids=' . urlencode(serialize($ids)) . '&delete=' . urlencode(serialize($delete)) . '&enabled=' . urlencode(serialize($enabled)), 'confirm', 3, $lang->t( 'Do you really want to delete the module(s) : #' . $value['module_id'] . ' - ' . $value['name'] . '?' ), 'admin' );
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

        $functions->redirect( 'index.php?mod=admin&sub=modules&action=show_all', 'metatag|newsite', 3, $lang->t( 'The modules have been updated.' ), 'admin' );

    }

	/**
	* Uninstall a module by deleting:
	* 1) it's db informations by $module_id
	* 2) it's directory content by $folder_name
	*
	* @global $db
    * @global $functions
    * @global $lang
	*/

	function uninstall()
	{
		global $db, $functions, $lang;

		$module_id 	 = $_GET['module_id'];
		$folder_name = $_GET['folder_name'];
		$module_name = $_POST['module_name'];
		$confirm 	 = $_POST['confirm'];
 		$abort 		 = $_POST['abort'];

        if ( empty( $confirm ) && empty( $abort ) )
        {
            $functions->redirect( 'index.php?mod=admin&sub=modules&action=uninstall&module_id=' . $module_id . '&folder_name=' . $folder_name, 'confirm', 3, $lang->t( 'Do you really want to delete the module: #' . $module_id . ' - ' . $module_name . '?' ), 'admin' );
        }
        elseif ( !empty( $confirm ) )
        {
            $stmt = $db->prepare( 'DELETE FROM ' . DB_PREFIX . 'modules WHERE module_id = ?' );
            $stmt->execute( array($module_id) );
            $functions->delete_dir_content( ROOT_MOD . '/' . $folder_name, true );
	        $functions->redirect( 'index.php?mod=admin&sub=modules&action=show_all', 'metatag|newsite', 3, $lang->t( 'The modules has been uninstalled.' ), 'admin' );
        }
        else
        {
        	$functions->redirect( 'index.php?mod=admin&sub=modules&action=show_all', 'metatag|newsite', 3, $lang->t( 'Aborted. No changes have been made.' ), 'admin' );
        }
  	}

    /**
     * Adds a module to the DB
     * Array Data from $_POST['info'] is inserted into the modules table
     *
     * @global $db
     * @global $cfg
     * @global $functions
     * @global $lang
     *
     */

    function add_to_db()
    {
        global $db, $cfg, $functions, $lang;

        $info_array = $_POST['info'];
        $x = 0;

        foreach ( $info_array as $info )
        {
            $info['enabled'] = !empty($info['enabled']) ? $info['enabled'] : 0;
            $info['core'] = !empty($info['core']) ? $info['core'] : 0;
            if ( $info['add'] == 1 )
            {
                $stmt = $db->prepare( 'INSERT INTO `' . DB_PREFIX . 'modules`(`author`, `homepage`, `license`, `copyright`, `name`, `title`, `description`, `class_name`, `file_name`, `folder_name`, `enabled`, `image_name`, `module_version`, `clansuite_version`, `core` )
                                       VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)' );
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
                                        $info['module_version'],
                                        $cfg->version,
                                        $info['core'] ) );
                $x++;
            }
        }
        if ( $x > 0 )
        {
            $functions->redirect( 'index.php?mod=admin&sub=modules&action=show_all', 'metatag|newsite', 3, $lang->t( 'The module(s) have been stored into the whitelist.' ), 'admin' );
        }
        else
        {
            $functions->redirect( 'index.php?mod=admin&sub=modules&action=show_all', 'metatag|newsite', 3, $lang->t( 'No module(s) have been stored into the whitelist! Please use the "Add" checkbox...' ), 'admin' );
        }
    }

    /**
     * Try a chmod
     *
     * @global $functions
     * @global $input
     * @global $lang
     * @todo: move function into functions class, because it's a try of chmod and not specific to this module
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
                if ( !$functions->chmod( ROOT_MOD, '755', 1 ) )
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
                if ( !$functions->chmod( ROOT_UPLOAD, '755', 1 ) )
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
            $functions->redirect( 'index.php?mod=admin', 'metatag|newsite', 5, $lang->t( 'Wrong type given as chmod.' ), 'admin' );
        }
    }
}
?>