<?php
/**
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
* @version    SVN: $Id: modules.class.php 129 2006-06-09 12:09:03Z vain $
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
// Start modules class
//----------------------------------------------------------------
class modules
{
    public $loaded = array();
    
    //----------------------------------------------------------------
    // Register {mod} in SMARTY Template Engine
    //----------------------------------------------------------------
    function __construct()
    {
        global $tpl;
        
        $tpl->register_function('mod', array('modules','get_instant_content'), false);
    }
    
    //----------------------------------------------------------------
    // {mod} handler function
    //----------------------------------------------------------------
    static function get_instant_content($params)
    {
        global $modules, $cfg, $lang, $error;
        
        //----------------------------------------------------------------
        // Init Vars
        //----------------------------------------------------------------
        $sub = $params['sub'];
        $mod = $params['name'];
        $file_name   = '';
        $folder_name = '';
        $class_name  = '';
        
        if (array_key_exists($mod, $cfg->modules ) )
        {
            $config_file = MOD_ROOT . '/' . $cfg->modules[$mod]['folder_name'] . '/module.config.php';
            if (file_exists($config_file ) )
            {
                if ($sub!='' )
                {
                    if (isset($sub_files) AND array_key_exists($sub, $sub_files ) )
                    {
                        $folder_name = $cfg->modules[$mod]['folder_name'];
                        $file_name     = $sub_files[$sub][0];
                        $class_name = $sub_files[$sub][1];
                    }
                    else
                    {
                        $error->show($lang->t('Module Failure'), $lang->t('The subfile you have requested is not registered in module.config.php!'), 3);
                    }
                }
                else
                {
                    $folder_name = $cfg->modules[$mod]['folder_name'];
                    $file_name     = $cfg->modules[$mod]['file_name'];
                    $class_name = $cfg->modules[$mod]['class_name'];
                }
            }
            else
            {
                $error->show($lang->t('Module Failure'), $lang->t('The module.config.php is missing in the dir of the module you requested!'), 1);
            }
            
            //----------------------------------------------------------------
            // Load file and class
            // Give Return Value of requested function
            //----------------------------------------------------------------
            if ($folder_name!='' && $file_name!='' && $class_name!='' )
            {
                $file = MOD_ROOT . '/' . $folder_name . '/' . $file_name;
                
                if (file_exists($file ) )
                {
                    if (!in_array($mod, $modules->loaded ) )
                    {
                        $modules->loaded[] = $mod;
                    }
                    
                    require_once($file );
                    $module_{$mod} = new $class_name;
                    $func_params = split('\|', $params['params']);
                    
                    if(method_exists($module_{$mod}, $params['func'] ) )
                        echo call_user_func_array(array($module_{$mod}, $params['func']), $func_params);
                    else
                    {
                        $error->show($lang->t('Module Failure'), $lang->t('The method/function you tried to access within the modules class does not exist!'), 3);
                    }
                }
            }
        }
    }
    
    
    //----------------------------------------------------------------
    // Get normal content of a module from auto_run()
    //----------------------------------------------------------------
    function get_content($mod='' , $sub='' )
    {
        global $cfg, $error, $lang, $functions;
        
        //----------------------------------------------------------------
        // Init Vars
        //----------------------------------------------------------------
        $mod = $mod=='' ? $cfg->std_module : $mod ;
        $file_name   = '';
        $folder_name = '';
        $class_name  = '';
        
        
        if (array_key_exists($mod, $cfg->modules ) )
        {
            $config_file = MOD_ROOT . '/' . $cfg->modules[$mod]['folder_name'] . '/module.config.php';
            if (file_exists($config_file ) )
            {
                if ($sub!='' )
                {
                    if (isset($sub_files) AND array_key_exists($sub, $sub_files ) )
                    {
                        $folder_name = $cfg->modules[$mod]['folder_name'];
                        $file_name     = $sub_files[$sub][0];
                        $class_name = $sub_files[$sub][1];
                    }
                    else
                    {
                        $content['OUTPUT'] = $error->show($lang->t('Module Failure'), $lang->t('The subfile you have requested is not registered in module.config.php! You are being redirected in 5 seconds...'), 2);
                    }
                }
                else
                {
                    $folder_name = $cfg->modules[$mod]['folder_name'];
                    $file_name     = $cfg->modules[$mod]['file_name'];
                    $class_name = $cfg->modules[$mod]['class_name'];
                }
            }
            else
            {
                $content['OUTPUT'] = $error->show($lang->t('Module Failure'), $lang->t('The module.config.php is missing in the dir of the module you requested! You are being redirected in 5 seconds...'), 2);
            }
            
            //----------------------------------------------------------------
            // Load file and class
            // Give Return Value of $content
            //----------------------------------------------------------------
            if ($folder_name!='' && $file_name!='' && $class_name!='' )
            {
                $file = MOD_ROOT . '/' . $folder_name . '/' . $file_name;
                
                if (file_exists($file ) )
                {
                    require_once($file );
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
                $functions->redirect('/index.php', 'metatag', '5' );
                return $content;
            }
        }
        else
        {
            $error->error_log['no_module']['not_in_array'] = $lang->t('The module you tried to enter is not registered in config.class.php');
            
            $mod = $cfg->std_module;
            
            $content['OUTPUT'] = $lang->t('This module does not exist! You are being redirected in 5 seconds...');
            $functions->redirect('/index.php', 'metatag', '5' );
            return $content;
        }
    }
}
?>