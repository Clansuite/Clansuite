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
 * Clansuite View Class - View for XSLT/XML
 *
 * This is a wrapper/adapter for returning XML/XSLT data.
 *
 * @author     Jens-André Koch <vain@clansuite.com>
 * @copyright  Jens-André Koch (2005-onwards)
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  View
 */
class Clansuite_Renderer_Xslt extends Clansuite_Renderer_Base
{
    /**
     * holds instance of XSLT Render Engine (object)
     * @var object xslt
     */
    protected $xslt = null;

    /**
     * holds the abs path to the xsl stylesheet
     * @var string
     */
    protected $xslfile = null;

    public function __construct(Phemto $injector = null)
    {
        # apply instances to class (parent class)
        $this->injector = $injector;

        # instantiate the render engine
        $this->xslt = new XSLTProcessor;
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
        # $this->response()->setContentType('text/html');

        # import the stylesheet  for later transformation
        $this->xslt->importStyleSheet( DOMDocument::load($this->getXSLStyleSheet()));

        # then import the xml data (or file) into the XSLTProcessor and start the transform
        $dom =  $this->xslt->transformToXML( DOMDocument::load( $data ) );

        return  $dom;
    }
}
?>