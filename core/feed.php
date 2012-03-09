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

namespace Koch;

# Security Handler
if(defined('IN_CS') === false)
{
    exit('Koch Framework not loaded. Direct Access forbidden.');
}

/**
 * Koch Framework Class for Feed Handling
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
 * @author     Jens-Andr� Koch <vain@clansuite.com>
 * @copyright  Jens-Andr� Koch (2005-onwards)
 *
 * @category    Koch
 * @package     Core
 * @subpackage  Feed
 */
class Feed
{
    /**
     * Fetches a feed by URL and caches it - using the SimplePie Library.
     *
     * @param string $feed_url This is the URL you want to parse.
     * @param int $cache_duration This is the number of seconds that you want to store the feedcache file for.
     * @param string $cache_location This is where you want the cached feeds to be stored.
     */
    public static function fetchRSS($feed_url, $number_of_items = null, $cache_duration = null, $cache_location = null)
    {
        /**
         * SimplePie is a bunch of crap, and not e_strict, yet. hmpf!
         * Therefore we have to cheat with the error_reporting toggle.
         * @link: http://tech.groups.yahoo.com/group/simplepie-support/message/3289
         */
        $old_errorlevel = error_reporting();
        error_reporting(0);

        # load simplepie
        include ROOT_LIBRARIES . 'simplepie/simplepie.inc';

        # instantiate simplepie
        $simplepie = new SimplePie();

        # if cache_location was not specified manually
        if($cache_location == null)
        {
            # we set it to the default cache directory for feeds
            $cache_location = ROOT_CACHE; # . 'feeds';
        }

        # if cache_duration was not specified manually
        if($cache_duration == null)
        {
            # we set it to the default cache duration time of 1800
            $cache_duration = 1800;
        }

        # if number of items to fetch is null
        if($number_of_items == null)
        {
            # we set it to the default value of 5 items
            $number_of_items = 5;
        }

        # finally: fetch the feed and cache it!
        $simplepie->set_feed_url($feed_url);
        $simplepie->set_cache_location($cache_location);
        $simplepie->set_cache_duration($cache_duration);
        $simplepie->set_timeout(5);
        $simplepie->set_output_encoding('UTF-8');
        $simplepie->set_stupidly_fast(true);
        $simplepie->init();
        $simplepie->handle_content_type();

        # set old error reporting level
        error_reporting($old_errorlevel);

        return $simplepie;
    }

    /**
     * Fetches a feed by URL and caches it.
     * Be advised to use the method fetchRSS() instead.
     *
     * @param string $feed_url This is the URL you want to parse.
     * @param boolean $cache If true caches the content. Default true.
     * @return string Feed content.
     */
    public static function fetchRawRSS($feed_url, $cache = true)
    {
        if($cache === true)
        {
            # Cache Filename and Path
            $cachefile = ROOT_CACHE . md5($feed_url);

            # define cache lifetime
            $cachetime = 60*60*3; # 10800min = 3h
        }

        # try to return the file from cache
        if (true === $cache and is_file($cachefile) and (time() - filemtime($cachefile)) < $cachetime)
        {
            return file_get_contents($cachefile);
        }
        else # get the feed from the source
        {
            if($cache === true)
            {
                # ensure cachefile exists, before we write
                touch($cachefile);
                chmod($cachefile, 0666);
            }
            # Get Feed from source, Write File
            $feedcontent = file_get_contents($feed_url, FILE_TEXT);

            # ensure that we have rss content
            if(mb_strlen($feedcontent) > 0)
            {
                if($cache === true)
                {
                    $fp = fopen($cachefile, 'w');
                    fwrite($fp, $feedcontent);
                    fclose($fp);
                }

                return $feedcontent;
            }
            else
            {
                return null;
            }
        }
    }

    /**
     * Returns UniversalFeedCreator Object
     *
     * @return object UniversalFeedCreator
     */
    public static function getFeedcreator()
    {
        Koch_Loader::loadLibrary('feedcreator');

        return new UniversalFeedCreator();
    }
}
?>