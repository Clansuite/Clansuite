<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-André Koch © 2005 - onwards
    * http://www.clansuite.com/
    *
    * This file is part of "Clansuite - just an eSports CMS".
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
    die('Clansuite not loaded. Direct Access forbidden.');
}

/**
 * A abstract base class for all our view rendering engines.
 * All renderers must extend from this class.
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Renderer
 */
abstract class Clansuite_Renderer_Base implements ArrayAccess
{
    /**
     * @var Object Holds instance of the Rendering Engine Object
     */
    public $renderer = null;

    /**
     * @var string The layout template
     */
    public $layoutTemplate = null;

    /**
     * @var string The "content" template
     */
    public $template = null;

    /**
     * @var string Variable for the RenderMode (available: WRAPPED)
     */
    public $renderMode = null;

    /**
     * @var object Clansuite_Config
     */
    protected $config = null;

    /**
     * Construct Renderer
     *
     * @param Clansuite_Config Object
     */
    public function __construct(Clansuite_Config $config)
    {
        $this->config   = $config;
    }

    /**
     * Returns the render engine object
     *
     * @return string
     */
    abstract public function getEngine();

    /**
     * Initialize the render engine object
     *
     * @return Engine Object
     */
    abstract public function initializeEngine();

    /**
     * Configure the render engine object
     */
    abstract public function configureEngine();

    /**
     * Renders the given Template with renderMode wrapped (with Layout)
     *
     * @return string
     */
    abstract public function render($template);

    /**
     * Renders the given Template with renderMode unwrapped (without Layout)
     *
     * @return string
     */
    abstract public function renderPartial($template);

    /**
     * Assigns a value to a template parameter.
     *
     * @param string $tpl_parameter The template parameter name
     * @param mixed $value The value to assign
     */
    abstract public function assign($tpl_parameter, $value = null);

    /**
     * Executes the template rendering and returns the result.
     *
     * @param string $template Template Filename
     * @param mixed $data Additional data to process
     * @return string
     */
    abstract public function fetch($template, $data = null);

    /**
     * Executes the template rendering and displays the result.
     *
     * @param string $template Template Filename
     * @param mixed $data Additional data to process
     * @return string
     */
    abstract public function display($template, $data = null);

    /**
     * Clear all assigned Variables
     */
    abstract public function clearVars();

    /**
     * Reset the Cache of the Renderer
     */
    abstract public function clearCache();

    /**
     * Set the template name
     *
     * @param string $template Name of the Template (full path)
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    }

    /**
     * Get the template name
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
    public function getTemplatePath($template)
    {
        # done: if template is a qualified path and template filename
        if(is_file($template) === true)
        {
            return $template;
        }

        # fetch the template by searching in the Theme Template Paths
        $theme_template = $this->getThemeTemplatePath($template);

        # check if template was found there, else it's null
        if($theme_template != null)
        {
            #Clansuite_Debug::firebug(__METHOD__ .' tries fetching template ("'. $theme_template . '") from THEME directory.');
            return $theme_template;
        }
        else # fetch the template by searching in the Module Template Path
        {
            #Clansuite_Debug::firebug(__METHOD__ .' tries fetching template ("'. $template . '") from MODULE directory.');
            return $this->getModuleTemplatePath($template);
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
        $module    = Clansuite_HttpRequest::getRoute()->getModuleName();
        $submodule = Clansuite_HttpRequest::getRoute()->getSubModuleName();
        $renderer  = Clansuite_HttpRequest::getRoute()->getRenderEngine();

        # get frontend and backend theme from session for path construction
        $frontendtheme = (isset($_SESSION['user']['frontend_theme'])) ? $_SESSION['user']['frontend_theme'] : 'standard';
        $backendtheme  = (isset($_SESSION['user']['backend_theme']))  ? $_SESSION['user']['backend_theme'] : 'admin';

        $theme_paths = array();

        /**
         * 1. BACKEND THEME
         * when controlcenter or admin is requested, it has to be a BACKEND theme
         */
        if($module == 'controlcenter' or $submodule == 'admin')
        {
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
    public function getThemeTemplatePath($template)
    {
        $paths = self::getThemeTemplatePaths();

        self::findTemplateInPaths($paths, $template);
    }

    /**
     * Returns Module Template Paths
     *
     * @return array Module Template Paths
     */
    public static function getModuleTemplatePaths()
    {
        # fetch modulename for template path construction
        $module = Clansuite_TargetRoute::getModuleName();

        # fetch renderer name for template path construction
        $renderer = Clansuite_HttpRequest::getRoute()->getRenderEngine();

        # compose two templates paths in the module dir
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
    public function getModuleTemplatePath($template)
    {
        $paths = self::getModuleTemplatePaths();

        # check if template exists in one of the defined paths
        self::findTemplateInPaths($paths, $template);

        # fetch renderer name for template path construction
        $renderer = Clansuite_HttpRequest::getRoute()->getRenderEngine();

        # the template with that name is not found on our default paths
        return ROOT_THEMES_CORE . 'view' . DS . $renderer . DS . 'template_not_found.tpl';
    }

    /**
     * Checks all paths of the array for the template
     *
     * @param array $paths Paths to check
     * @param strig $template template name
     * @return string
     */
    public static function findTemplateInPaths($paths, $template)
    {
        # check if template exists in one of the defined paths
        foreach($paths as $path)
        {
            $find_template = $path . $template;

            if(is_file($find_template) === true)
            {
                return $find_template;
            }
        }
    }

    /**
     * Constants for overall usage in all templates of all render engines
     *
     * a) Assign Web Paths
     * b) Meta
     * c) Clansuite version
     * d) Page related
     *
     * @return array $template_constants
     */
    public function getConstants()
    {
        $modulename = Clansuite_HttpRequest::getRoute()->getModuleName();

        $template_constants = array();

        /**
         * a) Assign Web Paths
         *
         *    Watch it! These Paths are relative (based on WWW_ROOT), not absolute!
         */
        $template_constants['www_root']             = WWW_ROOT;
        $template_constants['www_root_upload']      = WWW_ROOT . 'uploads/';
        $template_constants['www_root_mod']         = WWW_ROOT . 'modules/' . $modulename . '/';
        $template_constants['www_root_theme']       = WWW_ROOT_THEMES_FRONTEND . $_SESSION['user']['frontend_theme'] . '/';
        $template_constants['www_root_themes']      = WWW_ROOT_THEMES;
        $template_constants['www_root_themes_core'] = WWW_ROOT_THEMES_CORE;
        $template_constants['www_root_themes_backend']  = WWW_ROOT_THEMES_BACKEND;
        $template_constants['www_root_themes_frontend'] = WWW_ROOT_THEMES_FRONTEND;

        /**
         * b) Meta Informations
         */
        $template_constants['meta'] = $this->config['meta'];

        /**
         * c) Clansuite Version
         *
         *    Note: This is doubled functionality.
         *    You can use $smarty.const.CLANSUITE_VERSION or $clansuite_version in a template.
         */
        $template_constants['clansuite_version']       = CLANSUITE_VERSION;
        $template_constants['clansuite_version_state'] = CLANSUITE_VERSION_STATE;
        $template_constants['clansuite_version_name']  = CLANSUITE_VERSION_NAME;
        $template_constants['clansuite_revision']      = CLANSUITE_REVISION;
        $template_constants['clansuite_url']           = CLANSUITE_URL;

        /**
         * d) Page related
         */

        # Page Title
        $template_constants['pagetitle'] = $this->config['template']['pagetitle'];

        # Normal CSS (global)
        # @todo the selected $_SESSION['user']['frontend_theme'] decides on the theme type => frontend/backend
        $template_constants['css'] = $template_constants['www_root_theme'] . 'css/' . $this->config['template']['css'];

        # Minifed Stylesheets of a certain group (?g=) of css files
        $template_constants['minfied_css'] = ROOT_LIBRARIES . 'minify/?g=css&amp;' . $_SESSION['user']['frontend_theme'];

        # Normal Javascript (global)
        $template_constants['javascript'] = $template_constants['www_root_theme'] . 'javascript/' . $this->config['template']['javascript'];

        # Minifed Javascripts of a certain group (?g=) of js files
        $template_constants['minfied_javascript'] =  WWW_ROOT . 'libraries/minify/?g=js&amp;' . $_SESSION['user']['frontend_theme'];

        # Breadcrumb
        $template_constants['trail'] = Clansuite_Breadcrumb::getTrail();

        # Templatename itself
        $template_constants['templatename'] = $this->getTemplate();

        # Assign Benchmarks
        #$template_constants['db_exectime'] = benchmark::returnDbexectime() );

        # Help Tracking
        $template_constants['helptracking'] = $this->config['help']['tracking'];

        # Debug Display
        #Clansuite_Debug::printR($template_constants);

        return $template_constants;
    }

    /**
     * Set the Layout Template. The layout template is a Wrapper-Template.
     * The Content of a Module is rendered at variable {$content} inside this layout!
     *
     * @param string $template Template Filename for the wrapper Layout
     */
    public function setLayoutTemplate($template)
    {
        $this->layoutTemplate = $template;
    }

    /**
     * Returns the Name of the Layout Template.
     * Returns the config value if no layout template is set.
     *
     * @return string layout name, config layout as default
     */
    public function getLayoutTemplate()
    {
        if ($this->layoutTemplate == null)
        {
            $this->setLayoutTemplate($this->config['template']['layout']);
        }

        return $this->layoutTemplate;
    }

    /**
     * Magic Method __call / Overloading.
     *
     * This is basically a simple passthrough (aggregation)
     * of a method and its arguments to the renderingEngine!
     * Purpose: We don't have to rebuild all methods in the specific renderEngine Wrapper/Adapter
     * or pull out the renderEngine Object itself. We just pass things to it.
     *
     * @param string $method Name of the Method
     * @param array $arguments Array with Arguments
     *
     * @return Function Call to Method
     */
    public function __call($method, $arguments)
    {
        #echo'Magic used for Loading Method = '. $method . ' with Arguments = '. var_dump($arguments);
        if(method_exists($this->renderer, $method))
        {
            return call_user_func_array(array($this->renderer, $method), $arguments);
        }
        else
        {
            throw new Exception('Method "'. $method .'" not existant in RenderEngine "' . get_class($this->renderer) .'"!', 1);
        }
    }

    # object duplication / cloning is not permitted
    protected function __clone()
    {
        return;
    }

    /**
     * Gets a config file item based on keyname
     *
     * @param    string    the config item key
     * @return   void
     */
    public function __get($configkey)
    {
        if(isset($this->renderer[$configkey]))
        {
            return $this->renderer[$configkey];
        }
        else
        {
            return null;
        }
    }

    /**
     * Set a config file item based on key:value
     *
     * @param string the config item key
     * @param string the config item value
     * @return   void
     */
    public function __set($key, $value)
    {
        $this->renderer[$key] = $value;
        return true;
    }

    /**
     * Method allows 'isset' to work on $this->data
     *
     * @param string $name Name of Variable Key $this->data[$name]
     * @return return mixed
     */
    public function __isset($name)
    {
        return isset($this->renderer[$name]);
    }

    /**
     * Method allows 'unset' calls to work on $this->data
     *
     * @param string $key
     */
    public function __unset($key)
    {
        unset($this->renderer[$key]);
    }

    /**
     * Implementation of SPL ArrayAccess
     */
    public function offsetExists($offset)
    {
        return isset($this->renderer[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->__get($offset);
    }

    public function offsetSet($offset, $value)
    {
        $this->__set($offset, $value);
    }

    public function offsetUnset($offset)
    {
        unset($this->renderer[$offset]);
        return true;
    }
}
?>