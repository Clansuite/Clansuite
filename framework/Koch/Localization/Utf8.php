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

namespace Koch\Localization;

/**
 * Koch Framework - Class for Initalizing UTF8 (fallbacks).
 *
 * Koch Framework relies on mbstring.
 * This class allows running the application without the mbstring extension.
 * It loads functional replacements for the mbstring methods.
 * UTF8 functions and lookup tables are based on the Dokuwiki UTF-8 library written by Andreas Gohr.
 * @link http://github.com/splitbrain/dokuwiki/raw/master/inc/utf8.php
 */
class Utf8
{
    public static function initialize()
    {
        // detect, if the mbstring extension is loaded and set flag constant
        define('UTF8_MBSTRING', extension_loaded('mbstring'));

        // mbstring extension is loaded
        if (UTF8_MBSTRING === true) {
            // we do not accept mbstring function overloading set in php.ini
            if (ini_get('mbstring.func_overload') & MB_OVERLOAD_STRING) {
                $msg = _('The string functions are overloaded by mbstring. Please stop that.
                          Check php.ini - setting: mbstring.func_overload.');
                trigger_error($msg, E_USER_ERROR);
            }

            // if not already set, set internal encoding to UTF-8
            mb_internal_encoding('UTF-8');

        } else { // mbstring extension is NOT loaded, we provide mbstring function fallbacks

            // load functional replacements for mbstring functions
            include KOCH_FRAMEWORK . 'localization\MbstringWrapper.php';

            // load utf-8 character tables for lookups
            include KOCH_FRAMEWORK . 'localization\utf8\CharacterTable.php';

            // load utf8 fallback functions
            include KOCH_FRAMEWORK . 'localization\utf8\Utf8FallbackFunctions.php';
        }
    }
}
