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
 * Clansuite View Class - View for json data
 *
 * This is a wrapper/adapter for returning json data.
 *
 * @author     Jens-André Koch <vain@clansuite.com>
 * @copyright  Jens-André Koch (2005-onwards)
 *
 * @package     clansuite
 * @category    view
 * @subpackage  view_json
 */
class view_json extends Clansuite_Renderer_Base
{
    public function __construct()
    {
        # eventlog initalization
    }

    /**
     * Render PHP data as JSON
     */
    public function render($data)
    {
        # take php's json encode
		if (function_exists('json_encode'))
		{
			$json_encoded_data = json_encode($data);
		}
		# take a separate library
		elseif( clansuite_loader::loadLibrary('json') == true)
		{
		    # create a new instance of Services_JSON
            $json = new Services_JSON();
            # encode
			$json_encoded_data = $json->encode($data);
		}
		else
		{
		    trigger_error('Error: No json_encode function available!', 1);
		    exit(0);
		}

		return $json_encoded_data;
    }
}
?>