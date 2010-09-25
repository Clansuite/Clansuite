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
    * @author     Jens-André Koch <vain@clansuite.com>
    * @copyright  Jens-André Koch (2005 - onwards)
    * @link       http://www.clansuite.com
    *
    * @version    SVN: $Id$
    */

# Security Handler
if (defined('IN_CS') === false)
{
    die('Clansuite not loaded. Direct Access forbidden.' );
}

/**
 * Clansuite Renderer Class - Renderer for Xtemplate.
 *
 * This is a wrapper/adapter for rendering with XTemplate.
 *
 * @link http://www.phpxtemplate.org/ Offical Website of PHP XTemplate
 * @link http://xtpl.sourceforge.net/ Project's Website at Sourceforge
 *
 * @author     Jens-André Koch <vain@clansuite.com>
 * @copyright  Jens-André Koch (2005-onwards)
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Renderer
 */
class Clansuite_Renderer_Xtemplate extends Clansuite_Renderer_Base
{
    public function __construct(Clansuite_Config $config)
    {
        parent::__construct($config);
        $this->initializeEngine();
    }

    public function initializeEngine()
    {
        # prevent redeclaration
        if(class_exists('XTemplate', false) == false)
        {
            # check if Smarty library exists
            if(is_file(ROOT_LIBRARIES . 'xtemplate/xtemplate.class.php') === true)
            {
                include ROOT_LIBRARIES . 'xtemplate/xtemplate.class.php';
            }
            else
            {
                throw new Exception('XTemplate Library missing!');
            }
        }

        # Do it with XTemplate style > eat like a bird, poop like an elefant!
        return $this->renderer = new XTemplate();
    }

    public function configureEngine()
    {

    }

    public function render()
    {
        $this->renderer->out();
    }

    public function assign($key, $value)
    {
        $this->renderer->assign($key, $value);
    }
}
?>