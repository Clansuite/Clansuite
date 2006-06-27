<?php
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
 */
/**
 *  @package <b> Easy Captcha Class </b>
 *
 *  @author Jens-André Koch <jakoch@web.de>
 *  @copyright (c) Jens-André Koch, 2006
 *
 *  License: Lesser General Public License - LGPL
 *
 *  This software is free software; you can redistribute it and/or
 *  modify it under the terms of the GNU Lesser General Public
 *  License as published by the Free Software Foundation; either
 *  version 2.1 of the License, or (at your option) any later version.
 *
 *  This software is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 *  Lesser General Public License for more details. 
 *  {@link http://www.gnu.org/licenses/lgpl.html}
 *
 *  @version $Id: captcha.class.php 128 2006-06-09 12:07:21Z vain $
 */
class captcha {
  
  	var $strlength = '4';
	
    // image settings
    var $img_height = 50;
    var $img_width = 200;
    // font settings
    var $font_site = 30;
    var $font_heigth = 45;
    var $font = '../templates/core/fonts/Vera.ttf';
    // graphic-effects
    var $waves = false;
    // type of captcha
    var $captcha_type = array('img','number-2-ascii','logic');

function gd_version() {
   static $gd_version_number = null;
   if ($gd_version_number === null) {
       ob_start(); phpinfo(8);
       $module_info = ob_get_contents();
       ob_end_clean();
       if (preg_match("/\bgd\s+version\b[^\d\n\r]+?([\d\.]+)/i", 
	    $module_info,$matches)) { $gd_version_number = $matches[1];} 
	   else {   $gd_version_number = 0; }
	   }
   return $gd_version_number;
}

/**
 * Init Class, init function  -> generate_image();
 */
function captcha() { $this->generate_Image(); 
}

/**
* get a random captcha string by size (@link $strlength)
*
* @param int $strlength
* @return $captchastr
*/
function randomString($strlength)	{ 
		/** 
		 * Exclusion of Characters
		 * (@link http://www.lookuptables.com/ ASCII Chars Lookuptable)
		 * 
		 * Excluded-Chars: 0, 1, 7, I, O
		 */
		$excludeChars = array(48, 49, 55, 73, 79); 
		
		$captchastr = '';
		while(strlen($captchastr) < $strlength){
			$random=rand(48,122);
			if(!in_array($random, $excludeChars) &&
				( ($random >= 50 && $random <= 57)   // ASCII 48->57: numbers 0-9
				| ($random >= 65 && $random <= 90))  // ASCII 65->90: A-Z
				| ($random >= 97 && $random <= 122)  // ASCII 97->122: a-z
				){
				$captchastr.=chr($random);
			}
		}
		return $captchastr;
	}


/**
* generate the captcha image
*
* @return image!
*/
function generate_image(){
// random captcha string
$strlength = rand(3,5);
$captchastr = $this->randomString($strlength);

// set string to session 
session_start();
$_SESSION['captchastr'] = $captchastr;

// send headers
header('Expires: Mon, 01 Jan 1997 05:00:00 GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');
header('Easy-Captcha: by Jens-André Koch');

//initialize image $captcha with dimensions from $img_width, $img_heigth
if ($this->gd_version() >= 2) {
   $captcha = @imagecreatetruecolor($this->img_width, $this->img_height)
   or die("Cannot Initialize new GD image stream");
} else {
   $captcha = ImageCreate($this->img_width, $this->img_height)
   	or die("Cannot Initialize new GD image stream");
}

// create textcolor from random RGB colors
$text_color = imageColorAllocate($captcha, rand(50,240), rand(50,240), rand(0,255));


// switch between captcha types 
switch ($captchatype_randomizer = rand(1,2)) 
{
case 1:  // viel stuff @ image   
    
	// create backgroundcolor from random RGB colors
    $background_color = imagecolorallocate($captcha, rand(100, 255), rand(100, 255), rand(0, 255));

    /**
    * Background Fill Effects
    */
    switch ($background_randomizer = rand(1,2)) {
    case 1:   // Solid 
       imagefill ( $captcha, 0, 0, $background_color );
	   break;
    case 2:   // Gradient
       for($i = 0, $rd = rand(0, 100), $gr = rand(0, 100), $bl= rand(0, 100); $i <= $this->img_height; $i++){
       		  $g = @imagecolorallocate($captcha, $rd+=2, $gr+=2, $bl+=2);
       		  @imageline($captcha, 0, $i, $this->img_width, $i, $g);
       		  }
       break;
    }		

    // add noise 
    for($i=1; $i<=4;$i++)
    {
    imageellipse($captcha,rand(1,200),rand(1,50),rand(50,100),rand(12,25),$text_color);
    }
    for($i=1; $i<=4;$i++)
    {
    imageellipse($captcha,rand(1,200),rand(1,50),rand(50,100),rand(12,25),$background_color);
    }


    // loop through $captchastr and apply random font-effect to every char
    for($i=0;$i<=$strlength;$i++)
    {    
	/**
     * Font Rotation Effect
     */
    switch ($rotation_randomizer = rand(1,2)) 	{
    case 1: $rotangle = rand(0,15);  break; 	// Clock-Rotation
    case 2: $rotangle = rand(345,360); break; 	// Counter-Rotation
    } 
    
    // string ausgeben ! 
	// imagettftext( resource image, float size, float angle, int x, int y, int color, string fontfile, string text )
	// $i*25 = spaces the characters 25 pixels apart
	// todo : substr durch $char =  ersetzen
	imagettftext($captcha,$fontsize = rand(16,32),$rotangle,15+($i*25),30,$text_color+($i*12),$this->font,$captchastr[$i]);
    }
    // interlacen
	#$this->interlace($captcha);
    
    // rotation
    #if (function_exists('imagerotate')) {
    #$im2 = imagerotate($captcha,rand(-20,30),$background_color);
    # imagedestroy($captcha);
    # $captcha = $im2;
	# }
break;

case '2': // wenig stuff @ image 
     // background white
	 $white = ImageColorAllocate ($captcha, 255, 255, 255);
	 imagefill ($captcha, 1, 1, $white );
      
	  // loop through $captchastr and apply random font-effect to every char
      for($i=0;$i<=$strlength;$i++)
      {  
      imagettftext($captcha,rand(28,35),rand(-5,5),25+($i*17),38,$text_color,$this->font,$captchastr[$i]);
	  }
	  break;
} // switch ($captchatype_randomizer)

// DEBUG : print $captchastr with $text_color
ImageString ($captcha,2, 1, 35, "DEBUG :".$captchastr, $text_color);

/**
 * Final: Render Image! & Free Memory.
 */
ImagePNG ($captcha);
imageDestroy($captcha);
}

/**
 * Interlaces a Image ( every 2th line is blacked )
 */

function interlace (&$image) {
    $imagex = imagesx($image);
    $imagey = imagesy($image);
    $black = imagecolorallocate($image, 255, 255, 255);
    for ($y = 0; $y < $imagey; $y += 2) {
        imageline($image, 0, $y, $imagex, $y, $black);
    }
} 

// remove old captcha images
function remove_old(){
}

// generate a ascii captcha from random numbers
function generate_number_to_ascii(){
}

// generate a logic captcha, based on random calculations
function generate_logic(){
}

}

$captcha_image = new captcha;

?>