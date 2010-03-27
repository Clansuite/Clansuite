<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-André Koch © 2005 - onwards
    * http://www.clansuite.com/
    *
    * This file is part of "Clansuite - just an eSports CMS".
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
    *
    * @author     Jens-André Koch   <vain@clansuite.com>
    * @copyright  Copyleft: All rights reserved. Jens-André Koch (2005-onwards)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    *
    * @version    SVN: $Id$
    */

# Security Handler
if (defined('IN_CS') == false){ die('Clansuite not loaded. Direct Access forbidden.');}

if (!class_exists('Clansuite_Formelement',false)) { require ROOT_CORE . 'viewhelper/formelement.core.php'; }

/**
 *  Clansuite_Formelement
 *  |
 *  \- Clansuite_Formelement_Captcha
 *      |
 *      \- Clansuite_Formelement_SimpleCaptcha
 */
class Clansuite_Formelement_SimpleCaptcha extends Clansuite_Formelement_Captcha implements Clansuite_Formelement_Interface
{
    public function __construct()
    {
        # Load Recaptcha Library
        # require_once( ROOT_LIBRARIES ... );
    }

    /**
     * display captcha
     */
    public function render()
    {
        $captcha = new Clansuite_JustAn_Captcha();
        return $captcha->generateCaptchaImage();
    }

    /**
     * validate captcha
     *
     * In the code that processes the form submission, you need to add code to validate the CAPTCHA.
     */
    public function validate()
    {
        # @todo comparision of form input with session string
        # $_SESSION['simple_captcha_string']
    }
}

/**
 * Just a simple Captcha Class
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
 * @link http://www.cs.sfu.ca/~mori/research/gimpy/ Greg Mori's - Breaking Captchas
 * @link http://www.pwntcha.net/test.html PwnTcha : Test the Captcha - Strength
 * @link http://captcha.megaleecher.net/ Megaleecher Captcha Kill Pro Class
 *
 * This file is part of "Clansuite - just an eSports CMS".
 *
 * @license    GNU/GPL v2 or (at your option) any later version, see "/doc/LICENSE".
 *
 * @author     Jens-André Koch   <vain@clansuite.com>
 * @copyright  Copyleft: All rights reserved. Jens-André Koch (2005-onwards)
 *
 * @link       http://www.clansuite.com
 * @link       http://gna.org/projects/clansuite
 *
 * @version    SVN: $Id$
 */
class Clansuite_JustAn_Captcha
{

    /**
     * @var int image height
     */
    public $image_height = 40;

    /**
     * @var int image width
     */
    public $image_width = 140;

    /**
     * @var array $fonts Several available Fonts
     */
    protected static $fonts = array( 'Vera.ttf' ); #, 'b.ttf', 'c.ttf);

    /*
     * @var string The selected font for the captcha
     */
    public $font;


    public function __construct()
    {
        /**
         * @todo randomize the font (add more to $fonts array)
         */
        $this->font = ROOT_THEMES . 'core/fonts/' . $this->fonts[0];
    }

    /**
     * Get GD Version
     * Each gd version provides different features. We need to determine which we can use.
     */
    public function gd_version()
    {
        static $gd_version_number = null;

        if ($gd_version_number === null)
        {
            # fetch phpinfo into buffer
            ob_start();
                phpinfo(8);
                $extension_info = ob_get_contents();
            ob_end_clean();

            # grab gd_version from buffer
            if (preg_match("/\bgd\s+version\b[^\d\n\r]+?([\d\.]+)/i", $extension_info, $matches))
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
     * Generates a random string in requested $string_length for usage with captcha
     *
     * @param $string_length The length of the captcha string.
     */
    function generateRandomString($string_length)
    {
        # Excluded-Chars: 0, 1, 7, I, O
        # why? because they are too simple to recognize even when effects applied upon.)
        $excludeChars = array(48, 49, 55, 73, 79);

        $captcha_string = '';

        while (strlen($captcha_string) < $string_length)
        {
            # a random char between 48 and 122
            $random = rand(48,122);

            # not the excluded chars and only special chars segments
            if (in_array($random, $excludeChars) == false and
            ( ($random >= 50 && $random <= 57)   # ASCII 48->57:  numbers   0-9
            | ($random >= 65 && $random <= 90))  # ASCII 65->90:  uppercase A-Z
            | ($random >= 97 && $random <= 122)  # ASCII 97->122: lowercase a-z
            )
            {
                # adds a random char to the string
                $captcha_string .= chr($random);
            }
        }

        return $captcha_string;
    }

    /**
     * Generates the captcha image
     */
    public function generateCaptchaImage()
    {
        # random captcha string
        $captcha_string = $this->generateRandomString(rand(3,5));

        # set string to session
        $_SESSION['simple_captcha_string'] = $captcha_string;

        # send headers
        header('Expires: Mon, 01 Jan 1997 05:00:00 GMT');
        header('Cache-Control: no-store, no-cache, must-revalidate');
        header('Cache-Control: post-check=0, pre-check=0', false);
        header('Pragma: no-cache');
        header('Content-Type: image/jpeg');

        # initialize image $captcha with dimensions from $img_width, $img_heigth
        if ($this->gd_version() >= 2)
        {
            $captcha = @imagecreatetruecolor($this->image_width, $this->image_height)
                or die("Cannot Initialize new GD image stream");
        }
        else
        {
            $captcha = ImageCreate($this->image_width, $this->image_height)
                or die("Cannot Initialize new GD image stream");
        }

        # create textcolor from random RGB colors
        $text_color = imageColorAllocate($captcha, rand(50,240), rand(50,240), rand(0,255));


        # switch between captcha types
        switch ($captchatype_randomizer = rand(1,2))
        {
            case 1: # captcha with some effects

                # create backgroundcolor from random RGB colors
                $background_color = imagecolorallocate($captcha, rand(100, 255), rand(100, 255), rand(0, 255));

            /**
             * Background Fill Effects
             */
            switch ($background_randomizer = rand(1,2))
            {
                case 1: # Solid Fill

                        imagefill($captcha, 0, 0, $background_color );
                    break;

                case 2: # Gradient Fill

                    for ($i = 0, $rd = rand(0, 100), $gr = rand(0, 100), $bl= rand(0, 100); $i <= $this->image_height; $i++)
                    {
                        $g = @imagecolorallocate($captcha, $rd+=2, $gr+=2, $bl+=2);
                        @imageline($captcha, 0, $i, $this->image_width, $i, $g);
                    }
                    break;
            }

            # add some noise
            for ($i=1; $i<=4; $i++)
            {
                imageellipse($captcha,rand(1,200),rand(1,50),rand(50,100),rand(12,25),$text_color);
            }
            for ($i=1; $i<=4; $i++)
            {
                imageellipse($captcha,rand(1,200),rand(1,50),rand(50,100),rand(12,25),$background_color);
            }


            # loop through $captcha_string by charwise and apply a random font-effect
            for ($i=0; $i<=$string_length; $i++)
            {
                /**
                * Font Rotation Effect
                */
                switch ($rotation_randomizer = rand(1,2))
                {
                    case 1: # Clock-Rotation

                        $rotangle = rand(0,15);
                        break;

                    case 2: # Counter-Rotation

                        $rotangle = rand(345,360);
                        break;
                }

                # add string to image
                imagettftext( $captcha, $fontsize = rand(16,32),
                              $rotangle,
                              15+($i*25), # $i*25 = spaces the characters 25 pixels apart
                              30, $text_color+($i*12),
                              $this->font,$captcha_string[$i]);
            }

            # add interlacing
            # $this->interlace($captcha);

            # add rotation
            /**
            if(function_exists('imagerotate'))
            {
                #$im2 = imagerotate($captcha,rand(-20,30),$background_color);
                # imagedestroy($captcha);
                # $captcha = $im2;
            }
            */

            break;

        case '2': # a very simple captcha

                # apply a white background
                $white = ImageColorAllocate($captcha, 255, 255, 255);
                imagefill($captcha, 1, 1, $white );

                # loop through $captcha_str and apply a random font-effect to every char
                for ($i=0; $i<=$string_length; $i++)
                {
                    imagettftext($captcha,rand(28,35),rand(-5,5),25+($i*17),38,$text_color,$this->font,$captcha_string[$i]);
                }
            break;
        }

        # Finally: Render image and free memory.
        ImagePNG($captcha);
        imageDestroy($captcha);
    }

    /**
     * Interlaces the Image
     * Interlacing means, that every 2th line is blacked or greyed out.
     *
     * @param $image The image to interlace.
     */
    private function interlace($image)
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
?>