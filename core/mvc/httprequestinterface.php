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
    * @copyright  Copyleft: All rights reserved. Jens-André Koch (2005 - onwards)
    * @link       http://www.clansuite.com
    *
    * @version    SVN: $Id$
    */

namespace Koch\MVC;

# Security Handler
if(defined('IN_CS') === false)
{
    exit('Koch Framework not loaded. Direct Access forbidden.');
}

/**
 * Interface for the Request Object
 *
 * @category    Koch
 * @package     Core
 * @subpackage  HttpRequest
 */
interface HttpRequestInterface
{
    # Parameters
    public function issetParameter($name, $arrayname = 'POST');
    public function getParameter($name, $arrayname = 'POST');
    public function expectParameter($parameter, $arrayname);
    public function expectParameters(array $parameters);
    public static function getHeader($name);

    # Direct Access to individual Parameters Arrays
    public function getParameterFromCookie($name);
    public function getParameterFromGet($name);
    public function getParameterFromPost($name);
    public function getParameterFromServer($name);

    # Request Method
    public static function getRequestMethod();
    public static function setRequestMethod($method);
    public static function isAjax();

    # $_SERVER Stuff
    public static function getServerProtocol();
    public static function isSecure();
    public static function getRemoteAddress();
}
?>