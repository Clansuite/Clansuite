<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-André Koch © 2005 - onwards
    * http://www.clansuite.com/
    *
    * This file is part of "Clansuite - just an eSports CMS".
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
    * @author     Jens-André Koch <vain@clansuite.com>
    * @copyright  Jens-André Koch (2005 - onwards)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    *
    * @version    SVN: $Id$
    */

# Security Handler
if (defined('IN_CS') == false) { die('Clansuite not loaded. Direct Access forbidden.'); }

/**
 * Clansuite_Module_Account
 *
 * @category    Clansuite
 * @package     Modules
 * @subpackage  Account
 */
class Clansuite_Module_Account extends Clansuite_Module_Controller implements Clansuite_Module_Interface
{
    /**
     * Module_Admin -> Execute
     */
    public function initializeModule(Clansuite_HttpRequest $request, Clansuite_HttpResponse $response)
    {
        # read module config
        $this->getModuleConfig();

        parent::initModel('users');
    }

    public function action_show()
    {
        $this->setTemplate('action_login.tpl');
        $this->action_login();

        #$this->prepareOutput();
    }

    /**
     * Login Block
     */
    public function widget_login(&$item)
    {
        # @todo assign not the whole config, only the parameters need

        # Get Render Engine & Assign vars
        $this->getView()->assign('config', $this->getClansuiteConfig());
    }

    /**
     * @todo ban action
     */
    private function checkLoginAttemps()
    {
        if ( empty($_SESSION['login_attempts']) == false
             and $_SESSION['login_attempts'] >= $config['login']['max_login_attempts'] )
        {
            # @todo ban action
            $this->redirect('index.php', 3, '200',
            _('You are temporarily banned for the following amount of minutes:').'<br /><b>'
            .$config['login']['login_ban_minutes'].'</b>' );
            die();
        }
    }

    /**
     * Login
     */
    public function action_login()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Breadcrumb::add( _('Login'), '/index.php?mod=account&amp;action=login');

        # Get Objects
        $request = $this->injector->instantiate('Clansuite_HttpRequest');
        $config = $this->injector->instantiate('Clansuite_Config');

        # Get Input Variables
        # from $_POST
        $nick        = $request->getParameter('nickname');
        $email       = $request->getParameter('email');
        $password    = $request->getParameter('password');
        $remember_me = $request->getParameter('remember_me');
        $submit      = $request->getParameter('submit');
        # from $_GET
        $referer	 = $request->getParameter('referer');

        # Init Error Array
        $error = array();

        // Determine the Login method
        if( $config['login']['login_method'] == 'nick' )
        {
            $value = $nick;
            unset($nick);
        }
        elseif( $config['login']['login_method'] == 'email' )
        {
            $value = $email;
            unset($email);
        }

        // get user class
        $user = $this->injector->instantiate('Clansuite_User');

        // Perform checks on Inputvariables & Form filled?
        if ( isset($value) && !empty($value) && !empty($password) )
        {
            $this->checkLoginAttempts();

            // check whether user_id + password match
            $user_id = $user->checkUser($config['login']['login_method'], $value, $password);

            // proceed if true
            if ($user_id != false)
            {
                // perform login for user_id and redirect

                $user->loginUser( $user_id, $remember_me, $password );

                #$this->redirect( !empty($referer) ? WWW_ROOT . '/' . base64_decode($referer) : 'index.php', 'metatag|newsite', 3 , _('You successfully logged in...') );
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
                    # @todo whats LOGIN_ALREADY??
                    #if( !defined('LOGIN_ALREADY') )
                    #{
                        #define('LOGIN_ALREADY', 1);
                        $_SESSION['login_attempts']++;
                    #}
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
        $view = $this->getView();

        // Login Form / User Center
        if ( $_SESSION['user']['user_id'] == 0 )
        {
            // Assing vars & output template
            $view->assign('config', $config);
            $view->assign('error', $error);
            $view->assign('referer', $referer);


            #$this->prepareOutput();
            #return $smarty->fetch('account/login.tpl');
            //return $smarty->fetch('login.tpl');

        }
        else
        {
            //  Show usercenter
            #var_dump($smarty->template_dir);
            #var_dump($smarty->plugins_dir);
            $this->setTemplate('usercenter.tpl');
        }

        $this->prepareOutput();
    }

    /**
     * Logout
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
    public function action_logout()
    {
        // Set Pagetitle and Breadcrumbs
        Clansuite_Breadcrumb::add( _('Logout'), '/index.php?mod=account&amp;action=logout');

        // Get Inputvariables
        $request = $this->injector->instantiate('Clansuite_HttpRequest');

        // $_POST
        $confirm = (int) $request->getParameter('confirm');

        // User instance
        $user = $this->injector->instantiate('Clansuite_User');


        if( $confirm == 1 )
        {
            // Logout the user
            $user->logoutUser();

            // Redirect
            $this->redirect( 'index.php', 3, 200, _( 'You have successfully logged out...') );
            die();
        }
        else
        {
            $this->prepareOutput();
        }
    }

    /**
     * Register a User Account
     */
    public function action_register()
    {
        # Request Controller
        $request = $this->injector->instantiate('Clansuite_HttpRequest');
        $security = $this->injector->instantiate('Clansuite_Security');
        $config = $this->injector->instantiate('Clansuite_Config');
        $view = $this->getView();

        $user = $this->injector->instantiate('Clansuite_User');

        # Get Inputvariables from $_POST
        $nick       = $request->getParameter('nick');
        $email      = $request->getParameter('email');
        $email2     = $request->getParameter('email2');
        $pass       = $request->getParameter('password');
        $pass2      = $request->getParameter('password2');
        $submit     = $request->getParameter('submit');
        $captcha    = $request->getParameter('captcha');

        # Set Error Array
        $error = array();

        // Perform checks on Inputvariables & Form filled?
        if ( empty($email) or empty($email2) or empty($nick) or empty($pass) or empty($pass2) )
        {
            if( isset($submit) )
            {
                // Not all necessary fields are filled
                $error['not_filled'] = 1;
            }
        }
        else
        {   // Form is filled

            // Check both emails match
            if ($email != $email2 )
            {
                $error['emails_mismatching'] = 1;
            }

            // Check email
            if ($input->check($email, 'is_email' ) == false )
            {
                $error['email_wrong'] = 1;
            }

            // Check nick
            if ($input->check($nick, 'is_abc|is_int|is_custom', '-_()<>[]|.:\'{}$', 25 ) == false )
            {
                $error['nick_wrong'] = 1;
            }

            // Check both passwords
            /*
            if ($pass != $pass2 )
            {
                $err['passes_do_not_fit'] = 1;
            }

            // Check for correct Captcha
            if (strtolower($captcha) != strtolower($_SESSION['captcha_string']) )
            {
                $err['wrong_captcha'] = 1;
            }
            */

            // Check the password
            if (strlen($pass) < $config['login']['min_pass_length'])
            {
                $error['pass_too_short'] = 1;
            }

            // Check if email already exists
            $result = Doctrine_Query::create()
                            ->select('email')
                            ->from('CsUsers')
                            ->where('email = ?')
                            ->fetchOne(array($email), Doctrine::HYDRATE_ARRAY);

            if( $result )
                $error['email_exists'] = 1;

            // Check if nick already exists
            $result = Doctrine_Query::create()
                            ->select('nick')
                            ->from('CsUsers')
                            ->where('nick = ?')
                            ->fetchOne(array($nick), Doctrine::HYDRATE_ARRAY);

            if( $result )
                $error['nick_exists'] = 1;

            #var_dump($err);
            // No errors - then proceed
            // Register the user!
            if ( count($error) == 0  )
            {
                // Generate activation code & salted hash
                $hashArray = $security->build_salted_hash($pass);
                $hash = $hashArray['hash'];
                $salt = $hashArray['salt'];

                // Insert User to DB
                $userIn = new CsUser();
                $userIns->email = $email;
                $userIns->nick = $nick;
                $userIns->passwordhash = $hash;
                $userIns->salt = $salt;
                $userIns->joined = time();

                if($config['login']['email_activation'] == 0)
                {
                    $userIns->activated = 1;
                    $userIns->save();
                    $user->loginUser($userIns->user_id, 1, $pass);
                    $this->redirect( 'index.php', 3, 200, _('You have sucessfully registered and you are logged in.') );
                    die();
                }
                else
                {
                    $code = md5 ( microtime() );
                    $userIns->activation_code = $code;
                    $userIns->save();
                    // Send activation mail
                    if( $this->_send_activation_email($email, $nick, $userIns->user_id, $code) )
                    {
                        $this->redirect( 'index.php', 0, 200, _('You have sucessfully registered! Please check your mailbox.') );
                        die();
                    }
                    else
                    {
                        trigger_error( 'Sending of email activation failed.' );
                    }
                }

            }
        }

        $smarty = $this->getView();
        
        // Assign vars

        $smarty->assign( 'config', $moduleconfig );
        $smarty->assign( 'min_length', $moduleconfig['login']['min_pass_length'] );
        $smarty->assign( 'err', $error );
        #$smarty->assign( 'captcha_url',  WWW_ROOT . '/index.php?mod=captcha&' . session_name() . '=' . session_id() );

        #$view->assign( 'captcha_url',  WWW_ROOT . '/index.php?mod=captcha&' . session_name() . '=' . session_id() );

        // Output
        $this->prepareOutput();
    }

    /**
     * Send Activation Email
     */
    public function action_activation_email()
    {
        $err = array();

        // Request Controller
        $request = $this->injector->instantiate('Clansuite_HttpRequest');

        // Input validation
        $input = $this->injector->instantiate('input');

        // Get Inputvariables from $_POST
        $email  = $request->getParameter('email');
        $submit = $request->getParameter('submit');

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
                $result = Doctrine_Query::create()
                                ->select('user_id,nick,activated')
                                ->from('CsUser')
                                ->where('email = ?')
                                ->fetchOne(array($email));

                // Email was not found
                if ( !$result )
                {
                    $err['no_such_mail'] = 1;
                }
                else
                {
                    // Email already activated
                    if ( $result->activated == 1 )
                    {
                        $err['already_activated'];
                    }

                    // Email was found & is not active
                    if ( count($err) == 0 )
                    {
                        // Prepare user_id, nick, and activation code
                        $code    = md5 ( microtime() );

                        // Insert Code into DB WHERE user_id
                        $result->activation_code = $code;
                        $result->save();

                        if( $this->_send_activation_email($email, $result->nick, $result->user_id, $code) )
                        {
                            $this->redirect( 'index.php', 200, _('Activation mail has been resend to your mailbox.') );
                        }
                        else
                        {
                            trigger_error( 'Re-Sending of email activation failed.' );
                        }
                    }
                }
            }
        }

        // get View Ctrl.
        $view = $this->getView();

        // Assign tpl vars
        $view->assign( 'err', $err );

        // Output
        #$this->setTemplate('activation_email.tpl');
        $this->prepareOutput();
    }

    /**
    * Activate Account
    *
    * @input: user_id, code
    *
    * validate code
    * SELECT activated WHERE user_id and code
    * 1. code wrong for user_id
    * 2. code found, but already activated=1
    * 3. code found, SET activated=1
    *
    *
    */
    public function action_activate_account()
    {
        // Request Controller
        $request = $this->injector->instantiate('Clansuite_HttpRequest');

        // Get Inputvariables from $_GET
        $user_id = (int) $request->getParameter('user_id');
        $code    = $input->check($request->getParameter('code'), 'is_int|is_abc') ? $request->getParameter('code') : false;

        // Activation code is wrong
        if ( !$code )
        {
            $error->show( _( 'Code Failure' ), _('The given activation code is wrong. Please make sure you copied the whole activation URL into your browser.'), 2 );
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
                $error->show( _( 'Already' ), _('This account has been already activated.'), 2 );
                return;
            }
            else
            {
                // UPDATE activated=1 WHERE user_id
                $stmt = $db->prepare( 'UPDATE ' . DB_PREFIX . 'users SET activated = ? WHERE user_id = ?' );
                $stmt->execute( array ( 1, $user_id ) );
                $this->redirect( 'index.php?mod=account&action=login', 'metatag|newsite', 3, _('Your account has been activated successfully - please login.') );
            }
        }
        else
        {   // Activation Code not matching user_id
            $error->show( _( 'Code Failure' ), _('The activation code does not match to the given user id'), 2 );
            return;
        }
    }

    /**
     * Forgot Password
     */
    public function action_forgot_password()
    {
        // Request Controller
        $request = $this->injector->instantiate('Clansuite_HttpRequest');
        #$validation = $this->injector->instantiate('Clansuite_Validation');
        $config = $this->injector->instantiate('Clansuite_Config');
        $security = $this->injector->instantiate('Clansuite_Security');
        $error = array();

        $email = $request->getParameter('email');
        $pass = $request->getParameter('password');
        $submit = $request->getParameter('submit');
        if( !empty($submit) )
        {
            if( empty($email) || empty($pass) )
            {
                $error['form_not_filled'] = 1;
            }
            elseif ( !isset($pass{$config['login']['min_pass_length']-1}) )
            {
                $error['pass_too_short'] = 1;
            }
            else
            {
                if ( !$validation->check( $email, 'is_email' ) )
                {
                    $error['email_wrong'] = 1;
                }

                if ( count($error) == 0 )
                {
                    // Select a DB Row
                    $result = Doctrine_Query::create()
                                    ->select('user_id, nick, activated')
                                    ->from('CsUser')
                                    ->where('email = ?')
                                    ->fetchOne(array($email));


                    if ( !$result )
                    {
                        $error['no_such_mail'] = 1;
                    }
                    else if ( $result->activated != 1 )
                    {
                        $error['account_not_activated'] = 1;
                    }
                    else
                    {
                        if ( count($error) == 0 )
                        {
                            // Generate activation code & salted hash
                            $hashArr = $security->build_salted_hash($pass);
                            $hash = $hashArr['hash'];
                            $salt = $hashArr['salt'];

                            // Insert User to DB
                            $result->new_passwordhash = $hash;
                            $result->new_salt = $salt;

                            $code = md5 ( microtime() );
                            $result->activation_code = $code;
                            $result->save();

                            // Send activation mail
                            if( $this->_send_password_email($email, $result->nick, $result->user_id, $code) )
                            {
                                $this->redirect( 'index.php', 0, 200, _('Check your mailbox to activate your new password.') );
                                die();
                            }
                            else
                            {
                                trigger_error( 'Sending of email activation failed.' );
                            }

                        }
                    }
                }
            }
        }

        $smarty = $this->getView();
        $smarty->assign('err', $error);

        #$this->setTemplate('forgot_password.tpl');
        $this->prepareOutput();
    }

    /**
     * Activate Password
     */
    public function action_activate_password()
    {
        // Request Controller
        $request = $this->injector->instantiate('Clansuite_HttpRequest');
        $input = $this->injector->instantiate('input');

        $user_id = (int) $request->getParameter('user_id');
        $code    = $input->check($request->getParameter('code'), 'is_int|is_abc') ? $request->getParameter('code') : false;

        if ( !$code )
        {
            $this->error( _( 'Code Failure: The given activation code is wrong. Please make sure you copied the whole activation URL into your browser.') );
            return;
        }



        // Select a DB Row
        $result = Doctrine_Query::create()
                        ->select('user_id, activated, new_passwordhash, activation_code, new_salt')
                        ->from('CsUser')
                        ->where('user_id = ? AND activation_code = ?')
                        ->fetchOne(array($user_id, $code));

        if ( $result )
        {
            if ( empty($result->new_passwordhash) )
            {
                $this->error( _( 'Already: There has been no password reset requested.'));
                return;
            }
            else
            {
                $result->passwordhash = $result->new_passwordhash;
                $result->salt = $result->new_salt;
                $result->activation_code = '';
                $result->new_salt = '';
                $result->new_passwordhash = '';
                $result->save();

                setcookie('cs_cookie_user_id', false);
                setcookie('cs_cookie_password', false);

                $this->redirect( 'index.php?mod=account&action=login', 3, 200, _('Your new password has been successfully activated. Please login...') );
                die();
            }
        }
        else
        {
            $this->error( _( 'Code Failure: The activation code does not match to the given user id') );
            return;
        }
    }

    /**
     * Private Function to send a activation email
     */
    private function _send_activation_email($email, $nick, $user_id, $code)
    {
        $config = $this->injector->instantiate('Clansuite_Config');
        $this->injector->register('Clansuite_Mailer');
        $mailer = $this->injector->instantiate('Clansuite_Mailer');

        $to_address     = '"' . $nick . '" <' . $email . '>';
        $from_address   = '"' . $config['email']['fromname'] . '" <' . $config['email']['from'] . '>';
        $subject        = _('Account activation');

        $body  = _("To activate your account click on the link below:\r\n");
        $body .= WWW_ROOT."/index.php?mod=account&action=activate_account&user_id=%s&code=%s\r\n";
        $body .= "----------------------------------------------------------------------------------------------------------\r\n";
        $body .= _('Username').": %s\r\n";
        $body .= _('Password').": *"._('hidden')."*";
        $body .= "----------------------------------------------------------------------------------------------------------\r\n";
        $body  = sprintf($body, $user_id, $code, $nick);

        // Send mail
        if ( $mailer->sendmail($to_address, $from_address, $subject, $body) == true )
        {
            return true;
        }
        else
        {
            trigger_error( _( 'Mailer Error: There has been an error in the mailing system. Please inform the webmaster.' ) );
            return false;
        }
    }

    /**
     * Send a link to validate new password
     */
    private function _send_password_email($email, $nick, $user_id, $code)
    {
        $config = $this->injector->instantiate('Clansuite_Config');
        $this->injector->register('Clansuite_Mailer');
        return true;
        $mailer = $this->injector->instantiate('Clansuite_Mailer');

        $to_address     = '"' . $nick . '" <' . $email . '>';
        $from_address   = '"' . $config['email']['fromname'] . '" <' . $config['email']['from'] . '>';
        $subject        = _('Account activation');

        $body  = _("To reset your password, click the link below:\r\n");
        $body .= WWW_ROOT."/index.php?mod=account&action=activate_password&user_id=%s&code=%s\r\n";
        $body .= "----------------------------------------------------------------------------------------------------------\r\n";
        $body .= _('Username').": %s\r\n";
        $body .= _('Password').": *"._('hidden')."*";
        $body .= "----------------------------------------------------------------------------------------------------------\r\n";
        $body  = sprintf($body, $user_id, $code, $nick);

        // Send mail
        if ( $mailer->sendmail($to_address, $from_address, $subject, $body) == true )
        {
            return true;
        }
        else
        {
            trigger_error( _( 'Mailer Error: There has been an error in the mailing system. Please inform the webmaster.' ) );
            return false;
        }
    }



	/**
     * form to edit profiledata
     */
	public function action_profile_edit ()
	{

		# get id
        #$user_id = $this->getHttpRequest()->getParameter('id');
		$user_id = 2;

        # fetch userdata
        #$data = Doctrine::getTable('CsUsers')->fetchSingleUserData($user_id);

        # Load Form Class (@todo autoloader / di)
        require ROOT_CORE . 'viewhelper/form.core.php';

        # Create a new form
        # @todo form object with auto-population of values
        $form = new Clansuite_Form('userdata_form', 'post', 'index.php?mod=account&sub=profile&action=update&type=editprofile');

        /**
         * user_id as hidden field
         */
        #$form->addElement('hidden')->setName('userdata_form[user_id]')->setValue($data['user_id']);

        # Assign some formlements
		#$form->addDecorator('fieldset')->setLegend('General Data');
		#$form->addGroup('general');
        $form->addElement('text')->setName('userdata_form[firstname]')->setLabel(_('First Name'));
		$form->addElement('text')->setName('userdata_form[name]')->setLabel(_('Name'));
		$form->addElement('text')->setName('userdata_form[nickname]')->setLabel(_('Nickname'));
		$form->addElement('password')->setName('userdata_form[password]')->setLabel(_('Password'));
		$form->addElement('text')->setName('userdata_form[country]')->setLabel(_('Country'));
		$form->addElement('textarea')->setName('userdata_form[signature]')->setID('userdata_form[signature]')->setCols('10')->setRows('10')->setLabel(_('Your Signature:'));

		#$form->addDecorator('fieldset')->setLegend('Special Data');
		#$form->addGroup('special');

		$form->addElement('text')->setName('userdata_form[city]')->setLabel(_('City'));
		$form->addElement('textarea')->setName('userdata_form[address]')->setID('userdata_form[address]')->setCols('110')->setRows('30')->setLabel(_('Your Address:'));
		$form->addElement('text')->setName('userdata_form[mailaddress]')->setLabel(_('Mailaddress'));
		$form->addElement('text')->setName('userdata_form[phonenumber]')->setLabel(_('Phonenumber'));
		$form->addElement('text')->setName('userdata_form[handynumber]')->setLabel(_('Handynumber'));

		#$form->addDecorator('fieldset')->setLegend('Contact Data');
		#$form->addGroup('contact');

		$form->addElement('text')->setName('userdata_form[icq]')->setLabel(_('ICQ'));
		$form->addElement('text')->setName('userdata_form[msn]')->setLabel(_('MSN'));
		$form->addElement('text')->setName('userdata_form[xfire]')->setLabel(_('XFire'));
		$form->addElement('text')->setName('userdata_form[steam]')->setLabel(_('Steam'));
		$form->addElement('text')->setName('userdata_form[skype]')->setLabel(_('Skype'));
		$form->addElement('text')->setName('userdata_form[jabber]')->setLabel(_('Jabber'));

		$form->addElement('submitbutton')->setValue('Submit');
        $form->addElement('resetbutton')->setValue('Reset');
		$form->addElement('cancelbutton');

        # Debugging Form Object
        #clansuite_xdebug::printR($form);

        # Debugging Form HTML Output
        #clansuite_xdebug::printR($form->render());

        # assign the html of the form to the view
        $this->getView()->assign('form', $form->render());

        $this->prepareOutput();
	}

	/**
     * form to edit avatar
     */
	public function action_profile_edit_avatar ()
	{

		# get id
        #$user_id = $this->getHttpRequest()->getParameter('id');
		$user_id = 2;

        # fetch userdata
        $data = Doctrine::getTable('CsUsers')->fetchSingleUserData($user_id);

        # Load Form Class (@todo autoloader / di)
        require ROOT_CORE . 'viewhelper/form.core.php';

        # Create a new form
        # @todo form object with auto-population of values
        $form = new Clansuite_Form('useravatar_form', 'post', 'index.php?mod=account&sub=profile&action=update&type=edituseravatar');

        /**
         * user_id as hidden field
         */
        $form->addElement('hidden')->setName('useravatar_form[user_id]')->setValue($data['user_id']);

        # Assign some formlements
		$form->addDecorator('fieldset')->setLegend('Edit your User-Avatar');
		$form->addGroup('avatar');
		$form->addElement('jqselectimage')->addToGroup('avatar')->setName('useravatar_form[avatar]')->setLabel(_('Choose your Avatar:'));
		$form->addElement('jquploadify')->addToGroup('avatar')->setName('useravatar_form[avatar]')->setLabel(_('Upload your Avatar:'))->setDescription('max. 100x100px and max 500kb');

		$form->addElement('submitbutton')->setValue('Submit');
        $form->addElement('resetbutton')->setValue('Reset');
		$form->addElement('cancelbutton');

        # Debugging Form Object
        #clansuite_xdebug::printR($form);

        # Debugging Form HTML Output
        #clansuite_xdebug::printR($form->render());

        # assign the html of the form to the view
        $this->getView()->assign('form', $form->render());

        $this->prepareOutput();
	}

	/**
     *  form to edit userpic
     */
	public function action_profile_edit_userpic ()
	{

		# get id
        #$user_id = $this->getHttpRequest()->getParameter('id');
		$user_id = 2;

        # fetch userdata
        $data = Doctrine::getTable('CsUsers')->fetchSingleUserData($user_id);

        # Load Form Class (@todo autoloader / di)
        require ROOT_CORE . 'viewhelper/form.core.php';

        # Create a new form
        # @todo form object with auto-population of values
        $form = new Clansuite_Form('userpic_form', 'post', 'index.php?mod=account&sub=profile&action=update&type=edituserpic');

        /**
         * user_id as hidden field
         */
        $form->addElement('hidden')->setName('userpic_form[user_id]')->setValue($data['user_id']);

        # Assign some formlements
		$form->addDecorator('fieldset')->setLegend('Edit your User-Picture');
		$form->addGroup('userpic');
		$form->addElement('jquploadify')->addToGroup('userpic')->setName('userpic_form[userpic]')->setLabel(_('Upload your User-Picture:'))->setDescription('max. 150x150px and max 1Mb');

		$form->addElement('submitbutton')->setValue('Submit');
        $form->addElement('resetbutton')->setValue('Reset');
		$form->addElement('cancelbutton');

        # Debugging Form Object
        #clansuite_xdebug::printR($form);

        # Debugging Form HTML Output
        #clansuite_xdebug::printR($form->render());

        # assign the html of the form to the view
        $this->getView()->assign('form', $form->render());

        $this->prepareOutput();
	}

	/**
     *  form to update profiledata
     */
	public function action_profile_update()
	{
        $this->prepareOutput();
	}

	/**
     * form to save profiledata
     */
	public function action_profile_save ()
	{
        $this->prepareOutput();
	}
}
?>