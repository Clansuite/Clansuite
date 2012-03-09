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

namespace Koch\Logger;

# Security Handler
if(defined('IN_CS') === false)
{
    exit('Koch Framework not loaded. Direct Access forbidden.');
}

/**
 * Koch FrameworkCore File - Koch_Logger_Email
 *
 * This class is a service wrapper for sending logging messages via email.
 * The email is send using the Koch_Mailer, which is a wrapper for SwiftMailer.
 *
 * @author      Jens-Andr� Koch <vain@clansuite.com>
 * @copyright   Jens-Andr� Koch (2005 - onwards)
 * @license     GPLv2 any later license
 *
 * @category    Koch
 * @package     Core
 * @subpackage  Logger
 */
class Email implements Logger
{
    private $config;

    private $instance = null;

    private $mailer = null;

    public function __construct(Koch_Config $config)
    {
        $this->config = $config;

        # mailing of critical errors makes only sense, if we have a email of the sysadmin
        if ( $config['mail']['to_sysadmin'] == true)
        {
            $this->mailer = new Koch_Mailer($config);
        }
    }

    /**
     * returns an instance / singleton
     *
     * @return an instance of the logger
     */
    public static function getInstance()
    {
        if (self::$instance == 0)
        {
            self::$instance = new Koch_Logger_Email;
        }
        return self::$instance;
    }

    /**
     * writeLog
     * sends an email with the errormessage
     *
     * @param array $data array('message', 'label', 'priority')
     */
    public function writeLog($data)
    {
        $to_address   = $this->config['mail']['to_sysadmin'];
        $from_address = $this->config['mail']['from'];
        # append date/time to msg
        $subject      = '[' . date(DATE_FORMAT, mktime()) . '] ' . $data['label'];
        $body         = var_export($data);

        $this->sendmail($to_address, $from_address, $subject, $body);
    }
}
?>