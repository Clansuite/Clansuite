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
    * @author     Jens-André Koch   <vain@clansuite.com>
    * @copyright  Copyleft: All rights reserved. Jens-André Koch (2005-onwards)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/
    *
    * @version    SVN: $Id$
    */

# Security Handler
if (defined('IN_CS') == false){ die('Clansuite not loaded. Direct Access forbidden.');}

# conditional include of the parent class
if (false == class_exists('Clansuite_Formelement_File',false))
{ 
    include dirname(__FILE__) . '/file.form.php';
}

/**
 *  Clansuite_Formelement
 *  |
 *  \- Clansuite_Formelement_File
 *      |
 *      \- Clansuite_Formelement_Uploadify
 */
class Clansuite_Formelement_Uploadify extends Clansuite_Formelement_File implements Clansuite_Formelement_Interface
{
    /**
     * This renders an file upload form using jQuery Uploadify.
     */
    public function render()
    {
        # load the required scripts and styles
        $javascript =  '<link href="'. WWW_ROOT_THEMES_CORE .'/cssc/uploadifye/default.css" rel="stylesheet" type="text/css" />
                        <link href="'. WWW_ROOT_THEMES_CORE .'/css/uploadify/uploadify.css" rel="stylesheet" type="text/css" />
                        <script type="text/javascript" src="'.WWW_ROOT_THEMES_CORE . '/javascript/uploadify/swfobject.js"></script>
                        <script type="text/javascript" src="'.WWW_ROOT_THEMES_CORE . '/javascript/jquery/jquery.uploadify.v2.1.0.min.js"></script>';

        # attach the uploadify handler and apply some configuration
        $javascript .= "<script type=\"text/javascript\">// <![CDATA[
                        $(document).ready(function() {
                            $('#uploadify').uploadify({
                            'uploader'  : '".WWW_ROOT_THEMES_CORE."/javascript/uploadify/uploadify.swf',
                            'script'    : '".WWW_ROOT_THEMES_CORE."/javascript/uploadify/uploadify.php',
                            'cancelImg' : '".WWW_ROOT_THEMES_CORE."/images/icons/cancel.png',
                            'auto'      : true,
                            'folder'    : '/uploads'
                            });
                        });
                        // ]]></script>";

        # output the div elements
        $html = "<div id=\"fileQueue\"></div>
                 <input type=\"file\" name=\"uploadify\" id=\"uploadify\" />
                 <p><a href=\"javascript:jQuery('#uploadify').uploadifyClearQueue()\">Cancel All Uploads</a></p>";

        return $javascript.$html;
    }

    public function __toString()
    {
        return $this->render();
    }
}
?>