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
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' ); }

/**
 * Clansuite View Class - View for PHPTAL
 *
 * This is a wrapper/adapter for rendering with PHPTAL.
 *
 * @author     Jens-André Koch <vain@clansuite.com>
 * @copyright  Jens-André Koch (2005-onwards)
 *
 * @package     clansuite
 * @category    view
 * @subpackage  view_phptal
 */
class view_phptal extends Clansuite_Renderer_Base
{
    /**
     * holds PHPTAL Render Engine object
     * @var object PHPTAL
     */
    protected $phptal = null;
    
    public function __construct()
    {
        # eventlog initalization
    }
    
    /**
     * Plug in PHPTAL object into View
     *      
     * @param object PHPTAL $phptal
     */
    public function setEngine(PHPTAL $phptal)
    {
        $this->phptal = $phptal;
        # @todo check, if $this should be injected into phptal?
        $this->phptal->set('this', $this);
        return $this;
    }

    /**
     * Get PHPTAL object from View
     */
    public function getEngine()
    {
        return $this->phptal;
    }

    /**
     * Set PHPTAL variables
     *
     * @param string $key variable name
     * @param string $value variable value
     */
    public function __set($key, $value)
    {
        $this->phptal->set($key, $value);
    }

    /**
     * Get PHPTAL Variable Value
     *
     * @param string $key variable name
     * @return mixed variable value
     */
    public function __get($key)
    {
        return $this->phptal->$key;
    }

    /**
     * Check if PHPTAL variable is set
     *
     * @param string $key variable name
     */
    public function __isset($key)
    {
        return isset($this->phptal->$key);
    }

    /**
     * Unset PHPTAL variable
     *
     * @param string $key variable name
     */
    public function __unset($key)
    {
        if (isset($this->phptal->$key))
        {
            unset($this->phptal->$key);
        }
    }

    /**
     * Clone PHPTAL object
     * 
     * @todo Check if clone is needed to work with several instances of phptal for widgets? 
     */
    /*
    public function __clone()
    {
        $this->phptal = clone $this->phptal;
    }
    */

    /**
     * Display template
     */
    protected function render($template)
    {
        $this->phptal->setTemplate($template);
        
        try
        {
            echo $this->phptal->execute();
        } 
        catch (Clansuite_Exception $e)
        {
                throw new Clansuite_Exception($e);
        }
    }
}
?>