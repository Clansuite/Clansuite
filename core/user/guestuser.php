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
    * @author     Jens-André Koch   <vain@clansuite.com>
    * @copyright  Jens-André Koch (2005 - onwards)
    * @link       http://www.clansuite.com
    *
    * @version    SVN: $Id$
    */

namespace Koch\User;

# Security Handler
if(defined('IN_CS') === false)
{
    exit('Koch Framework not loaded. Direct Access forbidden.');
}

/**
 * Koch Framework - Guest user.
 *
 * The object represents the initial values of the user object.
 * This is the guest visitor.
 */
class GuestUser
{
    /**
     * @var object Koch_GuestUser is a singleton.
     */
    static private $instance = null;

    /**
     * @var object Koch\Configuration
     */
    private $config = null;

    /**
     * @return This object is a singleton.
     */
    public static function instantiate()
    {
        if(null === self::$instance)
        {
            self::$instance = new self;
        }

        return self::$instance;
    }

    function __construct()
    {
        $this->config = \Clansuite\CMS::getInjector()->instantiate('Koch\Config\Config');

        /**
         * Fill $_SESSION[user] with Guest-User-infos
         */

        $_SESSION['user']['authed']         = 0;  # guests are not authed
        $_SESSION['user']['user_id']        = 0;  # guests have user_id 0
        $_SESSION['user']['nick']           = _('Guest');

        $_SESSION['user']['passwordhash']   = '';
        $_SESSION['user']['email']          = '';

        $_SESSION['user']['disabled']       = 0;
        $_SESSION['user']['activated']      = 0;

        /**
         * Language for Guests
         *
         * This sets the default locale as defined by configuration value
         * [language}[locale] for all guests visitors,
         * If not already set via a GET request and
         * processed in the filter (like "index.php?lang=fr").
         */
        if(false === isset($_SESSION['user']['language_via_url']))
        {
            $_SESSION['user']['language'] = $this->config['language']['locale'];
        }

        /**
         * Theme for Guests
         *
         * Sets the Default Theme for all Guest Visitors, if not already set via a GET request.
         * Theme for Guest Users as defined by config['template']['frontend_theme']
         */
        if(empty($_SESSION['user']['frontend_theme']))
        {
            $_SESSION['user']['frontend_theme'] = $this->config['template']['frontend_theme'];
        }

        # @todo remove this line, when user login is reactivated
        $_SESSION['user']['backendend_theme'] = 'admin';

        /**
         * Permissions for Guests
         */

        $_SESSION['user']['group']  = 1; # @todo hardcoded for now
        $_SESSION['user']['role']   = 3;
        #$_SESSION['user']['rights'] = Koch_ACL::createRightSession( $_SESSION['user']['role'] );

        #Koch_Debug::printR($_SESSION);
        #Koch_Debug::firebug($_SESSION);
    }
}
?>