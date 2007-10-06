<?php
   /**
    * Clansuite - just an E-Sport CMS
    * Jens-Andre Koch, Florian Wolf
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
    * @copyright  Jens-Andre Koch (2005-2007)
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
 
 * A abstract base class for all our view renderers. 
 * All renderers must extend from this class.
 *
 * @access     public
 * @package    Clansuite Core
 * @subpackage View
 */
abstract class renderer_base 
{
    protected $view;
    protected $injector;

    /**
     * Construct View from Module.
     * @param Module_Name_View $view module_name_view
     */
    public function __construct($view, $injector)
    { 
        $this->view = $view; 
        $this->injector = $injector;       
    }
    
    
    
    /**
     * Assigns a value to a template parameter.
     *
     * @access public
     * @param string $tpl_parameter The template parameter name
     * @param mixed $value The value to assign
     */
    #abstract public function assign($tpl_parameter, $value);
    
    /**
     * Executes the template rendering and returns the result.
     *
     * @access public
     * @param string $template Template Filename
     * @param mixed $data Additional data to process
     * @return string
     */
    #abstract public function fetch($template, $data = null);
    
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