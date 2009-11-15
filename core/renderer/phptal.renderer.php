<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-Andr� Koch � 2005 - onwards
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
    * @author     Jens-Andr� Koch <vain@clansuite.com>
    * @copyright  Jens-Andr� Koch (2005 - onwards)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    *
    * @version    SVN: $Id$
    */

// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' ); }

# Load Clansuite_Renderer_Base
require dirname(__FILE__) . '/renderer.base.php';

/**
 * Clansuite Renderer Class - Renderer for PHPTAL
 *
 * This is a wrapper/adapter for rendering with PHPTAL.
 *
 * @link http://phptal.org/ Official Website of the PHPTal Project.
 * @link http://phptal.sourceforge.net/ Project's Website at Sourceforge
 *
 * @author     Jens-Andr� Koch <vain@clansuite.com>
 * @copyright  Jens-Andr� Koch (2005-onwards)
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Renderer
 */
class Clansuite_Renderer_Phptal extends Clansuite_Renderer_Base
{
    /**
     * RenderEngineConstructor
     *
     * parent::__construct does the following:
     * 1) Apply instances of Dependency Injector Phemto and Clansuite_Config to the RenderBase
     * 2) Initialize the RenderEngine via parent class constructor call = self::initializeEngine()
     * 3) Configure the RenderEngine with it's specific settings = self::configureEngine();
     * 4) Eventlog
     */
    function __construct(Phemto $injector = null, Clansuite_Config $config)
    {
        parent::__construct();
    }

    /**
     * Sets up PHPTAL Template Engine
     *
     * @return PHPTAL Object
     */
    public function initializeEngine()
    {
		# prevent redeclaration
        if (!class_exists('PHPTAL'))
        {
    		# check if library exists
            if ( is_file(ROOT_LIBRARIES . 'phptal/PHPTAL.php') )
            {
                require(ROOT_LIBRARIES . 'phptal/PHPTAL.php');
                $this->renderer = new PHPTAL();
            }
            else // throw error in case PHPTAL library is missing
            {
                die('PHPTAL Template Library missing!');
            }
        }
        else // throw error in case engine was already loaded
        {
            die('PHPTAL already loaded!');
        }

		return $this->phptal;
    }

    /**
     * Render Engine Configuration
     * Configures the PHPTAL Object
     */
    public function configureEngine()
    {

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
     * Returns a clean Smarty Object
     *
     * @return Smarty Object
     */
    public function getEngine()
    {
        if($this->renderer)
        {
			/**
			 * we don't know what happened to the renderer on it's way
			 * so in order to get a clean render object
			 * we remove all prior assigns and configuration settings
			 */
			#$this->renderer->clear_all_assign();
			#$this->renderer->clear_config();
		}
		else
		{
		    self::initializeEngine();
		}

		# then we reload the base configuration to have default template paths and debug-settings
		#self::configureEngine();

		return $this->renderer;
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