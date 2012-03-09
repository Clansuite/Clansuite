<?php
    /**
     * Koch Framework
     * Jens-André Koch © 2005 - onwards
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
     * @author     Jens-André Koch <vain@clansuite.com>
     * @copyright  Jens-André Koch (2005 - onwards)
     * @link       http://www.clansuite.com
     *
     * @version    SVN: $Id$
     */

namespace Koch\Cache;

# Security Handler
if(defined('IN_CS') === false)
{
    die('Koch Framework not loaded. Direct Access forbidden.');
}

/**
 * Koch FrameworkCache Handler for Windows Cache
 *
 * Windows Cache Extension for PHP is a PHP accelerator that is used to
 * increase the speed of PHP applications running on Windows and Windows Server.
 * Microsoft Internet Information Services (MS IIS) is required.
 *
 * @link http://www.iis.net/download/wincacheforphp
 *
 * Download:
 * @link http://sourceforge.net/projects/wincache/files/
 *
 * It's a PECL extension.
 * @link http://pecl.php.net/package/WinCache
 *
 * @category    Koch
 * @package     Core
 * @subpackage  Cache
 */
class Wincache extends Base implements Cache
{
    /**
     * Contains checks if a key exists in the cache
     *
     * @param string $key Identifier for the data
     * @return boolean true|false
     */
    public function contains($key)
    {
        return wincache_ucache_exists($key);
    }

    /**
     * Read a key from the cache
     *
     * @param string $key Identifier for the data
     * @return mixed boolean FALSE if the data was not fetched from the cache, DATA on success
     */
    public function fetch($key)
    {
        return wincache_ucache_get($key);
    }

    /**
     * Stores data by key into cache
     *
     * @param string $key Identifier for the data
     * @param mixed $data Data to be cached
     * @param integer $cache_lifetime How long to cache the data, in minutes.
     * @return boolean True if the data was successfully cached, false on failure
     */
    public function store($key, $data, $cache_lifetime = 0)
    {
        return wincache_ucache_set($key, $data, $cache_lifetime * 60);
    }

    /**
     * Delete data by key from cache
     *
     * @param string $key Identifier for the data
     * @return boolean True if the data was successfully removed, false on failure
     */
    public function delete($key)
    {
        return xcache_unset($key);
    }

    /**
     * Get stats and usage Informations for display
     *
     * @todo if you want the feature, implement it ;)
     * Combine info array by taking a look at ucache_meminfo() and additional functions.
     * @link http://www.php.net/manual/en/function.wincache-ucache-meminfo.php
     */
    public function stats()
    {
    }

    public function clear()
    {
        wincache_ucache_clear();
    }
}
?>