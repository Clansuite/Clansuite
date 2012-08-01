<?php

/**
 * Koch Framework
 * Jens-André Koch © 2005 - onwards
 *
 * This file is part of "Koch Framework".
 *
 * License: GNU/GPL v2 or any later version, see LICENSE file.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace Koch\View\Renderer;

/**
 * Koch Framework - View Renderer for JSON data.
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
 * @category    Koch
 * @package     Core
 * @subpackage  View
 */
class Json extends AbstractRenderere
{
    public function initializeEngine()
    {
    }

    public function configureEngine()
    {
    }

    /**
     * jsonEncode
     *
     * @param mixed|array $data Data to be json encoded.
     * @return $json_encoded_data
     */
    public function jsonEncode($data)
    {
        if (empty($data)) {
            return '[]';
        } else {
            // use php's json encode to modifiy data representation
            return json_encode($data);
        }
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
        $this->response->addHeader ('X-JSON', '('.$this->jsonEncode($data).')');

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
        $this->response->addHeader('Content-Type', 'application/json; charset='.$this->config['locale']['outputcharset']);

        return $this->jsonEncode($data);
    }
}
