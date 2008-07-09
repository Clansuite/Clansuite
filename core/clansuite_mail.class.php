<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-Andre Koch © 2005-2008
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
    * @license    GNU/GPL, see COPYING.txt
    *
    * @author     Jens-Andre Koch   <vain@clansuite.com>
    * @author     Florian Wolf      <xsign.dll@clansuite.com>
    * @copyright  Jens-Andre Koch (2005-$LastChangedDate$), Florian Wolf (2006-2007)
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
 * @author     Jens-Andre Koch   <vain@clansuite.com>
 * @copyright  Jens-Andre Koch (2005-$LastChangedDate$)
 * @since      Class available since Release 0.1
 *
 * @package     clansuite
 * @category    core
 * @subpackage  mailer
 */
class mailer
{
    /**
     * CONSTRUCTOR
     *
     * @global $cfg
     * @global $swiftmailer
     */
    function __construct()
    {
        global $cfg;

        /**
         *  Include the Swiftmailer Class
         */

        require( ROOT_CORE . '/swiftmailer/Swift.php');

        /**
         * Include the Swiftmailer Connection Class 
         * and Set $connection
         */

        if ($cfg->mailmethod != 'smtp')
        {
            require( ROOT_CORE . '/swiftmailer/Swift/Connection/Sendmail.php');
        }


        switch ($cfg->mailmethod)
        {
            case 'smtp':
                require( ROOT_CORE . '/swiftmailer/Swift/Connection/SMTP.php');
                $connection = new Swift_Connection_SMTP( $cfg->mailerhost, $cfg->mailerport, $cfg->mailencryption );
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
         * This globalizes $swiftmailer and initialize the class
         */

        global $swiftmailer;

        $swiftmailer = new Swift($connection, $cfg->mailerhost);
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

    function sendmail($to_address, $from_address, $subject, $body)
    {
        global $error, $swiftmailer;

        /**
         * If anything goes wrong you can see what happened in the logs
         */
         
        if ($swiftmailer->isConnected())
        {
            /**
             * Sends a simple email
             */
             
            $swiftmailer->send($to_address, $from_address, $subject, $body);

            /**
             * Closes cleanly... works without this but it's not as polite. :) 
             */
            $swiftmailer->close();
            
            return true;
        }
        else
        {
            $error->show( $lang->t( 'The mailer failed to connect. Errors:') .'<pre>' . print_r($swiftmailer->errors, 1) . '</pre>' . 'Log: <pre>' . print_r($swiftmailer->transactions, 1) .'</pre>' );
            $error->error_log['mailer']['error'] = $lang->t('Mailer Error! Description: ') . print_r($swiftmailer->errors, 1);
            
            return false;
        }
    }
}

?>