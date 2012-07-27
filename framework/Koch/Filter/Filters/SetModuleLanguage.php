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

namespace Koch\Filter\Filters;

use Koch\Filter\FilterInterface;
use Koch\MVC\HttpRequestInterface;
use Koch\MVC\HttpResponseInterface;
use Koch\Localization\Localization;
use Koch\Router\TargetRoute;

/**
 * Koch Framework - Filter for Setting the Module Language.
 *
 * Purpose: Sets the TextDomain for the requested Module
 *
 * @category    Koch
 * @package     Core
 * @subpackage  Filters
 */
class setmodulelanguage implements FilterInterface
{
    /* @var Koch\Localization */
    private $locale = null;

    public function __construct(Localization $locale)
    {
        // set instance of localization to class
        $this->locale = $locale;
    }

    public function executeFilter(HttpRequestInterface $request, HttpResponseInterface $response)
    {
        $modulename = TargetRoute::getController();

        $this->locale->loadTextDomain('LC_ALL', $modulename, $this->locale->getLocale(), $modulename);
    }
}
