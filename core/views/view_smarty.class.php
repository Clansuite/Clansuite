<?php
// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}

/**
 * Clansuite Core Class - clansuite_view_smarty
 *
 * Smarty Template System
 *
 * {@link http://smarty.php.net/ smarty.php.net}
 * {@link http://smarty.incutio.com/ smarty wiki}
 *
 * @author     Jens-Andre Koch <vain@clansuite.com>
 * @copyright  Jens-Andre Koch (2005-$Date$)
 * @since      Class available since Release 0.2
 *
 * @package     clansuite
 * @category    core
 * @subpackage  template
 */

class view_smarty extends renderer_base
{
    private $smarty     = null;

    protected $injector   = null;

    private $config     = null;
    private $db         = null;
    private $trail      = null;
    private $functions  = null;
    private $language   = null;

    /**
     * 1) Initialize Smarty via class constructor
     * 2) Load Settings for Smarty
     */
    function __construct(Phemto $injector=null)
    {
      # apply instances to class
      $this->injector = $injector;

	  # get instances from injector
      $this->config         = $this->injector->instantiate('configuration');
      $this->db             = $this->injector->instantiate('db');
      $this->trail          = $this->injector->instantiate('trail');
      $this->functions      = $this->injector->instantiate('functions');
      $this->language       = $this->injector->instantiate('language');

      /**
       * Sets up Smarty Template Engine (Smarty Object)
       *    by initializing Render_SmartyDoc as
       *    custom-made Smarty Document Processor
       *
       * @note by vain: Please leave the following commented lines,
       *                i need them for SmartyDOC development!
       */
      require(ROOT_LIBRARIES . '/Smarty/Smarty.class.php');
      #$this->smarty = new Smarty();
      require(ROOT_LIBRARIES . '/Smarty/Render_SmartyDoc.class.php');
      #require(ROOT_LIBRARIES . '/smarty/SmartyDoc2.class.php');
      $this->smarty = new Render_SmartyDoc();

      /**
       * Set Configurations to Smarty Object
       */
      self::smarty_configuration();
    }

    function smarty_configuration()
    {
        #### SMARTY DEBUGGING
        $this->smarty->debugging           = DEBUG ? true : false;
        $this->smarty->debug_tpl           = ROOT_TPL . '/core/debug.tpl';
        DEBUG ? $this->smarty->clear_compiled_tpl() : ''; # clear compiled tpls in case of debug
        # $this->debug_tpl        = SMARTY_DIR."libs/";   # define path to debug_tpl file only if not found with std path or moved
        # $this->debug_ctrl       = "NONE";               # NONE ... not active, URL ... activates debugging if SMARTY_DEBUG found in quey string
        # $this->global_assign    = "";                   # list of vars assign to all template files
        # $this->undefined        = null;                 # defines value of undefined variables

        #### SMARTY FILTERS
        # $this->autoload_filters = "";                   # loading filters used for every template
        $this->smarty->autoload_filters    = array(    'pre' => array('inserttplnames')
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

        # Directories
        /**
        * This sets multiple template dirs
        * First  is "/templates_path/user_session_theme"
        * Second is "/templates_path/core/"
        */

        # template_dir set to CORE / fallback for errors
        $this->smarty->template_dir = array( ROOT_TPL . '/' . $_SESSION['user']['theme'] . '/',
                                             ROOT_TPL . '/core/' ) ;
        #var_dump($this->smarty->template_dir);
        $this->smarty->compile_dir  = ROOT_LIBRARIES .'/smarty/templates_c/';  # directory for compiled files
        $this->smarty->config_dir   = ROOT_LIBRARIES .'/smarty/configs/';      # directory for config files (example.conf)
        $this->smarty->cache_dir    = ROOT_LIBRARIES .'/smarty/cache/';        # directory for cached files
        $this->smarty->plugins_dir  = ROOT_LIBRARIES .'/smarty/plugins/';

        # Modifiers
        #$this->smarty->default_modifiers          = array('escape:"htmlall"');	# array which modifiers used for all variables, to exclude a var from this use: {$var|nodefaults}
        # @todo: check functionality
        $this->smarty->register_modifier('timemarker',  array('benchmark', 'timemarker'));

        /**
         * Sets up {translate} block in SMARTY Template Engine
         *
         * This makes {translate}{/translate} avaiable in templates.
         * It's callback to language::smarty_translate()
         *
         * @see language::smarty_translate()
         * {@link function smarty_translate}
         */
        $this->smarty->register_block("translate", array('language','smarty_translate'), false);

        /**
         * Assign Paths, which were defined as Constants (for general use in tpl)
         * @see config.class
         */
        $this->smarty->assign('www_root'         , WWW_ROOT );
        $this->smarty->assign('www_root_upload'  , WWW_ROOT . '/' . $this->config['upload_folder'] );
        $this->smarty->assign('www_root_tpl'     , WWW_ROOT . '/' . $this->config['tpl_folder'] . '/' . $_SESSION['user']['theme'] );
        $this->smarty->assign('www_root_tpl_core', WWW_ROOT_TPL_CORE );
     }

    /**
     * Returns Smarty Object
     */
    function getEngine()
    {
        return $this->smarty;
    }

    /**
     *
     */
    function display($template, $data = null)
    {
        echo 'view_smarty->display() called!';
        $this->smarty->display($template, $data = null);
    }

    function render($templatename)
    {
        #echo 'Rendering via Smarty:<br />';
        #var_dump($this->smarty);
        #var_dump($_SESSION);
        /**
         * Assign Config Values (for use in header of tpl)
         */
        # Meta Inforamtions about the website
        $this->smarty->assign('meta', $this->config['meta']);
        # ClanSuite Version from config.class.php
        $this->smarty->assign('clansuite_version'    , $this->config['version']);
        $this->smarty->assign('db_counter'    , $this->db->query_counter + $this->db->exec_counter + $this->db->stmt_counter );     # Query counters (DB)
        # Redirects, if necessary
        $this->smarty->assign('redirect'      , $this->functions->redirect );
        # Normal CSS (global)
        $this->smarty->assign('css'           , WWW_ROOT_TPL .'/'. $_SESSION['user']['theme'] .'/'. $this->config['std_css']);
        # Normal Javascript (global)
        $this->smarty->assign('javascript'    , WWW_ROOT_TPL .'/'. $_SESSION['user']['theme'] .'/'. $this->config['std_javascript']);
        # Page Title
        $this->smarty->assign('std_page_title', $this->config['std_page_title']);
        # Breadcrumb
        $this->smarty->assign_by_ref('trail'  , $this->trail->path);
        # Assign Statistic Variables
        $statistic = $this->injector->instantiate('statistic');
        $this->smarty->assign('stats', $statistic->get_statistic_array());
        # Assign Benchmarks
        $this->smarty->assign('db_exectime', benchmark::returnDbexectime() );

        /**
         * Check for our Copyright-Sign {$copyright} and assign it
         * Keep in mind ! that we spend a lot of time and ideas on this project.
         * Do not remove this! Please give something back to the community.
         */
        #self::check_copyright( ROOT_TPL . '/' . $_SESSION['user']['theme'] . '/' . $this->config->tpl_wrapper_file );
        $this->smarty->assign('copyright', $this->smarty->fetch(ROOT_TPL . '/core/copyright.tpl'));

        //$resource_name = ???, $cache_id = ???, $compile_id = ???
        #$this->smarty->display($this->module->template);

        #var_dump($this->smarty);

        #var_dump($this->module_view->template);
        $modulcontent =  $this->smarty->fetch($templatename);
        #var_dump($modulcontent);
        $this->smarty->assign('content',  $modulcontent );
        #DEBUG ? $debug->show_console() : '';
        #var_dump($this->config['tpl_wrapper_file']);
        #var_dump($this->smarty->template_dir);
        $this->smarty->displayDOC($this->config['tpl_wrapper_file']);
    }
}
?>