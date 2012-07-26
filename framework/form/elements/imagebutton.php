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

namespace Koch\Formelement;

class Imagebutton extends Input implements FormelementInterface
{
    /**
     * width of image (px)
     *
     * @var int
     */
    public $width;

    /**
     * height of image (px)
     *
     * @var int
     */
    public $height;

    /**
     * URL of image
     *
     * @var string
     */
    public $source;

    public function __construct()
    {
        $this->type = 'image';
    }

    /**
     * sets URL of image
     *
     * @param string $source
     */
    public function setImageURL($source)
    {
        $this->source = $source;
    }

    /**
     * sets width and height of image (px)
     *
     * @param int $width
     * @param int $height
     */
    public function setDimensions($width, $height)
    {
        $this->width = $width;
        $this->height = $height;
    }
}
