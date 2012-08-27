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

namespace Koch\Localization\Adapter\Gettext\Extractors;

/**
 * Gettext_Extractor_PHP
 *
 * Extracts translation strings by scanning for certain functions: translate(), t(), _().
 *
 * @category    Koch
 * @package     Core
 * @subpackage  Gettext
 */
class Php extends Base implements Extractor
{
    /**
     * The function tags to extract translation strings from
     *
     * @var array
     */
    protected $tags_to_scan = array('translate', 't', '_');

    /**
     * Parses given file and returns found gettext phrases
     *
     * @param string $file
     *
     * @return array
     */
    public function extract($file)
    {
        $pInfo = pathinfo($file);
        $data = array();
        $tokens = token_get_all(file_get_contents($file));
        $next = false;

        foreach ($tokens as $c) {
            if (true === is_array($c)) {
                if ($c[0] !== T_STRING and $c[0] !== T_CONSTANT_ENCAPSED_STRING) {
                    continue;
                }

                if ($c[0] === T_STRING and true === in_array($c[1], $this->tags_to_scan)) {
                    $next = true;
                    continue;
                }

                if ($c[0] === T_CONSTANT_ENCAPSED_STRING and $next === true) {
                    $data[substr($c[1], 1, -1)][] = $pInfo['basename'] . ':' . $c[2];
                    $next = false;
                }
            } else {
                if ($c === ')') {
                    $next = false;
                }
            }
        }

        return $data;
    }
}
