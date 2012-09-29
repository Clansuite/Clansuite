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
 * Koch Framework - Class for Image Handling.
 *
 * @package     Koch
 * @subpackage  Core
 * @category    Image
 */
class Image
{
    /**
     * @var string
     */
    protected $imageSource;
    /**
     * @var string
     */
    protected $imageName;
    /**
     * @var string
     */
    protected $imageTarget;
    /**
     * @var string
     */
    protected $imageExtension;
    /**
     * @var resource
     */
    protected $originalImage;
    /**
     * @var resource
     */
    protected $workImage;
    /**
     * @var resource
     */
    protected $newImage;
    /**
     * @var int
     */
    protected $originalWidth;
    /**
     * @var int
     */
    protected $originalHeight;
    /**
     * @var string
     */
    protected $thumbName;
    /**
     * @var int
     */
    protected $newWidth;
    /**
     * @var int
     */
    protected $newHeight;
    protected $startX;
    protected $startY;
    /**
     * @var float
     */
    protected $aspectRatio;
    /**
     * @var boolean
     */
    protected $keepAspectRatio;
    /**
     * @var int
     */
    protected $jpegQuality;

    /**
     * Construct of Koch_Image Core Class
     */
    public function __construct($source, $target)
    {
        $this->imageSource = $source;
        $this->imageName = basename($source);
        $this->imageTarget = $target;
        $this->imageExtension = $this->getImageExtension($this->imageName);
        $this->originalImage = $this->getOriginalImageResource();
        $this->originalWidth = imagesx($this->originalImage);
        $this->originalHeight = imagesy($this->originalImage);
    }

    public function newCrop($config)
    {
        Koch_Crop::__construct($config);
    }

    public function newWatermarkImage($config)
    {
        Koch_Watermark::__construct('image', $config);
    }

    public function newWatermarkText($config)
    {
        Koch_Watermark::__construct('text', $config);
    }

    public function newThumbnail($config)
    {
        new Koch_Thumbnail($config, $this);
    }

    /**
     * Overwrites exist Image/File Name
     *
     * @param String $name
     */
    public function newName($name)
    {
        $this->imageName = $name . '.' . strtolower($this->imageExtension);
    }

    /**
     * Get the extension of given image
     *
     * @return string
     */
    private function getImageExtension($filename)
    {
        return pathinfo($filename, PATHINFO_EXTENSION);
    }

    /**
     * 	Initialize Original Image Resource
     *
     * @return resource
     */
    protected function getOriginalImageResource()
    {
        $method = 'createImageFrom' . $this->imageExtension;

        return $this->$method($this->imageSource);
    }

    private function createImageFromGIF($source)
    {
        return imagecreatefromgif($source);
    }

    private function createImageFromJPEG($source)
    {
        return imagecreatefromjpeg($source);
    }

    private function createImageFromPNG($source)
    {
        return imagecreatefrompng($source);
    }

    public function getWorkImageResource($width, $height)
    {
        if (function_exists("ImageCreateTrueColor")) {
            return imagecreatetruecolor($width, $height);
        } else {
            return imagecreate($width, $height);
        }
    }

    public function resample()
    {

        imagecopyresampled(
                $this->workImage, $this->originalImage, 0, 0, $this->startX, $this->startY, $this->newWidth, $this->newHeight, $this->originalWidth, $this->originalHeight
        );

        $this->startX = 0;
        $this->startY = 0;
        $this->originalWidth = $this->newWidth;
        $this->originalHeight = $this->newHeight;
        $this->originalImage = $this->workImage;
        $this->newImage = $this->workImage;
    }

    public function save()
    {
        $method = 'save' . $this->imageExtension;
        $this->$method();
    }

    private function saveGIF()
    {
        imagegif($this->newImage, $this->thumbName . $this->imageName);
    }

    private function saveJPEG()
    {
        imagejpeg($this->newImage, $this->thumbName . $this->imageName, $this->jpegQuality);
    }

    private function savePNG()
    {
        imagejpeg($this->newImage, $this->thumbName . $this->imageName);
    }

    public function __destruct()
    {
        imagedestroy($this->originalImage);
    }

}
