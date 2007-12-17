<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-Andre Koch © 2005-2007
    * http://www.clansuite.com/
    *
    * Modules Handler Class
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
    * @license    see COPYING.txt
    * @version    SVN: $Id$
    * @link       http://gna.org/projects/clansuite
    * @since      File available since Release 0.1
    */

// Security Handler
if (!defined('IN_CS')){ die('You are not allowed to view this page.' ); }

/**
 * Start of Core - Modules Class
 * @package Clansuite Core
 * @subpackage Modules
 */
class modules
{
    /**
     * $loaded array
     *
     *
     * @var array
     * @access public
     */
    public $loaded = array();

    /**
     * Constructor sets up {mod} block in SMARTY Template Engine
     * {@link function smarty_translate}
     *
     * @global $tpl object Contains Smarty Template Data
     */
    function __construct()
    {
        global $tpl;

        $tpl->register_function('mod', array('modules','get_instant_content'), false);
    }

    /**
     * Load whitelist of modules and submodules
     * into array $cfg->modules['some_modulname']['submodules]['some_submodulename']
     *
     * @global $db
     * @global $cfg
     * @todo: is this whitelisting required to ensure security matters? are there other options avaiable?
     */
    function load_whitelist()
    {
        global $db, $cfg;

        // get all modules from db
        // into $modules array
        $stmt = $db->prepare( 'SELECT name,file_name,folder_name,class_name,module_id,core,enabled FROM ' . DB_PREFIX . 'modules' );
        $stmt->execute();
        $modules = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // get submodules for each module via relation table
        // into $submodules array
        $stmt = $db->prepare( 'SELECT s.name, s.file_name, s.class_name, s.submodule_id
                               FROM ' . DB_PREFIX . 'mod_rel_sub r,
                                ' . DB_PREFIX . 'submodules s
                               WHERE r.submodule_id =  s.submodule_id
                               AND   r.module_id = ?');
        foreach ($modules as $modul => $value)
        {
            $stmt->execute( array( $value['module_id'] ) );
            $submodules = $stmt->fetchALL(PDO::FETCH_NAMED|PDO::FETCH_GROUP);

            // array correction via subtract [0] array
            foreach($submodules as $submodul => $value)
            {
                $modules[$modul]['submodules'][$submodul] = $value[0];
            }
        }

        // add submodule values to each module
        foreach ($modules as $modul => $value)
        {
            $cfg->modules[$value['name']] = $value;
        }
    }

    /**
    * Get Instant Content
    * Fetches the content of a module for {mod} handler function.
    *
    * @param $params
    */
    static function get_instant_content($params)
    {
        global $modules, $cfg, $lang, $error, $trail;

        // Init Vars
        $params['params']   = !isset( $params['params'] ) ? '' : $params['params'];
        $params['sub']      = !isset( $params['sub'] ) ? '' : $params['sub'];
        $params['name']     = !isset( $params['name'] ) ? '' : $params['name'];
        $sub = $params['sub'];
        $mod = $params['name'];
        $file_name   = '';
        $folder_name = '';
        $class_name  = '';


        if (array_key_exists($mod, $cfg->modules ) )
        {
            // submodules of which modul
            $sub_files = $cfg->modules[$mod]['submodules'];

            if ( $sub != '' )
            {
                 // does submodules exits in array
                if (isset($sub_files) and array_key_exists($sub, $sub_files ) )
                {
                    $folder_name = $cfg->modules[$mod]['folder_name'];
                    $file_name   = $sub_files[$sub]['file_name'];
                    $class_name  = $sub_files[$sub]['class_name'];

                }
                else
                {
                    $error->show($lang->t('Module Failure'), $lang->t('The subfile you have requested is not registered in the DB!'), 3);
                }
            }
            else
            {
                $folder_name = $cfg->modules[$mod]['folder_name'];
                $file_name   = $cfg->modules[$mod]['file_name'];
                $class_name  = $cfg->modules[$mod]['class_name'];
            }

            /**
            * @desc Load file and class
            * @desc Give Return Value of requested function
            */
            if ( $folder_name!='' && $file_name!='' && $class_name!='' )
            {
                $file = ROOT_MOD . '/' . $folder_name . '/' . $file_name;

                if ( file_exists($file ) )
                {
//                    if (!in_array($mod, $modules->loaded ) )
//                    {
//                        $modules->loaded[] = $mod;
//                    }

                    require_once( $file );
                    $module_{$mod} = new $class_name;
                    $func_params = split('\|', $params['params']);
                    $_REQUEST['main_action'] = $_REQUEST['action'];
                    $_REQUEST['action'] = $params['func'];

                    // trail stop on
                    $trail->trail_stop(true);

                    // load module
                    $output = call_user_func_array( array( $module_{$mod}, 'auto_run' ), $func_params );

                    // trail stop off
                    $trail->trail_stop(false);

                    echo $output['OUTPUT'];
                    $_REQUEST['action'] = $_REQUEST['main_action'];

                }
            }
        }
    }


    /**
    * Get normal content of a module from auto_run()
    *
    * @param $mod
    * @param $sub
    */

    function get_content($mod='' , $sub='' )
    {
        global $cfg, $error, $lang, $functions, $db;

        /**
        * @desc Init Vars
        */

        $mod = $mod=='' ? $cfg->std_module : $mod ;

        $file_name   = '';
        $folder_name = '';
        $class_name  = '';


        if (array_key_exists($mod, $cfg->modules ) )
        {
            $sub_files = $cfg->modules[$mod]['submodules'];

            if ( $sub != '' )
            {
                if ( isset($sub_files) and is_array($sub_files) and array_key_exists($sub, $sub_files ) )
                {
                    $folder_name = $cfg->modules[$mod]['folder_name'];
                    $file_name   = $sub_files[$sub]['file_name'];
                    $class_name  = $sub_files[$sub]['class_name'];
                }
                else
                {
                    $content['OUTPUT'] = $error->show($lang->t('Module Failure'), $lang->t('The subfile you have requested is not registered in the DB!'), 2);
                }
            }
            else
            {
                $folder_name = $cfg->modules[$mod]['folder_name'];
                $file_name   = $cfg->modules[$mod]['file_name'];
                $class_name  = $cfg->modules[$mod]['class_name'];
            }


            /**
            * @desc Load file and class
            * @desc Give Return Value of $content
            */

            if ($folder_name!='' && $file_name!='' && $class_name!='' )
            {
                $file = ROOT_MOD . '/' . $folder_name . '/' . $file_name;

                if (file_exists( $file ) )
                {
                    require_once( $file );
                    ${$mod} = new $class_name;
                    $content = ${$mod}->auto_run();

                    if (!in_array($mod, $this->loaded ) )
                    {
                        $this->loaded[] = $mod;
                    }

                    return $content;
                }
            }
            else
            {
                if( $_REQUEST['mod'] == 'admin' )
                {
                    $functions->redirect( 'index.php?mod=admin', 'metatag', '3' );
                }
                else
                {
                    $functions->redirect( 'index.php', 'metatag', '3' );
                }
                return $content;
            }
        }
        else
        {
            $error->error_log['no_module']['not_in_array'] = $lang->t('The module you tried to enter is not registered in the whitelist.');

            $mod = $cfg->std_module;

            $content['OUTPUT'] = $error->show($lang->t('Module Failure'), $lang->t('The module does not exist!'), 2);
            $functions->redirect( 'index.php', 'metatag', '3' );
            return $content;
        }
    }
}
?>