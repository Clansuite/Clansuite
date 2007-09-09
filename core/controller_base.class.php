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

/**
 * Abstract class is parent class for all (Page)Controllers
 * 1. saves a copy of the cfg class
 * 2. makes sure that controllers have an index() and execute() method
 * 3. provide access to create_global_view
 *
 */
abstract class controller_base
{
    /**
     * Variable $output contains the output (view-data) of the module
     * @access protected
     */
    protected $output = null;
    
    // note by vain: check if these are still needed -> from v0.1 now deprecated?
    // positional check: this is view-related!
    protected $additional_head  = null;
    protected $suppress_wrapper = null;
    
    function __construct()
    {
       
    }  
    
    /**
     * Set dependency injector (SetterInjection)
     */
    public function setInjector($injector) 
    {
    	$this->injector = $injector;
    }
    
    /** 
     * Set view
     */
    public function setView($view)
    {
        $this->view = $view;
    }
    
    /**
     * controller_base::getView();
     *
     * 1. initialize proper view::viewfactory('smarty, json, rss'); as VIEW
     * 2. assign model data to that view object
     *
     * @access public
     */
    
    public function getView()
    {
       # $response = $injector->instantiate('response');
    
        #$response->setRenderer($template);
        #$response->setContent($this->output);
        #$response->flush();
        echo $this->output;
    }
    
    public function display($template)
    {
    	if($this->get('layout') == 'rss'){
    		header('Content-Type: application/rss+xml');
    	}
    	$this->smarty->display($template);
    }
    
    /**
     * Force Extending classes to define this methods
     */
    abstract function index();
    abstract function execute($request, $response);
}
?>