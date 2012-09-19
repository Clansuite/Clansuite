<?php

/**
 * Koch Framework
 * Jens-André Koch © 2005 - onwards
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

namespace Koch\Logger\Adapter;

use Koch\Logger\LoggerInterface;

/**
 * Koch Framework - Log to email.
 *
 * This class is a service wrapper for sending logging messages via email.
 * The email is send using the Koch_Mailer, which is a wrapper for SwiftMailer.
 *
 * @category    Koch
 * @package     Core
 * @subpackage  Logger
 */
class Email implements LoggerInterface
{
    private $config;

    private $mailer = null;

    public function __construct(\Koch\Config\Config $config)
    {
        $this->config = $config;

        // mailing of critical errors makes only sense, if we have a email of the sysadmin
        if ($config['mail']['to_sysadmin'] == true) {
            $this->mailer = new \Koch\Mail\SwiftMailer($config);
        }
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
        // append date/time to msg
        $subject      = '[' . date(DATE_FORMAT, mktime()) . '] ' . $data['label'];
        $body         = var_export($data);

        $this->sendmail($to_address, $from_address, $subject, $body);
    }
}
