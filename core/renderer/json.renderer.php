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

# Security Handler
if (defined('IN_CS') == false){ die('Clansuite not loaded. Direct Access forbidden.' ); }

# Load Clansuite_Renderer_Base
require dirname(__FILE__) . '/renderer.base.php';

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
 * This class implements two ways of rendering data as json.
 * 1) The method renderByHeader() wraps the json directly in the header.
 * 2) The method render() returns the json data for later rendering (as body).
 *
 * @author     Jens-Andr� Koch <vain@clansuite.com>
 * @copyright  Jens-Andr� Koch (2005-onwards)
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  View
 */
class Clansuite_Renderer_Json extends Clansuite_Renderer_Base
{
    /**
     * RenderEngineConstructor
     *
     * parent::__construct does the following:
     * 1) Apply instances of Dependency Injector Phemto and Clansuite_Config to the RenderBase
     * 2) Initialize the RenderEngine via parent class constructor call = self::initializeEngine()
     * 3) Configure the RenderEngine with it's specific settings = self::configureEngine();
     */
    function __construct(Phemto $injector = null, Clansuite_Config $config)
    {
        parent::__construct();

        # we need the response object for direct rendering
        $this->response = $this->injector->instantiate('Clansuite_HttpResponse');
    }

    public function initializeEngine()
    {
    }

    public function configureEngine()
    {
    }

    /**
     * jsonEncode
     *
     * @param mixed $data the data which should json encoded.
     * return $json_encoded_data
     */
    public function jsonEncode($data)
    {
        # take php's json encode
        return json_encode($data);
    }

    /**
     * Render PHP data as JSON (through HEADER)
     * This method does not return the json encoded string for rendering,
     * instead it applies it directly to the header.
     *
     * @param $data array php-array
     */
    public function renderByHeader($data)
    {
        $this->response->addHeader ("X-JSON", '('.$this->jsonEncode($data).')');

        return;
    }

    /**
     * Render PHP data as JSON (through BODY)
     * This method returns the json encoded string.
     *
     * @param $data array
     * @return $json_encoded_data
     */
    public function render($data)
    {
        /**
         * The MIME media type for JSON text is application/json.
         * @see http://www.ietf.org/rfc/rfc4627
         */
        $this->response->addHeader ("Content-Type' 'application/json; charset={$this->config['language']['outputcharset']}");

        return $this->jsonEncode($data);
    }
}
?>