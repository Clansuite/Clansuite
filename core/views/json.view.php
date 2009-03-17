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
 * Clansuite View Class - View for JSON data
 *
 * This is a wrapper/adapter for returning JSON data.
 *
 * JSON stands for JavaScript Object Notation (JSON).
 * It's an lightweight, text-based, language-independent data interchange format.
 * It was derived from the ECMAScript Programming Language Standard.
 * JSON defines formatting rules for the portable representation of structured data.
 * @see http://www.ietf.org/rfc/rfc4627.
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
    /**
     * holds instance of Dependency Injector Phemto
     */
    protected $injector   = null;

    public function __construct(Phemto $injector = null)
    {
      # apply instances to class
      $this->injector = $injector;
      #var_dump($injector);

	  # get instances from injector
      $this->config         = $this->injector->instantiate('Clansuite_Config');
      $this->response       = $this->injector->instantiate('Clansuite_HttpResponse');

      # eventlog initalization
    }

    /**
     * Render PHP data as JSON
     */
    public function render($data)
    {
        /**
         * The MIME media type for JSON text is application/json.
         * @see http://www.ietf.org/rfc/rfc4627
         */
        $this->response->setHeader ("Content-Type: application/json;charset={$this->config['language']['outputcharset']}");

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