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
    * @author     Jens-André Koch   <vain@clansuite.com>
    * @copyright  Copyleft: All rights reserved. Jens-André Koch (2005-onwards)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    *
    * @version    SVN: $Id$
    */

# Security Handler
if (defined('IN_CS') == false){ die('Clansuite not loaded. Direct Access forbidden.');}

# conditional include of the parent class
if (false == class_exists('Clansuite_Formelement',false))
{ 
    include ROOT_CORE . 'viewhelper/formelement.core.php';
}

/**
 *  Clansuite_Formelement
 *  |
 *  \- Clansuite_Formelement_Captcha
 */
class Clansuite_Formelement_Captcha extends Clansuite_Formelement implements Clansuite_Formelement_Interface
{
    /**
     * @var string Name the Captcha Type: 'recaptcha', 'simplecaptcha', 'somenamecaptcha'.
     */
    private $captcha;

    /**
     * @var object The captcha object.
     */
    private $captchaObject;

    public function __construct()
    {
        # formfield type
        $this->type  = 'captcha';

        return $this;
    }

    public function setCaptcha($captcha = null)
    {
        # if no captcha is given, take the one definied in configuration
        if($captcha == null)
        {
            $config = Clansuite_CMS::getInjector()->instantiate('Clansuite_Config');
            $captcha = $config['antispam']['captchatype'];
            unset($config);
        }

        $this->captcha = strtolower($captcha);

        return $this;
    }

    public function getCaptcha()
    {
        # cut "captcha" (last 7 chars)
        return substr($this->captcha, 0, -7);
    }

    public function setCaptchaFormelement(Clansuite_Formelement_Interface $captchaObject)
    {
        $this->captchaObject = $captchaObject;

        return $this;
    }

    public function getCaptchaFormelement()
    {
        if(empty($this->captchaObject))
        {
            return $this->setCaptchaFormelement($this->captchaFactory());
        }
        else
        {
            return $this->captchaObject;
        }
    }

    /**
     * The CaptchaFactory loads and instantiates a captcha object
     */
    private function captchaFactory()
    {
        $name = $this->getCaptcha();
        #Clansuite_Xdebug::firebug($name);

        # construct classname
        $classname = 'Clansuite_Formelement_'. $name.'Captcha';

        # load file
        if (class_exists($classname, false) === false)
        {
            include ROOT_CORE.'viewhelper/formelements/'.$name.'captcha.form.php';
        }

        # instantiate
        $editor_formelement = new $classname();

        return $editor_formelement;
    }

    /**
     * At some point in the lifetime of this object you decided that this captcha should be a captcha element of specific kind.
     * The captchaFactory will load the file and instantiate the captcha object. But you already defined some properties
     * like Name or Size for this captcha. Therefore it's now time to transfer these properties to the captcha object.
     * Because we don't render this captcha, but the requested captcha object.
     */
    private function transferPropertiesToCaptcha()
    {
        # get captcha formelement
        $formelement = $this->getCaptchaFormelement();

        # transfer props from $this to captcha formelement
        $formelement->setRequired($this->required);
        $formelement->setLabel($this->label);
        $formelement->setName($this->name);
        $formelement->setValue($this->value);

        # return the formelement, to call e.g. render() on it
        return $formelement;
    }


    /**
     * Renders the captcha representation of the specific captcha formelement.
     *
     * @return $html HTML Representation of captcha formelement
     */
    public function render()
    {
        $html = '';

        if(empty($this->captcha) == false)
        {
            $html .= $this->getCaptchaFormelement()->transferPropertiesToCaptcha()->render();
        }

        return $html;
    }

    public function __toString()
    {
        return $this->render();
    }
}
?>