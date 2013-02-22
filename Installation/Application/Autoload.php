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

class Autoload
{
    public function __construct()
    {
        // register composer's autoloader
        include VENDOR_PATH . 'autoload.php';

        include KOCH_FRAMEWORK . 'Autoload/Loader.php';
        \Koch\Autoload\Loader::register(APPLICATION_PATH . '/Configuration/kf_classmap.php');

        // then register our autoloader on top of the autoloading stack
        spl_autoload_register('self::autoload', false, true);
    }

    private static function autoload($classname)
    {
        // return early, as we don't handle loading vendor stuff
        if (strpos($classname, 'Doctrine') !== false or
            strpos($classname, 'Koch') !== false) {
            return;
        }



        // remove namespace
        $filename = str_replace('Clansuite\Installation\\', '', $classname);
        $filename = str_replace('\\', DS, $filename);

        // load class
        include INSTALLATION_ROOT . $filename . '.php';
    }
}
