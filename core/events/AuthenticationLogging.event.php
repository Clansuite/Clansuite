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
    *
    * @author     Jens-André Koch <vain@clansuite.com>
    * @copyright  Jens-André Koch (2005 - onwards)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    *
    * @version    SVN: $Id: eventhandler.core.php 2830 2009-03-17 13:22:09Z vain $
    */

// Security Handler
if (!defined('IN_CS')){die('Clansuite not loaded. Direct Access forbidden.');}

/**
 * Clansuite Event - Authentication Logging
 * 
 * @author     Jens-André Koch <vain@clansuite.com>
 * @copyright  Jens-André Koch (2005 - onwards) 
 *
 * Usage:
 * $logger = new Clansuite_Logger('auth.log');
 * $dispatcher->addEventHandler('onInvalidLogin', $logger);
 * $dispatcher->addEventHandler('onLogin', $logger); 
 */
class AuthenticationLogging implements Clansuite_Event
{
    protected $logger;
        
    public function __construct($logger)
    {
        # set request object
        $this->request = $requst;
    }
    
    public function execute(Event $event)
    {
        $authData = $event->getInfo();
        
        $logData = array(date(),                # date
                         $this->request->           # remote adress
                         $event->getName();     # onLogin etc.
                         $authData['username']  # username
                         );
        
        $this->logger->log($logData);
    }
}
?>