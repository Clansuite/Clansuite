<?php

/**
 * Clansuite - just an eSports CMS
 * Jens-André Koch © 2005 - onwards
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
 */

namespace Clansuite\Core\Mvc;

/**
 * Clansuite - Base Class for Module Controllers the Application.
 *
 * This class extends the AbstractController of the Koch Framework.
 * Some basic functionality is already present.
 * If you need more, this is the place to add functionality shared, by all controllers.
 */
class ModuleController extends \Koch\Module\AbstractController
{
    /**
     * Proxy/convenience method: returns the Clansuite Configuration as array
     *
     * @return $array Clansuite Main Configuration (/configuration/clansuite.config.php)
     */
    public function getClansuiteConfig()
    {
        return $this->config = \Clansuite\Application::getClansuiteConfig();
    }
}
