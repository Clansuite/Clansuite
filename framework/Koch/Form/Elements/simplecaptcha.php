<?php

/**
 * Koch Framework
 * Jens-André Koch © 2005 - onwards
 *
 * This file is part of "Koch Framework".
 *
 * License: GNU/GPL v2 or any later version, see LICENSE file.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 */

/**
 * Renders a simple image captcha formelement.
 */
class SimpleCaptcha extends Captcha implements FormelementInterface
{
    public $name = 'simplecaptcha';
    public $type = 'captcha';

    /**
     * display captcha
     */
    public function render()
    {
        $captcha = new \Koch\Captcha();
<<<<<<< .mine
=======>>>>>>> .theirs        #Koch_Debug::firebug('Last Captcha String = '.$_SESSION['user']['simple_captcha_string']);

        return $captcha->generateCaptchaImage();
    }

    /**
     * validate captcha
     *
     * In the code that processes the form submission, you need to add code to validate the CAPTCHA.
     */
    public function validate()
    {
        // @todo comparision of form input with session string
        // $_SESSION['user']['simple_captcha_string']
    }
}
<<<<<<< .mine
=======>>>>>>> .theirs
