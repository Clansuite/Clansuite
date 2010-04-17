<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-André Koch © 2005 - onwards
    * http://www.clansuite.com/
    *
    * This file is part of "Clansuite - just an eSports CMS".
    *
    * Clansuite Converter - v0.1dev - 21-september-2008 by vain
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

/**
 * @package         Clansuite
 * @subpackage      Converter
 * @category        Converter
 *
 * @description     English Installation Language
 * @implements      ArrayAccess
 *
 * Encoding: UTF-8
 *
 * @author: vain
 */

class Language implements ArrayAccess
{
    private $language = array();

    # table of strings
    function __construct()
    {
        // GLOBAL

        # Headerüberschrift
        $this->language['HEADER_HEADING'] = 'Converter';

        # Menu
        $this->language['MENU_HEADING'] = 'Converter-Steps';
        $this->language['MENUSTEP1'] = '[1] Select Language';
        $this->language['MENUSTEP2'] = '[2] Select CMS';
        $this->language['MENUSTEP3'] = '[3] Select Version';
        $this->language['MENUSTEP4'] = '[4] DB-Connection';
        $this->language['MENUSTEP5'] = '[5] Select Tables';
        $this->language['MENUSTEP6'] = '[6] Conversion';
        $this->language['MENUSTEP7'] = '[7] Final Report';

        # Right Side Menu
        $this->language['INSTALL_PROGRESS'] = 'Install Progress';
        $this->language['COMPLETED'] = 'COMPLETED';
        $this->language['CHANGE_LANGUAGE'] = 'Change Language';
        $this->language['SHORTCUTS'] = 'Clansuite Shortcuts';
        $this->language['HELP'] = 'Help';
        $this->language['LICENSE'] = 'License';

        # Control-Buttons
        $this->language['NEXTSTEP'] = 'Next >>';
        $this->language['BACKSTEP'] = '<< Back';

        # Help Text for Control-Buttons
        $this->language['CLICK_NEXT_TO_PROCEED'] = 'Click the Button ['. $this->language['NEXTSTEP'] .'] to proceed with the next step.';
        $this->language['CLICK_BACK_TO_RETURN'] = 'Click the Button ['. $this->language['BACKSTEP'] .'] to return to the prior one.';

        // Steps

        # STEP 1 - Language Selection
        $this->language['STEP1_HEADING'] = 'Step [1] Language Selection';

        $this->language['STEP1_WELCOME'] = 'Welcome to the Clansuite Converter Tool.';
        $this->language['STEP1_THANKS_CHOOSING'] = 'Thanks for choosing to use Clansuite!';
        $this->language['STEP1_APPINSTALL_STEPWISE'] = 'This application will help you to convert data from your old CMS to Clansuite.';
        $this->language['STEP1_CHOOSELANGUAGE'] = 'Please select your language.';

        # STEP 2 - CMS Selection
        $this->language['STEP2_HEADING'] = 'Step [2] Select CMS';

        $this->language['STEP2_SELECT_OLD_CMS_AND_VERSION'] = 'Please chose your old CMS and version:';
        $this->language['STEP2_REQUEST_CONVERTER'] = 'If you can\'t make a find, ask for the converter you are looking for in our ';

        $this->language['STEP2_CHOOSEVERSION'] = 'Chose version:';
        $this->language['STEP2_NO_VERSION_FOUND'] = 'There were no version data found. Please create a directory named after the version number!';

        # STEP 3
        $this->language['STEP3_HEADING'] = 'Step [3] Connect to Database';

        $this->language['STEP3_ENTER_CONNECTION_DATA'] = 'Geben Sie nun Bitte die Datenbankverbindungsdaten für das ausgewählte CMS an!';
        $this->language['STEP3_DATABASE_ACCESS_INFORMATION'] = 'Database Access Information';

        $this->language['DB_HOST'] = 'Database Hostname';
        $this->language['DB_TYPE'] = 'Databank Type';
        $this->language['DB_NAME'] = 'Database Name';
        $this->language['DB_CREATE_DATABASE'] = 'Create Database?';
        $this->language['DB_USERNAME'] = 'Database Username';
        $this->language['DB_PASSWORD'] = 'Database Password';
        $this->language['DB_PREFIX'] = 'Table Prefix';

        # STEP 4
        $this->language['STEP4_HEADING'] = 'Step [4] Select tables to import';

        # STEP 5
        $this->language['STEP5_HEADING'] = 'Step [5] Conversion';

        # STEP 6
        $this->language['STEP6_HEADING'] = 'Step [6] Final report';
        $this->language['STEP6_CONVERT_FINISHED'] = 'The conversion succeeded!';

    }

    /**
     * Implementation of SPL ArrayAccess
     */
    public function offsetExists($offset)
    {
        return isset($this->language[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->language[$offset];
    }

    public function offsetSet($offset, $value)
    {
        $this->__set($offset, $value);
    }

    // hmm? why should configuration be unset?
    public function offsetUnset($offset)
    {
        unset($this->language[$offset]);
        return true;
    }
}
?>