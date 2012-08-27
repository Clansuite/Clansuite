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

namespace Koch\Exception\Renderer;

use Koch\Exception\Errorhandler;

class SmartyTemplateError
{
    /**
     * Smarty Error Display
     *
     * This method defines the html-output when an Smarty Template Error occurs.
     * It's output is a shortened version of the normal error report, presenting
     * only the errorname, filename and the line of the error.
     * The parameters used for the small report are $errorname, $errorfile, $errorline.
     * If you need a full errorreport, you can add more parameters from the methodsignature
     * to the $errormessage output.
     *
     * Smarty Template Errors are only displayed, when Koch Framework is in DEBUG Mode.
     * @see clansuite_error_handler()
     *
     * A direct link to the template editor for editing the file with the error
     * is only displayed, when Koch Framework runs in DEVELOPMENT Mode.
     * @see addTemplateEditorLink()
     *
     * @param  integer $errno      contains the error as integer
     * @param  string  $errstr     contains error string info
     * @param  string  $errfile    contains the filename with occuring error
     * @param  string  $errline    contains the line of error
     * @param  array   $errcontext contains vars from error context
     * @return string  HTML with Smarty Error Text and Link.
     */
    public static function render($errno, $errorname, $errstr, $errfile, $errline, $errcontext)
    {
        $html = '';
        $html .= '<span>';
        $html .= '<h4><font color="#ff0000">&raquo; Smarty Template Error &laquo;</font></h4>';
        #$html .= '<u>' . $errorname . ' (' . $errno . '): </u><br/>';
        $html .= '<b>' . wordwrap($errstr, 50, "\n") . '</b><br/>';
        $html .= 'File: ' . $errfile . '<br/>Line: ' . $errline;
        $html .= Errorhandler::getTemplateEditorLink($errfile, $errline, $errcontext);
        $html .= '<br/></span>';

        return $html;
    }
}
