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
if (defined('IN_CS') == false)
{ 
    die('Clansuite not loaded. Direct Access forbidden.' );
}

# Load Clansuite_Renderer_Base
require dirname(__FILE__) . '/renderer.base.php';

/**
 * Clansuite View Class - View for Smarty Templates
 *
 * This is a wrapper/adapter for the PDF Engine tcpdf.
 *
 * @link http://www.tcpdf.com/ TCPDF Website
 *
 * @author     Jens-André Koch <vain@clansuite.com>
 * @copyright  Jens-André Koch (2005 - onwards)
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Renderer
 */

class Clansuite_Renderer_PDF extends Clansuite_Renderer_Base
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

    public function initializeEngine()
    {

    }

    public function configureEngine()
    {

    }

    public function render()
    {

    }
}
?>