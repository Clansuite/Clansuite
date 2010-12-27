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
    * @version    SVN: $Id: xdebug.core.php 4866 2010-10-25 19:57:34Z vain $
    */

# Security Handler
if(defined('IN_CS') === false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

/**
 * Clansuite_Securitytoken
 *
 * Manages generation of temporary security tokens.
 * This is used to disable the maintenance mode.
 *
 * @author Paul Brand <info@isp-tenerife.net>
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Security
 */
class Clansuite_Securitytoken
{
    /**
     * @var string $_token
     */
    private static $_token = '7aafb74dbe23ea352308be7b071db836';

    /**
     * @var string $_secure
     */
    private static $_secure = '128 Bit Verschluesselungsstring, hier kann eingetragen werden was will. Es dient nur der Sicherheit';

    /**
     * @var string $_token
     */
    private static $_tokenSavePath = 'cache/secure/';

    public function generateTestToken()
    {
        self::generateToken();
        return self::getToken();
    }

    /**
     * Returns the security token.
     *
     * @return string Token.
     */
    public function getToken()
    {
        return self::$_token;
    }


    /**
     * Set a token
     *
     * @param string $token the token string.
     */
    public function setToken($token=null)
    {
        if(null === $token)
        {
            return false;
        }

        self::$_token = $token;
    }

    /**
     * Generate a security token and saves it to file
     */
    protected function generateToken()
    {
        self::$_token = md5(self::$_secure . time());
        self::saveToken(self::$_token);
    }

    /**
     * saved the security token as a <token>.dat
     *
     * @param string $token Token String.
     */
    protected function saveToken($token)
    {
        $filename = $token . '.dat';

        if(false === file_exists(ROOT . self::$_tokenSavePath . $filename))
        {
            if(!$filehandle = fopen(ROOT . self::$_tokenSavePath . $filename, 'wb'))
            {
                echo _('Could not open file: ' . $filename);
                return false;
            }

            if(fwrite($filehandle, $token) == false)
            {
                echo _('Could not write to file: ' . $filename);
                return false;
            }
            fclose($filehandle);
        }

        return true;
    }

    /**
     * Read a security token
     *
     * @param string $token
     * @return boolean
     */
    protected function readToken($token)
    {
        if(file_exists(ROOT . self::$_tokenSavePath . $token . '.dat'))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}
?>