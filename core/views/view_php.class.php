<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-Andre Koch � 2005-2008
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
    * @copyright  Jens-Andre Koch (2005-2008)
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
 * Clansuite Core Class - clansuite_view_php
 *
 * This is a wrapper/adapter for using native PHP as Template Engine.
 *
 * @author     Jens-Andre Koch <vain@clansuite.com>
 * @copyright  Jens-Andre Koch (2005-$Date: 2008-01-14 02:41:36 +0100 (Mo, 14 Jan 2008) $)
 * @since      Class available since Release 0.2
 *
 * @package     clansuite
 * @category    core
 * @subpackage  view_php
 */

class view_php extends renderer_base
{
	var $data = array();

	function __construct($directory)
	{
		$this->directory = $directory;
	}

	function set($key, $value = NULL)
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

	function fetch($filename, $directory = DIR_TEMPLATE)
	{
	    $file = $directory . $this->directory . '/' . $filename;
	    if (file_exists($file))
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
}
?>