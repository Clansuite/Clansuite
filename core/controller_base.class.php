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
    
    public $renderEngine = null;
    
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
     * sets the Rendering Engine
     * 
     * 
     */    
    public function setRenderEngine($renderer)
    {
        $this->renderEngine = $renderer;
    }
    
    /**
     * returns the Rendering Engine, if empty Smarty is set and returned
     *
     * @access public
     * @return renderengine object, smarty as default
     */
    public function getRenderEngineName()
    {
        if(empty($this->renderEngine)) 
        { 
            $this->setRenderEngine('smarty');
        }
        return $this->renderEngine;
    }
    
    /**
     * Returns the Rendering Engine
     * param1 getRenderEngineName looks up the Renderer-Name
     * param2 pass injector to renderer 
     *
     * @access public
     * @return renderengine object
     */
    public function getRenderEngine()
    {
        return view_factory::getRenderer($this->getRenderEngineName(), $this->injector);
    }
    
    /**
     * controller_base::getView();
     * returns an instance of the render engine object and prepares it for rendering the output
     *
     * 1. initialize proper viewfactory('smarty, json, rss'); as VIEW
     * 2. assign model data to that view object
     * 3. return VIEW
     *
     * @access public
     */    
    public function getView()
    {                   
        
        #$response = $this->injector->instantiate('response');    
        #$response->setRenderer($template);
        #$response->setContentType();
        #$response->setContent($this->output);
        #$response->flush();
        
    }
       
    /**
     * Force Extending classes to define this methods
     */
    abstract function index();
    abstract function execute($request, $response);
}
?>