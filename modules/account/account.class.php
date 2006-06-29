<?php
/**
* Users Modul Handler Class
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
* @license    ???
* @version    SVN: $Id$
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
// Start Module Account Class
//----------------------------------------------------------------	
class module_account
{
	public $output 			= '';
	public $mod_page_title 	= '';
	public $additional_head = '';
	
	//----------------------------------------------------------------
	// First function to run - switches between $_REQUEST['action'] Vars to the functions
	// Loads necessary language files
	//----------------------------------------------------------------
	function auto_run()
	{
		global $lang;
				
		
		switch ($_REQUEST['action'])
		{
			// ---- (  Login / Logout  ) ----
			
			case 'login':
				$this->login();
				$title = ' Login ';
				break;
				
			case 'logout':
				$this->logout();
				$title = ' Logout ';
				break;
			
			// ----- ( Register ) ----
			
			case 'register':
				$this->register();
				break;
			
			case 'register-done':
				$title = ' Account created ';
				$tpl->fetch('account/register-done.tpl');
				break;
			
			case 'register-error':
				$title = ' Register error ';    
				$tpl->fetch('account/register-error.tpl');
				break;
			
			// ---- ( Activations ) ----
			
			case 'activate-account':
				$title = ' Activate account ';
				$this->activate_account();
				break;
			
			case 'activate-password':
				$title = ' Activate account ';
				$this->activate_password();
				break;
			
			case 'activation-email':
				$this->activation_email();
				break;
			
			case 'activation-email-sent':
				$tpl->fetch('account/activation-email-sent.tpl');
				$title = ' Activation email sent ';
				break;	
			
			// ----- ( forgot password ) ----
			
			case 'forgot-password':
				$this->forgot_password();
				$title = ' Forgot Password ';
				break;
			
			case 'forgot-password-sent':
				$tpl->fetch('account/forgot-password-sent.tpl');
				$title = ' Forgot Password Sent ';
				break;			
			
			// ----- ( default ) ----
			
			default:
				$this->login();
				break;	
		}
		
	// Titelzeile zusammensetzen
	$this->mod_page_title = $lang->t( 'User :: ' . $title );
	
	return array( 	'OUTPUT' 	  => $this->output,
			'MOD_PAGE_TITLE'  => $this->mod_page_title,
			'ADDITIONAL_HEAD' => $this->additional_head );
	}
	
	/**
	* @desc Show the entracne - welcome message etc.
	*/
	function login()
	{ global $user;
	 
		global $tpl, $user;
		
		$email = $_POST['email'];
		$password = $_POST['password'];
		$remember = $_POST['remember'];

		echo $email;
		echo $password;
		
		if ($email && $password) 
		{ 
		
		$userid = user::check_user($email, $password); 
		
		if ($userid != false) 
		{ 
		 user::login($userid, $remember); 
		 header('Location: index.php?mod=admin');
	    	 exit;
		} 
		else 
		{
		
		// User existiert nicht oder Passwort falsch!
		unset($user['{/php}']);
		
		// Login-Attempts
		// bei 3-5 versuchen 20min ip ban? 
		if(!isset($_SESSION['login_attempts'])) 
		{ $_SESSION['login_attempts'] = '1'; }
		else 
		{ $_SESSION['login_attempts']++; }
				
		#echo 'Ihre Anmeldedaten waren nicht korrekt!';
		#echo 'Dies ist Ihr '. $_SESSION['login_attempts'].'ter Versuch sich anzumelden !';
		
		}		
		} 
		$this->output .= $tpl->fetch('account/login.tpl');
	}
		
	/**
	* Logout	
	*/
	function logout()
	{
	global $session, $functions;
	
	$session->_session_destroy;
		
	$functions->redirect( '/index.php', 'metatag', '2' );
	}
	
	/**
	* Register	
	*/
	function register()
	{
	global $db, $tpl, $input;
	
	$email = $_POST['email'];
	$email2 = $_POST['email2'];
	$nick = $_POST['nick'];

	$err = array();

	if ($email && $email2 && $nick 
		&& $input->check( $email, 'is_email' ) 
		&& $email == $email2 
		&& $input->check( $nick, 'is_abc' )) 
		{
    
    	//todo: 
    	//entfällt der check, wenn db-felder UNIQUE KEYs sind ?
	//select count(*) ??? anstelle getrow?
    	$user1 = $db->getRow("SELECT * FROM users WHERE email = ?", $email);
    	$user2 = $db->getRow("SELECT * FROM users WHERE nick = ?", $nick);
    
	if ($user1) { $err['email_exists'] = 1; }
	if ($user2) { $err['nick_exists'] = 1; }
    
    	if (count($err) == 0) {
        
        $password = genString(6);
        $db->execute("INSERT INTO users (email, nick, password, joined) VALUES (?, ?, ?, NOW())", $email, $nick, md5($password));
        $user_id = $db->insertId();
        
        include ROOT.'/core/mail.class.php';
        
        $body  = "To activate an account click on the link below:\r\n";
        $body .= "http://$domain".WWW_ROOT."/index.php?mod=account&action=activate-account&user_id=%s&code=%s\r\n";
        $body .= "Password: %s";
        $body  = sprintf($body, $user_id, md5(md5($password)), $password);
        
        $mailer->Subject = 'Account activation';
        $mailer->Body = $body;
        $mailer->AddAddress($email, $nick);
              
        if ($mailer->send()) {
            header('Location: /index.php?mod=account&action=register-done');
            exit;
        } else {
            header('Location: /index.php?mod=account&action=register-error');
            exit;
        	}
    	}
	}
	$this->output .= $tpl->fetch('account/register.tpl');
	}
		
	/**
	* Send Activation Email	
	*/	
	function activation_email()
	{
	global $db;
	
	$email = $_POST['email'];

	$noSuchAccount = false;
	$errorWhileSending = false;
	$alreadyActivated = false;

	if ($email) {
    	$u1 = new User(null, $email);
    
    	if ($u1->exists() && !$u1->isActivated()) {
        
        $password = genString(6);
        $db->execute("UPDATE users SET password = ? WHERE user_id = ?", md5($password), $u1->getId());
        
        include ROOT.'/core/mail.class.php';
        
        $body  = "To activate an account click on the link below:\r\n";
        $body .= "http://$domain".WWW_ROOT."/index.php?mod=account&action=activate-account&user_id=%s&code=%s\r\n";
        $body .= "Password: %s";
        $body  = sprintf($body, $u1->getId(), md5(md5($password)), $password);
        
        $mailer->Subject = 'Account activation';
        $mailer->Body = $body;
        $mailer->AddAddress($email, $nick);
        
        if ($mailer->send()) {
            header('Location: index.php?mod=account&action=activation-email-sent');
            exit;
        } else {
            $errorWhileSending = true;
        }

	    } else {
	        if (!$u1->exists()) $noSuchAccount = true;
	        if ($u1->exists() && $u1->isActivated()) $alreadyActivated = true;
	    }
		}
		}
		
	/**
	 * Activate Account
	 */
	function activate_account(){
	$user_id = (int) get('user_id');
	$code = get('code');
	
	$alreadyActivated = false;
	$success = false;
	
	if ($user_id && $code) {
	    $user = $db->getRow("SELECT * FROM users WHERE user_id = ?", $user_id);
	    if ($user) {
	        if ($user['level'] != 0) {
	            $alreadyActivated = true;
	        } else if ($code == md5($user['password'])) {
	            $db->execute("UPDATE users SET level = 1 WHERE user_id = ?", $user_id);
	            $success = true;
	        }
	    }
	}
	$tpl->fetch(account/activate-account.tpl);
	}
	
	/**
	 * Activate Password
	 */	
	function activate_password()
	{
	$user_id = (int) get('user_id');
	$code = get('code');
	
	$noNewPassword = false;
	$success = false;
	
	if ($user_id && $code) {
	    $user = $db->getRow("SELECT * FROM users WHERE user_id = ?", $user_id);
	    if ($user) {
	        if ($code == md5($user['new_password'])) {
	            $db->execute("UPDATE users SET password = new_password, new_password = null WHERE user_id = ?", $user_id);
	            $success = true;
	        } else {
	            if ($user['new_password'] === null) {
	                $noNewPassword = true;
	            }
	        }
	    }
	    unset($user);
	}
	}
	
	
	/**
	 * Forgot Password
	 */
	function forgot_password(){
	
	$email = $_POST['email'];

	$errorWhileSending = false;
	$noSuchAccount = false;
	$accountNotActivated = false;
	
	if ($email) {
	    $u1 = new User(null, $email);
	    if ($u1->exists() && $u1->isActivated()) {
	
	        $password = genString(6);
	        $db->execute("UPDATE users SET new_password = ? WHERE user_id = ?", md5($password), $u1->getId());
	       
	        include ROOT.'/core/mail.class.php';
	        	
	        $body  = "To activate new password click on the link below:\r\n";
	        $body .= "http://$domain".WWW_ROOT."/index.php?mod=account&action=activate-password&user_id=%s&code=%s\r\n";
	        $body .= "New Password: %s";
	        $body  = sprintf($body, $u1->getId(), md5(md5($password)), $password);
	        
	        $mailer->Subject = 'New password';
        	$mailer->Body = $body;
        	$mailer->AddAddress($email, $nick);
	        	        
	        if ($mailer->send()) {
	            header('location: index.php?mod=account&action=forgot-password-sent');
	            exit;
	        } else {
	            $errorWhileSending = true;
	        }
	
	    } else {
	        if (!$u1->exists()) $noSuchAccount = true;
	        if ($u1->exists() && !$u1->isActivated()) $accountNotActivated = true;
	    }
	}
	// Formular
	$tpl->fetch(account/forgot-password.tpl);
	}
	
	// ---- Zusatzfunktionen ----
	
	// Zeichenkette erstellen
	// verwendet von :
	// activation-email & register & forgot_password
	function genString($len) {
	    $s = '';
	    for ($i = 1; $i <= $len; ++$i) {
	        $rand = rand(1, 3);
	        switch ($rand) {
	            case 1:
	                $s .= chr(rand(48, 57)); // [0-9]
	                break;
	            case 2:
	                $s .= chr(rand(97, 122)); // [a-z]
	                break;
	            case 3:
	                $s .= chr(rand(65, 90)); // [A-Z]
	                break;
	        }
	    }
	    return $s;
	}
	
}
?>