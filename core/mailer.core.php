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
    *
    * @version    SVN: $Id$
    */

//Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}

/**
 * Clansuite_Mailer - Clansuite Core Class for Mail Handling with SwiftMailer
 *
 * This is a simple wrapper for SwiftMailer.
 * @link http://swiftmailer.org/
 *
 * @author     Jens-André Koch  <vain@clansuite.com>
 * @copyright  Jens-André Koch  (2005-onwards)
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Mailer
 */
class Clansuite_Mailer
{
    public  $mailer = null;
    private $config = null;

    /**
     * CONSTRUCTOR
     */
    function __construct( Clansuite_Config $config )
    {
        $this->config = $config;
        $this->loadMailer();
    }

    /**
     * Loads and instantiates Swift Mailer
     */
    private function loadMailer()
    {
        /**
         *  Include the Swiftmailer Class
         */

        require( ROOT_LIBRARIES . '/swiftmailer/Swift.php');

        /**
         * Include the Swiftmailer Connection Class and Set $connection
         */

        if ($this->config['email']['mailmethod'] != 'smtp')
        {
            require( ROOT_LIBRARIES . '/swiftmailer/Swift/Connection/Sendmail.php');
        }

        switch ($this->config['email']['mailmethod'])
        {
            case 'smtp':
                require( ROOT_LIBRARIES . '/swiftmailer/Swift/Connection/SMTP.php');
                $connection = new Swift_Connection_SMTP( $this->config['email']['mailerhost'], $this->config['email']['mailerport'], $this->config['email']['mailencryption'] );
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
        $this->mailer = new Swift($connection, $this->config['email']['mailerhost']);
    }

    /**
     * This is the sendmail command, it's a shortcut method to swiftmailer
     * Return true or false if successfully
     *
     * @param string $to_address
     * @param string $from_address
     * @param string $subject
     * @param string $body
     * @return boolean true|false
     */
    public function sendmail($to_address, $from_address, $subject, $body)
    {
        if ($this->mailer->isConnected())
        {
            # sends a simple email via the instantiated mailer
            $this->mailer->send($to_address, $from_address, $subject, $body);

            # close mailer
            $this->mailer->close();

            return true;
        }
        else
        {
            trigger_error('The mailer failed to connect.
                           Errors: <br/>' .'<pre>' . print_r($this->mailer->errors, 1) . '</pre>' . '
                           Log: <pre>' . print_r($this->mailer->transactions, 1) .'</pre>' );
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
        if(is_null($this->mailer))
        {
            $this->loadMailer();
        }

        return $this->mailer;
    }
}
?>