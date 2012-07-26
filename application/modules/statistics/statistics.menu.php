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
 * along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 */

/**
 * Clansuite Modulenavigation for Module wwwstats
 */
$modulenavigation = array(
    '1' => array(
        'action' => 'show',
        'name' => 'Overview',
        'url' => '/statistics/admin', // &action=show
        'icon' => '',
        'title' => ''
    ),
    '2' => array(
        'action' => 'create',
        'name' => 'Create new',
        'url' => '/statistics/admin/create',
        'icon' => '',
        'title' => ''
    ),
    '3' => array(
        'action' => 'settings',
        'name' => 'Settings',
        'url' => '/statistics/admin/settings',
        'icon' => '',
        'title' => ''
    ),
);
?>