<?php

/**
 * Clansuite - just an eSports CMS
 * Jens-André Koch © 2005 - onwards
 * http://www.clansuite.com/
 *
 * This file is part of "Clansuite - just an eSports CMS".
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

namespace Clansuite\Installation\Application;

/**
 * Clansuite Installation Exception
 *
 * @category    Clansuite
 * @package     Installation
 * @subpackage  Exception
 */
class Exception extends \Exception
{
    /**
     * Define Exceptionmessage && Code via constructor
     * and hand it over to the parent Exception Class
     */
    public function __construct($message = '', $code = 0)
    {
        parent::__construct($message, $code);
    }

    /**
     * Transform the Object to String
     */
    public function __toString()
    {
        // Header
        $html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
                           "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                        <html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">';
        $html .= '<head><title>Clansuite Installation Error</title>';
        $html .= '<link rel="stylesheet" href="../themes/core/css/error.css" type="text/css" />';
        $html .= '</head><body>';

        /**
         * Fieldset for Exception Message
         */
        $html .= '<fieldset class="error_yellow">';
        $html .= '<div style="float:left; padding: 15px;">';
        $html .= '<img src="images/Clansuite-Toolbar-Icon-64-error.png" style="border: 2px groove #000000;" alt="Clansuite Error Icon" /></div>';
        $html .= '<legend>Clansuite Installation Error</legend>';
        $html .= '<p><strong>' . $this->getMessage() . '</strong>';

        /**
         * Display a table with all pieces of information of the exception.
         */
        if (DEBUG == true) {
            $html .= '<table>';
            $html .= '<tr><td><strong>Errorcode</strong></td><td>' . $this->getCode() . '</td></tr>';
            $html .= '<tr><td><strong>Message</strong></td><td>' . $this->getMessage() . '</td></tr>';
            $html .= '<tr><td><strong>Pfad</strong></td><td>' . dirname($this->getFile()) . '</td></tr>';
            $html .= '<tr><td><strong>Datei</strong></td><td>' . basename($this->getFile()) . '</td></tr>';
            $html .= '<tr><td><strong>Zeile</strong></td><td>' . $this->getLine() . '</td></tr>';
            $html .= '</table>';
        }

        $html .= '</p></fieldset><br />';

        /**
         * Fieldset for Help Message
         */
        $html .= '<fieldset class="error_yellow">';
        $html .= '<legend>Help</legend>';
        $html .= '<ol>';
        $html .= '<li>You might use <a href="phpinfo.php">phpinfo()</a> to check your serversettings.</li>';

        if ( get_cfg_var('cfg_file_path') ) {
            $cfg_file_path = get_cfg_var('cfg_file_path');
        }
        $html .= '<li>Check your php.ini ('. $cfg_file_path .') and ensure all needed extensions are loaded. <br />';
        $html .= 'After a modification of your php.ini you must restart your webserver.</li>';

        $html .= '<li>Check the webservers errorlog.</li></ol><p>';
        $html .= "If you can't solve the error yourself, feel free to contact us at our website's <a href=\"http://forum.clansuite.com/index.php?board=25.0\">Installation - Support Forum</a>.<br/>";
        $html .= '</p></fieldset>';
        $html .= '</body></html>';

        // stfu...
        exit($html);
    }
}
