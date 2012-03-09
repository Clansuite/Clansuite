<?php
   /**
    * Koch Framework
    * Jens-André Koch © 2005 - onwards
    * http://www.clansuite.com/
    *
    * This file is part of "Koch Framework".
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
    * @version    SVN: $Id$
    */

# Security Handler
if(defined('IN_CS') === false)
{
    die('Koch Framework not loaded. Direct Access forbidden.');
}

namespace Koch\View;

/**
 * Koch_View_Mapper
 *
 * By definition a mapper sets up a communication between two independent objects.
 * Koch_View_Mapper is a "class action" to "template" mapper.
 * This has nothing to do with rendering, but with template selection for the view.
 * If no template was set manually in the action of a module (class),
 * this class will help determining the template,
 * by mapping the requested class and action to a template.
 *
 * layout_template selection, depends on the main configuration and user selection (settings).
 *
 */
class Mapper
{
    /**
     * @var string Template name.
     */
    public $template = null;

    /**
     * Ensures the template extension is correct.
     *
     * @param string $template The template filename.
     */
    public static function checkTemplateExtension($template)
    {
        # get extension of template
        $template_extension = mb_strtolower(pathinfo($template, PATHINFO_EXTENSION));

        # whitelist definition for listing all allowed template filetypes
        $allowed_extensions = array('html', 'php', 'tpl');

        # check if extension is one of the allowed ones
        if(false === in_array($template_extension, $allowed_extensions))
        {
            $message = 'Template Extension invalid <strong>' . $template_extension . '</strong> on <strong>' . $template . '</strong>';
            trigger_error($message, E_USER_NOTICE);
        }
    }

    /**
     * Returns the Template Name
     * Maps the action name to a template.
     *
     * @return Returns the templateName as String
     */
    public function getTemplateName()
    {
        # if the templateName was not set manually, we construct it from module/action infos
        if(empty($this->template) === true)
        {
            # construct template name
            $template = Koch_TargetRoute::getActionName() . '.tpl';

            $this->setTemplate($template);
        }

        return $this->template;
    }

    /**
     * Set the template name
     *
     * @param string $template Name of the Template with full Path
     */
    public function setTemplate($template)
    {
        #self::checkTemplateExtension($template);
        $this->template = (string) $template;
    }

    /**
     * Get the template name
     *
     * Proxies to Koch_View_Mapper::getTemplate()
     *
     * @return string $template Name of the Template (full path)
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * Returns the Template Path
     *
     * Fetches the template by searching in the
     * 1) Theme Template Paths
     * 2) Modules Template Paths
     * Note the Path Priority: Themes before Modules.
     *
     * @return $templatepath
     */
    public static function getTemplatePath($template)
    {
        # done: if template is a qualified path and template filename
        if(is_file($template) === true)
        {
            return $template;
        }

        # fetch the template by searching in the Theme Template Paths
        $theme_template = self::getThemeTemplatePath($template);

        # check if template was found there, else it's null
        if($theme_template != null)
        {
            #Koch_Debug::firebug(__METHOD__ .' tries fetching template ("'. $theme_template . '") from THEME directory.');
            return $theme_template;
        }
        else # fetch the template by searching in the Module Template Path
        {
            #Koch_Debug::firebug(__METHOD__ .' tries fetching template ("'. $template . '") from MODULE directory.');
            return self::getModuleTemplatePath($template);
        }
    }

    /**
     * Return Theme Template Paths
     *
     * @return array Theme Template Paths
     */
    public static function getThemeTemplatePaths()
    {
        # get module, submodule, renderer names
        $module = Koch_HttpRequest::getRoute()->getModuleName();
        $submodule = Koch_HttpRequest::getRoute()->getSubModuleName();
        #$renderer  = Koch_HttpRequest::getRoute()->getRenderEngine();

        $theme_paths = array();

        /**
         * 1. BACKEND THEME
         * when controlcenter or admin is requested, it has to be a BACKEND theme
         */
        if($module == 'controlcenter' or $submodule == 'admin')
        {
            # get backend theme from session for path construction
            $backendtheme = Koch_HttpRequest::getRoute()->getBackendTheme();

            # (a) USER BACKENDTHEME - check in the active session backendtheme
            # e.g. /themes/backend/ + admin/template_name.tpl
            $theme_paths[] = ROOT_THEMES_BACKEND . $backendtheme . DS;
            # e.g. /themes/backend/ + admin/modules/template_name.tpl
            $theme_paths[] = ROOT_THEMES_BACKEND . $backendtheme . DS . 'modules' . DS . $module . DS;
            # (b) BACKEND FALLBACK - check the fallback dir: themes/admin
            $theme_paths[] = ROOT_THEMES_BACKEND . 'admin' . DS;
        }
        /**
         * 2. FRONTEND THEME
         */
        else
        {
            # get frontend theme from session for path construction
            $frontendtheme = Koch_HttpRequest::getRoute()->getFrontendTheme();

            # (a) USER FRONTENDTHEME - check, if template exists in current session user THEME
            $theme_paths[] = ROOT_THEMES_FRONTEND . $frontendtheme . DS;
            # (b) FRONTEND FALLBACK - check, if template exists in usertheme/modulename/tpl
            $theme_paths[] = ROOT_THEMES_FRONTEND . $frontendtheme . DS . 'modules' . DS . $module . DS;
            # (c) FRONTEND FALLBACK - check, if template exists in standard theme
            $theme_paths[] = ROOT_THEMES_FRONTEND . 'standard' . DS;
        }

        return $theme_paths;
    }

    /**
     * Returns the fullpath to Template by searching in the Theme Template Paths
     *
     * Note: For the implementation of module specific renderers and their related templates two ways exist:
     * a) add either a directory named after the "renderer/", like modules/modulename/view/renderer/actioname.tpl
     * b) name fileextension of the templates after the renderer (.xtpl, .phptpl, .tal).
     *
     * @param string $template Template Filename
     * @return string
     */
    public static function getThemeTemplatePath($template)
    {
        $paths = self::getThemeTemplatePaths();

        return self::findFileInPaths($paths, $template);
    }

    /**
     * Returns Module Template Paths
     *
     * @return array Module Template Paths
     */
    public static function getModuleTemplatePaths()
    {
        # fetch modulename for template path construction
        $module = Koch_TargetRoute::getModuleName();

        # fetch renderer name for template path construction
        $renderer = Koch_HttpRequest::getRoute()->getRenderEngine();

        # compose templates paths in the module dir
        $module_paths = array(
            ROOT_MOD,
            ROOT_MOD . $module . DS,
            ROOT_MOD . $module . DS . 'view' . DS,
            ROOT_MOD . $module . DS . 'view' . DS . $renderer . DS
        );

        return $module_paths;
    }

    /**
     * Returns the fullpath to Template by searching in the Module Template Path
     *
     * @param string $template Template Filename
     * @return string
     */
    public static function getModuleTemplatePath($template)
    {
        $paths = self::getModuleTemplatePaths();

        $module_template = null;

        # check if template exists in one of the defined paths
        $module_template = self::findFileInPaths($paths, $template);

        if($module_template != null)
        {
            return $module_template;
        }
        else
        {
            # fetch renderer name for template path construction
            $renderer = Koch_HttpRequest::getRoute()->getRenderEngine();

            # the template with that name is not found on our default paths
            return ROOT_THEMES_CORE . 'view' . DS . $renderer . DS . 'template_not_found.tpl';
        }
    }

    /**
     * Checks all paths of the array for the filename
     *
     * @param array $paths Paths to check
     * @param strig $filename template name
     * @return string Filepath.
     */
    public static function findFileInPaths($paths, $filename)
    {
        # check if the file exists in one of the defined paths
        foreach($paths as $path)
        {
            $file = $path . $filename;

            if(is_file($file) === true)
            {
                # file found
                return $file;
            }
        }

        # file not found
        return false;
    }
}
?>