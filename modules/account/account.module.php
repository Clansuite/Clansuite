<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-Andre Koch © 2005 - onwards
    * http://www.clansuite.com/
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
    * @author     Jens-Andre Koch <vain@clansuite.com>
    * @copyright  Copyleft: All rights reserved. Jens-Andre Koch (2005 - onwards)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    * @since      File available since Release 0.2
    *
    * @version    SVN: $Id$
    */

//Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}


/**
 * Clansuite
 *
 * Module:  Account (User Account Registration/ Login / Logout etc. )
 *
 */
class Module_Account extends ModuleController implements Clansuite_Module_Interface
{
    /**
     * Module_Admin -> Execute
     */
    public function execute(httprequest $request, httpresponse $response)
    {
        # proceed to the requested action
        $this->processActionController($request);
        # read module config
        #$this->config->readConfig( ROOT_MOD . '/admin/admin.config.php');
    }

    /**
    * @desc First function to run - switches between $_REQUEST['action'] Vars to the functions
    * @desc Loads necessary language files
    */

    function auto_run()
    {
        global $lang, $trail;

        switch ($_REQUEST['action'])
        {
            // Login
            case 'login':
                $trail->addStep($lang->t('Login'), '/index.php?mod=account&amp;action=login');
                $this->login();
                break;

            // Logout
            case 'logout':
                $trail->addStep($lang->t('Logout'), '/index.php?mod=account&amp;action=logout');
                $this->logout();
                break;

            // Registration
            case 'register':
                $trail->addStep($lang->t('Registration'), '/index.php?mod=account&amp;action=registration');
                $this->register();
                break;

            // Activate Account
            case 'activate_account':
                $trail->addStep($lang->t('Activate account'), '/index.php?mod=account&amp;action=activate_account');
                $this->activate_account();
                break;

            // Send Activation Email
            case 'activation_email':
                $trail->addStep($lang->t('Resend activation email'), '/index.php?mod=account&amp;action=activation_email');
                $this->activation_email();
                break;

            // Forgot Password
            case 'forgot_password':
                $trail->addStep($lang->t('Forgot Password'), '/index.php?mod=account&amp;action=forgot_password');
                $this->forgot_password();
                break;

            // Activate Password
            case 'activate_password':
                $trail->addStep($lang->t('Activate Password'), '/index.php?mod=account&amp;action=activate_password');
                $this->activate_password();
                break;

            // Default Action: if authed present logout, else login
            default:
                if ( $_SESSION['user']['authed'] == 1 )
                {
                    $this->logout();
                }
                else
                {
                    $this->login();
                }
                break;
        }


        return array( 'OUTPUT'          => $this->output,
                      'ADDITIONAL_HEAD' => $this->additional_head,
                      'SUPPRESS_WRAPPER'=> $this->suppress_wrapper );
    }

    /**
     * Login
     */
   public function login($param_array, $smarty)
    {
        #var_dump($smarty);
        
        // Set Pagetitle and Breadcrumbs
        trail::addStep( _('Login'), '/index.php?mod=account&amp;action=login');
       
        // Get Inputvariables from $_POST
        $nick        = $_POST['nickname'];
        $email       = $_POST['email'];
        $password    = $_POST['password'];
        $remember_me = $_POST['remember_me'];
        $submit      = $_POST['submit'];
        $referer	 = $_GET['referer'];

        // Set Error Array
        $error = array();

        $config = parent::getInjector()->instantiate('Clansuite_Config');

        // Determine the Login method
        if( $config->login_method == 'nick' )
        {
            $value = $nick;
        }

        if( $config->login_method == 'email' )
        {
            $value = $email;
        }

        // get user class
        $user = parent::getInjector()->instantiate('Clansuite_User');

        // Perform checks on Inputvariables & Form filled?
        if ( isset($value) && !empty($value) && !empty($password) )
        {
            // check, if user_id exists
            $user_id = $user->check_user($config->login_method, $value, $password);

            // proceed if true
            if ($user_id != false)
            {
                // perform login for user_id and redirect
                
                $user->login( $user_id, $remember_me, $password );
                
                #$this->redirect( !empty($referer) ? WWW_ROOT . '/' . base64_decode($referer) : 'index.php', 'metatag|newsite', 3 , $lang->t('You successfully logged in...') );
            }
            else
            {
                // log the login attempts to ban the ip at a specific number
                if (!isset($_SESSION['login_attempts']))
                {
                    $_SESSION['login_attempts'] = 1;
                }
                else
                {
                    if( !defined('LOGIN_ALREADY') )
                    {
                        define('LOGIN_ALREADY', 1);
                        $_SESSION['login_attempts']++;
                    }
                }

                // ban ip
                if ( $_SESSION['login_attempts'] > $config->max_login_attempts )
                {
                    die( $this->redirect('http://www.clansuite.com', 'metatag|newsite', 5 , $lang->t('You are temporarily banned for the following amount of minutes:').'<br /><b>'.$config->login_ban_minutes.'</b>' ) );
                }

                // Error Variables
                $error['mismatch'] = 1;
                $error['login_attempts'] = $_SESSION['login_attempts'];
            }
        }
        else
        {
            if ( isset ( $submit ) )
            { $error['not_filled'] = 1; }
        }

        # Get Render Engine
        # => already loaded ...
        #$this->smarty = $this->getView();
        
        // Login Form / User Center
        if ( $_SESSION['user']['user_id'] == 0 )
        {
            // Assing vars & output template            
            $smarty->assign('cfg', $config);
            $smarty->assign('err', $error);
            $smarty->assign('referer', $referer);
            echo $smarty->fetch('account/login.tpl');
        }
        else
        {
            //  Show usercenter
           echo $smarty->fetch('account/usercenter.tpl');
        }
    }

    /**
    * @desc Logout
    *
    * @input: $confirm
    *
    * If logout is confirmed:
    *
    * Destroy Session
    * Delete Cookie
    * Redirect to index.php
    *
    * else:
    * @output: $tpl->fetch( 'account/logout.tpl' )
    *
    */

    function logout()
    {
        global $session, $functions, $tpl, $lang;

        // Get Inputvariables from $_POST
        $confirm = $_POST['confirm'];

        if( $confirm == '1' )
        {
            // Destroy the session
            $session->_session_destroy(session_id());

            // Delete cookies
            setcookie('user_id', false );
        	setcookie('password', false );

            // Redirect
            $this->redirect( 'index.php', 'metatag|newsite', 3, $lang->t( 'You have successfully logged out...') );
        }
        else
        {
            $this->output .= $tpl->fetch( 'account/logout.tpl' );
        }
    }

    /**
    * @desc Register a User
    *
    * Get $_POST INPUT
    * @Input: email, email2, nick, password, password2, submit, captcha
    *
    * Perform checks on $_POST
    * Generate Activation Code
    * Insert User into DB -> VALUES (:email, :nick, :password, :joined, :code)');
    * Get user id
    * Load Mail & Send mail to User
    * Assign Captcha to Template
    * Show template
    *
    * @Output :  $tpl->fetch('account/register.tpl');
    *
    */

    function register()
    {
        global $db, $tpl, $config, $input, $functions, $error, $security, $lang;

        // Get Inputvariables from $_POST
        $email      = $_POST['email'];
        $email2     = $_POST['email2'];
        $nick       = $_POST['nick'];
        $pass       = $_POST['password'];
        $pass2      = $_POST['password2'];
        $submit     = $_POST['submit'];
        $captcha    = $_POST['captcha'];

        // Set Error Array
        $err = array();

        // Perform checks on Inputvariables & Form filled?
        if ( empty($email) OR empty($email2) OR empty($nick) OR empty($pass) OR empty($pass2) )
        {
            if( isset($submit) )
            {
                // Not all necessary fields are filled
                $err['not_filled'] = 1;
            }
        }
        else
        {   // Form is filled

            // Check both emails match
            if ($email != $email2 )
            {
                $err['emails_mismatching'] = 1;
            }

            // Check email
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

            // Check for correct Captcha
            if (strtolower($captcha) != strtolower($_SESSION['captcha_string']) )
            {
                $err['wrong_captcha'] = 1;
            }

            // Check the password
            if ($input->check($pass, 'is_pass_length') == false )
            {
                $err['pass_too_short'] = 1;
            }

            // Check if email already exists
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

            // No errors - then proceed
            // Register the user!
            if ( count($err) == 0  )
            {
                // Generate activation code & salted hash

                $code = md5 ( microtime() );
                $hash = $security->db_salted_hash($pass);

                // Insert user into DB
                $stmt = $db->prepare('INSERT INTO '. DB_PREFIX .'users (email, nick, password, joined, code) VALUES (:email, :nick, :password, :joined, :code)');
                $stmt->execute( array(  ':code'         => $code,
                                        ':email'        => $email,
                                        ':nick'         => $nick,
                                        ':password'     => $hash,
                                        ':joined'       => time() ) );

                // Get user id
                $stmt = $db->prepare('SELECT user_id FROM ' . DB_PREFIX .'users WHERE email = ? AND nick = ?' );
                $stmt->execute( array( $email, $nick ) );
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                // Load mailer & send mail
                require ( ROOT_CORE . '/mail.class.php' );
                $mailer = new mailer;

                $to_address     = '"' . $nick . '" <' . $email . '>';
                $from_address   = '"' . $config->fromname . '" <' . $config->from . '>';
                $subject        = $lang->t('Account activation');

                $body  = $lang->t("To activate your account click on the link below:\r\n");
                $body .= WWW_ROOT."/index.php?mod=account&action=activate_account&user_id=%s&code=%s\r\n";
                $body .= "----------------------------------------------------------------------------------------------------------\r\n";
                $body .= $lang->t('Username').": %s\r\n";
                $body .= $lang->t('Password').": %s\r\n";
                $body .= "----------------------------------------------------------------------------------------------------------\r\n";
                $body  = sprintf($body, $user['user_id'], $code, $nick, $pass);

                // Send mail
                if ( $mailer->sendmail($to_address, $from_address, $subject, $body) == true )
                {
                    $this->redirect( 'index.php', 'metatag|newsite', 3, $lang->t('You have sucessfully registered! Please check your mailbox...') );
                }
                else
                {
                    $this->output .= $error->show( $lang->t( 'Mailer Error' ), $lang->t( 'There has been an error in the mailing system. Please inform the webmaster.' ), 2 );
                    return;
                }
            }
        }

        // Assign vars
        $tpl->assign( 'min_length', $config->min_pass_length );
        $tpl->assign( 'err', $err );
        $tpl->assign( 'captcha_url',  WWW_ROOT . '/index.php?mod=captcha&' . session_name() . '=' . session_id() );

        // Get the template
        $this->output .= $tpl->fetch('account/register.tpl');
    }

    /**
    * @desc Re-Send Activation Email
    */

    function activation_email()
    {
        global $db, $functions, $lang, $security, $tpl, $input;

        // Get Inputvariables from $_POST
        $email  = $_POST['email'];
        $submit = $_POST['submit'];

        // Perform checks on Inputvariables & Form filled?
        if ( empty($email) )
        {
            if ( !empty ( $submit ) )
            {
                $err['form_not_filled'] = 1;
            }
        }
        else
        {   // Form filled -> proceed

            if ( !$input->check( $email, 'is_email' ) )
            {
                $err['email_wrong'] = 1;
            }

            // No Input-Errors
            if ( count($err) == 0 )
            {
                // Select WHERE email
                $stmt = $db->prepare( 'SELECT user_id,nick FROM ' . DB_PREFIX . 'users WHERE email = ?' );
                $stmt->execute( array($email) );
                $res = $stmt->fetch();

                // Email was not found
                if ( !is_array($res) )
                {
                    $err['no_such_mail'] = 1;
                }
                else
                {
                    // Email already activated
                    if ( $res['activated'] == 1 )
                    {
                        $err['already_activated'];
                    }

                    // Email was found & is not active
                    if ( count($err) == 0 )
                    {
                        // Prepare user_id, nick, and activation code
                        $user_id = $res['user_id'];
                        $nick    = $res['nick'];
                        $code    = md5 ( microtime() );

                        // Insert Code into DB WHERE user_id
                        $stmt = $db->prepare( 'UPDATE ' . DB_PREFIX . 'users SET code = ? WHERE user_id = ?' );
                        $stmt->execute( array ( $code, $user_id ) );

                        // Load mailer & Prepare mail
                        require ( ROOT_CORE . '/mail.class.php' );
                        $mailer = new mailer;

                        $to_address     = '"' . $nick . '" <' . $email . '>';
                        $from_address   = '"' . $config->fromname . '" <' . $config->from . '>';
                        $subject        = $lang->t('Account activation (again)');

                        $body  = $lang->t("To activate your account click on the link below:\r\n");
                        $body .= WWW_ROOT."/index.php?mod=account&action=activate_account&user_id=%s&code=%s\r\n";
                        $body .= "----------------------------------------------------------------------------------------------------------\r\n";
                        $body .= $lang->t('Username').": %s\r\n";
                        $body .= "----------------------------------------------------------------------------------------------------------\r\n";
                        $body  = sprintf($body, $user_id, $code, $nick);

                        // Send mail
                        if ( $mailer->sendmail($to_address, $from_address, $subject, $body) == true )
                        {
                            $this->redirect( 'index.php', 'metatag|newsite', 3, $lang->t('You have sucessfully received the activation mail! Please check your mailbox...') );
                        }
                        else
                        {
                            $this->output .= $error->show( $lang->t( 'Mailer Error' ), $lang->t( 'There has been an error in the mailing system. Please inform the webmaster.' ), 2 );
                            return;
                        }
                    }
                }
            }
        }

        // Assign tpl vars
        $tpl->assign( 'err', $err );

        // Output
        $this->output .= $tpl->fetch('account/activation_email.tpl');
    }

    /**
    * @desc Activate Account
    *
    * @input: user_id, code
    *
    * validate code
    * SELECT activated WHERE user_id and code
    * 1. code wrong for user_id
    * 2. code found, but already activated=1
    * 3. code found, SET activated=1
    *
    * @output:
    *
    */

    function activate_account()
    {
        global $input, $db, $error, $lang, $functions;

        // Get Inputvariables from $_GET
        $user_id = (int) $_GET['user_id'];
        $code    = $input->check($_GET['code'], 'is_int|is_abc') ? $_GET['code'] : false;

        // Activation code is wrong
        if ( !$code )
        {
            $this->output .= $error->show( $lang->t( 'Code Failure' ), $lang->t('The given activation code is wrong. Please make sure you copied the whole activation URL into your browser.'), 2 );
            return;
        }

        // SELECT activated WHERE user_id and code
        $stmt = $db->prepare( 'SELECT activated FROM ' . DB_PREFIX . 'users WHERE user_id = ? AND code = ?' );
        $stmt->execute( array( $user_id, $code ) );
        $res = $stmt->fetch();

        if ( is_array ( $res ) )
        {
            // Account already activated
            if ( $res['activated'] == 1 )
            {
                $this->output .= $error->show( $lang->t( 'Already' ), $lang->t('This account has been already activated.'), 2 );
                return;
            }
            else
            {
                // UPDATE activated=1 WHERE user_id
                $stmt = $db->prepare( 'UPDATE ' . DB_PREFIX . 'users SET activated = ? WHERE user_id = ?' );
                $stmt->execute( array ( 1, $user_id ) );
                $this->redirect( 'index.php?mod=account&action=login', 'metatag|newsite', 3, $lang->t('Your account has been activated successfully - please login.') );
            }
        }
        else
        {   // Activation Code not matching user_id
            $this->output .= $error->show( $lang->t( 'Code Failure' ), $lang->t('The activation code does not match to the given user id'), 2 );
            return;
        }
    }

    /**
    * @desc Forgot Password
    */

    function forgot_password()
    {
        global $db, $functions, $lang, $security, $tpl, $input;

        $email = $_POST['email'];

        if( empty($email) )
        {
            $err['form_not_filled'] = 1;
        }
        else
        {
            if ( !$input->check( $email, 'is_email' ) )
            {
                $err['email_wrong'] = 1;
            }

            if ( count($err) == 0 )
            {
                $stmt = $db->prepare( 'SELECT user_id,nick FROM ' . DB_PREFIX . 'users WHERE email = ?' );
                $stmt->execute( array($email) );
                $res = $stmt->fetch();

                if ( !is_array($res) )
                {
                    $err['no_such_mail'] = 1;
                }
                else
                {
                    if ( count($err) == 0 )
                    {
                        $random   = $functions->random_string(7);
                        $user_id  = $res['user_id'];
                        $nick     = $res['nick'];
                        $code     = md5 ( microtime() );
                        $new_pass = $security->db_salted_hash($random);

                        $stmt = $db->prepare( 'UPDATE ' . DB_PREFIX . 'users SET code = ?, new_password = ? WHERE user_id = ?' );
                        $stmt->execute( array ( $code, $new_pass, $user_id ) );

                        // Load mailer
                        require ( ROOT_CORE . '/mail.class.php' );
                        $mailer = new mailer;

                        $to_address     = '"' . $nick . '" <' . $email . '>';
                        $from_address   = '"' . $config->fromname . '" <' . $config->from . '>';
                        $subject        = $lang->t('Password reset');

                        $body  = $lang->t("Your password would be resetted by clicking on this link:\r\n");
                        $body .= WWW_ROOT."/index.php?mod=account&action=activate_password&user_id=%s&code=%s\r\n";
                        $body .= "----------------------------------------------------------------------------------------------------------\r\n";
                        $body .= $lang->t('Username').": %s\r\n";
                        $body .= $lang->t('New Password').": %s\r\n";
                        $body .= "----------------------------------------------------------------------------------------------------------\r\n";
                        $body  = sprintf($body, $user_id, $code, $nick, $random);

                        // Send mail
                        if ( $mailer->sendmail($to_address, $from_address, $subject, $body) == true )
                        {
                            $this->redirect( 'index.php', 'metatag|newsite', 3, $lang->t('You have sucessfully received the password activation mail! Please check your mailbox...') );
                        }
                        else
                        {
                            $this->output .= $error->show( $lang->t( 'Mailer Error' ), $lang->t( 'There has been an error in the mailing system. Please inform the webmaster.' ), 2 );
                            return;
                        }
                    }
                }
            }
        }

        // Assign tpl vars
        $tpl->assign( 'err', $err );

        // Output
        $this->output .= $tpl->fetch('account/forgot_password.tpl');
    }

    /**
    * @desc Activate Password
    */

    function activate_password()
    {
        global $input, $db, $error, $lang, $functions;

        $user_id = (int) $_GET['user_id'];
        $code    = $input->check($_GET['code'], 'is_int|is_abc') ? $_GET['code'] : false;

        if ( !$code )
        {
            $this->output .= $error->show( $lang->t( 'Code Failure' ), $lang->t('The given activation code is wrong. Please make sure you copied the whole activation URL into your browser.'), 2 );
            return;
        }

        $stmt = $db->prepare( 'SELECT user_id,activated,new_password FROM ' . DB_PREFIX . 'users WHERE user_id = ? AND code = ?' );
        $stmt->execute( array( $user_id, $code ) );
        $res = $stmt->fetch();
        if ( is_array ( $res ) )
        {
            if ( empty($res['new_password']) )
            {
                $this->output .= $error->show( $lang->t( 'Already' ), $lang->t('There has been no password reset request.'), 2 );
                return;
            }
            else
            {
                $stmt = $db->prepare( 'UPDATE ' . DB_PREFIX . 'users SET password = new_password WHERE user_id = ?' );
                $stmt->execute( array ( $user_id ) );

                setcookie('user_id', false);
                setcookie('password', false);

                $this->redirect( 'index.php?mod=account&action=login', 'metatag|newsite', 3, $lang->t('Your new password has been successfully activated. Please login...') );
            }
        }
        else
        {
            $this->output .= $error->show( $lang->t( 'Code Failure' ), $lang->t('The activation code does not match to the given user id'), 2 );
            return;
        }
    }
}