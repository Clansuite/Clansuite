<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-Andre Koch © 2005 - onwards
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
    * @version    SVN: $Id: menueditor.module.php 2248 2008-07-12 01:48:54Z vain $
    */

//Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}

/**
 * Module:       Module_Creator
 * Submodule:    Admin
 *
 * @author     Florian Wolf <xsign.dll@clansuite.com>
 * @author     Jens-Andre Koch <vain@clansuite.com>
 * @copyright  Copyleft: All rights reserved. Jens-Andre Koch (2005-onwards)
 *
 * @package clansuite
 * @subpackage module_admin
 * @category modules
 */
class Module_Modulecreator_Admin extends ModuleController implements Clansuite_Module_Interface
{
    /**
     * Module_Module_Creator -> Execute
     */
    public function execute(httprequest $request, httpresponse $response)
    {
        # proceed to the requested action
        $this->processActionController($request);
    }

    /**
     * Shows the Admin Module Editor
     */
    public function action_admin_show()
    {
        # Permission check
        #$perms::check('cc_show_menueditor');

        # Set Pagetitle and Breadcrumbs
        trail::addStep( _('Show'), '/index.php?mod=module_editor&amp;sub=admin&amp;action=show');

        // Set Layout Template
        $this->getView()->setLayoutTemplate('admin/index.tpl');

        // Prepare the Output
        $this->prepareOutput();
    }

    /**
     * Create the new mod
     */
    public function action_admin_create()
    {
        # Permission check
        #$perms::check('cc_update_menueditor');

        # Set Pagetitle and Breadcrumbs
        trail::addStep( _('Create'), '/index.php?mod=module_editor&amp;sub=admin&amp;action=create');
        
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
            $tpl->assign( 'module_version'      , (float) 0.1 );
            $tpl->assign( 'clansuite_version'   , $cfg->version );
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

                if ( is_file( ROOT_MOD . '/' . $name . '.module.php' ) )
                {
                    // - todo -
                    // sicherstellen das hauptmodul fï¿½r submodul als file besteht.
                    // eigentlich mï¿½sste das main_module_exists sein oder?

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

                if ( is_file( ROOT_MOD . '/' . $name ) OR is_array($res) )
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
                        $qry .= ' SET `author`=?, `homepage`=?, `license`=?, `copyright`=?, `name`=?, `title`=?, `description`=?, `class_name`=?, `file_name`=?, `folder_name`=?, `enabled`=?, `image_name`=?, `module_version`=?, `clansuite_version`=?, `core`=?';

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
}
?>