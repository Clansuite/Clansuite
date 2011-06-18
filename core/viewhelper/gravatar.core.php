<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-André Koch © 2005 - onwards
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
    * @license    GNU/GPL v2 or (at your option) any later version, see "/doc/LICENSE".
    * @author     Jens-André Koch <vain@clansuite.com>
    * @copyright  Copyleft: All rights reserved. Jens-André Koch (2005-onwards)
    * @link       http://www.clansuite.com
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
 * @author      Jens-André Koch <vain@clansuite.com>
 * @license     GNU/GPL v2 or any later license
 * @copyright   Copyleft: All rights reserved. Jens-André Koch (2005-onwards)
 *
 * @package     Clansuite
 * @subpackage  Libraries
 */
class clansuite_gravatar
{
    # Gravatar BASE URL
    private $gravatar_baseurl = 'http://www.gravatar.com/avatar/%s?&size=%s&rating=%s&default=%s';

    # Gravatar Ratings
    private $gravatar_ratings = array("g", "pg", "r", "x");

    # Gravatar Properties
    protected $gravatar_properties = array(
        "gravatar_id"	=> null,      // = md5(email)
        "default"		=> null,      // default avatar
        "size"			=> 80,        // default value = 80
        "rating"		=> null,      // rating
    );

    # Email
    public $email = '';

    # Turn on/off the use of cached Gravatars
    public $useCaching = true;

    /**
     *  Constructor
     */
    public function __construct($email = null, $rating = null, $size = null, $default = null, $nocaching = false)
    {
        $this->setEmail($email);
        $this->setRating($rating);
        $this->setSize($size);
        $this->setDefaultAvatar($default);

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
        if (false === filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            throw new \InvalidArgumentException('Invalid value of $email: '.$email);
        }

        $this->email = (string) strtolower($email);

        $this->gravatar_properties['gravatar_id'] = md5($this->email);

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
        $rating = strtolower($rating);

        if (in_array($rating, $this->gravatar_ratings) === true)
        {
            $this->gravatar_properties['rating'] = $rating;
        }
        else
        {
             $this->gravatar_properties['rating'] = 'g';
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
        $this->gravatar_properties['default'] = (string) urlencode($image_url);

        return $this;
    }

    /**
     *  setSize
     *
     *  Th maximum size for a Gravatar is 512px.
     *  This will make sure you set it between 16px and 512px.
     *  If not, Gravatar will return a size of 80px as default value.
     *
     * @param $size
     * @return $this
     */
    public function setSize($size)
    {
        $size = (int) $size;

        /*
        if(is_numeric($size) === false)
        {
            throw new \UnexpectedValueException('Value of $size must be numeric, is: '. $size);
        }

        if($size < 16 || $size > 512)
        {
            throw new \OutOfRangeException('Value of $size should be between 16 and 512 (size of image in pixels). value given: '.$size);
        }
        */

        $this->size = $size;

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
        $gravatar_url = (string) sprintf($this->gravatar_baseurl,
                                         $this->gravatar_properties['gravatar_id'],
                                         $this->gravatar_properties['size'],
                                         $this->gravatar_properties['rating'],
                                         $this->gravatar_properties['default']);
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
                                                  $this->gravatar_properties['gravatar_id'],
                                                  $this->gravatar_properties['size'],
                                                  $this->gravatar_properties['rating']);
            # getGravatar URL from cache
            $html .= '<img src="'. $cache->getGravatar() .'"';
        }
        else
        {
            # construct html for non-cached gravatar
            $html .= '<img src="'. $this->getGravatarURL() .'"';

            # add additional width and height
            if(isset($this->gravatar_properties['size']) === true)
            {
                $html .= ' width="'.$this->gravatar_properties['size'].'"';
                $html .= ' height="'.$this->gravatar_properties['size'].'"';
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
 * @author      Jens-André Koch <vain@clansuite.com>
 * @license     GNU/GPL v2 or any later license
 * @copyright   Copyleft: All rights reserved. Jens-André Koch (2005-onwards)
 *
 * @package     Clansuite
 * @subpackage  Libraries
 */
class clansuite_gravatar_cache
{
    # Gravatar Cache Settings
    public $cache_location       = 'uploads/images';
    public $gravatar_cache_url   = '/gravatar_cache/%s-%s-%s.png';
    public $cache_expire_time    = '7 days';
    public $cacheable            = true;

    # Gravatar Attributes
    public $gravatar_url         = null;
    public $gravatar_id          = null;
    public $size                 = null;
    public $rating               = null;

    function __construct( $gravatar_url, $gravatar_id, $size, $rating)
    {
        $this->gravatar_url = $gravatar_url;
        $this->gravatar_id  = $gravatar_id;
        $this->size         = $size;
        $this->rating       = $rating;
    }

    /**
     * Set absolute Path to the /gravatar_cache folder.
     * Cache might be located on another webserver.
     */
    public function setCacheLocation($path)
    {
        $this->cache_location = $path;
    }

    /**
     * Caching is possible, when we can
     * url_fopen the gravatar.com URL to download from there
     */
    public function checkIfCachable()
    {
        if ($this->cacheable == true or 1 == ini_get("allow_url_fopen") )
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
        $gravatar_filename  = '';
        $gravatar_filename .= (string) sprintf($this->gravatar_cache_url,
                                               $this->gravatar_id,
                                               $this->size,
                                               $this->rating);

        # absolute
        $absolute_cache_filename  = '';
        $absolute_cache_filename .= ROOT . $this->cache_location . $gravatar_filename;

        # relative
        $relative_cache_filename  = '';
        $relative_cache_filename .= WWW_ROOT .'/'. $this->cache_location . $gravatar_filename;

        # if the cache_file is detected on an absolute path and still in the cache time
        if (is_file($absolute_cache_filename) === true and
           (filemtime($absolute_cache_filename) > strtotime('-' . $this->cache_expire_time)) === true)
        {
            # return it a relative path
            return $relative_cache_filename;
        }
        else
        {
            # returnfrom gravatar.com
            return $this->setGravatar($absolute_cache_filename, $this->gravatar_url);
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
            # Get the Gravatar and Save a Cache File from
            file_put_contents($cache_filename, file_get_contents($gravatar_url));

            # Set CHMOD to 755 (rwx r-x r-x)
            chmod($cache_filename, 755);

            # Check if Cache file was created
            if (is_file($cache_filename) === true)
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
?>