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

if (!class_exists('Clansuite_Formelement_Textarea',false)) { require dirname(__FILE__) . '/textarea.form.php'; }

/**
 *  Clansuite_Formelement
 *  |
 *  \- Clansuite_Formelement_Textarea
 *      |
 *      \- Clansuite_Formelement_Wysiwygnicedit
 *
 * @see Http://www.nicedit.com/ Official Website of NicEdit
 * @see http://wiki.nicedit.com/ Wiki of NicEdit
 */
class Clansuite_Formelement_Wysiwygnicedit extends Clansuite_Formelement_Textarea implements Clansuite_Formelement_Interface
{
    /**
     * This renders a textarea with the WYSWIWYG editor NicEdit attached.
     */
    public function render()
    {  
        # a) loads the nicedit javascript file
        $javascript = '<script src="'.WWW_ROOT_THEMES_CORE . '/javascript/nicedit/nicedit.js'. '" type="text/javascript"></script>';

        # watch it! the online version has some icons changes
        #$javascript = '<script type="text/javascript" src="http://js.nicedit.com/nicEdit-latest.js"></script>';

        # b) handler to attach nicedit to all textareas
        $javascript .= "<script type=\"text/javascript\">// <![CDATA[
                        var wysiwyg;
                            bkLib.onDomLoaded(function() {
                              wysiwyg = new nicEditor({     fullPanel : true,
                                    iconsPath : '" . WWW_ROOT_THEMES_CORE . "/javascript/nicedit/nicEditorIcons.gif',
                                    maxHeight : 600,
                                    bbCode    : true,
                                    xhtml     : true
                                  }).panelInstance('".$this->name."');
                            });
                            // ]]></script>";

        # wysiwyg.instanceById('page_body').saveContent();

        # c) css style
        $html = '<STYLE type="text/css">'.CR.'
                 .nicEdit-main {
                    background-color: #eee !important;
                    font-size: 16px;
                    padding: 3px;
                    }'.CR.'
                </STYLE>';


        # if we are in inheritance mode, skip this, the parent class handles this already
 
        return $javascript.$html;
    }

    public function __toString()
    {
        return $this->render();
    }
}
?>