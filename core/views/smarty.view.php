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
 * @author     Jens-André Koch <vain@clansuite.com>
 * @copyright  Jens-André Koch (2005 - onwards)
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  View
 */

class view_smarty extends Clansuite_Renderer_Base
{
    /**
	 * holds instance of Smarty Template Engine (object)
	 * @var object Smarty
	 */
    protected $smarty     = null;

    /**
     * holds instance of Dependency Injector Phemto
     */
    protected $injector   = null;

    /**
     * variable for the RenderMode (available: WRAPPED)
     */
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
          # developer switch to enable Render_SmartyDoc
          $use_RenderSmartyDOC = true;

          if ( is_file(ROOT_LIBRARIES . 'smarty/Smarty.class.php') ) // check if library exists
          {
              require(ROOT_LIBRARIES . 'smarty/Smarty.class.php');
              $this->smarty = new Smarty();
          }
          else // throw error in case smarty library is missing
          {
              die('Smarty Template Library missing!');
          }

          if ( is_file(ROOT_LIBRARIES . 'smarty/SmartyDoc2.class.php') && ($use_RenderSmartyDOC === true) )
          {
              require(ROOT_LIBRARIES . 'smarty/Render_SmartyDoc.class.php');
              #require(ROOT_LIBRARIES . 'smarty/SmartyDoc2.class.php');
              # Set view and smarty to the smarty object
              $this->smarty = new Render_SmartyDoc();
              $this->view = $this->smarty;
          }
          else // throw error in case Smarty RenderDoc Library is missing
          {
              die('Smarty RenderDoc Library missing!');
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
        if ( $this->smarty->debugging == true )
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
                                                     #'pre'    => array('inserttplnames')
                                                     #,'post'   => array()
                                                     #,'output' => array()
                                                   );

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
        $this->smarty->request_use_auto_globals   = true;   # for templates using $smarty.get.*, $smarty.request.*, etc...
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
         * 5) "/themes/core/templates/"
         * 6) "/themes/admin/"
         */
        $this->smarty->template_dir   = array();
        $this->smarty->template_dir[] = ROOT_THEMES . $_SESSION['user']['theme'];
        $this->smarty->template_dir[] = ROOT_THEMES . $_SESSION['user']['theme'] .DS. Clansuite_ModuleController_Resolver::getModuleName() .DS;
        # this sets the "views" subdirectory under the directory containing the modulecontroller class file
        $this->smarty->template_dir[] = ROOT_MOD;
        $this->smarty->template_dir[] = ROOT_MOD    . Clansuite_ModuleController_Resolver::getModuleName() .DS. 'templates' .DS;
        $this->smarty->template_dir[] = ROOT_THEMES . 'core/templates/' .DS;
        $this->smarty->template_dir[] = ROOT_THEMES . 'admin' .DS;
        $this->smarty->template_dir[] = ROOT_THEMES;

        #var_dump($this->smarty->template_dir);

        $this->smarty->compile_dir    = ROOT .'cache/templates_c/';                   # directory for compiled files
        $this->smarty->config_dir     = ROOT_LIBRARIES .'smarty/configs/';            # directory for config files (example.conf)
        $this->smarty->cache_dir      = ROOT .'cache/';                               # directory for cached files
        $this->smarty->plugins_dir[]  = ROOT_LIBRARIES .'smarty/clansuite_plugins/';            # directory for clansuite smarty plugins
        $this->smarty->plugins_dir[]  = ROOT_LIBRARIES .'smarty/plugins/';            # direcotry for original smarty plugins

        # Modifiers
        # array which modifiers used for all variables, to exclude a var from this use: {$var|nodefaults}
        # $this->smarty->default_modifiers = array('escape:"htmlall"');
        # $this->smarty->register_modifier('timemarker',  array('benchmark', 'timemarker'));
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
        $template_constants['db_counter']= '0';
        #$this->db->query_counter + $this->db->exec_counter + $this->db->stmt_counter;

        return $template_constants;
    }

    /**
     * Assign the common template values and Clansuite constants as Smarty Template Variables.
     *
     * @access protected
     */
    protected function assignConstants()
    {
        # fetch the general clansuite constants from Clansuite_Renderer_Base->getConstants()
        $this->smarty->assign($this->getConstants());
        #var_dump($this->getConstants());

        # fetch the specific smarty constants from view_smarty->getSmartyConstants()
        $this->smarty->assign($this->getSmartyConstants());
        #var_dump($this->getSmartyConstants());

        # leave this for debugging purposes
        #var_dump($this->smarty);
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
        # Debug Display
        # echo 'Smarty was asked to render template: '.$template;
        $this->setTemplate($template);

        # Assign Constants
        $this->assignConstants();

        # @todo caching
        //$resource_name = ???, $cache_id = ???, $compile_id = ???

        /**
         * Fetch the Template of the module
         * and
         * 1) echo it directly
         * 2) Assign it to the Layout Template as $content
         *
         * Debugging Hint:
         * Change Fetch to Display to get an echo of the pure ModuleContent
         * else use the xdebug::printR to display the fetch!
         */

        $modulecontent =  $this->fetch($template);

        #clansuite_xdebug::printR($template);

        # check for existing errors and prepend them
        #if( errorhandler::hasErrors() == true )
        #{
            #$modulecontent = errorhandler::toString() . $modulecontent;
            #$modulecontent = errorhandler::getErrorStack.$modulecontent;
        #}

        /**
         * Decide on given set RenderMode, if modulecontent should be rendered WRAPPED or STANDALONE
         */
        if( $this->getRenderMode() !== 'WRAPPED' ) # render without wrapper: STANDALONE
        {
            #echo '<br />Smarty renders the following Template as NON WRAPPED : '.$template;
            return $modulecontent;
        }
        else # render with wrapper: WRAPPED
        {
            /**
             * fire some preRenderChecks to ensure that {$content} variable
             * and {include for copyright} exists in the layout template
             */
            if( true == $this->preRenderChecks())
            {
                # then assign the modulecontent to it
                $this->assign('content',  $modulecontent );
                #echo '<br />Smarty renders the following Template as WRAPPED : '.$template;

                return $this->smarty->fetchDOC($this->getLayoutTemplate());
            }
        }
    }

    /**
     * preRenderChecks
     */
    public function preRenderChecks()
    {
        foreach( $this->smarty->template_dir as $dir )
        {
            $file = $dir . DS . $this->getLayoutTemplate();
            if (is_file($file) != 0)
            {
                $filecontent = file_get_contents($file);

                $renderChecksArray = array(
                        '1' => array(
                                      'string' => '{include file=\'copyright.tpl\'}',
                                      'exceptionmessage' => "The content variable {include file='copyright.tpl'} must be within the wrapper template!",
                                      'exceptioncode' => '12'
                                    ),

                        '2' => array(
                                      'string' => '{include file=\'clansuite_header_notice.tpl\'}',
                                      'exceptionmessage' => "The content variable {include file='clansuite_header_notice.tpl'} must be within the wrapper template!",
                                      'exceptioncode' => '13'
                                    ),

                        '3' => array(
                                      'string' => '{$content}',
                                      'exceptionmessage' => 'The content variable {$content} must be within the wrapper template!',
                                      'exceptioncode' => '14'
                                    ),
                                );

                return self::preRenderCheck_checkForStrings($renderChecksArray, $filecontent);
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
     * @param $array array with the string to check for.
     * @param $filecontent string The content of the layouttemplate file.
     * @return boolean
     */
    public static function preRenderCheck_checkForStrings($strings_array, $filecontent)
    {
        foreach($strings_array as $preRenderCheck)
        {
           if( false != strpos($filecontent, $preRenderCheck['string']) )
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