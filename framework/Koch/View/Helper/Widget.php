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

namespace Koch\View\Helper;

use Koch\Mvc\Mapper as Mapper;

class Widget
{
    /**
     * loadModul
     *
     * - constructs classname
     * - constructs absolute filename
     * - hands both to requireFile, returns true if successfull
     *
     * The classname for modules is prefixed 'module_' . $modname
     * The filename is 'clansuite/modules/'. $modname .'.module.php'
     *
     * String Variants to consider:
     * 1) admin
     * 2) module_admin
     * 3) module_admin_menueditor
     *
     * @param  string  $module The name of the module, which should be loaded.
     * @return boolean
     */
    public static function loadModul($module)
    {
        echo 'Trying to load widget from Module : '. $module;

        $module_path = Mapper::getModulePath($module);
        $file = Mapper::mapControllerToFilename($module_path, $module);

        echo $module_path . ' - ' . $file;

        $module = mb_strtolower($module);

        // apply classname prefix to the modulename
        $module = \Koch\Functions\Functions::ensurePrefixedWith($module, 'clansuite_module_');

        // build classname from modulename
        $classname = \Koch\Functions\Functions::toUnderscoredUpperCamelCase($module);

        /**
         * now we have a common string like 'clansuite_module_admin_menu' or 'clansuite_module_news'
         * which we split at underscore, via explode, resulting in an array
         * like: Array ( [0] => clansuite [1] => module [2] => admin [3] => menu )
         * or  : Array ( [0] => clansuite [1] => module [2] => news )
         */
        $moduleinfos = explode('_', $module);
        unset($module);
        $filename = ROOT_MOD;

        // if there is a part [3], we have to require a submodule filename
        if ($moduleinfos['3'] !== null) {
            // and if part [3] is "admin", we have to require a admin submodule filename
            if ($moduleinfos['3'] == 'admin') {
                // admin submodule filename, like news.admin.php
                $filename .= $moduleinfos['2'] . DIRECTORY_SEPARATOR . 'controller' . DIRECTORY_SEPARATOR . $moduleinfos['2'] . '.admin.php';
            } else {
                // normal submodule filename, like menueditor.module.php
                $filename .= $moduleinfos['3'] . DIRECTORY_SEPARATOR . 'controller' . DIRECTORY_SEPARATOR . $moduleinfos['3'] . '.module.php';
            }
        } else {
            // module filename
            $filename .= $moduleinfos['2'] . DIRECTORY_SEPARATOR . 'controller' . DIRECTORY_SEPARATOR . $moduleinfos['2'] . '.module.php';
        }

        return class_exists($classname);
    }

}
