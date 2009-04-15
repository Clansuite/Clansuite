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
    * @copyright  Jens-André Koch (2005-2008)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    *
    * @version    SVN: $Id: cache.class.php 1813 2008-03-21 22:46:21Z vain $
    */

// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.'); }

/**
 * Clansuite Cache Handler for Filecaching
 *
 * The Filecache stores directly to disk.
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Cache
 */
class Clansuite_Cache_File implements Clansuite_Cache_Interface
{

    /**
     * isCached checks the cache for a key
     *
     * @param string $key Identifier for the data
     * @return boolean true|false
     */
    public function isCached($key)
    {
        $filepath = $this->filesystemKey($key);
        if (is_file($filepath))
        {
            return true;
        }
        return false;
    }

    /**
     * Read a key from the cache
     *
     * @param string $key Identifier for the data
     * @return mixed boolean FALSE if the data was not fetched from the cache, DATA on success
     */
    function fetch($key)
    {
        $filepath = $this->filesystemKey($key);

        # try to open file, read-only
        if(is_file($filepath)) && file = fopen($filepath, 'r'))
        {
             # get the expiration time stamp
             $expires = (int)fread($file, 10);
             # if expiration time exceeds the current time, return the cache
            if (!$expires || $expires > time())
            {
                $realsize = filesize($block) - 10;
                $cache = '';
                # read in a loop, because fread returns after 8192 bytes
                while ($chunk = fread($file, $realsize))
                {
                    $cache .= $chunk;
                }
                fclose($block);
                return unserialize($cache);
            }
            else
            {
                # close and delete the expired cache
                fclose($block);
                $this->delete($key);
            }
        }
        return false;
        }
    }

    /**
     * Stores data by key into cache
     *
     * @param string $key Identifier for the data
     * @param mixed $data Data to be cached
     * @param integer $cache_lifetime How long to cache the data, in seconds
     * @return boolean True if the data was successfully cached, false on failure
     */
    function store($key, $data, $cache_lifetime)
    {
        # get name and lifetime
        $filepath = $this->filesystemKey($key);
        $cache_lifetime = str_pad( (int) $cache_lifetime, 10, '0', STR_PAD_LEFT);

        # write key file
        $success = (bool) file_put_contents($filepath, $cache_lifetime, FILE_EX);

        # append serialized value to file
        if ( $success )
        {
            return (bool) file_put_contents($filepath, serialize($value), FILE_EX | FILE_APPEND);
        }
        return false;
    }

    /**
     * Delete data by key from cache
     *
     * @param string $key Identifier for the data
     * @return boolean True if the data was successfully removed, false on failure
     */
    function delete($key)
    {
        $filepath = $this->filesystemKey($key);
        if (is_file($filepath))
        {
            return unlink($filepath);
        }
        return true;
    }

    /**
     * Get stats and usage Informations for display
     *
     * @todo
     */
    function stats()
    {
    }

    protected function filesystemKey($key)
    {
        return ROOT_CACHE . md5($key);
    }
}
?>