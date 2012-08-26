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

use Koch\View\AbstractRenderer;

/**
 * Koch Framework - View Renderer for XSLT/XML.
 *
 * This is a wrapper/adapter for returning XML/XSLT data.
 *
 * @category    Koch
 * @package     View
 * @subpackage  Renderer
 */
class Xslt extends AbstractRenderer
{
    /**
     * holds instance of XSLT Render Engine (object)
     * @var object xslt
     */
    protected $xslt = null;

    /**
     * @var filepath to the XSL StyleSheet file
     */
    public $xslfile = null;

    /**
     * holds the abs path to the xsl stylesheet
     * @var string
     */
    protected $xslfile = null;

    public function __construct(Koch\Config $config)
    {
        parent::__construct($config);

        // instantiate the render engine
        $this->xslt = new \XSLTProcessor;
    }

    /**
     * Returns XSLT RenderEngine Object
     *
     * @return xslt_processor
     */
    public function getEngine()
    {
        return $this->xslt;
    }

    /**
     * setXSLStyleSheet
     *
     * @param $xslfile The fullpath to the XSL StyleSheet file for later combination with the xml data.
     */
    public function setXSLStyleSheet($xslfile)
    {
        $this->xslfile = $xslfile;
    }

    /**
     * getXSLStyleSheet
     *
     * @return $xslfile
     */
    public function getXSLStyleSheet()
    {
        return $this->xslfile;
    }

    /**
     * This renders the xml $data array.
     *
     * @param $data XML Data to render
     */
    public function render($data)
    {
        // $this->response()->setContentType('text/html');

        // import the stylesheet for later transformation
        $this->xslt->importStyleSheet( \DOMDocument::load($this->getXSLStyleSheet()));

        // then import the xml data (or file) into the XSLTProcessor and start the transform
        $dom = $this->xslt->transformToXML( \DOMDocument::load( $data ) );

        return $dom;
    }
}
