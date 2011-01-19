<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-Andr� Koch � 2005 - onwards
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
    * @author     Jens-Andr� Koch <vain@clansuite.com>
    * @copyright  Jens-Andr� Koch (2005 - onwards)
    * @link       http://www.clansuite.com
    *
    * @version    SVN: $Id$
    */

# Security Handler
if(defined('IN_CS') === false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

/**
 * Clansuite_Module_Account
 *
 * @category    Clansuite
 * @package     Modules
 * @subpackage  Account
 */
class Clansuite_Module_Account extends Clansuite_Module_Controller
{
    /**
     * Module_Admin -> Execute
     */
    public function initializeModule()
    {
        # read module config
        $this->getModuleConfig();

        parent::initModel('users');
    }

    public function action_show()
    {
        # internal forward
        $this->action_login();
    }

    /**
     * Login Block
     */
    public function widget_login($item)
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
             and $_SESSION['login_attempts'] >= self::$moduleconfig['login']['max_login_attempts'] )
        {
            # @todo ban action

            $this->redirect( WWW_ROOT, 3, '200',
            _('You are temporarily banned. Please come back in <b>' .self::$moduleconfig['login']['login_ban_minutes'].'</b> minutes.'));

            exit();
        }
    }

    /**
     * Login
     */
    public function action_login()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Breadcrumb::add( _('Login'), '/account/login');

        # Get Objects
        $config = $this->getClansuiteConfig();
        $user = $this->getInjector()->instantiate('Clansuite_User');

        # Get Input Variables
        $nick        = $this->request->getParameterFromPost('nickname');
        $email       = $this->request->getParameterFromPost('email');
        $password    = $this->request->getParameterFromPost('password');
        $remember_me = $this->request->getParameterFromPost('remember_me');
        $submit      = $this->request->getParameterFromPost('submit');
        $referer     = $this->request->getParameterFromGet('referer');

        # Init Error Array
        $error = array();
        $value = '';

        /**
         * Determine the default login method by config value
         */
        if( self::$moduleconfig['login']['login_method'] == 'nick' )
        {
            $value = $nick;
            unset($nick);
        }
        elseif( self::$moduleconfig['login']['login_method'] == 'email' )
        {
            $value = $email;
            unset($email);
        }

        /**
         * @todo this is a form validation -> move it
         *
         * Perform checks on Inputvariables & Form filled?
         */
        if ( isset($value) and empty($value) === false and empty($password) === false )
        {
            self::checkLoginAttemps();

            # check whether user_id + password match
            $user_id = $user->checkUser( self::$moduleconfig['login']['login_method'], $value, $password );

            # proceed if true
            if ($user_id != false)
            {
                # perform login for user_id
                $user->loginUser( $user_id, $remember_me, $password );

                # register hook for onLogin and pass the user object as context
                #$this->triggerEvent('onLogin', $user);

                $this->setFlashmessage('success', _('You logged in successfully.'));

                $this->redirectToReferer();
            }
            else
            {
                $this->triggerEvent('onInvalidLogin');

                # @todo this is a plugin 'login_attempts' -> move it
                # log the login attempts to ban the ip at a specific number
                if (false === isset($_SESSION['login_attempts']))
                {
                    $_SESSION['login_attempts'] = 1;
                }
                else
                {
                    $_SESSION['login_attempts']++;
                }

                // Error Variables
                $error['mismatch'] = 1;
                $error['login_attempts'] = $_SESSION['login_attempts'];
            }
        }
        elseif(isset($submit))
        {
            $error['not_filled'] = 1;
        }

        # Login Form / User Center
        if( $_SESSION['user']['user_id'] == 0 )
        {
            $view = $this->getView();
            $view->assign('config', $config);
            $view->assign('error', $error);
            # $view->assign('referer', $referer);

            $this->display(array('content_template' => 'action_login.tpl'));
        }
        else
        {
            $this->setTemplate('usercenter.tpl');
        }
    }

    /**
     * Logout
     *
     * If logout is confirmed: Destroy Session, Delete Cookie and Redirect to index.php
     */
    public function action_logout()
    {
        // Set Pagetitle and Breadcrumbs
        Clansuite_Breadcrumb::add( _('Logout'), '/account/logout');

        $confirm = (bool) $this->request->getParameterFromPost('confirm');

        if( $confirm == true )
        {
            # log the user OUT
            $this->getInjector()->instantiate('Clansuite_User')->logoutUser();
            $this->setFlashmessage('success', _('Logout successfull. Have a nice day. Goodbye.'));
            $this->redirect(WWW_ROOT, 3, 200);
            exit();
        }
        else
        {
            $this->display();
        }
    }

    /**
     * Register a User Account
     */
    public function action_register()
    {
        # Request Controller
        $config = $this->getInjector()->instantiate('Clansuite_Config');

        // Input filter
        $input = $this->getInjector()->instantiate('Clansuite_Inputfilter');

        $user = $this->getInjector()->instantiate('Clansuite_User');

        # Get Inputvariables from $_POST
        $nick       = $this->request->getParameter('nick');
        $email      = $this->request->getParameter('email');
        $email2     = $this->request->getParameter('email2');
        $pass       = $this->request->getParameter('password');
        $pass2      = $this->request->getParameter('password2');
        $submit     = $this->request->getParameter('submit');
        $captcha    = $this->request->getParameter('captcha');

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
            if (strlen($pass) < self::$moduleconfig['login']['min_pass_length'])
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
                $hashArray = Clansuite_Security::build_salted_hash($pass);
                $hash = $hashArray['hash'];
                $salt = $hashArray['salt'];

                // Insert User to DB
                $userIn = new CsUsers();
                $userIns->email = $email;
                $userIns->nick = $nick;
                $userIns->passwordhash = $hash;
                $userIns->salt = $salt;
                $userIns->joined = time();

                if(self::$moduleconfig['login']['email_activation'] == 0)
                {
                    $userIns->activated = 1;
                    $userIns->save();
                    $user->loginUser($userIns->user_id, true, $pass);
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

        $view = $this->getView();
        # Assign vars
        $view->assign( 'config', self::$moduleconfig );
        $view->assign( 'min_length', self::$moduleconfig['login']['min_pass_length'] );
        $view->assign( 'err', $error );
        #$view->assign( 'captcha_url',  WWW_ROOT . 'index.php?mod=captcha&' . session_name() . '=' . session_id() );

        # Output
        $this->display();
    }

    /**
     * Send Activation Email
     */
    public function action_activation_email()
    {
        $error = array();

        // Input validation
        #$input = $this->getInjector()->instantiate('input');

        // Input filter
        $input = $this->getInjector()->instantiate('Clansuite_Inputfilter');

        // Get Inputvariables from $_POST
        $email  = $this->request->getParameter('email');
        $submit = $this->request->getParameter('submit');

        // Perform checks on Inputvariables & Form filled?
        if ( empty($email) )
        {
            if ( !empty ( $submit ) )
            {
                $error['form_not_filled'] = 1;
            }
        }
        else
        {   // Form filled -> proceed

            if ( !$input->check( $email, 'is_email' ) )
            {
                $error['email_wrong'] = 1;
            }

            // No Input-Errors
            if ( count($error) == 0 )
            {
                // Select WHERE email
                $result = Doctrine_Query::create()
                                ->select('user_id,nick,activated')
                                ->from('CsUsers')
                                ->where('email = ?')
                                ->fetchOne(array($email));

                // Email was not found
                if ( !$result )
                {
                    $error['no_such_mail'] = 1;
                }
                else
                {
                    // Email already activated
                    if ( $result->activated == 1 )
                    {
                        $error['already_activated'];
                    }

                    // Email was found & is not active
                    if ( count($error) == 0 )
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
        $view->assign( 'err', $error );

        // Output
        #$this->setTemplate('activation_email.tpl');
        $this->display();
    }

    /**
     * Activate Account
     *
     * validate code
     * SELECT activated WHERE user_id and code
     * 1. code wrong for user_id
     * 2. code found, but already activated=1
     * 3. code found, SET activated=1
     */
    public function action_activate_account()
    {
        // Input filter
        $input = $this->getInjector()->instantiate('Clansuite_Inputfilter');

        # Inputvariables
        $user_id = (int) $this->request->getParameterFromGet('user_id');
        $code    = $input->check($this->request->getParameter('code'), 'is_int|is_abc') ? $this->request->getParameter('code') : false;

        $email  = $this->request->getParameterFromGet('email');
        if ( !$input->check( $email, 'is_email' ) )
        {
            $this->setFlashmessage( 'error', _('The given email is not valid!.') );
        }

        # Activation code is wrong
        if ( !$code )
        {
            #$error->show( _( 'Code Failure' ), _('The given activation code is wrong. Please make sure you copied the whole activation URL into your browser.'), 2 );
            $this->setFlashmessage( 'error', _('The given activation code is wrong. Please make sure you copied the whole activation URL into your browser.') );
            return;
        }

        $result = Doctrine_Query::create()
                            ->select('activated')
                            ->from('CsUsers')
                            ->where('user_id = ?')
                            ->andWhere('code = ?')
                            ->fetchArray(array($user_id, $email), Doctrine::HYDRATE_ARRAY);

        if ( is_array ( $result ) )
        {
            # Account already activated
            if ( $result['activated'] == 1 )
            {
                $this->setFlashmessage('error', 'This account has been already activated.');
                $this->redirectToReferer();
            }
            else # activate this account
            {
                Doctrine_Query::create()->update('CsUsers')->set('activated', 1)->where('user_id', $user_id);
                $this->setFlashmessage('success', _('Your account has been activated successfully. You may now login.'));
                $this->redirectToReferer();
            }
        }
        else
        {
            # Activation Code not matching user_id
            $this->setFlashmessage('error', _('The activation code does not match to the given user id'));
            $this->redirectToReferer();
        }
    }

    /**
     * Forgot Password
     */
    public function action_forgot_password()
    {
        #$validation = $this->getInjector()->instantiate('Clansuite_Validation');

        // Input filter
        $input = $this->getInjector()->instantiate('Clansuite_Inputfilter');

        $error = array();

        $email = $this->request->getParameter('email');
        $pass = $this->request->getParameter('password');
        $submit = $this->request->getParameter('submit');
        if( !empty($submit) )
        {
            if( empty($email) || empty($pass) )
            {
                $error['form_not_filled'] = 1;
            }
            elseif ( !isset($pass{self::$moduleconfig['login']['min_pass_length']-1}) )
            {
                $error['pass_too_short'] = 1;
            }
            else
            {
                if ( !$input->check( $email, 'is_email' ) )
                {
                    $error['email_wrong'] = 1;
                }

                if ( count($error) == 0 )
                {
                    // Select a DB Row
                    $result = Doctrine_Query::create()
                                    ->select('user_id, nick, activated')
                                    ->from('CsUsers')
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
                            $hashArr = Clansuite_Security::build_salted_hash($pass);
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

        $view = $this->getView();
        $view->assign('err', $error);

        #$this->setTemplate('forgot_password.tpl');
        $this->display();
    }

    /**
     * Activate Password
     */
    public function action_activate_password()
    {
        // Request Controller
        $input = $this->getInjector()->instantiate('Clansuite_Inputfilter');

        $user_id = (int) $this->request->getParameter('user_id');
        $code    = $input->check($this->request->getParameter('code'), 'is_int|is_abc') ? $this->request->getParameter('code') : false;

        if ( !$code )
        {
            #$this->error( _( 'Code Failure: The given activation code is wrong. Please make sure you copied the whole activation URL into your browser.') );
            $this->setFlashmessage( 'error', _( 'Code Failure: The given activation code is wrong. Please make sure you copied the whole activation URL into your browser.') );
            return;
        }

        # Select a DB Row
        $result = Doctrine_Query::create()
                        ->select('user_id, activated, new_passwordhash, activation_code, new_salt')
                        ->from('CsUsers')
                        ->where('user_id = ? AND activation_code = ?')
                        ->fetchOne(array($user_id, $code));

        if ( $result )
        {
            if ( empty($result->new_passwordhash) )
            {
                #$this->error( _( 'Already: There has been no password reset requested.'));
                $this->setFlashmessage( 'error', _( 'Already: There has been no password reset requested.') );
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

                $this->redirect('/account&action=login', 3, 200, _('Your new password has been successfully activated. Please login...') );
                die();
            }
        }
        else
        {
            #$this->error( _( 'Code Failure: The activation code does not match to the given user id') );
            $this->setFlashmessage( 'error', _( 'Code Failure: The activation code does not match to the given user id') );
            return;
        }
    }

    /**
     * Private Function to send a activation email
     */
    private function _send_activation_email($email, $nick, $user_id, $code)
    {
        $config = $this->getClansuiteConfig();

        $this->getInjector()->register('Clansuite_Mailer');
        $mailer = $this->getInjector()->instantiate('Clansuite_Mailer');

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
        $config = $this->getClansuiteConfig();

        $this->getInjector()->register('Clansuite_Mailer');
        $mailer = $this->getInjector()->instantiate('Clansuite_Mailer');

        $to_address     = '"' . $nick . '" <' . $email . '>';
        $from_address   = '"' . $config['email']['fromname'] . '" <' . $config['email']['from'] . '>';
        $subject        = _('Account activation');

        $body  = _("To reset your password, click the link below:\r\n");
        $body .= WWW_ROOT . "index.php?mod=account&action=activate_password&user_id=%s&code=%s\r\n";
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
        #$user_id = $this->request->getParameter('id');
        $user_id = 2;

        # fetch userdata
        #$data = Doctrine::getTable('CsUsers')->fetchSingleUserData($user_id);

        # Create a new form
        # @todo form object with auto-population of values
        $form = new Clansuite_Form('userdata_form', 'post', '/account&sub=profile&action=update&type=editprofile');

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
        #Clansuite_Debug::printR($form);

        # Debugging Form HTML Output
        #Clansuite_Debug::printR($form->render());

        # assign the html of the form to the view
        $this->getView()->assign('form', $form->render());

        $this->display();
    }

    /**
     * form to edit avatar
     */
    public function action_profile_edit_avatar ()
    {
        # get id
        #$user_id = $this->request->getParameter('id');
        $user_id = 2;

        # fetch userdata
        $data = Doctrine::getTable('CsUsers')->fetchSingleUserData($user_id);

        # Create a new form
        # @todo form object with auto-population of values
        $form = new Clansuite_Form('useravatar_form', 'post', '/account&sub=profile&action=update&type=edituseravatar');

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
        #Clansuite_Debug::printR($form);

        # Debugging Form HTML Output
        #Clansuite_Debug::printR($form->render());

        # assign the html of the form to the view
        $this->getView()->assign('form', $form->render());

        $this->display();
    }

    /**
     *  form to edit userpic
     */
    public function action_profile_edit_userpic ()
    {
        # get id
        #$user_id = $this->request->getParameter('id');
        $user_id = 2;

        # fetch userdata
        $data = Doctrine::getTable('CsUsers')->fetchSingleUserData($user_id);

        # Create a new form
        # @todo form object with auto-population of values
        $form = new Clansuite_Form('userpic_form', 'post', '/account&sub=profile&action=update&type=edituserpic');

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
        #Clansuite_Debug::printR($form);

        # Debugging Form HTML Output
        #Clansuite_Debug::printR($form->render());

        # assign the html of the form to the view
        $this->getView()->assign('form', $form->render());

        $this->display();
    }

    /**
     *  form to update profiledata
     */
    public function action_profile_update()
    {
        $this->display();
    }

    /**
     * form to save profiledata
     */
    public function action_profile_save ()
    {
        $this->display();
    }
}
?>