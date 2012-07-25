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
 * along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 */

/**
 * Shortens a URL via TinyURL Service
 *
 * @param <type> $long_url The long URL you want to shorten.
 * @return string A Shortened URL via TinyURL Service
 */
function shortTinyUrl($long_url)
{
    $long_url = urlencode($long_url);

    $handle = '';
    $handle = fopen('http://tinyurl.com/api-create.php?url=' . $long_url , 'rb');

    if ($handle) {
        $short_url = '';
        while (false == feof($handle)) {
            $short_url .= fgets($handle, 2000);
        }
        fclose($handle);
    } else {
        throw new Koch_Exception('Unable to shorten the link.');
    }

    return $short_url;
}
