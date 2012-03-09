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

# Security Handler
if (defined('IN_CS') === false)
{
    die('Koch Framework not loaded. Direct Access forbidden.');
}

namespace Koch\Event;

/**
 * Koch FrameworkEvent - Authentication Logging
 *
 * @author     Jens-Andr� Koch <vain@clansuite.com>
 * @copyright  Jens-Andr� Koch (2005 - onwards)
 *
 * Usage:
 * $logger = new Koch_Logger('auth.log');
 * $eventhandler->addEventHandler('onInvalidLogin', $logger);
 * $eventhandler->addEventHandler('onLogin', $logger);
 */
class AuthenticationLogging implements Interface
{
    protected $logger;

    public function __construct(Koch_Logger $logger, Koch_HttpRequest $request)
    {
        # set request object
        $this->request = $request;
        # set logger object
        $this->logger = $logger;
    }

    public function execute(Koch_Event $event)
    {
        $authdata = $event->getInfo();

        $logdata = array(
                date(),                              # date
                $this->request->getRemoteAddress(),  # remote adress
                $event->getName(),                   # onLogin etc.
                $authdata['username']                # username
        );

        $this->logger->log($logdata);
    }
}
?>