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
abstract class Clansuite_Renderer_Base
{
    /**
     * @var Object Holds instance of the Rendering Engine Object
     */
    public $renderer = null;

    /**
     * @var object Holds instance of the Theme Object
     */
    public $theme = null;

    /**
     * @var string The layout template
     */
    public $layoutTemplate = null;

    /**
     * @var string Variable for the RenderMode (available: WRAPPED)
     */
    public $renderMode = null;

    /**
     * @var object Clansuite_Config
     */
    protected $config = null;

    /**
     * @var array|object Viewdata
     */
    public $viewdata = null;

    /**
     * @var object Clansuite_View_Mapper
     */
    public $view_mapper = null;

    /**
     * Construct Renderer
     *
     * @param Clansuite_Config Object
     */
    public function __construct(Clansuite_Config $config)
    {
        $this->config = $config;
        $this->view_mapper = new Clansuite_View_Mapper();
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
     * @param string $template Template Name for "Frontloader" Rendering Engines (xtpl).
     * @return Engine Object
     */
    abstract public function initializeEngine($template = null);

    /**
     * Configure the render engine object
     */
    abstract public function configureEngine();

    /**
     * Renders the given Template with renderMode wrapped (with Layout)
     *
     * @param string Template
     * @param array|object Data to assign to the template.
     * @return string
     */
    abstract public function render($template, $viewdata);

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

    public function getViewMapper()
    {
        return $this->view_mapper;
    }

    public function setViewMapper(Clansuite_View_Mapper $view_mapper)
    {
        $this->view_mapper = $view_mapper;
    }

    /**
     * Set the template name.
     *
     * Proxies to Clansuite_View_Mapper::setTemplate()
     *
     * @param string $template Name of the Template (full path)
     */
    public function setTemplate($template)
    {
        $this->getViewMapper()->setTemplate($template);
    }

    /**
     * Get the template name
     *
     * Proxies to Clansuite_View_Mapper::getTemplate()
     *
     * @return string $template Name of the Template (full path)
     */
    public function getTemplate()
    {
        return $this->getViewMapper()->getTemplate();
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
        $template_constants['www_root']                 = WWW_ROOT;
        $template_constants['www_root_uploads']         = WWW_ROOT . 'uploads/';
        $template_constants['www_root_mod']             = WWW_ROOT . 'modules/' . $modulename . '/';
        $template_constants['www_root_theme']           = $this->getTheme()->getWebPath();
        $template_constants['www_root_themes']          = WWW_ROOT_THEMES;
        $template_constants['www_root_themes_core']     = WWW_ROOT_THEMES_CORE;
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

        # Normal CSS (mainfile)
        $template_constants['css'] = $this->getTheme()->getCSSFile();

        # Normal Javascript (mainfile)
        $template_constants['javascript'] = $this->getTheme()->getJSFile();

        # Breadcrumb
        $template_constants['trail'] = Clansuite_Breadcrumb::getTrail();

        # Templatename itself
        $template_constants['templatename'] = $this->getTemplate();

        # Help Tracking
        $template_constants['helptracking'] = $this->config['help']['tracking'];

        /**
         * e) test browserinfo
         *   test in themes/frontend/dark/modules/index/action_show.tpl
         */
        $BrowserInfo = new Clansuite_Browserinfo();
        $template_constants['browserinfo'] = $BrowserInfo->getBrowserInfo();

        /**
         * Debug Display
         */
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
        if($this->layoutTemplate == null)
        {
            $this->setLayoutTemplate( $this->getTheme()->getLayoutFile() );
        }

        return $this->layoutTemplate;
    }

    /**
     * Returns the object Clansuite_Theme for accessing Configuration Values
     *
     * @return object Clansuite_Theme
     */
    public function getTheme()
    {
        if($this->theme === null)
        {
            $themename = Clansuite_HttpRequest::getRoute()->getThemeName();

            $this->theme = new Clansuite_Theme($themename);
        }

        return $this->theme;
    }

    /**
     * Auto-Escape for Template Variables.
     * This reduces the risk of forgetting to escape vars correctly.
     *
     * All variables assign to the template will be STRINGS,
     * because htmlentities will cast all values to string.
     * Character encoding used is UTF-8.
     *
     * @todo: do we need a config toggle for this?
     *
     * @param string $key The variable name.
     * @param mixed $val The variable value.
     * @return boolean True if data was assigned to view; false if not.
     */
    public function autoEscape($key, $value)
    {
        if (is_array($value))
        {
            $clean = array();
            foreach ($value as $key2 => $value2)
            {
                $clean[$key2] = htmlentities($value2, ENT_QUOTES, 'utf-8');
            }

            return $this->assign($clean);
        }
        else
        {
            $clean = htmlentities($value2, ENT_QUOTES, 'utf-8');

            return $this->assign($key, $clean);
        }
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
            throw new Exception('Method "'. $method .'()" not existant in Render Engine "' . get_class($this->renderer) .'"!', 1);
        }
    }

    # object duplication / cloning is not permitted
    protected function __clone()
    {
        return;
    }
}
?>