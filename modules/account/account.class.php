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
    die('You are not allowed to view this page statically.' );
}


//----------------------------------------------------------------
// Start Module Account Class
//----------------------------------------------------------------
class module_account
{
    public $output          = '';
    public $mod_page_title  = '';
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
                $title = ' Registration ';
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
        $this->mod_page_title = $lang->t('User :: ' . $title );
        
        return array( 'OUTPUT'          => $this->output,
                      'MOD_PAGE_TITLE'  => $this->mod_page_title,
                      'ADDITIONAL_HEAD' => $this->additional_head );
    }
    
    //----------------------------------------------------------------
    // Login
    //----------------------------------------------------------------
    function login()
    {
        global $tpl, $users, $functions;
        
        $email = $_POST['email'];
        $password = $_POST['password'];
        $rememberme = $_POST['remember'];
        
        if ($email && $password)
        {
            
            $userid = $users->check_user($email, $password);
            
            if ($userid != false)
            {
                $users->login($userid, $rememberme);
                $functions->redirect('/index.php?mod=admin');
                exit;
            }
            else
            {                
                // User existiert nicht oder Passwort falsch!
                unset($user['{/php}']);
                
                // Login-Attempts
                // bei 3-5 versuchen 20min ip ban?
                if (!isset($_SESSION['login_attempts']))
                {
                    $_SESSION['login_attempts'] = '1';
                }
                else
                {
                    $_SESSION['login_attempts']++;
                }
                
                #echo 'Ihre Anmeldedaten waren nicht korrekt!';
                #echo 'Dies ist Ihr '. $_SESSION['login_attempts'].'ter Versuch sich anzumelden !';
                
            }
        }
        $this->output .= $tpl->fetch('account/login.tpl');
    }
    
    //----------------------------------------------------------------
    // Logout
    //----------------------------------------------------------------
    function logout()
    {
        global $session, $functions;
        
        $session->_session_destroy;
        
        $functions->redirect('/index.php', 'metatag', '2' );
    }
    
    //----------------------------------------------------------------
    // Register
    //----------------------------------------------------------------
    function register()
    {
        global $db, $tpl, $cfg, $input, $functions, $error, $security, $lang;
        
        $email  = $_POST['email'];
        $email2 = $_POST['email2'];
        $nick   = $_POST['nick'];
        $pass   = $_POST['password'];
        $pass2  = $_POST['password2'];
        
        $err = array();
        
        if ( empty($email) OR empty($email2) OR empty($nick) OR empty($pass) OR empty($pass2) )
        {
            if( isset($_POST['submit']) )
            {
                // Not all necessary fields are filled
                $err['not_filled'] = 1;
            }
        }
        else
        {
            //----------------------------------------------------------------
            // Form is filled
            //----------------------------------------------------------------

            // Check both mails
            if ($email != $email2 )
            {
                $err['emails_mismatching'] = 1;
            }
            
            // Check mail
            if ($input->check($email, 'is_email' ) == false )
            {
                $err['email_wrong'] = 1;
            }
            
            // Check nick
            if ($input->check($nick, 'is_abc|is_int|is_custom', '-_()<>[]|.:\'{}$', 25 ) == false )
            {
                $err['nick_wrong'] = 1;
            }

            // Check both passwords
            if ($pass != $pass2 )
            {
                $err['passes_do_not_fit'] = 1;
            }
            
            // Check password
            if ($input->check($pass, 'is_pass_length') == false )
            {
                $err['pass_too_short'] = 1;
            }
                        
            // Check if mail already exists
            $stmt = $db->prepare('SELECT COUNT(email) FROM ' . DB_PREFIX .'users WHERE email = ?' );
            $stmt->execute( array( $email ) );
            if ($stmt->fetchColumn() > 0)
            {
                $err['email_exists'] = 1;
            }
                        
            // Check if nick already exists
            $stmt = $db->prepare('SELECT COUNT(nick) FROM ' . DB_PREFIX .'users WHERE nick = ?' );
            $stmt->execute( array( $nick ) );
            if ($stmt->fetchColumn() > 0)
            {
                $err['nick_exists'] = 1;
            }
            
            //----------------------------------------------------------------
            // No errors - then proceed
            // Register the user!
            //----------------------------------------------------------------
            if ( count($err) == 0  )
            {               
                // user eintragen
                $stmt = $db->prepare('INSERT INTO '. DB_PREFIX .'users (email, nick, password, joined) VALUES (:email, :nick, :password, :joined)');
                $stmt->execute( array(  ':email'        => $email,
                                        ':nick'         => $nick,
                                        ':password'     => $security->build_salted_hash($password),
                                        ':joined'       => time() ) );
                
                // user_id ermitteln
                // old: $user_id = $stmt->lastInsertId();
                $stmt = $db->prepare('SELECT user_id FROM ' . DB_PREFIX .'users WHERE email = ? AND nick = ?' );
                $stmt->execute( array( $email, $nick ) );
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                
                // mailer laden
                require ( ROOT . '/core/mail.class.php' );
                $mailer = new mailer;
                
                $to_address     = '"' . $nick . '" <' . $email . '>';
                $from_address   = '"' . $cfg->fromname . '" <' . $cfg->from . '>';
                $subject        = $lang->t('Account activation');    
                
                $body  = $lang->t("To activate your account click on the link below:\r\n");
                $body .= WWW_ROOT."/index.php?mod=account&action=activate-account&user_id=%s&code=%s\r\n";
                $body .= "Password: %s";
                $body  = sprintf($body, $user['user_id'], $security->build_salted_hash($password), $password);
                                              
                // mail senden
                if ($mailer->sendmail($to_address, $from_address, $subject, $body) == true )
                {
                    $functions->redirect('/index.php?mod=account&action=register-done', 'metatag|newsite', 3, $lang->t('You have sucessfully registered! Please check your mailbox...') );
                }
                else
                {
                    $functions->redirect('/index.php?mod=account&action=register-error');
                    exit;
                }
            }
        }
        
        // Assign vars
        $tpl->assign( 'min_length', $cfg->min_pass_length );
        $tpl->assign( 'err', $err );
        
        // Get the template
        $this->output .= $tpl->fetch('account/register.tpl');
    }
    
    //----------------------------------------------------------------
    // Send Activation Email
    //----------------------------------------------------------------
    function activation_email()
    {
        global $db, $functions;
        
        $email = $_POST['email'];
        
        $noSuchAccount = false;
        $errorWhileSending = false;
        $alreadyActivated = false;
        
        if ($email)
        {
            $u1 = new User(null, $email);
            
            if ($u1->exists() && !$u1->isActivated())
            {
                
                $password = genString(6);
                $stmt = $db->prepare('UPDATE '. DB_PREFIX .'users SET password = ? WHERE user_id = ?');
                $stmt->execute(array(md5($password), $u1->getId() ) );
                 
                require ( ROOT.'/core/mail.class.php' );
                $mailer = new mailer;
                $to_address = '"' . $nick . '" <' . $email . '>';
               
                $from_address = '"' . $cfg->fromname . '" <' . $cfg->from . '>';
                
                $subject = 'Account activation';
                
                $body  = "To activate an account click on the link below:\r\n";
                $body .= WWW_ROOT."/index.php?mod=account&action=activate-account&user_id=%s&code=%s\r\n";
                $body .= "Password: %s";
                $body  = sprintf($body, $u1->getId(), md5(md5($password)), $password);
                
                // mail senden
                if ($mailer->sendmail($to_address, $from_address, $subject, $body) == true )
                {
                    $functions->redirect('/index.php?mod=account&action=activation-email-sent');
                    exit;
                }
                else
                {
                    $errorWhileSending = true;
                }
                
            }
            else
            {
                if (!$u1->exists())
                {
                    $noSuchAccount = true;
                }
                if ($u1->exists() && $u1->isActivated())
                {
                    $alreadyActivated = true;
                }
            }
        }
    }
    
    //----------------------------------------------------------------
    // Activate Account
    //----------------------------------------------------------------
    function activate_account()
    {
        
        $user_id = (int) $_GET['user_id'];
        $code = $_GET['code'];
        
        $alreadyActivated = false;
        $success = false;
        
        if ($user_id && $code)
        {
            $stmt = $db->prepare('SELECT activated FROM ' . DB_PREFIX .'users WHERE user_id = ?' );
            $stmt->execute(array($user_id ) );
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user)
            {
                if ($user['status'] != 0)
                {
                    $alreadyActivated = true;
                }
                else
                if ($code == md5($user['password']))
                {
                    $stmt = $db->prepare('UPDATE users SET level = 1 WHERE user_id = ?');
                    $stmt->execute(array($user_id ) );
                    $success = true;
                }
            }
        }
        $tpl->fetch(account/activate-account.tpl);
    }
    
    //----------------------------------------------------------------
    // Activate Password
    //----------------------------------------------------------------
    function activate_password()
    {
        $user_id = (int) $_GET['user_id'];
        $code = $_GET['code'];
        
        $noNewPassword = false;
        $success = false;
        
        if ($user_id && $code)
        {
            $stmt = $db->prepare('SELECT password, new_password FROM ' . DB_PREFIX .'users WHERE user_id = ?' );
            $stmt->execute(array($user_id ) );
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user)
            {
                if ($code == md5($user['new_password']))
                {
                    $stmt = $db->prepare('UPDATE ' . DB_PREFIX .'users SET :password, :new_password WHERE :user_id' );
                    $stmt->execute(array(':password'       => $user['new_password'],
                    ':newpassword' => null,
                    ':userid'     => $user_id )
                    );
                    $success = true;
                }
                else
                {
                    if ($user['new_password'] === null)
                    {
                        $noNewPassword = true;
                    }
                }
            }
            unset($user);
        }
    }
    
    
    //----------------------------------------------------------------
    // Forgot Password
    //----------------------------------------------------------------
    function forgot_password()
    {
        global $db, $tpl, $functions;
        
        $email = $_POST['email'];
        
        $errorWhileSending = false;
        $noSuchAccount = false;
        $accountNotActivated = false;
        
        if ($email)
        {
            $u1 = new User(null, $email);
            if ($u1->exists() && $u1->isActivated())
            {
                
                $password = genString(6);
                $stmt = $db->prepare('UPDATE '. DB_PREFIX .'users SET new_password = ? WHERE user_id = ?');
                $stmt->execute(array(md5($password), $u1->getId()));
                
                require ( ROOT.'/core/mail.class.php' );
                $mailer = new mailer;
                
                $to_address = '"' . $email . '" <' . $email . '>';
               
                $from_address = '"' . $cfg->fromname . '" <' . $cfg->from . '>';
                
                $subject = 'New password';
                
                $body  = "To activate new password click on the link below:\r\n";
                $body .= WWW_ROOT."/index.php?mod=account&action=activate-password&user_id=%s&code=%s\r\n";
                $body .= "New Password: %s";
                $body  = sprintf($body, $u1->getId(), md5(md5($password)), $password);
                                
                // mail senden
                if ($mailer->sendmail($to_address, $from_address, $subject, $body) == true )
                {
                    header('location: index.php?mod=account&action=forgot-password-sent');
                    exit;
                }
                else
                {
                    $errorWhileSending = true;
                }
                
            }
            else
            {
                if (!$u1->exists())
                {
                    $noSuchAccount = true;
                }
                if ($u1->exists() && !$u1->isActivated())
                {
                    $accountNotActivated = true;
                }
            }
        }
        // Formular
        $tpl->fetch(account/forgot-password.tpl);
    }

    // ---- Zusatzfunktionen ----

    // Zeichenkette erstellen
    // verwendet von :
    // activation-email & register & forgot_password
    function genString($len)
    {
        $s = '';
        for ($i = 1; $i <= $len; ++$i)
        {
            $rand = rand(1, 3);
            switch ($rand)
            {
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