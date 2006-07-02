<?php
/**
* Mail Handler Class (Wrapper for PHPMailer)
*
* PHP versions 5.1.4
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
*    You should have received a copy of the GNU General Public License
*    along with this program; if not, write to the Free Software
*    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*
* @author     Florian Wolf <xsign.dll@clansuite.com>
* @author     Jens-Andre Koch <vain@clansuite.com>
* @copyright  2006 Clansuite Group
* @license    see COPYING.txt
* @version    SVN: $Id: mail.class.php 129 2006-06-09 12:09:03Z vain $
* @link       http://gna.org/projects/clansuite
* @since      File available since Release 0.1
*/


//----------------------------------------------------------------
// Security Handler
//----------------------------------------------------------------
if (!defined('IN_CS'))
{
    die('You are not allowed to view this page statically.' );
}

class mailer
{
    function __construct()
    {
        global $cfg;
        
        //----------------------------------------------------------------
        // Get swift mailer class
        //----------------------------------------------------------------
        require( CORE_ROOT . '/swiftmailer/swift.php');
                
        //----------------------------------------------------------------
        // Include Connection Class & Set $connection
        //----------------------------------------------------------------
        if ($cfg->mailmethod != 'smtp')
        {
            require( CORE_ROOT . '/swiftmailer/Swift/Swift_Sendmail_Connection.php'); 
        }


        switch ($cfg->mailmethod)
        {
            case 'smtp':
                require('Swift/Swift/Swift_SMTP_Connection.php');
                $connection = new Swift_SMTP_Connection( $cfg->mailerhost, $cfg->mailerport, $cfg->mailencryption );
                break;
            
            case 'sendmail':
                $connection = new Swift_Sendmail_Connection;
                break;
            
            case 'exim':
                $connection = new Swift_Sendmail_Connection('/usr/sbin/exim -bs');
                break;
            
            case 'qmail':
                $connection = new Swift_Sendmail_Connection('/usr/sbin/qmail -bs');
                break;
            
            case 'postfix':
                $connection = new Swift_Sendmail_Connection('/usr/sbin/postfix -bs');
                break;
            
            default:
                $connection = new Swift_Sendmail_Connection;
        }
            
        //----------------------------------------------------------------
        // $mailer init
        //----------------------------------------------------------------
        global $swiftmailer;
        $swiftmailer = new Swift($connection, $cfg->mailerhost);
    }
    
    
    function sendmail($to_address, $from_address, $subject, $body)
    {
        global $error, $swiftmailer;
        
        //If anything goes wrong you can see what happened in the logs
        if ($swiftmailer->isConnected())
        {
            //Sends a simple email
            $swiftmailer->send($to_address, $from_address, $subject, $body);
            
            //Closes cleanly... works without this but it's not as polite.
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