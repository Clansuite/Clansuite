<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-Andr� Koch � 2005 - onwards
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
    * @author     Jens-Andr� Koch <vain@clansuite.com>
    * @copyright  Jens-Andr� Koch (2005 - onwards)
    * @link       http://www.clansuite.com
    *
    * @version    SVN: $Id$
    */

# Security Handler
if (defined('IN_CS') == false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

/**
 * Clansuite Cache Handler for eAccelerator
 *
 * eAccelerator was born in December 2004 as a fork of the Turck MMCache project (by Dmitry Stogov).
 * eAccelerator stores compiled PHP scripts in shared memory and executes code directly from it.
 * It creates locks only for a short time, while searching for a compiled PHP script in the cache,
 * so one script can be executed simultaneously by several engines. Files that can't fit in shared
 * memory are cached on disk only.
 *
 * @link http://eaccelerator.net/
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Cache
 */
class Clansuite_Cache_Eaccelerator implements Clansuite_Cache_Interface
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        if( extension_loaded('eaccelerator') === false)
        {
            throw new Exception('The PHP extension eAccelerator (cache) is not loaded! You may enable it in "php.ini!"', 300);
        }
    }

    /**
     * Contains checks if a key exists in the cache
     *
     * @param string $key Identifier for the data
     * @return boolean true|false
     */
    public function contains($key)
    {
        if ( true === eaccelerator_get($key) )
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Read a key from the cache
     *
     * @param string $key Identifier for the data
     * @return mixed boolean FALSE if the data was not fetched from the cache, DATA on success
     */
    public function fetch($key)
    {
        $data = eaccelerator_get($key);
        if ($data == false)
        {
            return false;
        }
        return unserialize($data);
    }

    /**
     * Stores data by key into cache
     *
     * @param string $key Identifier for the data
     * @param mixed $data Data to be cached
     * @param integer $cache_lifetime How long to cache the data, in seconds
     * @return boolean True if the data was successfully cached, false on failure
     */
    public function store($key, $data, $cache_lifetime)
    {
        $data = serialize($data);
        return eaccelerator_put($key, $data, $cache_lifetime);
    }

    /**
     * Delete data by key from cache
     *
     * @param string $key Identifier for the data
     * @return boolean True if the data was successfully removed, false on failure
     */
    public function delete($key)
    {
        return eaccelerator_rm($key);
    }

    /**
     *  Get stats and usage Informations for display from eAccelerator
     */
    public function stats()
    {
        if(CSID_EXTENSION_LOADED_EAC == false)
        {
            return;
        }

        # get info Get info about eAccelerator
        $eac_sysinfos['infos'] = eaccelerator_info();


        # List cached keys
        $keys = eaccelerator_list_keys();

        if (is_array($keys))
        {
            foreach ($keys as $key)
            {
                $eac_sysinfo['keys'][] = $key;
            }
        }
        return null;
    }
}
?>