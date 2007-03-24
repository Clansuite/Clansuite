<?php
   /**
    * Clansuite - just an E-Sport CMS
    * Jens-Andre Koch, Florian Wolf © 2005-2007
    * http://www.clansuite.com/
    *
    * File:         captcha.class.php
    * Requires:     PHP 5.1.4+
    *
    * Purpose:      Clansuite Core Class for Easy Captcha Handling
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
    * @copyright  Jens-Andre Koch (2005-$LastChangedDate$), Florian Wolf (2006-2007)
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
 * Easy Captcha Class
 *
 * A Captcha is a acronym for "Completely Automated Public Turing-Test to
 * Tell Computers and Humans Apart". It is type of a challenge-response test
 * to determine whether or not the user is human.
 *
 * The Purpose of Captcha's is to prevent bots from using various types of
 * computing services, like posting to guestbooks, boards or register email
 * accounts. The bot-generated spam could be reduced by requiring that the
 * (unrecognized) sender passes that CAPTCHA test before the service is delivered.
 *
 * Remember: Captchas are deafeatable!
 * It's a matter of artificial intelligence and pattern recognition.
 * {@link http://www.cs.sfu.ca/~mori/research/gimpy/ Greg Mori's - Breaking Captchas}
 * {@link http://www.pwntcha.net/test.html PwnTcha : Test the Captcha - Strength}
 * {@link http://captcha.megaleecher.net/ Megaleecher Captcha Kill Pro Class}
 *
 * Clansuite Core Class - Easy Captcha Class
 *
 * @author     Jens-Andre Koch <vain@clansuite.com>
 * @copyright  Jens-Andre Koch (2006-$LastChangedDate$)
 * @since      Class available since Release 0.1
 *
 * @package     clansuite
 * @category    core
 * @subpackage  captcha
 */
class captcha
{    
    /**
     * @var integer
     * @todo comment variables and rest of file
     */
    public  $str_length = 4;
    
    // image settings
    public  $img_height = 40;
    public  $img_width = 140;
    // font settings
    public  $font = '/fonts/Vera.ttf';
    
    /**
     * CONSTRUCTOR
     */

    function __construct()
    {
        $this->generate_Image();
    }
    
    /**
     * Get GD Version
     */

    function gd_version()
    {
        static $gd_version_number = null;
        
        if ($gd_version_number === null)
        {
            ob_start();
            phpinfo(8);
            $module_info = ob_get_contents();
            ob_end_clean();
            if (preg_match("/\bgd\s+version\b[^\d\n\r]+?([\d\.]+)/i",
            $module_info,$matches))
            {
                $gd_version_number = $matches[1];
            }
            else
            {
                $gd_version_number = 0;
            }
        }
        return $gd_version_number;
    }
    
    /**
     * Get a random captcha string by size (@link $strlength)
     */

    function randomString($str_length)
    {
        /**
         * Exclusion of characters
         * Excluded-Chars: 0, 1, 7, I, O
         */

        $excludeChars = array(48, 49, 55, 73, 79);
        
        $captcha_str = '';
        while (strlen($captcha_str) < $str_length)
        {
            $random=rand(48,122);
            if (!in_array($random, $excludeChars) &&
            ( ($random >= 50 && $random <= 57)   // ASCII 48->57: numbers 0-9
            | ($random >= 65 && $random <= 90))  // ASCII 65->90: A-Z
            | ($random >= 97 && $random <= 122)  // ASCII 97->122: a-z
            )
            {
                $captcha_str.=chr($random);
            }
        }
        return $captcha_str;
    }
    
    
    /**
     * Generate the image
     */

    function generate_image()
    {
        // random captcha string
        $str_length = rand(3,5);
        $captcha_str = $this->randomString($str_length);
        
        // set string to session
        $_SESSION['captcha_string'] = $captcha_str;
        
        // send headers
        header('Expires: Mon, 01 Jan 1997 05:00:00 GMT');
        header('Cache-Control: no-store, no-cache, must-revalidate');
        header('Cache-Control: post-check=0, pre-check=0', false);
        header('Pragma: no-cache');
        header('Content-Type: image/jpeg');
        header('Easy-Captcha: by Jens-André Koch');
        
        //initialize image $captcha with dimensions from $img_width, $img_heigth
        if ($this->gd_version() >= 2)
        {
            $captcha = @imagecreatetruecolor($this->img_width, $this->img_height)
                or die("Cannot Initialize new GD image stream");
        }
        else
        {
            $captcha = ImageCreate($this->img_width, $this->img_height)
                or die("Cannot Initialize new GD image stream");
        }
        
        // create textcolor from random RGB colors
        $text_color = imageColorAllocate($captcha, rand(50,240), rand(50,240), rand(0,255));
        
        
        // switch between captcha types
        switch ($captchatype_randomizer = rand(1,2))
        {
            case 1:
            // create backgroundcolor from random RGB colors
                $background_color = imagecolorallocate($captcha, rand(100, 255), rand(100, 255), rand(0, 255));
            
            /**
            * Background Fill Effects
            */
            switch ($background_randomizer = rand(1,2))
            {
                case 1:
                    // Solid
                    imagefill($captcha, 0, 0, $background_color );
                    break;
                case 2:
                    // Gradient
                    for ($i = 0, $rd = rand(0, 100), $gr = rand(0, 100), $bl= rand(0, 100); $i <= $this->img_height; $i++)
                    {
                        $g = @imagecolorallocate($captcha, $rd+=2, $gr+=2, $bl+=2);
                        @imageline($captcha, 0, $i, $this->img_width, $i, $g);
                    }
                break;
            }
            
            // add noise
            for ($i=1; $i<=4; $i++)
            {
                imageellipse($captcha,rand(1,200),rand(1,50),rand(50,100),rand(12,25),$text_color);
            }
            for ($i=1; $i<=4; $i++)
            {
                imageellipse($captcha,rand(1,200),rand(1,50),rand(50,100),rand(12,25),$background_color);
            }
            
            
            // loop through $captcha_str and apply random font-effect to every char
            for ($i=0; $i<=$str_length; $i++)
            {
                /**
                * Font Rotation Effect
                */
                switch ($rotation_randomizer = rand(1,2))
                {
                case 1:
                    $rotangle = rand(0,15);
                    break;
                    // Clock-Rotation
                case 2:
                    $rotangle = rand(345,360);
                    break;
                    // Counter-Rotation
                }
                
                // string ausgeben !
                // imagettftext( resource image, float size, float angle, int x, int y, int color, string fontfile, string text )
                // $i*25 = spaces the characters 25 pixels apart
                // todo : substr durch $char =  ersetzen
                imagettftext($captcha,$fontsize = rand(16,32),$rotangle,15+($i*25),30,$text_color+($i*12),$this->font,$captcha_str[$i]);
            }
            // interlacen
            #$this->interlace($captcha);
            
            // rotation
            #if(function_exists('imagerotate')) {
                #$im2 = imagerotate($captcha,rand(-20,30),$background_color);
                # imagedestroy($captcha);
                # $captcha = $im2;
            # }
            break;
            
        case '2':
            // wenig stuff @ image
            // background white
            $white = ImageColorAllocate($captcha, 255, 255, 255);
            imagefill($captcha, 1, 1, $white );
            
            // loop through $captcha_str and apply random font-effect to every char
            for ($i=0; $i<=$str_length; $i++)
            {
                imagettftext($captcha,rand(28,35),rand(-5,5),25+($i*17),38,$text_color,$this->font,$captcha_str[$i]);
            }
            break;
        }

        /**
         * Final: Render Image! & Free Memory.
         */

        ImagePNG($captcha);
        imageDestroy($captcha);
    }

    /**
     * Interlaces a Image ( every 2th line is blacked )
     */

    function interlace(&$image)
    {
        $imagex = imagesx($image);
        $imagey = imagesy($image);
        $black = imagecolorallocate($image, 255, 255, 255);
        for ($y = 0; $y < $imagey; $y += 2)
        {
            imageline($image, 0, $y, $imagex, $y, $black);
        }
    } 
}

/**
 *
 * @todo: init class at the end! find a better way?
 */
$captcha = new captcha;

?>