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

# Security Handler
if (defined('IN_CS') == false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

# Load Clansuite_Renderer_Base
if(false === class_exists('Clansuite_Renderer_Base',false) )
{
    include dirname(__FILE__) . '/renderer.base.php';
}

/**
 * Clansuite View Class - View for Smarty Templates
 *
 * This is a wrapper/adapter for the Smarty Template Engine.
 *
 * @link http://smarty.php.net/ Official Website of Smarty Template Engine
 * @link http://smarty.incutio.com/ Smarty Wiki
 *
 * @author     Jens-Andr� Koch <vain@clansuite.com>
 * @copyright  Jens-Andr� Koch (2005 - onwards)
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Renderer
 */

class Clansuite_Renderer_Smarty extends Clansuite_Renderer_Base
{
    /**
     * RenderEngineConstructor
     *
     * parent::__construct does the following:
     * 1) Apply instances of Clansuite_Config to the RenderBase
     * 2) Initialize the RenderEngine via parent class constructor call = self::initializeEngine()
     * 3) Configure the RenderEngine with it's specific settings = self::configureEngine();
     */
    function __construct(Clansuite_Config $config, Clansuite_HttpResponse $response)
    {
        parent::__construct($config, $response);

        self::initializeEngine();
        self::configureEngine();
    }

    /**
     * ==============================================
     * Sets up Smarty Template Engine (Smarty Object)
     * ==============================================
     *
     * @return Smarty Object
     */
    public function initializeEngine()
    {
        # prevent redeclaration
        if (class_exists('Smarty',false) == false)
        {
            # check if Smarty library exists
            if ( is_file(ROOT_LIBRARIES . 'smarty/Smarty.class.php') )
            {
                include ROOT_LIBRARIES . 'smarty/Smarty.class.php';
            }
            else // throw error in case smarty library is missing
            {
                throw new Exception('Smarty Template Library missing!');
            }
        }
        else // throw error in case smarty was already loaded
        {
            throw new Exception('Smarty already loaded!');
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
        $this->renderer->compile_dir    = ROOT .'cache/templates_c/';           # directory for compiled files
        $this->renderer->config_dir     = ROOT_LIBRARIES .'smarty/configs/';    # directory for config files (example.conf)
        $this->renderer->cache_dir      = ROOT .'cache/cache/';                 # directory for cached files

        #### SMARTY DEBUGGING
        $this->renderer->debugging           = DEBUG ? true : false;             # set smarty debugging, when debug on
        #$this->renderer->debug_tpl          = ROOT_THEMES . 'core/templates/debug.tpl';   # set debugging template for smarty
        $this->renderer->debug_tpl           = ROOT_LIBRARIES . 'smarty/debug.tpl';   # set debugging template for smarty
        if ( $this->renderer->debugging == true )
        {
            $this->renderer->utility->clearCompiledTemplate(); # clear compiled tpls in case of debug
            $this->renderer->cache->clearAll();                # clear cache
        }

        # $this->renderer->debug_ctrl       = "NONE";               # NONE ... not active, URL ... activates debugging if SMARTY_DEBUG found in query string
        # $this->renderer->global_assign    = "";                   # list of vars assign to all template files
        # $this->renderer->undefined        = null;                 # defines value of undefined variables

        $this->renderer->auto_literal       = true;                 # auto delimiter of javascript/css (The literal tag of Smarty v2.x)

        #### SMARTY FILTERS
        $autoload_filters = array();
        if( $this->config['error']['debug'] )
        {
            $autoload_filters = array( 'pre'    => array('inserttplnames') );
        }
        $this->renderer->autoload_filters    = $autoload_filters;
        #array(       # indicates which filters will be auto-loaded
        #'pre'    => array('inserttplnames'),
        #'post'   => array(),
        #'output' => array('trimwhitespaces')
        #);

        #### COMPILER OPTIONS
        # $this->renderer->compiler_class   = "Smarty_Compiler";     # defines the compiler class for Smarty ... ONLY FOR ADVANCED USERS
        # $this->renderer->compile_id       = 0;                     # set individual compile_id instead of assign compile_ids to function-calls (useful with prefilter for different languages)

        /**
         * recompile/rewrite templates only in debug mode
         * @see http://www.smarty.net/manual/de/variable.compile.check.php
         */
        if ( $this->renderer->debugging == true )
        {
            $this->renderer->compile_check      = true;             # if a template was changed it would be recompiled, if set to false nothing will be compiled (changes take no effect)
            $this->renderer->force_compile      = true;             # if true compiles each template everytime, overwrites $compile_check
        }
        else
        {
            $this->renderer->compile_check      = true;             # if a template was changed it would be recompiled, if set to false nothing will be compiled (changes take no effect)
            $this->renderer->force_compile      = false;            # if true compiles each template everytime, overwrites $compile_check
        }

        #### CACHING OPTIONS (set these options if caching is enabled)
        #clansuite_xdebug::printr($this->config['cache']);
        # var_dump($this->config['cache']);
        if ( $this->renderer->debugging == true )
        {
            $this->renderer->caching                = 0;
            $this->renderer->cache_lifetime         = $this->config['cache']['cache_lifetime']; # -1 ... dont expire, 0 ... refresh everytime
            # $this->renderer->cache_handler_func   = "";      # Specify your own cache_handler function
            $this->renderer->cache_modified_check   = 0;       # set to 1 to activate
        }
        else
        {
            $this->renderer->caching                = (bool) $this->config['cache']['caching'];
            $this->renderer->cache_lifetime         = $this->config['cache']['cache_lifetime']; # -1 ... dont expire, 0 ... refresh everytime
            # $this->renderer->cache_handler_func   = "";      # Specify your own cache_handler function
            $this->renderer->cache_modified_check   = 1;       # set to 1 to activate
        }

        #### DEFAULT TEMPLATE HANDLER FUNCTION
        # $this->renderer->default_template_handler_func = "";

        #### PASS THROUGH CODE TEMPLATES
        # You can use this options for php handling:
        #   + SMARTY_PHP_PASSTHRU ... display the tags
        #   + SMARTY_PHP_QUOTE    ... display as HTML-Entities
        #   + SMARTY_PHP_REMOVE   ... removes the tags
        #   + SMARTY_PHP_ALLOW    ... runs the php code in templates
        $this->renderer->php_handling = SMARTY_PHP_PASSTHRU;

        #### SECURITY SETTINGS for templates if access over FTP is granted to some users

        $this->renderer->security                            = false;
        # $this->renderer->secure_directory                  = "";    # defines trusted directories if security is enabled
        # $this->renderer->trusted_directory                 = "";    # defines trusted directories if security is enabled
        # $this->renderer->security_settings[PHP_HANDLING]   = false; # if true ... $php_handling will be ignored
        # $this->renderer->security_settings[IF_FUNCS]       = "";    # Array of allowed functions if IF statements
        # $this->renderer->security_settings[INCLUDE_ANY]    = false; # if true ... every template can be loaded, also those which are not in secure_dir
        # $this->renderer->security_settings[MODIFIER_FUNCS] = "";    # Array of functions which can used as variable modifier

        #### ENGINE SETTINGS

        # $this->renderer->left_delimiter           = "{";    # default : {
        # $this->renderer->right_delimiter          = "}";    # default : }
        $this->renderer->show_info_header           = false;  # if true : Smarty Version and Compiler Date are displayed as comment in template files
        $this->renderer->show_info_include          = false;  # if true : adds an HTML comment at start and end of template files
        # $this->renderer->request_vars_order       = "";     # order in which the request variables were set, same as 'variables_order' in php.ini
        $this->renderer->request_use_auto_globals   = true;   # for templates using $smarty.get.*, $smarty.request.*, etc...
        $this->renderer->use_sub_dirs               = true;   # set to false if creating subdirs is not allowed, but subdirs are more efficiant

        /**
         * Smarty Template Directories
         *
         * This sets multiple template dirs, with the following detection order:
         * The user-choosen Theme, before Module, before Core/Default/Admin-Theme.
         *
         * 1) "/themes/theme_of_user_session/"
         * 2) "/themes/theme_of_user_session/modulename/"
         * 3) "/modules/"
         * 4) "/modules/modulename/view/"
         * 5) "/themes/core/view/"
         * 6) "/themes/admin/"
         * 7) "/themes/"
         */
        $this->renderer->template_dir   = array();

        if(empty($_SESSION['user']['theme']))
        {
            $frontendtheme = 'standard';
        }
        else
        {
            $frontendtheme = $_SESSION['user']['theme'];
        }

        # 1) + 2) in case the controlcenter is the requested module
        if(Clansuite_Module_Controller_Resolver::getModuleName() == 'controlcenter' or Clansuite_Module_Controller_Resolver::getSubModuleName() == 'admin')
        {
            # Backend Theme Detections
            $_SESSION['user']['backendtheme'] = 'admin';
            $this->renderer->template_dir[] = ROOT_THEMES . $_SESSION['user']['backendtheme'];
            $this->renderer->template_dir[] = ROOT_THEMES . $_SESSION['user']['backendtheme'] .DS. Clansuite_Module_Controller_Resolver::getModuleName() .DS;

        }
        else
        {
            # Frontend Theme Detections
            $this->renderer->template_dir[] = ROOT_THEMES . $frontendtheme;
            $this->renderer->template_dir[] = ROOT_THEMES . $frontendtheme .DS. Clansuite_Module_Controller_Resolver::getModuleName() .DS;
        }

        /**
         * FALLBACKS
         */

        # 3) + 4) modules dir
        $this->renderer->template_dir[] = ROOT_MOD;
        $this->renderer->template_dir[] = ROOT_MOD . Clansuite_Module_Controller_Resolver::getModuleName() .DS. 'view' .DS;

        # 5) themes dir
        $this->renderer->template_dir[] = ROOT_THEMES . 'core'.DS.'view' .DS;

        # 6) the admin theme
        if(Clansuite_Module_Controller_Resolver::getModuleName() == 'controlcenter' or Clansuite_Module_Controller_Resolver::getSubModuleName() == 'admin')
        {
            $this->renderer->template_dir[] = ROOT_THEMES . 'admin' .DS;
        }

        # 7) THEMES in general
        $this->renderer->template_dir[] = ROOT_THEMES;

        #clansuite_xdebug::printR($this->renderer->template_dir);

        /**
         * Configure Smarty Viewhelper Directories
         * 1) original smarty plugins
         * 2) clansuite core/common smarty plugins
         * 3) clansuite module smarty plugins
         */
        $this->renderer->plugins_dir[]  = ROOT_LIBRARIES .'smarty/plugins/';
        $this->renderer->plugins_dir[]  = ROOT_CORE .'viewhelper/smarty/';
        $this->renderer->plugins_dir[]  = ROOT_MOD . Clansuite_Module_Controller_Resolver::getModuleName() . '/viewhelper/smarty/';

        #clansuite_xdebug::printR($this->renderer->plugins_dir);

        # Modifiers
        # array which modifiers used for all variables, to exclude a var from this use: {$var|nodefaults}
        # $this->renderer->default_modifiers = array('escape:"htmlall"');
        # $this->renderer->register_modifier('timemarker',  array('benchmark', 'timemarker'));
    }

    /**
     * Returns a clean Smarty Object
     *
     * @return Smarty Object
     */
    public function getEngine()
    {
        if($this->renderer)
        {
            # we reset all prior assigns and configuration settings
            $this->renderer->clear_all_assign();
            $this->renderer->clear_config();
        }
        else
        {
            self::initializeEngine();
        }

        # then we reload the base configuration to have default template paths and debug-settings
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
        if (is_dir($templatepath) && is_readable($templatepath))
        {
            $this->renderer->template_dir[] = $templatepath;
        }
        else
        {
            throw new Exception('Invalid Smarty Template path provided: Path not existing or not readable. Path: ' . $templatepath );
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
        # if array
        if (is_array($tpl_parameter))
        {
            $this->renderer->assign($tpl_parameter);
            return;
        }

        # if single key-value pair
        $this->renderer->assign($tpl_parameter, $value);
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
     * Executes the template fetching and returns the result.
     */
    public function fetch($template, $data = null)
    {
        # ask the renderer.base.core for the template path
        $template = $this->getTemplatePath($template);

        return $this->renderer->fetch($template, $data = null);
    }

    /**
     * Executes the template rendering and displays the result.
     */
    public function display($template, $data = null)
    {
        $template = $this->getTemplatePath($template);

        $this->renderer->display($template, $data = null);
    }

    public function getSmartyConstants()
    {
        # Assign DB Counters
        $template_constants['db_counter']= '0';
        #$this->db->query_counter + $this->db->exec_counter + $this->db->stmt_counter;

        return $template_constants;
    }

    /**
     * Assign the common template values and Clansuite constants as Smarty Template Variables.
     */
    protected function assignConstants()
    {
        # fetch the general clansuite constants from Clansuite_Renderer_Base->getConstants()
        $this->renderer->assign($this->getConstants());

        # fetch the specific smarty constants from Clansuite_Renderer_Smarty->getSmartyConstants()
        $this->renderer->assign($this->getSmartyConstants());
    }

    public function setRenderMode($mode)
    {
        $this->renderMode = $mode;
    }

    public function getRenderMode()
    {
        if(empty($this->renderMode))
        {
            $this->renderMode = 'WRAPPED';
        }
        return $this->renderMode;
    }

    public function renderPartial($template)
    {
        $this->setTemplate($template);
        $this->assignConstants();
        return $this->fetch($template);
    }

    /**
     * Clansuite_Renderer_Smarty->render
     *
     * Returns the mainframe layout with inserted modulcontent (templatename).
     *
     * 1. assign common values and constants
     * 2. fetch the modultemplate and assigns it as $content
     * 3. return the wrapper layout tpl
     *
     * @param string $templatename Template Filename
     * @return wrapper tpl layout
     */
    public function render($template)
    {
        # 1. assign common values and constants
        $this->assignConstants();

        /**
         * Assign the original template name and the requested module
         * This is used in template_not_found.tpl to provide a link to the templateeditor
         */
        $this->renderer->assign('modulename', Clansuite_Module_Controller_Resolver::getModuleName());
        $this->renderer->assign('actionname', Clansuite_Action_Controller_Resolver::getActionName());

        # @todo remove duplication, scan for templatename and template_to_render
        $this->renderer->assign('templatename', $template);
        $this->renderer->assign('template_to_render', $template);

        # @todo caching
        //$resource_name = ???, $cache_id = ???, $compile_id = ???

        # 2. fetch the modultemplate and assigns it as $content
        $modulecontent =  $this->fetch($template);

        /**
         * Decide on given set RenderMode, if modulecontent should be rendered WRAPPED or STANDALONE
         */
        if( $this->getRenderMode() !== 'WRAPPED' ) # render without wrapper: STANDALONE
        {
            return $modulecontent;
        }
        else # render with wrapper (layout): WRAPPED
        {
            # execute preRenderChecks (to ensure that {$content} and {copyright} exists in the layout template
            if( true == $this->preRenderChecks() )
            {
                # assign the modulecontent
                $this->assign('content', $modulecontent);

                return $this->renderer->fetch($this->getLayoutTemplate());
            }
        }
    }

    /**
     * preRenderChecks
     */
    public function preRenderChecks()
    {
        $layout_tpl_name = $this->getLayoutTemplate();

        foreach( $this->renderer->template_dir as $dir )
        {
            $filename = $dir . DS . $layout_tpl_name;

            if (is_file($filename) == true)
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
                        'exceptionmessage' => 'The copyright tag is missing.
                            Please insert {include file=\'copyright.tpl\'} in your layout/wrapper template file: <br /> '.$filename,
                        'exceptioncode' => '12'
                ),

                '2' => array(
                        'needle' => '{include file=\'clansuite_header_notice.tpl\'}',
                        'exceptionmessage' => 'The header notice tag is missing.
               Please insert {include file=\'clansuite_header_notice.tpl\'} in your layout/wrapper template file: <br /> '.$filename,
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
            if( false != strpos($filecontent, $preRenderCheck['needle']) )
            {
                return true;
            }
            else
            {
                throw new Clansuite_Exception($preRenderCheck['exceptionmessage'], $preRenderCheck['exceptioncode']);
            }
        }
    }
}
?>