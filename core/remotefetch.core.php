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
    * @author     Jens-André Koch <vain@clansuite.com>
    * @copyright  Jens-André Koch (2005 - onwards)
    * @link       http://www.clansuite.com
    *
    * @version    SVN: $Id$
    */

# Security Handler
if(defined('IN_CS') === false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

/**
 * Clansuite Remotefetch
 *
 * 1: Snoppy
 * 2: cURL
 * (3: Remote)
 * (4: FTP)
 */
class Clansuite_Remotefetch
{
    /**
     * Fetches remote content with Snoopy
     *
     * @param $url URL of remote content to fetch
     */
    public static function snoopy_get_file($url)
    {
        $remote_content = null;

        if(Clansuite_Loader::loadLibrary('snoopy'))
        {
            $s = new Snoopy();
            $s->fetch($url);

            if($s->status == 200)
            {
                $remote_content = $s->results;
            }
        }

        return $remote_content;
    }

    /**
     * Fetches remote content with cURL
     *
     * @param $url URL of remote content to fetch
     */
    public static function curl_get_file($url)
    {
        $c = curl_init();
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($c, CURLOPT_URL, $url);
        $contents = curl_exec($c);
        curl_close($c);

        if(false === empty($contents))
        {
            return $contents;
        }

        return false;
    }

}
?>