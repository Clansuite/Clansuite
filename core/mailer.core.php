<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-André Koch © 2005-2008
    * http://www.clansuite.com/
    *
    * File:         mail.class.php
    * Requires:     PHP 5.1.4+
    *
    * Purpose:      Clansuite Core Class for Mail Handling (Wrapper for SwiftMailer)
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
    * @author     Jens-André Koch   <vain@clansuite.com>
    * @author     Florian Wolf      <xsign.dll@clansuite.com>
    * @copyright  Jens-André Koch (2005-$LastChangedDate$), Florian Wolf (2006-2007)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    * @since      File available since Release 0.1
    *
    * @version    SVN: $Id$
    */

//Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}

/**
 * This Clansuite Core Class for for Mail Handling (Wrapper for SwiftMailer)
 *
 * Basically this is a wrapper for SwiftMail, setting up some parameters
 *
 * @author     Jens-André Koch   <vain@clansuite.com>
 * @copyright  Jens-André Koch (2005-$LastChangedDate$)
 * @since      Class available since Release 0.1
 *
 * @package     clansuite
 * @category    core
 * @subpackage  mailer
 */
class Clansuite_Mailer
{
    public $mailer = NULL;
    private $_config = NULL;


    /**
     * CONSTRUCTOR
     *
     */
    function __construct( Clansuite_Config $config )
    {
        $this->_config = $config;
        $this->loadMailer();
    }

    /**
    * @desc Loads Swift Mailer
    *
    */
    private function loadMailer()
    {
        /**
         *  Include the Swiftmailer Class
         */

        require( ROOT_LIBRARIES . '/swiftmailer/Swift.php');

        /**
         * Include the Swiftmailer Connection Class
         * and Set $connection
         */

        if ($this->_config['email']['mailmethod'] != 'smtp')
        {
            require( ROOT_LIBRARIES . '/swiftmailer/Swift/Connection/Sendmail.php');
        }


        switch ($this->_config['email']['mailmethod'])
        {
            case 'smtp':
                require( ROOT_LIBRARIES . '/swiftmailer/Swift/Connection/SMTP.php');
                $connection = new Swift_Connection_SMTP( $this->_config['email']['mailerhost'], $this->_config['email']['mailerport'], $this->_config['email']['mailencryption'] );
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

        /**
         * This globalizes $this->mailer and initialize the class
         */
        $this->mailer = new Swift($connection, $this->_config['email']['mailerhost']);
    }

    /**
     * This is the sendmail command, it's a shortcut method to swiftmailer
     * Return true or false if successfully
     *
     * @param string
     * @param string
     * @param string
     * @param string
     * @todo check if swiftmailer correctly writes errors to the docs? sends mails correctly?
     *       use templates as body of emails?
     *
     * @return bool
     */

    public function sendmail($to_address, $from_address, $subject, $body)
    {
        /**
         * If anything goes wrong you can see what happened in the logs
         */

        if ($this->mailer->isConnected())
        {
            /**
             * Sends a simple email
             */

            $this->mailer->send($to_address, $from_address, $subject, $body);

            /**
             * Closes cleanly... works without this but it's not as polite. :)
             */
            $this->mailer->close();

            return true;
        }
        else
        {
            trigger_error('The mailer failed to connect. Errors: <br/>' .'<pre>' . print_r($this->mailer->errors, 1) . '</pre>' . 'Log: <pre>' . print_r($this->mailer->transactions, 1) .'</pre>' );
            return false;
        }
    }
}
?>