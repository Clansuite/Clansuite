<?php
/**
 * @package         Clansuite
 * @subpackage      Clansuite Installation
 * @category        Installer
 * @author          Jens-Andre Koch <vain@clansuite.com>
 * @copyright       Jens-Andre Koch & Clansuite Development Team
 * @license         http://www.gnu.org/copyleft/gpl.html GNU/GPL Public Licence
 * @description     German Installation Language
 * @version         SVN: $Id$
 * @implements      ArrayAccess
 *
 * Encoding: UTF-8
 * Contributors: vain, vyper
 */

class language implements ArrayAccess
{
    private $language = array();

    // table of strings
    function __construct()
    {
        // STEP 1 - Language Selection
        $this->language['STEP1_LANGUAGE_SELECTION'] = 'Schritt [1] Sprachauswahl';

        $this->language['STEP1_THANKS_CHOOSING'] = 'Vielen Dank, dass Sie sich f�r Clansuite entschieden haben!';
        $this->language['STEP1_WELCOME'] = 'Willkommen zur Installation von Clansuite.';
        $this->language['STEP1_APPINSTALL_STEPWISE'] = 'Diese Anwendung f�hrt Sie schrittweise durch die Installation.';
        $this->language['STEP1_CHOOSELANGUAGE'] = 'W�hlen Sie bitte die Sprache aus.';

        // STEP 2 - System Check
        $this->language['STEP2_SYSTEMCHECK'] = 'Schritt [2] Systempr�fung';

        $this->language['STEP2_IN_GENERAL'] = 'In Schritt [2] pr�fen wir, ob Ihr Webserver die Installationsanforderungen von Clansuite erf�llt.';

        $this->language['STEP2_SYSTEMSETTINGS_REQUIRED'] = 'Einige der Systemeinstellungen sind zwingend erforderlich, damit Clansuite ordnungsgem�� funktioniert.';
        $this->language['STEP2_SYSTEMSETTINGS_RECOMMENDED'] = 'Andere sind lediglich empfohlene Einstellungen, sei es aus Sicherheits- oder Performancegr�nden.';
        $this->language['STEP2_SYSTEMSETTINGS_TAKEACTION'] = 'Die rot markierten Einstellungen zeigen Ihnen auf, wo noch Handlungsbedarf besteht.';
        $this->language['STEP2_SYSTEMSETTINGS_CHECK_VALUES'] = 'Die System�berpr�fung ergab folgendes:';

        $this->language['STEP2_SYSTEMSETTING_REQUIRED'] = 'Erforderliche Einstellungen';
        $this->language['STEP2_SYSTEMSETTING_RECOMMENDED'] = 'W�nschenswerte Einstellungen';

        $this->language['STEP2_SETTING'] = 'Einstellung';
        $this->language['STEP2_SETTING_ACTUAL'] = 'Tats�chlich';
        $this->language['STEP2_SETTING_EXPECTED'] = 'Erwartet';
        $this->language['STEP2_SETTING_STATUS'] = 'Status';

        # REQUIRED SETTINGS (in order)
        $this->language['PHP_VERSION'] = 'PHP Version 5.2+';
        $this->language['SESSION_FUNCTIONS'] = 'Session Funktionen';
        $this->language['PDO_LIBRARY'] = 'PDO - Bibliothek';
        $this->language['PDO_MYSQL_LIBRARY'] = 'PDO - MySQL - Bibliothek';

        # RECOMMENDED SETTINGS (in order)
        $this->language['PHP_MEMORY_LIMIT'] = 'PHP memory limit (Minimum 8M, recommend 16M)';
        $this->language['FILE_UPLOADS'] = 'Dateiuploads erlaubt?';
        $this->language['REGISTER_GLOBALS'] = 'REGISTER_GLOBALS';
        $this->language['ALLOW_URL_FOPEN'] = 'ALLOW_URL_FOPEN';
        $this->language['SAFE_MODE'] = 'SAFE_MODE';
        $this->language['OPEN_BASEDIR'] = 'OPEN_BASEDIR';
        $this->language['MAGIC_QUOTES_GPC'] = 'Magic Quotes GPC';
        $this->language['MAGIC_QUOTES_RUNTIME'] = 'Magic Quotes Runtime';
        $this->language['EXTENSION_HASH'] = 'PHP Bibliothek: Hash';
        $this->language['EXTENSION_GETTEXT'] = 'PHP Bibliothek: Gettext';
        $this->language['EXTENSION_TOKENIZER'] = 'PHP Bibliothek: Tokenizer';
        $this->language['EXTENSION_GD'] = 'PHP Bibliothek: GD';

        // STEP 3 - Licence
        $this->language['STEP3_LICENCE'] = 'Schritt [3] GNU/GPL Lizenz';

        $this->language['STEP3_SENTENCE1'] = 'Bitte nehmen Sie zur Kenntnis, dass der Clansuite Quellcode unter der GNU/GPL Lizenz ver�ffentlicht wurde! Die Urheberin der nachfolgenden GNU/GPL Lizenz ist die Free Software Foundation.';
        $this->language['STEP3_REVIEW'] = 'Bitte �berpr�fen Sie die Lizenzbestimmungen bevor Sie Clansuite installieren:';
        $this->language['STEP3_MUST_AGREE'] = 'Sie m�ssen der GNU/GPL Lizenz zustimmen um Clansuite zu installieren.';
        $this->language['STEP3_CHECKBOX'] = 'Ich habe zur Kenntnis genommen und stimme zu, dass Clansuite unter der GNU/GPL Lizenz steht!';

        // STEP 4 - Database
        $this->language['STEP4_DATABASE'] = 'Schritt [4] Datenbank';

        $this->language['STEP4_SENTENCE1'] = 'In Schritt [4] geben Sie Ihre MySQL-Datenbank Verbindungsdaten an und wir werden bei erfolgreicher Verbindung mit der Datenbank einige grundlegende Tabellen und Inhalte f�r Clansuite darin abzulegen.';
        $this->language['STEP4_SENTENCE2'] = 'Bitte geben Sie Ihren Nutzernamen und das dazugeh�rige Passwort an.';
        $this->language['STEP4_SENTENCE3'] = 'Wenn der Nutzer die Berechtigung zum Erstellen einer neuen Tabelle besitzt, so kann eine neue Tabelle mit dem gew�nschten Namen automatisch angelegt werden - andernfalls, ist eine bereits existierende Datenbank Tabelle anzugeben.';

        $this->language['STEP4_SENTENCE4'] = 'Tabellen und Eintr�ge werden angelegt.';
        $this->language['STEP4_SENTENCE5'] = 'Datenbanktabellen eines anderen CMS importieren.';

        $this->language['DB_HOST'] = 'Datenbank Hostname';
        $this->language['DB_TYPE'] = 'Databank Typ';
        $this->language['DB_NAME'] = 'Datenbank Name';
        $this->language['DB_CREATE_DATABASE'] = 'Datenbank erstellen';
        $this->language['DB_USERNAME'] = 'Datenbank Benutzer';
        $this->language['DB_PASSWORD'] = 'Datenbank Passwort';
        $this->language['DB_PREFIX'] = 'Tabellen Pr�fix';

        $this->language['ERROR_NO_DB_CONNECT'] = 'Es konnte keine Datenbankverbindung aufgebaut werden.';
        $this->language['ERROR_WHILE_CREATING_DATABASE'] = 'Die Datenbank konnte nicht erstellt werden.';
        $this->language['ERROR_FILL_OUT_ALL_FIELDS'] = 'Bitte f�llen Sie alle Felder aus!';

        // STEP 5 - Konfiguration
        $this->language['STEP5_CONFIG'] = 'Schritt [5] Konfiguration';

        $this->language['STEP5_SENTENCE1'] = 'Bitte nehmen Sie nun die grundlegenden Einstellungen f�r Ihre Internetpr�senz mit Clansuite vor.';
        $this->language['STEP5_SENTENCE2'] = 'Nach der Installation, k�nnen sie umfangreiche Einstellungen �ber das Admin-Control-Panel (ACP) vornehmen.';

        $this->language['STEP5_CONFIG_SITENAME'] = 'Name der Website';
        $this->language['STEP5_CONFIG_EMAILFROM'] = 'Systemmail';
        $this->language['STEP5_CONFIG_USERACCOUNT_ENCRYPTION'] = 'Verschl�sselung';
        $this->language['STEP5_CONFIG_TIMEZONE'] = 'Zeitzone';

        // STEP 6 - Create Administrator
        $this->language['STEP6_ADMINUSER'] = 'Schritt [6] Administrator anlegen';

        $this->language['STEP6_SENTENCE1'] = 'In Schritt [6] legen wir ein Benutzerkonto mit den von Ihnen eingegebenen Nutzerdaten an.';
        $this->language['STEP6_SENTENCE2'] = 'Diesem Konto werden wir Administratoren-Rechte geben, d.h. sie werden in der Lage sein, sich mit diesem Konto anzumelden und alle wesentlichen Systemeinstellungen vorzunehmen.';
        $this->language['STEP6_SENTENCE3'] = 'Bitte geben Sie Name und Passwort, sowie E-Mail und Sprache des Administrator-Benutzerkontos ein.';

        $this->language['STEP6_ADMIN_NAME']     = 'Administrator Name';
        $this->language['STEP6_ADMIN_PASSWORD'] = 'Administrator Passwort';
        $this->language['STEP6_ADMIN_LANGUAGE'] = 'Sprache';
        $this->language['STEP6_ADMIN_EMAIL']    = 'E-Mail Adresse';

        $this->language['STEP6_ERROR_COULD_NOT_CREATE_ADMIN'] = 'Benutzerkonto f�r den Administrator konnte nicht erstellt werden.';

        // STEP 7 - Abschluss
        $this->language['STEP7_FINISH'] = 'Schritt [7] Abschluss';

        $this->language['STEP7_SENTENCE1'] = 'Geschafft! Gratulation - Sie haben Clansuite erfolgreich installiert.';
        $this->language['STEP7_SENTENCE2'] = 'Das Entwicklerteam w�nscht Ihnen nun viel Freude beim Entdecken und Nutzen von Clansuite.';
        $this->language['STEP7_SENTENCE3'] = 'Sie finden nachfolgend die Links zur Hauptseite und zum Adminbereich, sowie ihre Logindaten als Administrator.';
        $this->language['STEP7_SENTENCE4'] = 'Besuchen Sie Ihre neue';
        $this->language['STEP7_SENTENCE5'] = 'Clansuite Webseite';
        $this->language['STEP7_SENTENCE6'] = 'oder das';
        $this->language['STEP7_SENTENCE7'] = 'Ihre Logindaten sind :';
        $this->language['STEP7_SENTENCE8'] = 'Hilfe zur Benutzung und Konfiguration der Clansuite-Software finden Sie im ';
        $this->language['STEP7_SENTENCE9'] = 'Benutzerhandbuch';
        $this->language['STEP7_SENTENCE10'] = 'Sicherheitshinweis';
        $this->language['STEP7_SENTENCE11'] = 'Vergessen Sie Bitte nicht, das Verzeichnis "/installation" aus Sicherheitsgr�nden umzubenennen bzw. zu l�schen.';

        // GLOBAL
        # Buttons
        $this->language['NEXTSTEP'] = 'Weiter >>';
        $this->language['BACKSTEP'] = '<< Zur�ck';
        # Help Text for Buttons
        $this->language['CLICK_NEXT_TO_PROCEED'] = 'Klicken Sie den Button ['. $this->language['NEXTSTEP'] .'] um fortzufahren.';
        $this->language['CLICK_BACK_TO_RETURN'] = 'Klicken Sie den Button ['. $this->language['BACKSTEP'] .'] um zum vorherigen Installationsschritt zur�ckzukehren.';
        # Right Side Menu
        $this->language['INSTALL_PROGRESS'] = 'Installations- fortschritt';
        $this->language['COMPLETED'] = 'Fertig';
        $this->language['CHANGE_LANGUAGE'] = 'Sprachauswahl';
        $this->language['SHORTCUTS'] = 'Clansuite Shortcuts';

        # Left Side Menu
        $this->language['MENU_HEADING'] = 'Installationsschritte';
        $this->language['MENUSTEP1'] = '[1] Sprachauswahl';
        $this->language['MENUSTEP2'] = '[2] Systempr�fung';
        $this->language['MENUSTEP3'] = '[3] GPL Lizenz';
        $this->language['MENUSTEP4'] = '[4] Datenbank';
        $this->language['MENUSTEP5'] = '[5] Konfiguration';
        $this->language['MENUSTEP6'] = '[6] Admin anlegen';
        $this->language['MENUSTEP7'] = '[7] Abschluss';

        ###

        $this->language['HELP'] = 'Hilfe';
        $this->language['LICENSE'] = 'Lizenz';
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
        // @todo: i have still no clue why utf8-encode() won't work!
        return $this->unicode_converter($this->language[$offset]);
    }

    public function offsetSet($offset, $value)
    {
        $this->__set($offset, $value);
    }

    // hmm� why should configuration be unset�
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
                                    "�" => "&#228;", "�" => "&#196;",
                                    "�" => "&#246;", "�" => "&#214;",
                                    "�" => "&#252;", "�" => "&#220;",
                                    "�" => "&#233;", "�" => "&#223;"
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
