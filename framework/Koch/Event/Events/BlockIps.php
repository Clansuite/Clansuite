<?php

/**
 * Koch Framework
 * Jens-Andrï¿½ Koch ï¿½ 2005 - onwards
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
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace Koch\Event\Events;

use Koch\Event\EventInterface;

/**
 * Koch Framework - Event for Blocking IPs.
 *
 * Usage:
 * $blockip = new BlockIps(array('127.0.0.1'));
 * $dispatcher->addEventHandler('onLogin', $blockip);
 * if ($event->isCancelled()) { }
 *
 */
class BlockIps implements EventInterface
{
    protected $blockedIps;

    public function __construct($blockedIps)
    {
        $this->blockedIps = $blockedIps;
    }

    public function execute(Koch_Event $event)
    {
        $request = Clansuite_CMS::getInjector()->instantiate('Koch_HttpRequest');

        $ip = $request->getRemoteAddress();

        if (in_array($ip,$this->blockedIps)) {
            $event->cancel();
        }
    }
}
