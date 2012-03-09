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

namespace Koch\View;

# Security Handler
if(defined('IN_CS') === false)
{
    exit('Koch Framework not loaded. Direct Access forbidden.');
}

/**
 * Koch FrameworkView Class - View for Smarty Templates
 *
 * This is a wrapper/adapter for the Smarty Template Engine.
 *
 * @link http://smarty.php.net/ Official Website of Smarty Template Engine
 * @link http://smarty.incutio.com/ Smarty Wiki
 *
 * @author     Jens-André Koch <vain@clansuite.com>
 * @copyright  Jens-André Koch (2005 - onwards)
 *
 * @category    Koch
 * @package     Core
 * @subpackage  Renderer
 */
class Smarty extends Renderer_Base
{

    /**
     * RenderEngineConstructor
     *
     * parent::__construct does the following:
     * 1) Apply instances of Koch_Config to the RenderBase
     * 2) Initialize the RenderEngine via parent class constructor call = self::initializeEngine()
     * 3) Configure the RenderEngine with it's specific settings = self::configureEngine();
     */
    function __construct(Koch_Config $config)
    {
        parent::__construct($config);
        $this->initializeEngine();
        $this->configureEngine();

        # debug display of all smarty related directories
        # $this->renderer->testInstall();
    }

    /**
     * Set up Smarty Template Engine
     *
     * @param string $template Template Name for "Frontloader" Rendering Engines (xtpl).
     */
    public function initializeEngine($template = null)
    {
        # prevent redeclaration
        if(class_exists('Smarty', false) === false)
        {
            # check if Smarty library exists
            if(is_file(ROOT_LIBRARIES . 'smarty/Smarty.class.php') === true)
            {
                include ROOT_LIBRARIES . 'smarty/Smarty.class.php';
            }
            else
            {
                throw new Exception('Smarty Template Library missing!');
            }
        }

        # Do it with smarty style > eat like a bird, poop like an elefant!
        $this->renderer = new Smarty();
    }

    /**
     * Render Engine Configuration
     * Configures the Smarty Object
     */
    public function configureEngine()
    {
        /**
         * Directories
         */
        $this->renderer->compile_dir = ROOT_CACHE . 'tpl_compile' . DS;
        $this->renderer->config_dir  = ROOT_LIBRARIES . 'smarty' . DS . 'configs' . DS;
        $this->renderer->cache_dir   = ROOT_CACHE . 'tpl_cache' . DS;

        /**
         * Debugging
         */
        $this->renderer->debugging = DEBUG ? true : false; # set smarty debugging, when debug on
        if($this->renderer->debugging === true)
        {
            $this->renderer->debug_tpl = ROOT_THEMES_CORE . 'view/smarty/debug.tpl';   # set debugging template for smarty
            #$this->renderer->debug_tpl  = ROOT_LIBRARIES . 'smarty/debug.tpl';   # set debugging template for smarty
            $this->renderer->clearCompiledTemplate(); # clear compiled tpls in case of debug
            $this->renderer->clearAllCache();         # clear cache
        }

        $this->renderer->auto_literal = true;     # auto delimiter of javascript/css (The literal tag of Smarty v2.x)

        /**
         * SMARTY FILTERS
         */
        $autoload_filters = array();
        if($this->renderer->debugging === true)
        {
            $autoload_filters = array('pre' => array('inserttplnames'));
        }
        $this->renderer->autoload_filters = $autoload_filters;
        #array(       # indicates which filters will be auto-loaded
        #'pre'    => array('inserttplnames'),
        #'post'   => array(),
        #'output' => array('trimwhitespaces')
        #);

        /**
         * COMPILER OPTIONS
         */
        # defines the compiler class for Smarty ... ONLY FOR ADVANCED USERS
        # $this->renderer->compiler_class   = "Smarty_Compiler";
        # set individual compile_id instead of assign compile_ids to function-calls (useful with prefilter for different languages)
        # $this->renderer->compile_id       = 0;

        /**
         * recompile/rewrite templates only in debug mode
         * @see http://www.smarty.net/manual/de/variable.compile.check.php
         */
        if($this->renderer->debugging === true)
        {
            # if a template was changed it would be recompiled, if set to false nothing will be compiled (changes take no effect)
            $this->renderer->compile_check = true;
            # if true compiles each template everytime, overwrites $compile_check
            $this->renderer->force_compile = true;
        }
        else
        {
            $this->renderer->compile_check = false;
            $this->renderer->force_compile = false;
        }

        /**
         * CACHING OPTIONS (set these options if caching is enabled)
         */
        # Koch_Debug::printr($this->config['smarty']);
        if($this->renderer->debugging === true)
        {
            $this->renderer->caching                = 0;
            $this->renderer->cache_lifetime         = 0;       # refresh templates on every load
            # $this->renderer->cache_handler_func   = "";      # Specify your own cache_handler function
            $this->renderer->cache_modified_check   = 0;       # set to 1 to activate
        }
        else
        {
            # $this->renderer->setCaching(true);
            $this->renderer->caching = (bool) $this->config['smarty']['cache'];
            # -1 ... dont expire, 0 ... refresh everytime
            $this->renderer->cache_lifetime = isset($this->config['smarty']['cache_lifetime']) ? $this->config['smarty']['cache_lifetime'] : 0;
            # $this->renderer->cache_handler_func   = "";      # Specify your own cache_handler function
            $this->renderer->cache_modified_check   = 1;       # set to 1 to activate
        }

        /**
         * DEFAULT TEMPLATE HANDLER FUNCTION
         */
        # $this->renderer->default_template_handler_func = "";

        /**
         *  ENGINE SETTINGS
         */
        # $this->renderer->left_delimiter           = "{";    # default : {
        # $this->renderer->right_delimiter          = "}";    # default : }
        #$this->renderer->show_info_header           = false;  # if true : Smarty Version and Compiler Date are displayed as comment in template files
        #$this->renderer->show_info_include          = false;  # if true : adds an HTML comment at start and end of template files
        # $this->renderer->request_vars_order       = "";     # order in which the request variables were set, same as 'variables_order' in php.ini
        #$this->renderer->use_sub_dirs               = true;   # set to false if creating subdirs is not allowed, but subdirs are more efficiant

        /**
         * Smarty Template Directories
         *
         * This sets multiple template dirs, with the following detection order:
         * The user-choosen Theme, before Module, before Core/Default/Admin-Theme.
         *
         * 1) "/themes/[frontend|backend]/theme_of_user_session/"
         * 2) "/themes/[frontend|backend]/theme_of_user_session/modulename/"
         * 3) "/modules/"
         * 4) "/modules/modulename/view/"
         * 5) "/themes/core/view/"
         * 6) "/themes/"
         */
        $tpl_array = array(
            Koch_View_Mapper::getThemeTemplatePaths(), # 1 + 2
            Koch_View_Mapper::getModuleTemplatePaths(), # 3 + 4
            ROOT_THEMES_CORE . 'view' . DS . 'smarty', # 5
            ROOT_THEMES # 6
        );

        # flatten that thing
        $this->renderer->template_dir = Koch_Functions::array_flatten($tpl_array);

        #Koch_Debug::printR($this->renderer->template_dir);

        /**
         * Smarty Plugins
         *
         * Configure Smarty Viewhelper Directories
         * 1) original smarty plugins               => libraries\smarty\plugins\
         * 2) clansuite core/common smarty plugins  => core\viewhelper\smarty\
         * 3) clansuite module smarty plugins       => modules\module_name\viewhelper\smarty\
         */

        $this->renderer->setPluginsDir(
            array(
                ROOT_LIBRARIES . 'smarty' . DS . 'plugins',
                ROOT_CORE . 'viewhelper' . DS . 'smarty' . DS,
                ROOT_MOD . Koch_TargetRoute::getModuleName() . DS . 'viewhelper' . DS . 'smarty' . DS
        ));


        #Koch_Debug::printR($this->renderer->plugins_dir);

        # $this->renderer->registerPlugin('modifier', 'timemarker',  array('benchmark', 'timemarker'));

        #$this->renderer->registerFilter(Smarty::FILTER_VARIABLE, 'htmlspecialchars');

        # Auto-Escape all variables
        #$this->renderer->default_modifiers = array('escape:"html":"UTF-8"');

        # compile time setting, tpls need recompiling
        $this->renderer->merge_compiled_includes = true;
    }

    /**
     * Returns a clean Smarty Object
     *
     * @return Smarty Object
     */
    public function getEngine()
    {
        if(is_object($this->renderer) === true)
        {
            # reset all prior assigns and configuration settings
            $this->renderer->clearAllAssign();
            $this->renderer->clearConfig();
        }
        else
        {
            self::initializeEngine();
        }

        # reload the base configuration to have default template paths and debug-settings
        self::configureEngine();

        return $this->renderer;
    }

    /**
     * Adds a Template Path
     *
     * @param string $templatepath Template-Directory to have Smarty search in
     */
    public function setTemplatePath($templatepath)
    {
        if(is_dir($templatepath) === true and is_readable($templatepath) === true)
        {
            $this->renderer->template_dir[] = $templatepath;
        }
        else
        {
            throw new Exception('Invalid Smarty Template path provided: Path not existing or not readable. Path: ' . $templatepath);
        }
    }

    /**
     * Get the TemplatePaths Array from Smarty
     *
     * @return array, string
     */
    public function getTemplatePaths()
    {
        return $this->renderer->template_dir;
    }

    /**
     * Set/Assign a Variable to Smarty
     *
     * 1. Set a single Key-Variable ($tpl_parameter) with it's value ($value)
     * 2. Set a array with multiple Keys and Values
     *
     * @see __set()
     * @param string|array $tpl_parameter Is a Key or an Array.
     * @param mixed $value (optional) In case a key-value pair is used, $value is the value.
     */
    public function assign($tpl_parameter, $value = null)
    {
        if(is_array($tpl_parameter) === true or is_object($tpl_parameter) === true )
        {
            $this->renderer->assign($tpl_parameter);
        }
        else
        {
            $this->renderer->assign($tpl_parameter, $value);
        }
    }

    /**
     * Checks if a template is cached.
     *
     * @param string $template the resource handle of the template file or template object
     * @param mixed $cache_id cache id to be used with this template
     * @param mixed $compile_id compile id to be used with this template
     * @return boolean Returns true in case the template is cached, false otherwise.
     */
    public function isCached($template, $cache_id = null, $compile_id = null)
    {
        if($this->renderer->isCached($template, $cache_id, $compile_id))
        {
            return true;
        }
        return false;
    }

    /**
     * Magic Method to get a already set/assigned Variable from Smarty
     *
     * @param string $key Name of Variable
     * @return mixed Value of key
     */
    public function __get($key)
    {
        return $this->renderer->getTemplateVars($key);
    }

    /**
     * Magic Method to set/assign Variable to Smarty
     *
     * @param string $key Name of the variable
     * @param mixed $val Value of variable
     */
    public function __set($key, $value)
    {
        $this->assign($key, $value);
    }

     /**
      * Magic Method to testing with empty() and isset() for Smarty Template Variables
      *
      * @param string $key
      * @return boolean
      */
     public function __isset($key)
     {
         return (null !== $this->renderer->getTemplateVars($key));
     }

     /**
      * Magic Method to unset() Smarty Template Variables
      *
      * @param string $key
      * @return void
      */
     public function __unset($key)
    {
         $this->renderer->clearAssign($key);
     }

    /**
     * Executes the template fetching and returns the result.
     *
     * @param string|object $template the resource handle of the template file  or template object
     * @param mixed $cache_id cache id to be used with this template
     * @param mixed $compile_id compile id to be used with this template
     * @param object $parent next higher level of Smarty variables
     * @param boolean $display Renders the template content on true.
     * @return Returns the $template content.
     */
    public function fetch($template, $cache_id = null, $compile_id = null, $parent = null, $display = false)
    {
        # ask the view mapper for the template path
        $template = Koch_View_Mapper::getTemplatePath($template);

        # create cache_id
        if($cache_id === null)
        {
            $cache_id = $this->createCacheId();
        }

        return $this->renderer->fetch($template, $cache_id, $compile_id, $parent, $display);
    }

    /**
     * Creates a cache_id.
     *
     * @return string Returns md5 string as cache_id.
     */
    protected static function createCacheId()
    {
        $module    = Koch_TargetRoute::getModuleName();
        $submodule = Koch_TargetRoute::getSubModuleName();
        $action    = Koch_TargetRoute::getActionName();

        return md5(strtolower($module . $submodule . $action));
    }

    /**
     * Executes the template rendering and displays the result.
     *
     * @param string|object $template the resource handle of the template file  or template object
     * @param mixed $cache_id cache id to be used with this template
     * @param mixed $compile_id compile id to be used with this template
     * @param object $parent next higher level of Smarty variables
     */
    public function display($template, $cache_id = null, $compile_id = null, $parent = null)
    {
        # redirect to fetch, but set display to true
        $this->fetch($template, $cache_id, $compile_id, $parent, true);
    }

    /**
     * Returns all assigned template variables.
     *
     * @return array
     */
    public function getVars()
    {
        return $this->renderer->getTemplateVars();
    }

    /**
     * Clears all assigned Variables
     */
    public function clearVars()
    {
        $this->renderer->clearAllAssign();
    }

    /**
     * Empty cache for a specific template
     *
     * @param string  $template_name template name
     * @param string  $cache_id      cache id
     * @param string  $compile_id    compile id
     * @param integer $exp_time      expiration time
     * @param string  $type          resource type
     * @return integer number of cache files deleted
     */
    public function clearCache($template_name, $cache_id = null, $compile_id = null, $exp_time = null, $type = null)
    {
        $this->renderer->clearCache($template_name, $cache_id = null, $compile_id = null, $exp_time = null, $type = null);
    }

    /**
     * Clears the Smarty Template Cache folder and removes compiled Templates
     */
    public function resetCache()
    {
        # empty cache folder
        $this->renderer->clearAllCache();
        # empty compile folder
        $this->renderer->clearCompiledTemplate();
    }

    /**
     * Enables or disables smarty caching
     *
     * @param bool $boolean
     */
    public function setCaching($boolean = 'true')
    {
        $this->renderer->caching = $boolean;

        return $this;
    }

    /**
     * Setter for RenderMode
     *
     * @param string $mode Set the renderMode (LAYOUT, NOLAYOUT)
     */
    public function setRenderMode($mode)
    {
        $this->renderMode = $mode;
    }

    /**
     * Getter for RenderMode
     *
     * @return string Returns the renderMode (LAYOUT, NOLAYOUT). Defaults to LAYOUT.
     */
    public function getRenderMode()
    {
        if(empty($this->renderMode))
        {
            $this->renderMode = 'LAYOUT';
        }
        return $this->renderMode;
    }

    /**
     * Koch_Renderer_Smarty->render
     *
     * Returns the mainframe layout with inserted modulcontent (templatename).
     *
     * 1. assign common values and constants
     * 2. fetch the modultemplate and assigns it as $content
     * 3. return the wrapper layout tpl
     *
     * @param string $templatename Template Filename
     * @param array|object $data Data to assign to the view.
     * @return wrapper tpl layout
     */
    public function render($template, $viewdata = null)
    {
        if(isset($viewdata))
        {
            $this->assign($viewdata);
        }

        # 1. assign common template values and Application constants as Smarty Template Variables.
        $this->renderer->assignGlobal($this->getConstants());

        /**
         * Assign the original template name and the requested module
         * This is used in template_not_found.tpl to provide a link to the templateeditor
         */
        $this->renderer->assignGlobal('modulename', Koch_TargetRoute::getModuleName());
        $this->renderer->assignGlobal('actionname', Koch_TargetRoute::getActionName());
        $this->renderer->assignGlobal('templatename', $template);

        /**
         * Rendering depends on the RenderMode.
         *
         * RenderMode "NOLAYOUT" means that only the (module) content template is rendered.
         *
         * RenderMode "LAYOUT" means that the (module) content template is embedded,
         * into a layout template, by replacing the {$content} placeholder.
         */
        if($this->getRenderMode() === 'NOLAYOUT')
        {
            return $this->fetch($template);
        }

        if($this->getRenderMode() === 'LAYOUT')
        {
            # ensure that smarty tags {$content} and {copyright} are present in the layout template
            #if(true === $this->preRenderChecks())
            #{
                # assign the modulecontent
                $this->assign('content', $this->fetch($template));

                return $this->fetch($this->getLayoutTemplate());
            #}
        }
    }

    public function renderPartial($template)
    {
        return $this->renderer->fetch($template);
    }

    /**
     * preRenderChecks
     */
    public function preRenderChecks()
    {
        $layout_tpl_name = $this->getLayoutTemplate();

        $this->renderer->template_dir = Koch_Functions::array_flatten($this->renderer->template_dir);

        foreach($this->renderer->template_dir as $dir)
        {
            $filename = $dir . $layout_tpl_name;

            if(is_file($filename) === true)
            {
                return self::preRenderCheck($filename, file_get_contents($filename));
            }
        }
    }

    /**
     * Ensures that the Layouttemplate has the Copyright-Signs applied
     *
     * - copyright.tpl
     * - clansuite_header_notice.tpl
     *
     * Keep in mind ! that we spend a lot of time and ideas on this project.
     * Do not remove this! Please give something back to the community.
     *
     * @param $filecontent string The content of the layouttemplate file.
     * @return boolean
     */
    public static function preRenderCheck($filename, $filecontent)
    {
        $renderChecksArray = array(
            '1' => array(
                'needle' => '{include file=\'copyright.tpl\'}',
                'exceptionmessage' => 'The copyright tag is missing. Please insert {include file=\'copyright.tpl\'}
                 in your layout/wrapper template file: <br /> ' . $filename,
                'exceptioncode' => '12'
            ),
            '2' => array(
                'needle' => '{include file=\'clansuite_header_notice.tpl\'}',
                'exceptionmessage' => 'The header notice tag is missing. Please insert
                 {include file=\'clansuite_header_notice.tpl\'} in your layout/wrapper template file: <br /> ' . $filename,
                'exceptioncode' => '13'
            ),
            '3' => array(
                'needle' => '{$content}',
                'exceptionmessage' => 'The content variable {$content} must be within the wrapper template!',
                'exceptioncode' => '14'
            ),
        );


        foreach($renderChecksArray as $preRenderCheck)
        {
            if(false != mb_strpos($filecontent, $preRenderCheck['needle']))
            {
                return true;
            }
            else
            {
                throw new Koch_Exception($preRenderCheck['exceptionmessage'], $preRenderCheck['exceptioncode']);
            }
        }
    }

}
?>