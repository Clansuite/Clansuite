<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-Andre Koch © 2005-2008
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
    * @copyright  Jens-Andre Koch (2005-2008)
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
 * Clansuite Core Class - clansuite_view_smarty
 *
 * This is a wrapper/adapter for the Smarty Template Engine.
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
 * @subpackage  view
 */

class view_smarty extends renderer_base
{
    /**
	 * holds instance of Smarty Template Engine (object)
	 * @access private
	 * @var Smarty $smarty
	 */
    private $smarty     = null;

    private $config     = null;
    private $db         = null;
    private $trail      = null;
    private $functions  = null;
    private $localization   = null;

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
      $this->trail          = $this->injector->instantiate('trail');
      $this->functions      = $this->injector->instantiate('functions');
      $this->localization   = $this->injector->instantiate('localization');

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
      require(ROOT_LIBRARIES . '/Smarty/Smarty.class.php');
      #$this->smarty = new Smarty();
      require(ROOT_LIBRARIES . '/Smarty/Render_SmartyDoc.class.php');
      #require(ROOT_LIBRARIES . '/smarty/SmartyDoc2.class.php');
      $this->smarty = new Render_SmartyDoc();

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
        $this->smarty->debugging           = DEBUG ? true : false;
        $this->smarty->debug_tpl           = ROOT_TPL . '/core/debug.tpl';
        DEBUG ? $this->smarty->clear_compiled_tpl() : ''; # clear compiled tpls in case of debug
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
        * First  is "/templates_path/user_session_theme"
        * Second is "/templates_path/core/"
        */
        $this->smarty->template_dir   = array();
        $this->smarty->template_dir[] = ROOT_TPL . '/' . $_SESSION['user']['theme'] . '/'; # user-session theme
        $this->smarty->template_dir[] = ROOT_MOD;
        $this->smarty->template_dir[] = ROOT_TPL . '/core/';                               # /templates/core
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
            throw new Exception('Invalid template path provided');
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
        if (is_array($tpl_parameter)) {
            $this->smarty->assign($tpl_parameter);
            return;
        }

        # if single key-value pair
        $this->smarty->assign($tpl_parameter, $value);
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
     * view_smarty->render
     *
     * Returns the mainframe layout with inserted modulcontent.
     *
     * 1. common assings to smarty
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
        #$this->smarty->assign('db_exectime', benchmark::returnDbexectime() );

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

        /**
         * Fetch the Template of the module
         *
         * Debugging Hint:
         * Change Fetch to DisplayDOC to get an echo of the pure ModuleContent
         * else var_dump the fetch!
         *
         */
        $modulcontent =  $this->smarty->fetch($templatename);
        #var_dump($modulcontent);

        /**
         * Assign Content
         *
         * Content of the Modul is assigned to Smarty as variable "content"
         * this is $content in the mainframe index.tpl
         */
        $this->smarty->assign('content',  $modulcontent );

        #DEBUG ? $debug->show_console() : '';
        #var_dump($this->config['tpl_wrapper_file']);
        #var_dump($this->smarty->template_dir);

        return $this->smarty->fetchDOC($this->config['tpl_wrapper_file']);
        // error if wrapper could not be found "Main Layout for Themeset: xy not found. Searched for: filename."
    }
}
?>