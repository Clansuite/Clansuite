<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-Andre Koch � 2005 - onwards
    * http://www.clansuite.com/
    *
    * This file is part of Clansuite - just an eSports CMS
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
    * @author     Jens-Andre Koch <vain@clansuite.com>
    * @copyright  Copyleft: All rights reserved. Jens-Andre Koch (2005-onwards)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    * @since      File available since Release 0.2
    *
    * @version    SVN: $Id$
    */

/**
 * Clansuite_Gravatar
 *
 * This is a service class for accessing the Globally Recognized Avatars as provided
 * by http://www.gravatar.com.
 *
 * I give credits and thanks to the following classes, discussions and hints:
 *
 * 1) Gravatar Implementation Infos:
 *    http://en.gravatar.com/site/implement/php &  http://en.gravatar.com/site/implement/url
 *
 * 2) TalkPHP :
 *    http://www.talkphp.com/script-giveaway/1905-gravatar-wrapper-class.html
 *
 * @author      Jens-Andre Koch <vain@clansuite.com>
 * @license     GNU/GPL v2 or any later license
 * @copyright   Copyleft: All rights reserved. Jens-Andre Koch (2005-onwards)
 *
 * @package     Clansuite
 * @subpackage  Libraries
 */
class clansuite_gravatar
{
    # Gravatar BASE URL
    private $GRAVATAR_BASEURL = 'http://www.gravatar.com/avatar/%s?&size=%s&rating=%s&default=%s';

    # Gravatar Ratings
    private $GRAVATAR_RATINGS = array("g", "pg", "r", "x");

    # Gravatar Properties
    protected $GRAVATAR_PROPERTIES = array(
        "gravatar_id"	=> null,      // = md5(email)
        "default"		=> null,      // default avatar
        "size"			=> 80,        // default value = 80
        "rating"		=> null,      // rating
    );

    # Email
    $this->email = '';

    # Turn on/off the use of cached Gravatars
    $this->useCaching = true;

    /**
     *  Constructor
     */
    public function __construct($email = null, $rating = null, $size = null, $default = null, $nocaching = false)
    {
        $this->setEmail($email);
        $this->setRating($rating);
        $this->setSize($size);
        $this->setDefault($default);
        if(true == $nocaching )
        {
            $this->disableCaching();
        }
    }

    /**
     *  setEmail
     *  1. convert email to lowercase
     *  2. set email to class
     *  3. set md5 of email as gravatar_id
     *
     * @param $email
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = (string)strtolower($email);
        $this->GRAVATAR_PROPERTIES['gravatar_id'] = md5($this->email);
        return $this;
    }

    /**
     * setRating
     * $rating has to be one of the predefined elements from GRAVATAR_RATINGS array
     * otherwise it sets the rating to default [g]
     *
     * @param $rating
     * @return $this
     */
    public function setRating($rating = 'g')
    {
        if (in_array($rating, $this->GRAVATAR_RATINGS))
        {
            $this->GRAVATAR_PROPERTIES['rating'] = strtolower($rating);
        }
        return $this;
    }

    /**
     *  setDefaultAvatar
     *
     *  sets a default avatar image
     */
    public function setDefaultAvatar($image_url)
    {
        $this->GRAVATAR_PROPERTIES['default'] = (string)urlencode($image_url);
        return $this;
    }

    /**
     *  setSize
     *  Maximum Size of Gravatar is 512.
     *  This will make sure you set it between 16 and 512.
     *  If not Gravatar will return a size of 80 as default value.
     * @param $size
     * @return $this
     */
    public function setSize($size)
    {
        $s = (int)$size;
        if ($s > 16 && $s < 512)
        {
            $this->size = $s;
        }
        return $this;
    }

    /**
     *  If you don't want to use cached gravatars, disable it.
     */
    public function disableCache()
    {
        $this->useCaching = false;
        return $this;
    }

    /**
     *  Construct a valid Gravatar URL
     */
    public function getGravatarURL()
    {
        $gravatar_url = (string)sprintf($this->GRAVATAR_BASEURL,
                                        $this->GRAVATAR_PROPERTIES['gravatar_id'],
                                        $this->GRAVATAR_PROPERTIES['size'],
                                        $this->GRAVATAR_PROPERTIES['rating'],
                                        $this->GRAVATAR_PROPERTIES['default']);
        return $gravatar_url;
    }

    /**
     *  Constructs and output's the complete gravatar <img /> html-tag
     */
    public function getHTML()
    {
        # init html string variable
        $html  = '';

        # check for caching and construct html from cached gravatar url
        if(true == $this->useCaching)
        {
            # initialize cache class
            $cache = new clansuite_gravatar_cache($this->getGravatarURL(),
                                                  $this->GRAVATAR_PROPERTIES['size'],
                                                  $this->GRAVATAR_PROPERTIES['rating'],
                                                  $this->GRAVATAR_PROPERTIES['default']);
            # getGravatar URL from cache
            $html .= '<img src="'. $cache->getGravatar() .'"';
        }
        else
        {
            # construct html for non-cached gravatar
            $html .= '<img src="'. $this->getGravatarURL() .'"';

            # add additional width and height
            if(isset($this->GRAVATAR_PROPERTIES['size']))
            {
                $html .= ' width="'.$this->GRAVATAR_PROPERTIES['size'].'"';
                $html .= ' height="'.$this->GRAVATAR_PROPERTIES['size'].'"';
            }
        }

        # add alt and title tags on both (cached, non-cached) html
        $html .= ' alt="Gravatar for '.$this->email.'" title="Gravatar for '.$this->email.'" />';

        return $html;
    }

    /**
     *  toString
     */
    public function __toString()
    {
        return $this->getHTML();
    }
}

/**
 * Clansuite_Gravatar_Cache
 *
 * This is a service class for accessing cached Gravatars as provided
 * by http://www.gravatar.com.
 *
 * @author      Jens-Andre Koch <vain@clansuite.com>
 * @license     GNU/GPL v2 or any later license
 * @copyright   Copyleft: All rights reserved. Jens-Andre Koch (2005-onwards)
 *
 * @package     Clansuite
 * @subpackage  Libraries
 */
class clansuite_gravatar_cache
{
    $this->cache_location       = WWW_ROOT . '/uploads/avatars';
    $this->gravatar_cache_url   = '/gravatar_cache/%s-%s-%s.jpg';
    $this->cache_expire_time    = '7 days';
    $this->cacheable            = false;
    $this->gravatar_url         = null;
    $this->gravatar_id          = null;
    $this->size                 = null;
    $this->rating               = null;

    function __construct( $gravatar_url, $gravatar_id, $size, $rating)
    {
        $this->gravatar_url = $gravatar_url;
        $this->gravatar_id  = $gravatar_id;
        $this->size         = $size;
        $this->rating       = $rating;
    }

    /**
     * Set URL to the /gravatar_cache folder.
     * Cache might be located on another webserver.
     */
    public function setCacheLocation($path);
    {
        $this->cache_location = $path;
    }

    /**
     * Caching is possible, when we can
     * url_fopen the gravatar.com URL to download from there
     */
    public function checkIfCachable()
    {
        if (1 == ini_get("allow_url_fopen") )
        {
            $this->cacheable = true;
        }
        return $this->cacheable;
    }

    /**
     * gets a gravatar cache url
     */
    public function getGravatar()
    {
        $cache_filename  = '';
        $cache_filename .= $this->cache_location;
        $cache_filename .= (string)sprintf($this->gravatar_cache_location,
                                           $this->gravatar_id,
                                           $this->size,
                                           $this->rating);

        if (is_file($cache_filename) && (filemtime($cache_filename) > strtotime('-' . $this->cache_expire_time)))
        {
            return $cache_filename;
        }
        else
        {
            $this->setGravatar($cache_filename, $this->gravatar_url)
        }
    }

    /**
     * sets the specified gravatar at $gravatar_url to the $cache_filename
     */
    public function setGravatar($cache_filename, $gravatar_url)
    {
        # Check if caching is possible
        if($this->checkIfCachable() == true)
        {
            # Save the Cache File
            file_put_contents($cache_filename, file_get_contents($gravatar_url));

            # Check if Cache file was created
            if (is_file($cache_filename))
            {
                return $cache_filename;
            }
            else
            {
                # passthrough the original URL
                return $gravatar_url;
            }
        }
        else
        {
             # caching was not possible due to lack of url_fopen
             # passthrough the original URL
             return $gravatar_url;
        }
    }
}

/**
 * Clansuite_Gravatar
 *
 * This is a service class for signing up to Globally Recognized Avatars as provided
 * by http://www.gravatar.com.
 *
 * I give credits and thanks to the following classes, discussions and hints:
 *
 * Wordpress Plugin: Gravatar Signup v1.6.3
 * http://txfx.net/code/wordpress/gravatar-signup/
 *
 * @author      Jens-Andre Koch <vain@clansuite.com>
 * @license     GNU/GPL v2 or any later license
 * @copyright   Copyleft: All rights reserved. Jens-Andre Koch (2005-onwards)
 *
 * @package     Clansuite
 * @subpackage  Libraries
 */
class clansuite_gravatar_signup
{
}
?>