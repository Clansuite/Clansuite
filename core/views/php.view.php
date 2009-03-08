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
    * @since      File available since Release 0.2
    *
    * @version    SVN: $Id$
    */

// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}

/**
 * Clansuite View Class - View for native PHP Templates
 *
 * This is a wrapper/adapter for using native PHP as Template Engine.
 *
 * @author     Jens-André Koch <vain@clansuite.com>
 * @copyright  Jens-André Koch (2005-onwards)
 * @since      Class available since Release 0.2
 *
 * @package     clansuite
 * @category    view
 * @subpackage  view_php
 */

class view_php extends Clansuite_Renderer_Base
{
	private $template;
	private $data = array();

	public function __construct($template)
	{
		$this->template = $template;
	}

	public function set($key, $value = NULL)
	{
		if (!is_object($key))
		{
			$this->data[$key] = $value;
		}
		else
		{
		    $this->data[$key] = $value->fetch();
		}
	}

	public function fetch($filename, $directory)
	{
	    $file = $directory . '/' . $filename;
	    if (is_file($file))
	    {
    		extract($this->data);

    		ob_start();
    		include($file);
    		$content = ob_get_contents();
    		ob_end_clean();

    		return $content;
		}
		else
		{
			exit('Error: Template ' . $file . ' not found!');
		}
	}

    public function assign()
    {

    }

    public function render()
    {

    }
}
?>