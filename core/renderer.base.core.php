<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-Andr� Koch � 2005 - onwards
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
    * @author     Jens-Andr� Koch <vain@clansuite.com>
    * @copyright  Jens-Andr� Koch (2005 - onwards)
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
 * @subpackage  Renderer
 */
abstract class Clansuite_Renderer_Base
{
    /**
     * Holds instance of the Rendering Engine Object
     *
     * @var Object
     */
    public $renderer            = null;     #

    public $layoutTemplate      = null;
    public $template            = null;

    /**
     * Variable for the RenderMode (available: WRAPPED)
     *
     * @var string
     */
    public $renderMode = null;

    /**
     * Instances of Dependency Injector Phemto and Clansuite_Config
     *
     * @var object
     */
    protected $injector         = null;
    protected $config           = null;

    /**
     * Construct Renderer
     *
     * @param Phemto $injector Dependency Injector
     * @param Clansuite_Config Object
     */
    public function __construct(Phemto $injector, Clansuite_Config $config)
    {
        # set injector and config
        $this->injector = $injector;
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
    /** abstract **/ public function initializeEngine(){}

    /**
     * Configure the render engine object
     */
    /** abstract **/ public function configureEngine(){}

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
    /** abstract **/ public function renderPartial($template) {}

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
     * Set the template name
     *
     * @param string $template Name of the Template with full Path
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
     * Path Priority
     * 1) Themes
     * 2) modulename/templates
     *
     * @return $templatepath
     */
    public function getTemplatePath($template)
    {
        # if template is a qualified path and template filename
        if(is_file($template)) { return $template; }

        # fetch the template via looking through Theme Template Paths
        $theme_template = $this->getThemeTemplatePath($template);

        # check if template was found there, else it's null
        if($theme_template != null)
        {
            #echo '<br>'. __METHOD__ .' via THEME = '. $theme_template . '<br>';
            return $theme_template;
        }
        else # try a lookup in the Module Template Path
        {
            # fetch the template via looking through Module Template Paths
            #$module_template = $this->getModuleTemplatePath($template);
            #echo '<br>'. __METHOD__ .' via MODULE = '. $module_template . '<br>';
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
        # init var
        $themepath = '';

        # get module and submodule names
        $module    = Clansuite_ModuleController_Resolver::getModuleName();
        $submodule = Clansuite_ModuleController_Resolver::getSubModuleName();

        # 1. because controlcenter or admin is requested, it has to be a BACKEND theme
        if($module == 'controlcenter' or $submodule == 'admin')
        {
            # (a) USER BACKENDTHEME - check in the active session backendtheme
            if(is_file(ROOT_THEMES . $_SESSION['user']['backendtheme'] .DS. $template) && isset($_SESSION['user']['backendtheme']) > 0)
            {
                return  ROOT_THEMES . $_SESSION['user']['backendtheme'] .DS. $template;
            }
            # (b) BACKEND FALLBACK - check the fallback dir: themes/admin
            elseif(is_file( ROOT_THEMES . 'admin' .DS. $template))
            {
                return ROOT_THEMES . 'admin' .DS. $template;
            }
        }
        else # 2. it's a FRONTEND theme
        {
            # (a) USER FRONTENDTHEME - check, if template exists in current session user THEME
            if(is_file( ROOT_THEMES . $_SESSION['user']['theme'] .DS. $template) && isset($_SESSION['user']['theme']) > 0)
            {
                return ROOT_THEMES . $_SESSION['user']['theme'] .DS. $template;
            }
            # (b) FRONTEND FALLBACK - check, if template exists in usertheme/modulename/tpl
            elseif(is_file( ROOT_THEMES . $_SESSION['user']['theme'] .DS. $module .DS. $template ))
            {
                return ROOT_THEMES . $_SESSION['user']['theme'] .DS. $module .DS.  $template;
            }
            # (c) FRONTEND FALLBACK - check, if template exists in standard theme
            elseif(is_file( ROOT_THEMES . '/standard/' . $template))
            {
                return ROOT_THEMES . '/standard/' . $template;
            }
        }
    }

    /**
     * Return the Modules Template-Path
     *
     * @param string $template Template Filename
     * @return string
     */
    public function getModuleTemplatePath($template)
    {
        # Debug Display
       # echo '<br>'. __METHOD__ .' INPUT '. $template . '<br>';

        # init var
        $modulepath = '';

        # Method 1: get module/action names
        $module = Clansuite_ModuleController_Resolver::getModuleName();
        #$action = Clansuite_ActionController_Resolver::getActionName();

        /*
        if(is_file( ROOT_MOD . $moduleName .'/templates/'. $actionName .'.tpl'))
        {
            return ROOT_MOD . $moduleName .'/templates/'. $actionName .'.tpl';
        }
        */

        # Method 2: detect it via $template string
        # Given is a string like "news/show.tpl"
        # we insert "/templates" at the last slash

        /*
        # attach "/template/" to the $template string, at "news\action_show.tpl" DS
        $template = substr_replace($template, DS.'templates'.DS , strpos($template,DS), 0);
        # single slash correction
        $template = str_replace("\\", "/",  $template);
        # get rid of double slashes
        $template = str_replace("//", "/",  $template);
        $template = str_replace("\\\\", "\\",  $template);
        */

        # Check, if template exists in module folder + 'templates/name.tpl'
        # template has to be something like "/news/templates/action_show.tpl"
        if(is_file( ROOT_MOD . $template ))
        {
            $modulepath = ROOT_MOD . $template;
        }

        # Check, if template exists in module folder + 'news' + 'templates/name.tpl'
        elseif(is_file( ROOT_MOD . $module . $template))
        {
            $modulepath =  ROOT_MOD . $module . $template;
        }

        # Check, if template exists in module folder + 'news' + 'templates' +'/name.tpl'
        elseif(is_file( ROOT_MOD . $module . '/templates/' . $template))
        {
            $modulepath =  ROOT_MOD . $module . '/templates/' . $template;
        }

        # If template is not existant, show template_not_found
        elseif(strlen($modulepath) == 0)
        {
            $modulepath = ROOT_THEMES . 'core/templates/template_not_found.tpl';
        }

        # single slash correction
        $modulepath = str_replace("\\", "/",  $modulepath);

        #echo '<br>'. __METHOD__ .' OUTPUT '.'<br>We found '. $template  . ' on the module path: '. $modulepath. '<br>';

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
        $template_constants['pagetitle'] = $this->config['template']['pagetitle'];

        # Normal CSS (global)
        $template_constants['css'] = WWW_ROOT_THEMES .'/'. $_SESSION['user']['theme'] .'/'. $this->config['template']['css'];

        # Normal Javascript (global)
        $template_constants['javascript'] = WWW_ROOT_THEMES .'/'. $_SESSION['user']['theme'] .'/'. $this->config['template']['javascript'];

        # Breadcrumb
        $template_constants['trail'] = Clansuite_Trail::getTrail();

        # Templatename itself
        $template_constants['template_to_render'] = $this->getTemplate();

        # Assign Benchmarks
        #$template_constants['db_exectime'] = benchmark::returnDbexectime() );

        # Debug Display
        #clansuite_xdebug::printR($template_constants);

        return $template_constants;
    }

     /**
     * Sets a so called Wrapper-Template = Layout.
     * The Content of a Module is rendered at variable $content inside this layout!
     *
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
     * @return string layout name, config layout as default
     */
    public function getLayoutTemplate()
    {
        #echo 'Layout Template: '.$this->layoutTemplate.'<br>';
        if (empty($this->layoutTemplate))
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
        #print 'Magic used for Loading Method = '. $method . ' with Arguments = '. var_dump($arguments);
        if(method_exists($this->renderer, $method))
        {
            # this should be faster then call_user_func_array
            return clansuite_loader::callMethod($this->renderer, $method, $arguments);

            # leave this for clarification
            # return call_user_func_array(array($this->renderer, $method), $arguments);
        }
        else
        {
            throw new Exception('Method "'. $method .'" not existant in RenderEngine "' . get_class($this->renderer) .'"!', 1);
        }
    }
}
?>