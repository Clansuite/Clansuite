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
    * @version    SVN: $Id: renderer.base.core.php 2614 2008-12-05 21:18:45Z vain $
    */

//Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}

/**
 * Clansuite
 *
 * Module:  Recaptcha ( http://recaptcha.net/ )
 *
 */
class Module_Recaptcha extends Clansuite_ModuleController implements Clansuite_Module_Interface
{
    /**
     * Module_Recaptcha -> Execute
     */
    public function execute(Clansuite_HttpRequest $request, Clansuite_HttpResponse $response)
    {
        # read module config
        $this->getModuleConfig();
    }

    /**
     * display_recaptcha
     */
    public function display_recaptcha()
    {
        # Load Recaptcha Library
        require_once( ROOT_MOD . 'recaptcha/library/recaptchalib.php');

        # fetch publickey from config, you got this key from the recaptcha signup page
        $publicKey = $this->getConfigValue('public_key','No PublicKey given!');

        echo recaptcha_get_html($publicKey);
    }

    /**
     * validate_recaptcha
     *
     * In the code that processes the form submission, you need to add code to validate the CAPTCHA.
     */
    public function validate_recaptcha()
    {
        # Load Recaptcha Library
        require_once( ROOT_MOD . 'recaptcha/library/recaptchalib.php');
        
        $resp = recaptcha_check_answer( $this->getConfigValue('public_key',''),
                                        $this->request->getRemoteAddress(),
                                        $this->request->getParameter('recaptcha_challenge_field'),  # POST
                                        $this->request->getParameter('recaptcha_response_field')    # POST
                                      );  

        if (!$resp->is_valid)
        {
            die ("The reCAPTCHA wasn't entered correctly. Go back and try it again. (reCAPTCHA said: " . $resp->error . ")");
        }
    }

    /**
     * Administrative Functions for recaptcha
     */
    public function get_apikey()
    {
        recaptcha_get_signup_url();
    }
}
?>