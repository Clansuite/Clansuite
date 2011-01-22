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
if (defined('IN_CS') === false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

/**
 * Clansuite_Systeminfo
 *
 * @author     Jens-André Koch
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  SystemInfo
 */
class Clansuite_SystemInfo
{
    /**
     * @var $extensions
     */
    private static $extensions;

    public static function getLoadedExtensions()
    {
        if(null === self::$extensions)
        {
            self::$extensions = get_loaded_extensions();
        }
    }

    public static function isLoadedExtension($extension_name)
    {
        self::getLoadedExtensions();

        return in_array($extension_name, self::$extensions);
    }
}

class Clansuite_SystemInfo_Cache
{
    /**
     * Returns a named array for usage in select/dropdown formelements.
     *
     * @return array Named Array.
     */
    public static function getNamedArray()
    {
        return array(
            'xcache'        => self::hasXcache(),
            'wincache'      => self::hasWincache(),
            'apc'           => self::hasApc(),
            'eaccelerator'  => self::hasEaccelerator(),
            'ioncube'       => self::hasIoncube(),
            'zend'          => self::hasZend(),
            'nusphere'      => self::hasNusphere()
        );
    }

    /**
     * Check for Xcache
     *
     * @link http://xcache.lighttpd.net
     * @return bool
     */
    public static function hasXcache()
    {
        return function_exists('xcache_isset');
    }

    /**
     * Check for Wincache
     *
     * @link http://www.iis.net/expand/WinCacheForPHP
     * @return bool
     */
    public static function hasWincache()
    {
        return function_exists('wincache_fcache_fileinfo');
    }

    /**
     * Check for Alternative PHP Cache
     *
     * @link http://pecl.php.net/package/apc
     * @return bool
     */
    public static function hasApc()
    {
        return function_exists('apc_add');
    }

    /**
     * Check for eAccelerator
     *
     * @link http://eaccelerator.net
     * @return bool
     */
    public static function hasEaccelerator()
    {
        return (bool) strlen(ini_get('eaccelerator.enable'));
    }

    /**
     * Check for ionCube Loader
     *
     * @link http://www.php-accelerator.co.uk
     * @return bool
     */
    public static function hasIoncube()
    {
        return (bool) strlen(ini_get('phpa'));
    }

    /**
     * Check for Zend Optimizer+
     *
     * @link http://www.zend.com/products/server
     * @return bool
     */
    public static function hasZend()
    {
        return (bool) strlen(ini_get('zend_optimizer.enable_loader'));
    }

    /**
     * Check for nuSphere's phpExpress
     *
     * @link http://www.nusphere.com/products/phpexpress.htm
     * @return bool
     */
    public static function hasNusphere()
    {
        return Clansuite_SystemInfo::isLoadedExtension('phpexpress');
    }
}
?>