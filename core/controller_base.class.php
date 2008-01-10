<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-Andre Koch © 2005-2007
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
 * Interface for all modules
 */ 
interface clansuite_module
{
    function execute(httprequest $request, httpresponse $response);
}

/**
 * Controller_Base
 *
 * Is an abstract class (parent class) to share some common features
 * for all (Module/Page)-Controllers. You could call it ActionController.
 * It`s abstract because it should only extended, not instantiated.
 *
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

    // Variable contains the name of the rendering engine
    public $renderEngine = null;

    // Variable contains the name of the template
    public $templateName = null;

    // Variable contains the Dependecy Injector
    public $injector = null;

    function __construct()
    {

    }

    /**
     * Set dependency injector (SetterInjection)
     * Type Hint set to only accept Phemto
     */
    public function setInjector(Phemto $injector)
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
     * Returns the Name of the Rendering Engine.
     * Returns Smarty if no rendering engine is set
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
     * Returns the Rendering Engine Object
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
     * Set the template name
     *
     * @param $templateName
     */
    public function setTemplate($templateName)
    {
        $this->templateName = $templateName;
    }

    /**
     * Returns the Template Name
     */
    public function getTemplateName()
    {
        # if the templateName was not set, we try several paths to find an tpl
        if(empty($this->templateName))
        {
            # construct templatename with partial path from module name and action
            # $tplname = getModuleName getActionName
            $tplname = 'modulename/actionname.tpl';

            # check if template exists in
            # modules/modulename/templates/renderer/actioname.tpl
            if(is_file( ROOT_MOD . getModuleName .'templates/'.$tplname))
            {

                $this->setTemplate($tplname);
            }
            else
            {

            }
        }
        return $this->templateName;
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
     * controller_base::forward();
     *
     * This forwards from one controller function to
     * another function of the same controller
     * an functions of an different controller.
     *
     * @access public
     */
    public function forward($functionName, $controllerName)
    {
        # forward to controller-name + controller-action
        
        # event log 

    }

    /**
     * controller_base::redirect();
     *
     *
     * @access public
     */
    public function redirect()
    {
        # redirect to ...

        # event log

    }

    /**
     * Force Extending classes to define this methods:
     */
    abstract function index();
    abstract function execute($request,$response);
}
?>
