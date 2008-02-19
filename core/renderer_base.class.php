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

//Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}

/**
 * A abstract base class for all our view rendering engines.
 * All renderers must extend from this class.
 *
 * @package    clansuite
 * @category   core
 * @subpackage view
 */
abstract class renderer_base
{
    public $view = null;     # holds instance of the Rendering Engine
    protected $injector; # holds instance of Dependency Injector Phemto (object)

    /**
     * Construct View from Module.
     * @param Module_Name_View $view module_name_view
     */
    public function __construct(Phemto $injector)
    {
        $this->injector = $injector;    # set Injector
    }

    /**
         * Magic Method __call / Overloading
         * This is basically a simple passthrough (aggregation)
         * of a method and its arguments to the renderingEngine!
         * Purpose: We don't have to rebuild all methods in the specific renderEngine Wrapper/Adapter
         * or pull out the renderEngine Object itself. We just pass things to it.
         */
    public function __call($method, $arguments)
    {
        #echo 'Magic used for Loading Method = '. $method . ' with Arguments = '. var_dump($arguments);
        if(method_exists($this->view, $method))
        {
            return call_user_func_array(array($this->view, $method), $arguments);
        }
        else
        {
            throw new Exception('Method "'. $method .'" not existant in RenderEngine "' . get_class($this->view) .'"!', 1);
        }
    }

    #abstract public function setTemplatePath($templatepath);
    #abstract public function getTemplatePaths();

    /**
     * Assigns a value to a template parameter.
     *
     * @access public
     * @param string $tpl_parameter The template parameter name
     * @param mixed $value The value to assign
     */
    abstract public function assign($tpl_parameter, $value = null);

    /**
     * Executes the template rendering and returns the result.
     *
     * @access public
     * @param string $template Template Filename
     * @param mixed $data Additional data to process
     * @return string
     */
    abstract public function fetch($template, $data = null);

    /**
     * Executes the template rendering and displays the result.
     *
     * @access public
     * @param string $template Template Filename
     * @param mixed $data Additional data to process
     * @return string
     */
    abstract public function display($template, $data = null);
}
?>