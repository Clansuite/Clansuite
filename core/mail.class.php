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
	die( 'You are not allowed to view this page statically.' );	
}

//----------------------------------------------------------------
// Get phpmailer class
//----------------------------------------------------------------
require (CORE_ROOT . '/phpmailer/class.phpmailer.php');
	
//----------------------------------------------------------------
// Start of mailer class extended by phpmailer
//----------------------------------------------------------------
class mailer extends phpmailer
{	
	global $cfg;

	//----------------------------------------------------------------
	// Set default Vars
	//----------------------------------------------------------------
    	public $WordWrap = 70;
	public $Encoding = "base64";
	public $Priority = 3;
	public $CharSet  = "UTF-8";
	$mailer->IsHTML(true); // set altBODY for non-html
	
	/* 
	* Function zur Initialisierung der Mail-Methode
	* in Abh�ngigkeit von (@link $cfg['mailmethod])
	* bei SMTP wird Auth verwendet 
	* M�glichkeiten: mail(), smtp, sendmail, qmail
	* @input :  $mailmethod
	* @output : assigns $mailer->method
	*/
	function __construct($cfg->mailmethod)
	{
		global $cfg;
	
		$mailer->SMTPAuth = FALSE;
		$mailer->Host = implode(';',$cfg['smtphost']);
	
    	/* smtp mit auth */
    	if($cfg->mailmethod) == "smtp") 	
    	{
    	 $mailer->IsSMTP();
    	 $mailer->SMTPAuth = TRUE;
    	 $mailer->User = $cfg['smtpusername'];
    	 $mailer->Password = $cfg['smtppassword'];
    	}
    	/* sendmail */
    	elseif($cfg->mailmethod) == "sendmail")
    	{
    	 $mailer->IsSendmail();
    	 $mailer->Sendmail = $cfg['sendmailpath'];
    	}
    	/* qmail */
    	elseif($cfg->mailmethod) == "qmail")
    	{
    	 $mailer->IsQmail();
    	}
    	/* mail */
    	else 
    	{
    	 $mailer->IsMail();}
    	}	
    	
	// set From, FromName and AddReply to $cfg-settings	
    	mailer:from($cfg->from, $cfg->from_name);
	
	}
	
	// Replace the default error_handler
    	function error_handler($msg) 
	{
    	global $error;
    	
        print("Mailer Error");
        print("Description:");
        printf("%s", $msg);
		$error->error_log['mailer']['error'] = $lang->t('Mailer Error! Description: ') . $msg;
        exit;
    }
	
	/* function from 
	*  relative assign From, FromName, AddReply to mailer
	*  @input $sender_mail, $sender_name
	*/
	function from($sender_mail, $sender_name)
	{
		$mailer->From = $sender_mail;
		$mailer->FromName = $sender_name;
		$mailer->AddReplyTo($sender_mail, $sender_name);
	}
	
}
$mailer = new mailer;
?>