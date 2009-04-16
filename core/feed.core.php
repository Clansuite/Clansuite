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
    * @author     Jens-André Koch <vain@clansuite.com>
    * @copyright  Jens-André Koch (2005 - onwards)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    *
    * @version    SVN: $Id: frontcontroller.core.php 2800 2009-03-03 14:45:13Z vain $
    */

// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}

/**
 * Clansuite Core Class for Feed Handling
 *
 * This is a Dual-Wrapper for SimplePie and FeedCreator.
 *
 * This is a wrapper for the Feed-Reader Library SimplePie.
 * SimplePie is PHP-Based RSS and Atom Feed Framework.
 * It's written and copyrighted by Ryan Parman and Geoffrey Sneddon, (c) 2004-2008.
 * SimplePie is licensed under the modified BSD (3-clause) license.
 *
 * This is a wrapper for the Feed-Creator Library FeedCreator.
 * It's originally written and copyrighted by Kai Blankenhorn, extended by Scott Reynen, Dirk Clemens, (c).
 * FeedCreator is licensed under LGPL v2.1 or any later version.
 *
 * @author     Jens-André Koch <vain@clansuite.com>
 * @copyright  Jens-André Koch (2005-onwards)
 * @since      Class available since Release 0.2
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Feed
 */
class Clansuite_Feed
{
    private static function instantiateSimplePie()
    {
        # try to load SimplePie library
        if( clansuite_loader::loadLibrary('simplepie') == true)
        {
            # create a new instance of SimplePie
            return new SimplePie();

        }
        else
        {
            trigger_error('Error: No Library SimplePie available!', 1);
            exit(0);
        }
    }

    /**
     * fetches a feed_url and caches it.
     *
     * @param string $feed_url This is the URL you want to parse.
	 * @param int $cache_duration This is the number of seconds that you want to store the feedcache file for.
	 * @param string $cache_location This is where you want the cached feeds to be stored.
     */
    public static function fetch($feed_url, $cache_duration = null, $cache_location = null)
    {
        # load simplepie
        $simplepie = self::instantiateSimplePie();

        # if cache_location was not specified manually
        if ( $cache_location == null)
        {
            # we set it to the default cache directory for feeds
            $cache_location = ROOT_CACHE . 'feeds';
        }

        # finally: fetch the feed and cache it!
        $simplepie->SimplePie($feed_url, $cache_location, $cache_duration)
    }

    public static function write()
    {
        clansuite_loader::loadLibrary('feedcreator.class.php');

        return new UniversalFeedCreator();
    }
}
?>