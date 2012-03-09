?php
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

# Security Handler
if(defined('IN_CS') === false)
{
    die('Koch Framework not loaded. Direct Access forbidden.');
}

namespace Koch\Cache;

abstract class Base
{
    /**
     * Prefix for the cache key.
     *
     * @var mixed Defaults to 'cs'.
     */
    protected $prefix = 'cs';

    /**
     * Set Prefix for the cache key.
     *
     * @param string $prefix The prefix for all cache keys.
     * @throws InvalidArgumentException if prefix is empty
     */
    public function setPrefix($prefix)
    {
        if(empty($prefix))
        {
            throw new InvalidArgumentException('Prefix must not be empty.');
        }

        $this->prefix = $prefix;
    }

    /**
     * Get Prefix for the cache key.
     *
     * @return string The cache prefix
     */
    public function getPrefix()
    {
        return $this->prefix;
    }
}
?>