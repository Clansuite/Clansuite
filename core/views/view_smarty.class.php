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
    * @copyright  Jens-Andre Koch (2005 - onwards)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    * @since      File available since Release 0.2
    *
    * @version    SVN: $Id$
    */

// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}

/**
 * Clansuite View Class - View for Smarty Templates
 *
 * This is a wrapper/adapter for the Smarty Template Engine.
 *
 * {@link http://smarty.php.net/ smarty.php.net}
 * {@link http://smarty.incutio.com/ smarty wiki}
 *
 * @author     Jens-Andre Koch <vain@clansuite.com>
 * @copyright  Jens-Andre Koch (2005 - onwards)
 * @since      Class available since Release 0.2
 *
 * @package     clansuite
 * @category    view
 * @subpackage  view_smarty
 */

class view_smarty extends renderer_base
{
    /**
	 * holds instance of Smarty Template Engine (object)
	 * @access private
	 * @var Smarty $smarty
	 */
    private $smarty     = null;

    private $db         = null;
    private $functions  = null;

    protected $injector   = null;

    public $renderMode = null;

    /**
     * 1) Initialize Smarty via class constructor
     * 2) Load Settings for Smarty
     */
    function __construct(Phemto $injector = null)
    {
      # apply instances to class
      $this->injector = $injector;
      #var_dump($injector);

	  # get instances from injector
      $this->config         = $this->injector->instantiate('Clansuite_Config');
      $this->db             = $this->injector->instantiate('db');
      $this->functions      = $this->injector->instantiate('functions');

      /**
       * ==============================================
       * Sets up Smarty Template Engine (Smarty Object)
       *    by initializing Render_SmartyDoc as
       *    custom-made Smarty Document Processor
       * ==============================================
       *
       * @note by vain: Please leave the following commented lines,
       *                i need them for SmartyDOC development!
       */
       if (!class_exists('Smarty')) // prevent redeclaration
       {
          if (is_file(ROOT_LIBRARIES . '/smarty/Smarty.class.php')) // check if library exists
          {
              require(ROOT_LIBRARIES . '/smarty/Smarty.class.php');
              #$this->smarty = new Smarty();
              require(ROOT_LIBRARIES . '/smarty/Render_SmartyDoc.class.php');
              #require(ROOT_LIBRARIES . '/smarty/SmartyDoc2.class.php');
              # Set view and smarty to the smarty object
              $this->view = $this->smarty = new Render_SmartyDoc();
          }
          else // throw error in case smarty library is missing
          {
              die('Smarty Template Library missing!');
          }
       }
       else // throw error in case smarty was already loaded
       {
          die('Smarty already loaded!');
       }

      /**
       * ===================================
       * Set Configurations to Smarty Object
       * ===================================
       */
        self::smarty_configuration();
    }

    /**
     * Smarty Configurations
     */
    private function smarty_configuration()
    {
        #### SMARTY DEBUGGING
        $this->smarty->debugging           = DEBUG ? true : false;             # set smarty debugging, when debug on
        $this->smarty->debug_tpl           = ROOT_THEMES . 'core/debug.tpl';   # set debugging template for smarty
        if ( $this->smarty->debugging == 1 )
        {
           $this->smarty->clear_compiled_tpl(); # clear compiled tpls in case of debug
        }
        # $this->debug_tpl        = SMARTY_DIR."libs/";   # define path to debug_tpl file only if not found with std path or moved
        # $this->debug_ctrl       = "NONE";               # NONE ... not active, URL ... activates debugging if SMARTY_DEBUG found in quey string
        # $this->global_assign    = "";                   # list of vars assign to all template files
        # $this->undefined        = null;                 # defines value of undefined variables

        #### SMARTY FILTERS
        # $this->autoload_filters = "";                   # loading filters used for every template
        #$this->smarty->autoload_filters    = array(       # indicates which filters will be auto-loaded
                                                    # 'pre'    => array('inserttplnames')
                                                    #,'post'   => array(),
                                                    #,'output' => array('tidyrepairhtml')
                                                   #);

        #### COMPILER OPTIONS
        # $this->compiler_class           = "Smarty_Compiler";     # defines the compiler class for Smarty ... ONLY FOR ADVANCED USERS
        # $this->compile_id               = 0;                     # set individual compile_id instead of assign compile_ids to function-calls (useful with prefilter for different languages)
        $this->smarty->compile_check      = true;                  # if a template was changed it would be recompiled, if set to false nothing will be compiled (changes take no effect)
        $this->smarty->force_compile      = true;                  # if true compiles each template everytime, overwrites $compile_check


        #### CACHING OPTIONS (set these options if caching is enabled)
        $this->smarty->caching              = $this->config['cache']['caching'];
        $this->smarty->cache_lifetime       = $this->config['cache']['cache_lifetime']; # -1 ... dont expire, 0 ... refresh everytime
        # $this->cache_handler_func         = "";            # Specify your own cache_handler function
        $this->smarty->cache_modified_check	= 0;             # set to 1 to activate

        #### DEFAULT TEMPLATE HANDLER FUNCTION
        # $this->default_template_handler_func = "";

        #### PASS THROUGH CODE TEMPLATES
        # You can use this options for php handling:
        #   + SMARTY_PHP_PASSTHRU ... display the tags
        #   + SMARTY_PHP_QUOTE    ... display as HTML-Entities
        #   + SMARTY_PHP_REMOVE   ... removes the tags
        #   + SMARTY_PHP_ALLOW    ... runs the php code in templates
        $this->smarty->php_handling = SMARTY_PHP_PASSTHRU;

        #### SECURITY SETTINGS for templates if access over FTP is granted to some users

        $this->smarty->security                    = false;
        # $this->secure_directory                  = "";    # defines trusted directories if security is enabled
        # $this->trusted_directory                 = "";    # defines trusted directories if security is enabled
        # $this->security_settings[PHP_HANDLING]   = false; # if true ... $php_handling will be ignored
        # $this->security_settings[IF_FUNCS]       = "";    # Array of allowed functions if IF statements
        # $this->security_settings[INCLUDE_ANY]    = false; # if true ... every template can be loaded, also those which are not in secure_dir
        # $this->security_settings[MODIFIER_FUNCS] = "";    # Array of functions which can used as variable modifier

        #### ENGINE SETTINGS

        # $this->left_delimiter                   = "{";    # default : {
        # $this->right_delimiter                  = "}";    # default : }
        $this->smarty->show_info_header           = false;  # if true : Smarty Version and Compiler Date are displayed as comment in template files
        $this->smarty->show_info_include          = false;  # if true : adds an HTML comment at start and end of template files
        # $this->request_vars_order               = "";     # order in which the request variables were set, same as 'variables_order' in php.ini
        $this->smarty->request_use_auto_globals   = false;  # for templates using $smarty.get.*, $smarty.request.*, etc...
        $this->smarty->use_sub_dirs               = true;   # set to false if creating subdirs is not allowed, but subdirs are more efficiant

        /**
         * Smarty Template Directories
         *
         * This sets multiple template dirs, with the following detection order:
         *
         * 1) "/themes/theme_of_user_session/"
         * 2) "/themes/theme_of_user_session/modulename/"
         * 3) "/modules/"
         * 4) "/modules/modulename/templates/"
         * 5) "/themes/core/"
         */
        $this->smarty->template_dir   = array();
        $this->smarty->template_dir[] = ROOT_THEMES . $_SESSION['user']['theme'];
        $this->smarty->template_dir[] = ROOT_THEMES . $_SESSION['user']['theme'] .DS. Clansuite_ModuleController_Resolver::getModuleName() .DS;                                           # /themes/user-session_theme
        $this->smarty->template_dir[] = ROOT_MOD;
        $this->smarty->template_dir[] = ROOT_MOD    . Clansuite_ModuleController_Resolver::getModuleName() .DS. 'templates' .DS;    # /modules
        $this->smarty->template_dir[] = ROOT_THEMES . 'core' .DS;                                                                      # /themes/core
        #var_dump($this->smarty->template_dir);

        $this->smarty->compile_dir    = ROOT_LIBRARIES .'smarty/templates_c/';         # directory for compiled files
        $this->smarty->config_dir     = ROOT_LIBRARIES .'smarty/configs/';             # directory for config files (example.conf)
        $this->smarty->cache_dir      = ROOT_LIBRARIES .'smarty/cache/';               # directory for cached files
        $this->smarty->plugins_dir[]  = ROOT_LIBRARIES .'smarty/clansuite_plugins/';   # directory for clansuite smarty plugins
        $this->smarty->plugins_dir[]  = ROOT_LIBRARIES .'smarty/plugins/';             # direcotry for original smarty plugins

        # Modifiers
        #$this->smarty->default_modifiers          = array('escape:"htmlall"');	# array which modifiers used for all variables, to exclude a var from this use: {$var|nodefaults}
        # @todo check functionality
        #$this->smarty->register_modifier('timemarker',  array('benchmark', 'timemarker'));

        ## Smarty Template Handler Functions

        
        # Additional Resource Handler: to fetch TPLs in "modules/templates" too
        require_once $this->smarty->_get_plugin_filepath('resource', 'fetch_module_templates');
        $this->smarty->default_template_handler_func = 'smarty_fetch_module_templates';

        # Additional Resource Handler: to autogenerate "not found" templates
        #require_once $this->smarty->_get_plugin_filepath('resource', 'fetch_module_templates');
        #$this->smarty->default_template_handler_func = 'make_template';
     }

    /**
     * Returns Smarty Object
     *
     * @return Smarty
     */
    public function getEngine()
    {
        return $this->smarty;
    }

    /**
     * Adds a Template Path
     *
     * @param string $templatepath Template-Directory to have Smarty search in
     * @return void
     */
    public function setTemplatePath($templatepath)
    {
        if (is_dir($templatepath) && is_readable($templatepath))
        {
            $this->smarty->template_dir[] = $templatepath;
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
        return $this->smarty->template_dir;
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
     * @return void
     */
    public function assign($tpl_parameter, $value = null)
    {
        # if array
        if (is_array($tpl_parameter))
        {
            $this->smarty->assign($tpl_parameter);
            return;
        }

        # if single key-value pair
        $this->smarty->assign($tpl_parameter, $value);
    }

     /**
     * Magic Method to get a already set/assigned Variable from Smarty
     *
     * @param string $key Name of Variable
     * @return mixed Value of key
     */
    public function __get($key)
    {
        return $this->smarty->get_template_vars($key);
    }

   /**
     * Magic Method to set/assign Variable to Smarty
     *
     * @param string $key der Variablenname
     * @param mixed $val der Variablenwert
     * @return void
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
        $template = $this->getTemplatePath($template);

        #echo 'Template in view_smarty->fetch() : '.$template . '<br>';

        return $this->smarty->fetch($template, $data = null);
    }

    /**
     * Executes the template rendering and displays the result.
     */
    public function display($template, $data = null)
    {
       $template = $this->getTemplatePath($template);

       #echo 'Template in view_smarty->display() : '.$template . '<br>';

       $this->smarty->display($template, $data = null);
    }

    public function getSmartyConstants()
    {
        # Assign DB Counters
        $template_constants['db_counter']= $this->db->query_counter + $this->db->exec_counter + $this->db->stmt_counter;
        # Redirects, if necessary
        $template_constants['redirect'] = $this->functions->redirect;

        /**
         * Copyright-Sign
         * Keep in mind ! that we spend a lot of time and ideas on this project.
         * Do not remove this! Please give something back to the community.
         */
        $template_constants['copyright'] = $this->smarty->fetch(ROOT_THEMES . 'core/copyright.tpl');

        return $template_constants;
    }

    /**
     * Assign the common template values and Clansuite constants as Smarty Template Variables.
     *
     * @access protected
     */
    protected function assignConstants()
    {
        # fetch the general clansuite constants from renderer_base->getConstants()
        $this->smarty->assign($this->getConstants());
        #var_dump($this->getConstants());

        # fetch the specific smarty constants from view_smarty->getSmartyConstants()
        $this->smarty->assign($this->getSmartyConstants());
        ##var_dump($this->getSmartyConstants());

        # leave this for debugging purposes
        #var_dump($this->smarty);
    }

    /**
     * view_smarty::smartyBlockError
     *
     * ErrorTemplate {error level="1" title="Error"}
     */
    public static function smartyBlockError($params, $string, &$smarty)
    {
        // Init Vars
        $params['level']   = !isset( $params['level'] ) ? 3 : $params['level'];
        $params['title']   = !isset( $params['level'] ) ? 'Unkown Error' : $params['title'];

        if ( !empty($string) )
        {
            $this->showError( $params['title'], $string, $params['level'] );
        }
    }

    /**
     * Show Error in Smarty Templates
     *
     * uses /themes/core/error.tpl
     */
    public static function showError( $error_head = 'Unknown Error', $string = '', $level = 3, $redirect = '' )
    {
        $this->smarty->assign('error_head'    , $error_head );
        $this->smarty->assign('debug_info'    , $string );

        switch ( $level )
        {
            # watch out: die() on error!
            case '1':
                $this->smarty->assign('error_type', 1);
                $redirect!='' ? $this->smarty->assign('redirect', '<meta http-equiv="refresh" content="5; URL=' . $redirect . '">') : '';
                $content = $this->smarty->fetch( 'error.tpl' );
                die( $content );
                break;

            case '2':
                $this->smarty->assign('error_type', 2);
                return( $this->smarty->fetch( 'error.tpl' ) );
                break;

            case '3':
                $this->smarty->assign('error_type', 3);
                echo( $this->smarty->fetch( 'error.tpl' ) );
                break;
        }
    }

    /**
     * Instantiates Module and assigns_by_reference
     *
     */
    public function loadModule($module_name)
    {
        $this->injector->register('httprequest');
        $this->injector->register('Clansuite_ModuleController_Resolver');

        $request              = $this->injector->instantiate('httprequest');
        $modulecontroller_resolver    = $this->injector->instantiate('Clansuite_ModuleController_Resolver');
        $modulecontroller_resolver->setModuleName($module_name);

        $module = $modulecontroller_resolver->getModuleController($request);

        $module->setView($this);
        $module->setRenderMode('NOT WRAPPED');

        $module->setInjector($this->injector);

        $this->smarty->assign_by_ref($module_name, $module);
    }

    /**
     * view_smarty::loadModule
     *
     * Static Function to Call variable Methods from templates via
     * {load_module name= sub= params=}
     *
     * [deprecated] :
     * This was formlerly {mod} inside templates.
     * Calling a function named get_instant_content() on core/modules.class.php.
     *
     * @param array $params Parameters
     * @static
     * @access public
     */
    public static function loadStaticModule($params, &$smarty)
    {
        # Init incomming Variables
        $mod    = isset( $params['name'] ) ? (string) $params['name'] : '';
        $sub    = isset( $params['sub'] )  ? (string) $params['sub']  : '';
        $action = (string) $params['action'];     
           
        $params = isset( $params['params'] ) ? (string) $params['params'] : '';        
        $items  = isset( $params['items'] )  ? (int)    $params['items']  : 5;

        # Construct the variable module_name
        if (isset($sub) && strlen($sub) > 0)
        {
            # like "module_admin_menueditor"
            $module_name = 'module_' . strtolower($mod) . '_'. strtolower($sub);
        }
        else
        {
            # like "module_admin"
            $module_name = 'module_' . strtolower($mod);
        }

        # Load class, if not already loaded
        if (!class_exists(ucfirst($module_name)))
        {
            clansuite_loader::loadModul($module_name);
        }
        
        # Parameter Array

        if( empty($params['params']) )
        {
            $param_array = null;
        }
        else
        {
            $param_array = split('\|', $params['params']);
        }
        
        # Instantiate Class
        $controller = new $module_name;
        $controller->moduleName = $mod;
        $controller->setView($smarty);
        
        /**
         * Get the Ouptut of the Object->Method Call
         *
         */  
        
        # exceptional handling for adminmenu      
        if ( $module_name == 'module_menu_admin' )
        {
            echo $controller->$action($param_array);         
        }
        else
        {
            # slow
            #call_user_func_array( array($controller, $action), $param_array );
            
            # fast
            $controller->$action($items);
        }
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

    /**
     * view_smarty->render
     *
     * Returns the mainframe layout with inserted modulcontent (templatename).
     *
     * 1. assign common values and constants
     * 2. fetch the modultemplate and assigns it as $content
     * 3. return the mainframe tpl
     *
     * @param string $templatename Template Filename
     * @return mainframe.tpl layout
     */
    public function render($template)
    {
        #echo 'Rendering via Smarty:<br />';
        #var_dump($this->smarty);
        #var_dump($_SESSION);

        $this->assignConstants();

        # Module Loading {loadModule }
        $this->smarty->assign_by_ref('cs', $this);
        $this->smarty->register_function('load_module', array('view_smarty','loadStaticModule'), false);

        # Error Block {error level="1" title="Error"}
        $this->smarty->register_block("error", array('view_smarty',"smartyBlockError"), false);

        //$resource_name = ???, $cache_id = ???, $compile_id = ???
        #$this->smarty->display($this->module->template);

        /**
         * Fetch the Template of the module
         * and
         * 1) echo it directly
         * 2) Assign it to the Layout Template as $content
         *
         * Debugging Hint:
         * Change Fetch to DisplayDOC to get an echo of the pure ModuleContent
         * else var_dump the fetch!
         */

        $modulecontent =  $this->fetch($template);

        if( $this->getRenderMode() !== 'WRAPPED' ) # without wrapper
        {
            #echo '<br />Smarty renders the following Template as NON WRAPPED : '.$template;
            return $modulecontent;
        }
        else # with wrapper
        {
            #echo '<br />Smarty renders the following Template as WRAPPED : '.$template;
            $this->smarty->assign('content',  $modulecontent );

            if( $this->check_content_var() )
            {
                return $this->smarty->fetchDOC($this->getLayoutTemplate());
            }
            else
            {
                die('The content variable {$content} must be within the wrapper template!');
            }
        }

        /**
         * Often used Debugging Stuff - leave it :D
         */
        #var_dump($modulcontent);

        # Show Debug Console
        #if(DEBUG){ require 'core/clansuite.debug.php'; $debug = new clansuite_debug; $debug->show_console(); };

        #var_dump($this->config['template']['tpl_wrapper_file']);
        #var_dump($this->getLayoutTemplate());
        #var_dump($this->smarty->template_dir);
        #exit;
    }

    /**
    * @desc Check for a content variable
    * @return boolean
    */
    public function check_content_var()
    {
        foreach( $this->smarty->template_dir as $dir )
        {
            $thefile = $dir . DS . $this->getLayoutTemplate();
            if( is_file($thefile) )
            {
                return (strpos(file_get_contents($thefile), '{$content}')!=FALSE);
            }
        }
    }
}
?>
