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

namespace Koch\Mail;

/**
 * Koch Framework - Class for Mail Handling with SwiftMailer.
 *
 * This is a simple wrapper for SwiftMailer.
 * @link http://swiftmailer.org/
 *
 * @category    Koch
 * @package     Core
 * @subpackage  Mailer
 */
class SwiftMailer
{
    public $mailer = null;

    private $config = null;

    /**
     * Constructor.
     */
    public function __construct( Koch\Config $config )
    {
        $this->config = $config;
        $this->loadMailer();
    }

    /**
     * Loads and instantiates Swift Mailer
     */
    private function loadMailer()
    {
        // Include the Swiftmailer Class
        include ROOT_LIBRARIES . 'swiftmailer/Swift.php';

        /**
         * Include the Swiftmailer Connection Class and Set $connection
         */
        if ($this->config['email']['mailmethod'] != 'smtp') {
            include ROOT_LIBRARIES . 'swiftmailer/Swift/Connection/Sendmail.php';
        }

        switch ($this->config['email']['mailmethod']) {
            case 'smtp':
                include ROOT_LIBRARIES . 'swiftmailer/Swift/Connection/SMTP.php';
                $connection = new Swift_Connection_SMTP($this->config['email']['mailerhost'], $this->config['email']['mailerport'], $this->config['email']['mailencryption']);
                break;

            case 'sendmail':
                $connection = new Swift_Connection_Sendmail;
                break;

            case 'exim':
                $connection = new Swift_Connection_Sendmail('/usr/sbin/exim -bs');
                break;

            case 'qmail':
                $connection = new Swift_Connection_Sendmail('/usr/sbin/qmail -bs');
                break;

            case 'postfix':
                $connection = new Swift_Connection_Sendmail('/usr/sbin/postfix -bs');
                break;

            default:
                $connection = new Swift_Connection_Sendmail;
        }

        //  This globalizes $this->mailer and initialize the class
        $this->mailer = new Swift($connection, $this->config['email']['mailerhost']);
    }

    /**
     * This is the sendmail command, it's a shortcut method to swiftmailer
     * Return true or false if successfully
     *
     * @param  string  $to      Recipient email
     * @param  string  $from    Sender email
     * @param  string  $subject Message subject (headline)
     * @param  string  $body    Message body
     * @return boolean true|false
     */
    public function send($to, $from, $subject, $body)
    {
        if ($this->mailer->isConnected()) {
            // sends a simple email via the instantiated mailer
            $this->mailer->send($to, $from, $subject, $body);

            // close mailer
            $this->mailer->close();

            return true;
        } else {
            trigger_error('The mailer failed to connect.
                           Errors: <br/>' . '<pre>' . print_r($this->mailer->errors, 1) . '</pre>' . '
                           Log: <pre>' . print_r($this->mailer->transactions, 1) . '</pre>', E_USER_NOTICE);

            return false;
        }
    }

    /**
     * Getter Method for the Swiftmailer Object
     *
     * @return object SwiftMailer
     */
    public function getMailer()
    {
        if ($this->mailer === null) {
            $this->loadMailer();
        }

        return $this->mailer;
    }
}
