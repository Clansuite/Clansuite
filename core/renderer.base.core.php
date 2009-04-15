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
    *
    * @author     Jens-André Koch <vain@clansuite.com>
    * @copyright  Jens-André Koch (2005 - onwards)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    *
    * @version    SVN: $Id$
    */

//Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}

/**
 * A abstract base class for all our view rendering engines.
 * All renderers must extend from this class.
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  View
 */
abstract class Clansuite_Renderer_Base
{
    public $view = null;     # holds instance of the Rendering Engine
    public $config = null;
    public $layoutTemplate = null;
    protected $injector;     # holds instance of Dependency Injector Phemto (object)

    /**
     * Construct View from Module.
     *
     * @param Phemto $injector Dependency Injector
     */
    public function __construct(Phemto $injector)
    {
        $this->injector = $injector;    # set Injector

        $this->config   = $this->injector->instantiate('Clansuite_Config');
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
     * @access public
     *
     * @return Function Call to Method
     */
    public function __call($method, $arguments)
    {
        #print 'Magic used for Loading Method = '. $method . ' with Arguments = '. var_dump($arguments);
        if(method_exists($this->view, $method))
        {
            # this should be faster then call_user_func_array
            return clansuite_loader::callMethod($this->view, $method, $arguments);

            # leave this for clarification
            # return call_user_func_array(array($this->view, $method), $arguments);
        }
        else
        {
            throw new Exception('Method "'. $method .'" not existant in RenderEngine "' . get_class($this->view) .'"!', 1);
        }
    }

    /**
     * Assigns a value to a template parameter.
     *
     * @param string $tpl_parameter The template parameter name
     * @param mixed $value The value to assign
     */
    /*abstract*/ public function assign($tpl_parameter, $value = null) {}

    /**
     * Executes the template rendering and returns the result.
     *
     * @param string $template Template Filename
     * @param mixed $data Additional data to process
     * @return string
     */
    /*abstract*/ public function fetch($template, $data = null) {}

    /**
     * Executes the template rendering and displays the result.
     *
     * @param string $template Template Filename
     * @param mixed $data Additional data to process
     * @return string
     */
    /*abstract*/ public function display($template, $data = null) {}
    
    /**
     * Returns the render engine object
     *
     * @return string
     */
    abstract public function getEngine();
    
    /**
     * Renders the given Template
     *
     * @return string
     */
    abstract public function render($template);

    /**
     * Returns the Template Path
     *
     * Path Priority
     * 1) Themes
     * 2) modulename/templates
     *
     * @return $templatepath
     */
    public function getTemplatePath($template)
    {
        # if template is a qualified path and template filename
        # @todo why this? not needed?
        if(is_file($template)) { return $template; }

        # default is Theme Template Path
        $theme_template = $this->getThemeTemplatePath($template);

        # return THEME path, if this is more than empty ( != not found)
        if(strlen($theme_template) > 0)
        {
            return $theme_template;
        }
        else # try a lookup in the Module Template Path
        {
            return $this->getModuleTemplatePath($template);
        }
    }

    /**
     * Return the Themes Template-Path
     *
     * @param string $template Template Filename
     * @return string
     *
     * @todo for renderer related templates we have to add "renderer/", like
     *        modules/modulename/templates/renderer/actioname.tpl
     */
    public function getThemeTemplatePath($template)
    {
        # Debug Display
        # echo $template;
        # echo ROOT_THEMES . $_SESSION['user']['theme'] .DS. $template;
        # echo ROOT_THEMES . 'admin' .DS. $template;

        # init var
        $themepath = '';

        # 1. Check, if template exists in current Session THEME/templates
        if(is_file( ROOT_THEMES . $_SESSION['user']['theme'] .DS. $template) && isset($_SESSION['user']['theme']) > 0)
        {
            $themepath =  ROOT_THEMES . $_SESSION['user']['theme'] .DS. $template;
        }

        # 2. Check, if template exists in standard theme
        /*
        elseif(is_file( ROOT_THEMES . '/standard/' . $template))
        {

            $themepath = ROOT_THEMES . '/standard/' . $template;
        }*/

        # ADMIN-Theme

        elseif(is_file( ROOT_THEMES . 'admin' .DS. $template))
        {
            $themepath = ROOT_THEMES . 'admin' .DS. $template;
        }

        #print '<br>getThemeTemplatePath: '. $themepath . '<br>';

        return $themepath;
    }

    /**
     * Return the Modules Template-Path
     *
     * @param string $template Template Filename
     * @return string
     */
    public function getModuleTemplatePath($template)
    {
        # init var
        $modulepath = '';

        # Method 1: get module/action names (we dont have action controller resolver yet)
        # Leave this!
        $moduleName = Clansuite_ModuleController_Resolver::getModuleName();
        # $actionName = Clansuite_ActionController_Resolver::getActionName(); ???
        /*
         if(is_file( ROOT_MOD . $moduleName .'/templates/'. $actionName .'.tpl'))
        {
            $modulepath = ROOT_MOD . $moduleName .'/templates/'. $actionName .'.tpl';
        }*/

        # Method 2: detect it via $template string
        # Given is a string like "news/show.tpl"
        # we insert "/templates" at the last slash

        #echo ROOT_THEMES . $_SESSION['user']['theme'] .DS. $moduleName .DS. $template;

        # if template was found in session theme directory
        #
        # Example:
        # index\action_show.tpl
        # D:\xampplite\htdocs\work\clansuite\trunk\themes\standard\index\index\action_show.tpl
        if(is_file( ROOT_THEMES . $_SESSION['user']['theme'] .DS. $moduleName .DS. $template ))
        {
            return ROOT_THEMES . $_SESSION['user']['theme'] .DS. $moduleName .DS.  $template;
        }

        # attach "/template/" to the $template string, at "news\action_show.tpl" DS
        $template = substr_replace($template, DS.'templates'.DS , strpos($template,DS), 0);

        # single slash correction
        $template = str_replace("\\", "/",  $template);
        # get rid of double slashes
        $template = str_replace("//", "/",  $template);
        $template = str_replace("\\\\", "\\",  $template);

        # Check, if template exists in module folder + 'templates/name.tpl'
        if(is_file( ROOT_MOD . $template ))
        {
            $modulepath = ROOT_MOD . $template;
        }

        # Check, if template exists in module folder + 'news' + 'templates/name.tpl'
        if(is_file( ROOT_MOD . $moduleName . $template))
        {
            $modulepath =  ROOT_MOD . $moduleName . $template;
        }

        # Check, if template exists in module folder + 'news' + 'templates' +'/name.tpl'
        if(is_file( ROOT_MOD . $moduleName . '/templates/' . $template))
        {
            $modulepath =  ROOT_MOD . $moduleName . '/templates/' . $template;
        }

        if(strlen($modulepath) == 0)
        {
            # NOT EXISTANT
            $modulepath = ROOT_THEMES . 'core/template_not_found.tpl';
        }

        #echo '<br>We tried to getModuleTemplatePath: '.$modulepath . '<br> while requested Template is: ' . $template;

        return $modulepath;
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
        /**
         * a) Assign Web Paths
         *
         * Watch it! These Paths are relative (based on WWW_ROOT), not absolute!
         *
         * @see config.class
         */
        $template_constants['www_root']             = WWW_ROOT;
        $template_constants['www_root_upload']      = WWW_ROOT .'/'. $this->config['paths']['upload_folder'];
        $template_constants['www_root_mod']         = WWW_ROOT .'/modules/' . Clansuite_ModuleController_Resolver::getModuleName();
        $template_constants['www_root_theme']       = WWW_ROOT_THEMES .'/'. $_SESSION['user']['theme'];
        $template_constants['www_root_themes']      = WWW_ROOT_THEMES;
        $template_constants['www_root_themes_core'] = WWW_ROOT_THEMES_CORE;

        # b) Meta Informations
        $template_constants['meta'] = $this->config['meta'];

        /**
         * c) Clansuite Version
         *
         *    Note:
         *    doubled functionality: you can use $smarty.const.CLANSUITE_VERSION or $clansuite_version
         */
        $template_constants['clansuite_version']       = CLANSUITE_VERSION;
        $template_constants['clansuite_version_state'] = CLANSUITE_VERSION_STATE;
        $template_constants['clansuite_version_name']  = CLANSUITE_VERSION_NAME;
        $template_constants['clansuite_revision']      = CLANSUITE_REVISION;

        /**
         * d) Page related
         */

        # Page Title
        $template_constants['std_page_title'] = $this->config['template']['std_page_title'];

        # Normal CSS (global)
        $template_constants['css'] = WWW_ROOT_THEMES .'/'. $_SESSION['user']['theme'] .'/'. $this->config['template']['std_css'];

        # Normal Javascript (global)
        $template_constants['javascript'] = WWW_ROOT_THEMES .'/'. $_SESSION['user']['theme'] .'/'. $this->config['template']['std_javascript'];

        # Breadcrumb
        $template_constants['trail'] = Clansuite_Trail::getTrail();

        # Assign Benchmarks
        #$template_constants['db_exectime'] = benchmark::returnDbexectime() );

        return $template_constants;
    }

     /**
     * Sets a so called Wrapper-Template = Layout.
     * The Content of a Module is rendered at variable $content inside this layout!
     *
     * @access public
     * @param string $template Template Filename for the wrapper Layout
     * @return string
     */
    public function setLayoutTemplate($template)
    {
        #if (is_file($template) && is_readable($template))
        #{
            $this->layoutTemplate = $template;
        #}
        #else
        #{
            #throw new Exception('Invalid Smarty Layout Template provided. Check Name and Path.');
        #}
    }

     /**
     * Returns the Name of the Layout Template.
     * Returns the config value if no layout template is set
     *
     * @access public
     * @return string layout name, config tpl_wrapper_file as default
     */
    public function getLayoutTemplate()
    {
        #echo 'Layout Template: '.$this->layoutTemplate.'<br>';
        if (empty($this->layoutTemplate))
        {
            $this->setLayoutTemplate($this->config['template']['tpl_wrapper_file']);
        }

        return $this->layoutTemplate;
    }
    
    /**
     * View Helpers
     *
     * You can use this inside templates
     * Smarty-> PHP = $this->helper(helpername);
     *
     * @name viewhelper
     * @param string $src
     * @param bool $nothrow
     */
    /*
    function viewhelper($src, $return)
    {
        $src = 'this->' . trim($src);            
        require_once $this->getEngine.'/HelperImplementation.php';
        return ENGINE_HelperImplementation::($src);
    }
    */
}
?>