<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-André Koch © 2005 - onwards
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

# Load Clansuite_Renderer_Base
require dirname(__FILE__) . '/renderer.base.php';

/**
 * Clansuite Renderer Class - Renderer for native PHP Templates
 *
 * This is a wrapper/adapter for using native PHP as Template Engine.
 *
 * @author     Jens-André Koch <vain@clansuite.com>
 * @copyright  Jens-André Koch (2005-onwards)
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Renderer
 */

class Clansuite_Renderer_Php extends Clansuite_Renderer_Base
{
    private $file;
	#private $template;
	private $data = array();

	public function __construct($file, $data)
	{
		$this->file = $file;
		$this->data = $data;
		
		return $this;
	}
	
	public function fetch($filename = null, $directory = null)
	{
	    if(is_null($filename))
	    {
	        $file = $directory . DS . $filename . '.tpl';
	    }
	    else
	    {
	        $file = $this->file;   
	    }

	    if (is_file($file))
	    {
	        /**
	         * extract all templatevariables
	         * and do not overwrite an existing variable, if there is a collision
             * just prefix them with invalid_
	         */
    		extract($this->data, EXTR_REFS | EXTR_PREFIX_INVALID, 'invalid_');

    		ob_start();
    		require $file;
    		$content = ob_get_contents();
    		ob_end_clean();

    		return $content;
		}
		else
		{
			exit('Error: Template ' . $file . ' not found!');
		}
	}

    /**
     * Assign specific variable to the template
     *
     * @param mixed $key Object with template vars (extraction method fetch), or array or key/value pair
     * @param mixed $value Variable value
     * @return Clansuite_Renderer_PHP
     */
    public function assign($key, $value=null)
    {
		if ( is_object($key))
		{
		    $this->data[$key] = $value->fetch();
		}
        elseif (is_array($key))
        {
            array_merge($this->data, $key);
        }
        else
        {
            $this->data[$key] = $value;
        }
        
        return $this;
    }

    /**
     * Display the rendered template
     *
     * @return string HTML Representation of Template with Vars
     */
    public function render()
    {
        return $this->fetch();
    }

    /**
     * Render the content and return it
     *
     * @example
     * echo new Clansuite_Renderer_PHP($file, array('title' => 'My title'));
     *
     * @return string  HTML Representation
     */
    public function __toString()
    {
        return $this->render();
    }
}
?>