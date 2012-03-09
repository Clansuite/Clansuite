<?php
   /**
    * Koch Framework
    * Jens-Andr� Koch � 2005 - onwards
    * http://www.clansuite.com/
    *
    * This file is part of "Koch Framework".
    *
    * LICENSE:
    *
    *    This program is free software; you can redistribute it and/or modify
    *    it under the terms of the GNU General Public License as published by
    *    the Free Software Foundation; either version 2 of the License, or
    *    (at your option) any later version.
    *
    *    This program is distributed in the hope that it will be useful,
    *    but WITHOUT ANY WARRANTY; without even the implied warranty of
    *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    *    GNU General Public License for more details.
    *
    *    You should have received a copy of the GNU General Public License
    *    along with this program; if not, write to the Free Software
    *    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
    *
    * @license    GNU/GPL v2 or (at your option) any later version, see "/doc/LICENSE".
    * @author     Jens-Andr� Koch <vain@clansuite.com>
    * @copyright  Jens-Andr� Koch (2005 - onwards)
    * @link       http://www.clansuite.com
    *
    * @version    SVN: $Id$
    */

//Security Handler
if(defined('IN_CS') === false)
{
    die('Koch Framework not loaded. Direct Access forbidden.');
}

namespace Koch;

/**
 * Koch Framework Class for Image Handling
 *
 * @author     Daniel Winterfeldt
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
        if(function_exists("ImageCreateTrueColor"))
        {
            return imagecreatetruecolor($width, $height);
        }
        else
        {
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

/**
 * Koch Framework Class for Image Watermarking
 *
 * @author     Daniel Winterfeldt
 *
 * @package     Koch
 * @subpackage  Core
 * @category    Image
 */
class Koch_Watermark extends Koch_Image
{

    public function __construct($function, $config)
    {
        if($function == 'image')
        {
            $watermark = imagecreatefrompng($config['file']);

            imagecopy($this->workImage, $watermark, $config['pos_x'], $config['pos_y'], 0, 0, imagesx($watermark), imagesy($watermark)
            );
        }
    }

}

/**
 * Koch Framework Class for Image Thumbnailing
 *
 * @author     Daniel Winterfeldt
 *
 * @package     Koch
 * @subpackage  Core
 * @category    Image
 */
class Koch_Thumbnail extends Koch_Image
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
        if($this->object->newWidth != 0)
        {
            $ratio = $this->object->originalWidth / $this->object->newWidth;
            $this->object->newHeight = ((int) round($this->object->originalHeight / $ratio));
            return $ratio;
        }

        if($this->object->newHeight != 0)
        {
            $ratio = $this->object->originalHeight / $this->object->newHeight;
            $this->object->newWidth((int) round($this->object->originalWidth / $ratio));
            return $ratio;
        }
    }

}

/**
 * Koch Framework Class for Image Cropping
 *
 * @author     Daniel Winterfeldt
 *
 * @package     Koch
 * @subpackage  Core
 * @category    Image
 */
class Koch_Crop extends Koch_Image
{

    public function __construct($config)
    {
        $this->startX = $config['start_x'];
        $this->startY = $config['start_y'];
        $this->newWidth = $config['width'];
        $this->newHeight = $config['height'];
        $this->originalWidth = $config['width'];
        $this->originalHeight = $config['height'];
        $this->jpegQuality = 100;

        $this->workImage = $this->getWorkImageResource($this->newWidth, $this->newHeight);
    }

}

?>