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

namespace Koch\Image;

/**
 * Koch Framework - Class for Image Thumbnailing
 *
 * @package     Koch
 * @subpackage  Core
 * @category    Image
 */
class Thumbnail extends Image
{
    protected $object;

    public function __construct($config, Koch_Image $object)
    {

        $this->object = $object;
        $this->object->thumbName = $config['thumb_name'];
        $this->object->newWidth = $config['new_width'];
        $this->object->newHeight = $config['new_height'];
        $this->object->keepAspectRatio = $config['keep_aspect_ratio'];
        $this->object->aspectRatio = $this->calcAspectRatio();
        $this->object->jpegQuality = $config['jpeg_quality'];
        $this->object->workImage = $object->getWorkImageResource($object->newWidth, $object->newHeight);
    }

    public function calcAspectRatio()
    {
        if ($this->object->newWidth != 0) {
            $ratio = $this->object->originalWidth / $this->object->newWidth;
            $this->object->newHeight = ((int) round($this->object->originalHeight / $ratio));

            return $ratio;
        }

        if ($this->object->newHeight != 0) {
            $ratio = $this->object->originalHeight / $this->object->newHeight;
            $this->object->newWidth((int) round($this->object->originalWidth / $ratio));

            return $ratio;
        }
    }
}
