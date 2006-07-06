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
                
                // ---- ( Activations ) ----
                
            case 'activate_account':
                $title = ' Activate account ';
                $this->activate_account();
                break;
                
            case 'activation_email':
                $this->activation_email();
                break;
                
                // ----- ( forgot password ) ----
                
            case 'forgot_password':
                $this->forgot_password();
                $title = ' Forgot Password ';
                break;
                
                // ----- ( default ) ----
                
            default:
                if ( $_SESSION['user']['authed'] == 1 )
                {
                    $title = ' Logout ';
                    $this->logout();
                }
                else
                {
                    $title = ' Login ';
                    $this->login();
                }
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
        global $tpl, $users, $functions, $cfg, $lang;
        
        $nick        = $_POST['nickname'];
        $email       = $_POST['email'];
        $password    = $_POST['password'];
        $remember_me = $_POST['remember_me'];
        $submit      = $_POST['submit'];
        
        $err = array();
        
        //----------------------------------------------------------------
        // Login method
        //----------------------------------------------------------------
        if( $cfg->login_method == 'nick' )
        { $value = $nick; }
        if( $cfg->login_method == 'email' )
        { $value = $email; }

        //----------------------------------------------------------------
        // Form filled?
        //----------------------------------------------------------------
        if ( isset($value) && !empty($password) && !empty($value) )
        {
            
            $user_id = $users->check_user($cfg->login_method, $value, $password);

            if ($user_id != false)
            {
                $users->login( $user_id, $remember_me, $password );
                $functions->redirect('/index.php?mod=admin', 'metatag|newsite', 3 , $lang->t('You successfully logged in...') );
            }
            else
            {              
                //----------------------------------------------------------------
                // Log login attempts
                // At a specific number, ban ip
                //----------------------------------------------------------------
                if (!isset($_SESSION['login_attempts']))
                {
                    $_SESSION['login_attempts'] = 1;
                }
                else
                {
                    $_SESSION['login_attempts']++;
                }
                
                //----------------------------------------------------------------
                // Ban ip
                //----------------------------------------------------------------
                if ( $_SESSION['login_attempts'] > $cfg->max_login_attempts )
                {
                    die( $functions->redirect('http://www.clansuite.com', 'metatag|newsite', 5 , $lang->t('You are temporarily banned for the following amount of minutes:').'<br /><b>'.$cfg->login_ban_minutes.'</b>' ) );
                }
                
                //----------------------------------------------------------------
                // Error: Mismatch & Login Attempts
                //----------------------------------------------------------------
                $err['mismatch'] = 1;
                $err['login_attempts'] = $_SESSION['login_attempts'];                
            }
        }
        else
        {
            if ( isset ( $submit ) )
            { $err['not_filled'] = 1; }
        } 
        
        // Assign Vars
        $tpl->assign('cfg', $cfg);
        $tpl->assign('err', $err);
        
        // Output Template
        $this->output .= $tpl->fetch('account/login.tpl');
    }
    
    //----------------------------------------------------------------
    // Logout
    //----------------------------------------------------------------
    function logout()
    {
        global $session, $functions, $tpl, $lang;
        
        $confirm = $_POST['confirm'];
        
        if( $confirm == '1' )
        {
            //----------------------------------------------------------------
            // Destrox the session
            //----------------------------------------------------------------
            $session->_session_destroy(session_id());

            //----------------------------------------------------------------
            // Delete cookies
            //----------------------------------------------------------------
            setcookie('user_id', false );
        	setcookie('password', false );
            
            //----------------------------------------------------------------
            // Redirect
            //----------------------------------------------------------------             
            $functions->redirect('/index.php', 'metatag|newsite', 3, $lang->t( 'You have successfully logged out...') );
        }
        else
        {
            $this->output .= $tpl->fetch( 'account/logout.tpl' );
        }
    }
    
    //----------------------------------------------------------------
    // Register
    //----------------------------------------------------------------
    function register()
    {
        global $db, $tpl, $cfg, $input, $functions, $error, $security, $lang;
        
        $email      = $_POST['email'];
        $email2     = $_POST['email2'];
        $nick       = $_POST['nick'];
        $pass       = $_POST['password'];
        $pass2      = $_POST['password2'];
        $submit     = $_POST['submit'];
        $captcha    = $_POST['captcha'];

        $err = array();
        
        if ( empty($email) OR empty($email2) OR empty($nick) OR empty($pass) OR empty($pass2) )
        {
            if( isset($submit) )
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

            // Check both passwords
            if (strtolower($captcha) != strtolower($_SESSION['captcha_string']) )
            {
                $err['wrong_captcha'] = 1;
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
                //----------------------------------------------------------------
                // Insert user into DB
                //----------------------------------------------------------------
                $stmt = $db->prepare('INSERT INTO '. DB_PREFIX .'users (email, nick, password, joined, code) VALUES (:email, :nick, :password, :joined, :code)');
                $stmt->execute( array(  ':code'         => md5 ( microtime() ),
                                        ':email'        => $email,
                                        ':nick'         => $nick,
                                        ':password'     => $security->db_salted_hash($pass),
                                        ':joined'       => time() ) );
                
                //----------------------------------------------------------------
                // Get user id (emulation)
                //----------------------------------------------------------------
                $stmt = $db->prepare('SELECT user_id FROM ' . DB_PREFIX .'users WHERE email = ? AND nick = ?' );
                $stmt->execute( array( $email, $nick ) );
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                
                //----------------------------------------------------------------
                // Load mailer & send mail
                //----------------------------------------------------------------
                require ( CORE_ROOT . '/mail.class.php' );
                $mailer = new mailer;
                
                $to_address     = '"' . $nick . '" <' . $email . '>';
                $from_address   = '"' . $cfg->fromname . '" <' . $cfg->from . '>';
                $subject        = $lang->t('Account activation');    
                
                $body  = $lang->t("To activate your account click on the link below:\r\n");
                $body .= WWW_ROOT."/index.php?mod=account&action=activate-account&user_id=%s&code=%s\r\n";
                $body .= "----------------------------------------------------------------------------------------------------------\r\n";
                $body .= $lang->t('Username').": %s\r\n";
                $body .= $lang->t('Password').": %s\r\n";
                $body .= "----------------------------------------------------------------------------------------------------------\r\n";
                $body  = sprintf($body, $user['user_id'], md5(microtime()), $nick, $pass);
                              
                // Send mail
                if ( $mailer->sendmail($to_address, $from_address, $subject, $body) == true )
                {
                    $functions->redirect('/index.php', 'metatag|newsite', 3, $lang->t('You have sucessfully registered! Please check your mailbox...') );
                }
                else
                {
                    $this->output .= $error->show( $lang->t( 'Mailer Error' ), $lang->t( 'There has been an error in the mailing system. Please inform the webmaster.' ), 2 );
                    return;
                }
            }
        }
        
        // Assign vars
        $tpl->assign( 'min_length', $cfg->min_pass_length );
        $tpl->assign( 'err', $err );
        $tpl->assign( 'captcha_url',  WWW_ROOT . '/index.php?mod=captcha&' . session_name() . '=' . session_id() );
        
        // Get the template
        $this->output .= $tpl->fetch('account/register.tpl');
    }
    
    //----------------------------------------------------------------
    // Re-Send Activation Email
    //----------------------------------------------------------------
    function activation_email()
    {
        global $db, $functions, $lang, $security, $tpl;
        
        $email = $_POST['email'];
        
        if( empty($email) )
        {
            $err['no_email'] = 1;   
        }
        
        $stmt = $db->prepare( 'SELECT user_id FROM ' . DB_PREFIX . 'users WHERE email = ?' );
        $stmt->execute( array($email) );
        $res = $stmt->fetch();
        
        if ( !is_array($res) )
        {
            $err['no_such_mail'];
        }
        else
        {
            if ( $res['activated'] == 1 )
            {
                $err['already_activated'];   
            }
            
            if ( count($err) == 0 )
            {
                $user_id = $res['user_id'];
                $nick    = $res['nick'];
                $code    = $res['code'];
                
                // Load mailer
                require ( CORE_ROOT . '/mail.class.php' );
                $mailer = new mailer;
                
                $to_address     = '"' . $nick . '" <' . $email . '>';
                $from_address   = '"' . $cfg->fromname . '" <' . $cfg->from . '>';
                $subject        = $lang->t('Account activation (again)');    
                
                $body  = $lang->t("To receive your activation email again click on the link below:\r\n");
                $body .= WWW_ROOT."/index.php?mod=account&action=activate-account&user_id=%s&code=%s\r\n";
                $body .= "----------------------------------------------------------------------------------------------------------\r\n";
                $body .= $lang->t('Username').": %s\r\n";
                $body .= "----------------------------------------------------------------------------------------------------------\r\n";
                $body  = sprintf($body, $user_id, $code, $nick);
                              
                // Send mail
                if ( $mailer->sendmail($to_address, $from_address, $subject, $body) == true )
                {
                    $functions->redirect('/index.php', 'metatag|newsite', 3, $lang->t('You have sucessfully received the activation mail! Please check your mailbox...') );
                }
                else
                {
                    $this->output .= $error->show( $lang->t( 'Mailer Error' ), $lang->t( 'There has been an error in the mailing system. Please inform the webmaster.' ), 2 );
                    return;
                }
            }
        }
        
        // Assign tpl vars
        $tpl->assign( 'err', $err );
        
        // Output
        $output .= $tpl->fetch('account/activation_email.tpl');
    }
    
    //----------------------------------------------------------------
    // Activate Account    
    //----------------------------------------------------------------
    function activate_account()
    {
        global $input, $db, $error;
        
        $user_id = (int) $_GET['user_id'];
        $code    = $input->check($_GET['code'], 'is_int|is_abc') ? $_GET['code'] : false;
        
        if ( !$code )
        {
            $this->output .= $error->show( $lang->t('The given activation code is wrong. Please make sure you copied the whole activation URL into your browser.'), 2 );
            return;
        }
        
        $stmt = $db->prepare( 'SELECT code,user_id FROM ' . DB_PREFIX . 'users WHERE user_id = ? AND code = ?' );
        $stmt->execute( array( $user_id, $code ) );
        $res = $db->fetch();
        if ( is_array ( $res ) )
        {
            if ( $res['activated'] == 1 )
            {
                $this->output .= $error->show( $lang->t('This account is already activated.'), 2 );
                return;
            }
            else
            {
                $stmt = $db->prepare( 'UPDATE ' . DB_PREFIX . 'users SET activated = ? WHERE user_id = ?' );
                $stmt->execute( array ( 1, $user_id ) );
                $functions->redirect( '/index.php', 'metatag|newsite', 3, $lang->t('Your account has been activated successfully.') );
            }
        }
        else
        {
            $this->output .= $error->show( $lang->t('The activation code does not match to the given user id'), 2 );
            return;
        }
    }    
    
    //----------------------------------------------------------------
    // Forgot Password
    //----------------------------------------------------------------
    function forgot_password()
    {
        global $db, $tpl, $functions;

                $new_pass   = $functions->random_string(6);
                $code       = md5 ( microtime() );
                
                $stmt = $db->prepare( 'UPDATE ' . DB_PREFIX .'users SET code = ? WHERE user_id = ?' );
                $stmt->execute( array( $code, $user_id ) );
        
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
                
                require ( CORE_ROOT . '/mail.class.php' );
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