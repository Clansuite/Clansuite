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

# Security Handler
if (defined('IN_CS') == false) { die('Clansuite not loaded. Direct Access forbidden.'); }

/**
 * Decorator for the ModuleController
 *
 * Purpose: attach plugins and methods at runtime to the module by nesting (wrapping) them.
 * Pattern: @book "GOF:175" - Decorator (structural pattern)
 *
 * @author     Jens-André Koch <vain@clansuite.com>
 * @copyright  Jens-André Koch (2005 - onwards)
 * @version    0.1
 */
class Clansuite_Module_ControllerDecorator implements Clansuite_Module_Interface
{
    # the moduleController to decorate
    protected $_moduleController;

    /**
     * Decorate
     */
    public function decorate(Clansuite_Module_Interface $moduleController)
    {
        $this->_moduleController = $moduleController;
    }

    /**
     * Checks if a decorator provides a certain method
     * Order of processing: first it checks the current decorator, then all encapsulated ones.
     */
    public function hasMethod($methodname)
    {
        # is the method provided by this decorator?
        if(method_exists($this, $methodname))
        {
            # yes
            return true;
        }

        # is the method provided by an encapsulated decorator?
        if($this->_moduleController instanceof Clansuite_Module_ControllerDecorator)
        {
            # dig into the encapsulated controller and ask for the method
            return $this->_moduleController->hasMethod($methodname);
        }

        # there was no method found
        return false;
    }

    /**
     * Magic Method __call()
     *
     * When a method call to the current decorator is not defined, it is catched by __call().
     * So the purpose of this method is to delegate method calls to the different decorators.
     * This result is, that you have the full combination of methods of the nested decorators
     * available, without losing methods.
     *
     * Several Performance-Issues:
     * 1) costs for calling __call
     * 2) costs for calling call_user_func_array()
     *    All we can do is to use our semi-micro optimization: Clansuite_Loader::callMethod().
     * 3) the nested call stack itself: the bigger the stack, the slower it becomes.
     */
    public function __call($method, $args)
    {
        # use optimized callMethod() to call method with it's arguments on that modulecontroller
        return Clansuite_Loader::callMethod($this->_moduleController, $method, $args);
    }
}
?>