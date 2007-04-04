<?php
   /**
    * Clansuite - just an E-Sport CMS
    * Jens-Andre Koch, Florian Wolf © 2005-2007
    * http://www.clansuite.com/
    *
    * File:         image.class.php
    * Requires:     PHP 5.1.4+
    *
    * Purpose:      Clansuite Core Class for Image resizing/watermarks
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
    * @license    GNU/GPL, see COPYING.txt
    *
    * @author     Jens-Andre Koch   <vain@clansuite.com>
    * @author     Florian Wolf      <xsign.dll@clansuite.com>
    * @copyright  Jens-Andre Koch (2005-$LastChangedDate$), Florian Wolf (2006-$LastChangedDate$)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    * @since      File available since Release 0.1
    *
    * @version    SVN: $Id$
    */

/**
 * Security Handler
 */
if (!defined('IN_CS')){ die('You are not allowed to view this page.' );}


/**
 * This Clansuite Core Class for image resizing
 *
 * @author     Jens-Andre Koch   <vain@clansuite.com>
 * @author     Florian Wolf      <xsign.dll@clansuite.com>
 * @copyright  Jens-Andre Koch (2005-$LastChangedDate$), Florian Wolf (2006-$LastChangedDate$)
 * @since      Class available since Release 0.1
 *
 * @package     clansuite
 * @category    core
 * @subpackage  image
 */
class image
{
	private $image = '';
	private $temp = '';

	/**
	* Constructor
	*
	* @return
	*/
	function __construct($sourceFile)
    {
		if(file_exists($sourceFile))
        {
			$this->image = ImageCreateFromJPEG($sourceFile);
		}
        else
        {
			$this->errorHandler();
		}
		return;
	}

	/**
	* Resize an image
	*
	* @return
	*/
	function resize($width = 100, $height = 100, $aspectradio = true)
    {
		$o_wd = imagesx($this->image);
		$o_ht = imagesy($this->image);
		if(isset($aspectradio)&&$aspectradio)
        {
			$w = round($o_wd * $height / $o_ht);
			$h = round($o_ht * $width / $o_wd);
			if(($height-$h)<($width-$w))
            {
				$width =& $w;
			}
            else
            {
				$height =& $h;
			}
		}
		$this->temp = imageCreateTrueColor($width,$height);
		imageCopyResampled($this->temp, $this->image,
		0, 0, 0, 0, $width, $height, $o_wd, $o_ht);
		$this->sync();
		return;
	}

	/**
	* Clean up - syncing
	*
	* @return
	*/
    function sync()
    {
		$this->image =& $this->temp;
		unset($this->temp);
		$this->temp = '';
		return;
	}

	/**
	* Show the image
	*
	* @return
	*/
	function show()
    {
		$this->_sendHeader();
		ImageJPEG($this->image);
		return;
	}

	/**
	* Throw error
	*
	* @global $error
	*/
	function errorHandler()
    {
        global $error;

        $error->show('Image failure', 'The image you have requested cannot be shown', 1, 'index.php');
	}

	/**
	* Store the image
	*
	* @return
	*/
	function store($file)
    {
		ImageJPEG($this->image,$file);
		return;
	}

	/**
	* Give a watermark upon the image
	*/
    function watermark($pngImage, $left = 0, $top = 0)
    {
		ImageAlphaBlending($this->image, true);
		$layer = ImageCreateFromPNG($pngImage);
		$logoW = ImageSX($layer);
		$logoH = ImageSY($layer);
		ImageCopy($this->image, $layer, $left, $top, 0, 0, $logoW, $logoH);
	}

	/**
	* Parse header
	*/
	private function _sendHeader()
    {
		header('Content-Type: image/jpeg');
	}
}
?>