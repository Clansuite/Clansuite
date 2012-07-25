<?php

/**
 * Koch Framework
 * Jens-Andr� Koch � 2005 - onwards
 *
 * This file is part of "Koch Framework".
 *
 * License: GNU/GPL v2 or any later version, see LICENSE file.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 */

namespace Koch\Event;

/**
 * Koch Framework - Event for Authentication Logging.
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
