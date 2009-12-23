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
    * @author     Jens-Andr� Koch   <vain@clansuite.com>
    * @copyright  Copyleft: All rights reserved. Jens-Andr� Koch (2005-onwards)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    * @since      File available since Release 0.2
    *
    * @version    SVN: $Id$
    */

// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.');}

if (!class_exists('Clansuite_Formelement_Textarea')) { require 'textarea.form.php'; }

/**
 *  Clansuite_Formelement
 *  |
 *  \- Clansuite_Formelement_Textarea
 *      |
 *      \- Clansuite_Formelement_Wysiwygtinymce
 *
 * @see Http://www.
 */
class Clansuite_Formelement_Wysiwygtinymce extends Clansuite_Formelement_Textarea implements Clansuite_Formelement_Interface
{
    /**
     * This renders a textarea with the WYSWIWYG editor TinyMCE attached.
     */
    public function render()
    {
        # a) loads the tinymce javascript file
        $javascript = '<script src="'.WWW_ROOT_THEMES_CORE . '/javascript/...'. '" type="text/javascript"></script>';

        # watch it! the online version has some icons changes
        #$javascript = '<script type="text/javascript" src="..."></script>';

        # b) handler to attach tinymce to all textareas
        $javascript .= "<script type=\"text/javascript\">// <![CDATA[
                            
...

                        // ]]></script>";

        # c) css style
        $html = '<STYLE type="text/css">'.CR.'
                 ...
                </STYLE>';

        # c) render a normal textarea
        $this->cols = 100;
        $this->rows = 30;
        $html .= parent::render_html_textarea();

        return $javascript.$html;
    }

    public function __toString()
    {
        return $this->render();
    }
}
?>