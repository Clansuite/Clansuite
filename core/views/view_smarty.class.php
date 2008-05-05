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

    public $layoutTemplate = null;

    private $config     = null;
    private $db         = null;
    private $functions  = null;

    /**
     * 1) Initialize Smarty via class constructor
     * 2) Load Settings for Smarty
     */
    function __construct(Phemto $injector = null)
    {
      # apply instances to class
      $this->injector = $injector;

	  # get instances from injector
      $this->config         = $this->injector->instantiate('configuration');
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
      require(ROOT_LIBRARIES . '/smarty/Smarty.class.php');
      #$this->smarty = new Smarty();
      require(ROOT_LIBRARIES . '/smarty/Render_SmartyDoc.class.php');
      #require(ROOT_LIBRARIES . '/smarty/SmartyDoc2.class.php');
      # Set view and smarty to the smarty object
      $this->view = $this->smarty = new Render_SmartyDoc();

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
        $this->smarty->debugging           = DEBUG ? true : false;              # set smarty debugging, when debug on
        $this->smarty->debug_tpl           = ROOT_THEMES . '/core/debug.tpl';   # set debugging template for smarty
        if ( defined('DEBUG') && DEBUG===1 )
        {
            $this->smarty->clear_compiled_tpl(); # clear compiled tpls in case of debug
        }
        # $this->debug_tpl        = SMARTY_DIR."libs/";   # define path to debug_tpl file only if not found with std path or moved
        # $this->debug_ctrl       = "NONE";               # NONE ... not active, URL ... activates debugging if SMARTY_DEBUG found in quey string
        # $this->global_assign    = "";                   # list of vars assign to all template files
        # $this->undefined        = null;                 # defines value of undefined variables

        #### SMARTY FILTERS
        # $this->autoload_filters = "";                   # loading filters used for every template
        $this->smarty->autoload_filters    = array(       # indicates which filters will be auto-loaded
                                                      'pre'    => array('inserttplnames')
                                                    #,'post'   => array(),
                                                    #,'output' => array('tidyrepairhtml')
                                                   );

        #### COMPILER OPTIONS
        # $this->compiler_class           = "Smarty_Compiler";     # defines the compiler class for Smarty ... ONLY FOR ADVANCED USERS
        # $this->compile_id               = 0;                     # set individual compile_id instead of assign compile_ids to function-calls (useful with prefilter for different languages)
        $this->smarty->compile_check      = true;                  # if a template was changed it would be recompiled, if set to false nothing will be compiled (changes take no effect)
        $this->smarty->force_compile      = true;                  # if true compiles each template everytime, overwrites $compile_check


        #### CACHING OPTIONS (set these options if caching is enabled)
        $this->smarty->caching              = $this->config['caching'];
        $this->smarty->cache_lifetime       = $this->config['cache_lifetime']; # -1 ... dont expire, 0 ... refresh everytime
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

        # Smarty Directories
        /**
         * This sets multiple template dirs
         *
         * with in the following detection order:
         * 1) "/themes/theme_of_user_session/"
         * 2) "/modules/"
         * 3) "/themes/core/"
         */
        $this->smarty->template_dir   = array();
        $this->smarty->template_dir[] = ROOT_THEMES . '/' . $_SESSION['user']['theme'] . '/';                                       # /themes/user-session_theme
        $this->smarty->template_dir[] = ROOT_MOD    . '/' . Clansuite_ModuleController_Resolver::getModuleName() . '/templates';    # /modules
        $this->smarty->template_dir[] = ROOT_THEMES . '/core';                                                                      # /themes/core
        #var_dump($this->smarty->template_dir);

        $this->smarty->compile_dir    = ROOT_LIBRARIES .'/smarty/templates_c/';         # directory for compiled files
        $this->smarty->config_dir     = ROOT_LIBRARIES .'/smarty/configs/';             # directory for config files (example.conf)
        $this->smarty->cache_dir      = ROOT_LIBRARIES .'/smarty/cache/';               # directory for cached files
        $this->smarty->plugins_dir[]  = ROOT_LIBRARIES .'/smarty/clansuite_plugins/';   # directory for clansuite smarty plugins
        $this->smarty->plugins_dir[]  = ROOT_LIBRARIES .'/smarty/plugins/';             # direcotry for original smarty plugins

        # Modifiers
        #$this->smarty->default_modifiers          = array('escape:"htmlall"');	# array which modifiers used for all variables, to exclude a var from this use: {$var|nodefaults}
        # @todo check functionality
        #$this->smarty->register_modifier('timemarker',  array('benchmark', 'timemarker'));

        /**
         * Assign Web Paths to Smarty
         *
         * The following variables are usable like constants in the templates.
         * They are based on the relative www_root, not the absoulte path.
         *
         * @see config.class
         */
        $this->smarty->assign('www_root'            , WWW_ROOT );
        $this->smarty->assign('www_root_upload'     , WWW_ROOT . '/' . $this->config['upload_folder'] );
        $this->smarty->assign('www_root_themes'     , WWW_ROOT_THEMES . '/' . $_SESSION['user']['theme'] );
        $this->smarty->assign('www_root_themes_core', WWW_ROOT_THEMES_CORE );
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
            throw new Exception('Invalid Smarty template path provided');
        }
    }

    /**
     * Get the TemplatePaths from Smarty
     *
     * @return string
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
        $this->smarty->assign($key, $value);
    }

    /**
     * Executes the template rendering and returns the result.
     */
    public function fetch($template, $data = null)
    {
        return $this->smarty->fetch($template, $data = null);
    }

    /**
     * Executes the template rendering and displays the result.
     */
    public function display($template, $data = null)
    {
       $this->smarty->display($template, $data = null);
    }

    /**
     * view_smarty->assignConstants
     *
     * Assign the common template values and Clansuite constants to the templates.
     * a) meta
     * b) clansuite version
     * c)
     *
     * @access protected
     */
    public function assignConstants()
    {
        /**
         * Assign Config Values (for use in header of tpl)
         */
        # a) Meta Inforamtions about the website
        $this->smarty->assign('meta', $this->config['meta']);

        # b) ClanSuite Version from config.class.php
        $this->smarty->assign('clansuite_version'           , $this->config['clansuite_version']);
        $this->smarty->assign('clansuite_version_state'     , $this->config['clansuite_version_state']);
        $this->smarty->assign('clansuite_version_name'      , $this->config['clansuite_version_name']);

        # c)
        # Page Title
        $this->smarty->assign('std_page_title', $this->config['std_page_title']);

        # Assign DB Counters
        $this->smarty->assign('db_counter'    , $this->db->query_counter + $this->db->exec_counter + $this->db->stmt_counter );     # Query counters (DB)
        # Redirects, if necessary
        $this->smarty->assign('redirect'      , $this->functions->redirect );
        # Normal CSS (global)
        $this->smarty->assign('css'           , WWW_ROOT_THEMES .'/'. $_SESSION['user']['theme'] .'/'. $this->config['std_css']);
        # Normal Javascript (global)
        $this->smarty->assign('javascript'    , WWW_ROOT_THEMES .'/'. $_SESSION['user']['theme'] .'/'. $this->config['std_javascript']);

        # Breadcrumb
        $this->smarty->assign('trail'  , trail::getTrail());
        # Assign Statistic Variables
        $statistic = $this->injector->instantiate('statistic');
        $this->smarty->assign('stats', $statistic->get_statistic_array());
        # Assign Benchmarks
        #$this->smarty->assign('db_exectime', benchmark::returnDbexectime() );

        /**
         * Check for our Copyright-Sign {$copyright} and assign it
         * Keep in mind ! that we spend a lot of time and ideas on this project.
         * Do not remove this! Please give something back to the community.
         */
        $this->smarty->assign('copyright', $this->smarty->fetch(ROOT_THEMES . '/core/copyright.tpl'));

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
            $this->show( $params['title'], $string, $params['level'] );
        }
    }

    /**
     * Show Error in Smarty Templates
     *
     * uses /themes/core/error.tpl
     */
    public static function show( $error_head = 'Unknown Error', $string = '', $level = 3, $redirect = '' )
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
    public static function loadModule($params)
    {
        # Debug: Show Parameters for requested Module
        #var_dump($params);

        /**
         *  Init incomming Variables
         *  @todo: find an easier way to do this..
         */
        $params['name']     = isset( $params['name'] ) ? $params['name'] : '';
        $params['sub']      = isset( $params['sub'] ) ? $params['sub'] : '';
        $params['params']   = isset( $params['params'] ) ? $params['params'] : '';
        $mod                = (string) $params['name'];
        $sub                = (string) $params['sub'];
        $action             = (string) $params['action'];

        # Construct the variable module_name
        if (isset($params['sub']) && strlen($params['sub']) > 0)
        {
            # like "module_admin_menueditor"
            $module_name = 'module_' . strtolower($mod) . '_'. strtolower($sub);
        }
        else
        {
            # like "module_admin"
            $module_name = 'module_' . strtolower($mod);
        }
       
        // Debug: Display the Modulename
        #echo $module_name;

        # Load class, if not already existing
        if(!class_exists($module_name))
        {
            clansuite_loader::loadModul($module_name);
        }

        # Instantiate Class
        $controller = new $module_name();

        # Parameter Array
        $param_array = split('\|', $params['params']);

        #echo "View_Smarty => LoadModule => $module_name | Action $action";
        #exit;

        # Get the Ouptut of the Object->Method Call
        $output = call_user_func_array( array($controller, $action), $param_array );

        # ! ECHO
        # @todo: Fix this?! , because it breaks MVC.
        # a) we're pulling php from templates (there is no other way!)
        # b) we're directly writing the output (maybe consider a composite tree for the view??)
        echo $output;
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
    public function render($templatename)
    {
        #echo 'Rendering via Smarty:<br />';
        #var_dump($this->smarty);
        #var_dump($_SESSION);

        $this->assignConstants();

        # Module Loading {loadModule }
        $this->smarty->register_function('load_module', array('view_smarty','loadModule'), false);
        # Error Block {error level="1" title="Error"}
        $this->smarty->register_block("error", array('view_smarty',"smartyBlockError"), false);

        //$resource_name = ???, $cache_id = ???, $compile_id = ???
        #$this->smarty->display($this->module->template);

        /**
         * Fetch the Template of the module and
         * Assign it to the Layout Template as $content
         *
         * Debugging Hint:
         * Change Fetch to DisplayDOC to get an echo of the pure ModuleContent
         * else var_dump the fetch!
         */
        $modulcontent =  $this->smarty->fetch($templatename);
        #var_dump($modulcontent);
        $this->smarty->assign('content',  $modulcontent );

        #DEBUG ? $debug->show_console() : '';
        #var_dump($this->config['tpl_wrapper_file']);
        #var_dump($this->getLayoutTemplate());
        #var_dump($this->smarty->template_dir);
        #exit;

        return $this->smarty->fetchDOC($this->getLayoutTemplate());
    }

    /**
     * Sets the name of the layout template.
     *
     * @param string $template Name of the Layout Template.
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
        if(empty($this->layoutTemplate))
        {
            $this->setLayoutTemplate($this->config['tpl_wrapper_file']);
        }
        return $this->layoutTemplate;
    }
}
?>