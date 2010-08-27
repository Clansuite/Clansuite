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
    * @author     Jens-André Koch <vain@clansuite.com>
    * @copyright  Jens-André Koch (2005-onwards)
    * @link       http://www.clansuite.com
    * 
    *
    * @version    SVN: $Id$
    */

# Security Handler
if (defined('IN_CS') === false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

# conditional include of the parent class
if (false == class_exists('Clansuite_Formelement',false))
{
    include ROOT_CORE . 'viewhelper/formelement.core.php';
}

/**
 *  Clansuite_Formelement
 *  |
 *  \- Clansuite_Formelement_Captcha
 *      |
 *      \- Clansuite_Formelement_ReCaptcha
 */
class Clansuite_Formelement_ReCaptcha extends Clansuite_Formelement_Captcha implements Clansuite_Formelement_Interface
{
    /**
     * @var string The ReCaptcha API PublicKey. You got this key from the ReCaptcha signup page.
     */
    private $publicKey;

    /**
     * @var string The ReCaptcha API PrivateKey.
     */
    private $publicKey;

    /**
     * @var string The ReCaptcha Error String.
     */
    private $error;

    /**
     * @var object Instance of Clansuite_HttpRequest Object.
     */

    public function __construct()
    {
        $this->request = Clansuite_CMS::getInjector()->instantiate('Clansuite_HttpRequest');

        # Load Recaptcha Library
        include_once ROOT_LIBRARIES . 'recaptcha/recaptchalib.php';

        /**
         * Fetch publickey from config
         *
         * [recaptcha]
         * public_key  = ""
         * private_key = ""
         */
        $config = Clansuite_CMS::getInjector()->instantiate('Clansuite_Config');
        $this->publicKey  = $config['recaptcha']['public_key'];
        $this->privateKey = $config['recaptcha']['private_key'];
        unset($config);
    }

    /**
     * Displays a ReCaptcha
     */
    public function render()
    {
        return recaptcha_get_html($this->publicKey);
    }

    /**
     * Validates a ReCaptcha
     *
     * In the code that processes the form submission, you need to add code to validate the CAPTCHA.
     */
    public function validate()
    {
        $response = recaptcha_check_answer( $this->privateKey,
                                            $this->request->getRemoteAddress(),
                                            $this->request->getParameterFromPost('recaptcha_challenge_field'),
                                            $this->request->getParameterFromPost('recaptcha_response_field')
                                          );

        if ($response->is_valid == false)
        {
            return _('The reCAPTCHA was not entered correctly. Go back and try again. (reCAPTCHA said: ' . $resp->error . ')');
        }
    }

    /**
     * Administrative Functions for ReCaptcha
     */
    public function get_apikey()
    {
        return recaptcha_get_signup_url();
    }
}
?>