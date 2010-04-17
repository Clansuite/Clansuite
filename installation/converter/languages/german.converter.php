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
 * @description     German Installation Language
 * @implements      ArrayAccess
 *
 * Encoding: UTF-8
 *
 * @author: vain
 * @author: vyper
 */

class Language implements ArrayAccess
{
    private $language = array();

    // table of strings
    function __construct()
    {
        // Headerüberschrift
        $this->language['HEADER_HEADING'] = 'Konvertierer';

        // Menu
        $this->language['MENU_HEADING'] = 'Konvertierungschritte';
        $this->language['MENUSTEP1'] = '[1] Sprachauswahl';
        $this->language['MENUSTEP2'] = '[2] CMS-Auswahl';
        $this->language['MENUSTEP3'] = '[3] DB-Verbindung';
        $this->language['MENUSTEP4'] = '[4] Tabellenauswahl';
        $this->language['MENUSTEP5'] = '[5] Konvertierung';
        $this->language['MENUSTEP6'] = '[6] Abschlussreport';

        // GLOBAL
        # Buttons
        $this->language['NEXTSTEP'] = 'Weiter >>';
        $this->language['BACKSTEP'] = '<< Zurück';
        # Help Text for Buttons
        $this->language['CLICK_NEXT_TO_PROCEED'] = 'Klicken Sie den Button ['. $this->language['NEXTSTEP'] .'] um fortzufahren.';
        $this->language['CLICK_BACK_TO_RETURN'] = 'Klicken Sie den Button ['. $this->language['BACKSTEP'] .'] um zum vorherigen Schritt zurückzukehren.';
        # Right Side Menu
        $this->language['INSTALL_PROGRESS'] = 'Installations- fortschritt';
        $this->language['COMPLETED'] = 'Fertig';
        $this->language['CHANGE_LANGUAGE'] = 'Sprachauswahl';
        $this->language['SHORTCUTS'] = 'Clansuite Shortcuts';

        # STEP 1 - Sprachauswahl
        $this->language['STEP1_HEADING'] = 'Schritt [1] Sprachauswahl';

        $this->language['STEP1_WELCOME'] = 'Willkommen zum Konvertierungs-Werkzeug für Clansuite.';
        $this->language['STEP1_THANKS_CHOOSING'] = 'Vielen Dank, dass Sie sich für einen Umstieg auf Clansuite entschieden haben!';
        $this->language['STEP1_APPINSTALL_STEPWISE'] = 'Diese Anwendung hilft Ihnen dabei, die Daten aus Ihrem bisherigen CMS auf Clansuite zu überführen.';
        $this->language['STEP1_CHOOSELANGUAGE'] = 'Wählen Sie Bitte die Sprache aus.';

        # STEP 2 - CMS-Auswahl
        $this->language['STEP2_HEADING'] = 'Schritt [2] CMS-Auswahl';

        $this->language['STEP2_SELECT_OLD_CMS_AND_VERSION'] = 'Bitte wählen Sie Ihr altes CMS und die entsprechende Version aus:';
        $this->language['STEP2_REQUEST_CONVERTER'] = 'Sollten Sie nicht fündig werden, dann fragen Sie nach dem gesuchten Konvertierer in unserem ';

        $this->language['STEP2_CHOOSEVERSION'] = 'Bitte wählen Sie die Version';
        $this->language['STEP2_NO_VERSION_FOUND'] = 'Es wurden keine Versionen gefunden. Bitte erstellen Sie ein nach der Versionsnummer benanntes Verzeichnis!';

        # STEP 3
        $this->language['STEP3_HEADING'] = 'Step [3] Db-Verbindung';

        $this->language['STEP3_ENTER_CONNECTION_DATA'] = 'Geben Sie nun Bitte die Datenbankverbindungsdaten für ';
        $this->language['STEP3_DATABASE_ACCESS_INFORMATION'] = 'Datenbankverbindungsdaten';

        $this->language['DB_HOST'] = 'Datenbank Hostname';
        $this->language['DB_TYPE'] = 'Databank Typ';
        $this->language['DB_NAME'] = 'Datenbank Name';
        $this->language['DB_CREATE_DATABASE'] = 'Datenbank erstellen';
        $this->language['DB_USERNAME'] = 'Datenbank Benutzer';
        $this->language['DB_PASSWORD'] = 'Datenbank Passwort';
        $this->language['DB_PREFIX'] = 'Tabellen Präfix';

        # STEP 4
        $this->language['STEP4_HEADING'] = 'Step [4] Tabellenauswahl';

        # STEP 5
        $this->language['STEP5_HEADING'] = 'Step [5] Konvertierung';

        # STEP 6
        $this->language['STEP6_HEADING'] = 'Step [6] Abschlussreport';
        $this->language['STEP6_CONVERT_FINISHED'] = 'Die Überführung Ihrer Daten wurde erfolgreich abgeschlossen!';
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
        // @todo i have still no clue why utf8-encode() won't work!
        return $this->unicode_converter($this->language[$offset]);
    }

    public function offsetSet($offset, $value)
    {
        $this->__set($offset, $value);
    }

    // hmmü why should configuration be unsetü
    public function offsetUnset($offset)
    {
        unset($this->language[$offset]);
        return true;
    }

    /**
     * unicode_converter
     *
     * @param string $string The string to unicode encode/decode
     * @param boolean $to_unicode Convert to (true) or from (false) Unicode
     */
    function unicode_converter($string, $to_unicode = true)
    {
        return htmlentities($string);
        /*
        $conversion_table = array(
                                    "ä" => "&#228;", "Ä" => "&#196;",
                                    "ö" => "&#246;", "Ö" => "&#214;",
                                    "ü" => "&#252;", "Ü" => "&#220;",
                                    "é" => "&#233;", "ß" => "&#223;"
                                  );

        # char to unicode
        if ($to_unicode)
        {
            $string = strtr($string, $conversion_table);
        }
        # unicode to char
        else
        {
            foreach ($conversion_table as $conversion_element)
            {
                $conversion_pool[$conversion_element] = array_search($conversion_element, $conversion_pool);
            }
            $string = strtr($string, $conversion_pool);
        }
        return $string;
        */
    }
}
?>