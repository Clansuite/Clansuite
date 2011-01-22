<?php
/**
 * @category        Clansuite
 * @package         Installation
 * @subpackage      Languages
 * @author          Jens-André Koch <vain@clansuite.com>
 * @copyright       Jens-André Koch & Clansuite Development Team
 * @license         http://www.gnu.org/copyleft/gpl.html GNU/GPL Public Licence
 * @description     German Installation Language
 * @version         SVN: $Id$
 * @implements      ArrayAccess
 *
 * Encoding: UTF-8
 * Contributors: vain, vyper
 */

class Language implements ArrayAccess
{
    private $language = array();

    // table of strings
    function __construct()
    {
        // STEP 1 - Language Selection
        $this->language['STEP1_LANGUAGE_SELECTION'] = 'Schritt [1] Sprachauswahl';

        $this->language['STEP1_WELCOME'] = 'Willkommen zur Installation von Clansuite.';
        $this->language['STEP1_THANKS_CHOOSING'] = 'Vielen Dank, dass Sie sich für Clansuite entschieden haben!';
        $this->language['STEP1_APPINSTALL_STEPWISE'] = 'Der Installationsassistent führt Sie schrittweise durch die Installation.';
        $this->language['STEP1_CHOOSELANGUAGE'] = 'Wählen Sie bitte die Sprache aus.';

        // STEP 2 - System Check
        $this->language['STEP2_SYSTEMCHECK'] = 'Schritt [2] Systemprüfung';

        $this->language['STEP2_IN_GENERAL'] = 'In Schritt [2] prüfen wir, ob Ihr Webserver die Installationsanforderungen von Clansuite erfüllt.';

        $this->language['STEP2_SYSTEMSETTINGS_REQUIRED'] = 'Einige der Systemeinstellungen sind zwingend erforderlich, damit Clansuite ordnungsgemäß funktioniert.';
        $this->language['STEP2_SYSTEMSETTINGS_RECOMMENDED'] = 'Andere sind lediglich empfohlene Einstellungen, sei es aus Sicherheits- oder Performancegründen.';
        $this->language['STEP2_SYSTEMSETTINGS_TAKEACTION'] = 'Bitte stellen Sie sicher, dass die erforderlichen Einstellungen alle grün markiert sind. Die rot markierten Einstellungen zeigen Ihnen auf, wo noch Handlungsbedarf besteht.';
        $this->language['STEP2_SYSTEMSETTINGS_CHECK_VALUES'] = 'Die Systemüberprüfung ergab folgendes:';

        $this->language['STEP2_SYSTEMSETTING_REQUIRED'] = 'Erforderliche Einstellungen (Muss)';
        $this->language['STEP2_SYSTEMSETTING_RECOMMENDED'] = 'Wünschenswerte Einstellungen (Kann)';

        $this->language['STEP2_SETTING'] = 'Einstellung';
        $this->language['STEP2_SETTING_ACTUAL'] = 'Tatsächlich';
        $this->language['STEP2_SETTING_EXPECTED'] = 'Erwartet';
        $this->language['STEP2_SETTING_STATUS'] = 'Status';

        # REQUIRED SETTINGS (in order)
        $this->language['PHP_VERSION'] = 'PHP Version';
        $this->language['SESSION_FUNCTIONS'] = 'Session Funktionen';
        $this->language['PDO_LIBRARY'] = 'PDO - Bibliothek';
        $this->language['PDO_MYSQL_LIBRARY'] = 'PDO - MySQL - Bibliothek';
        $this->language['CLASS_REFLECTION'] = 'PHP Reflection';
        $this->language['EXTENSION_SPL'] = 'Standard PHP Library (SPL)';
        $this->language['IS_WRITEABLE_TEMP_DIR'] = 'Nutzbar: Temporäres Verzeichnis';
        $this->language['IS_WRITEABLE_CLANSUITE_ROOT'] = 'Beschreibbar: /clansuite';
        $this->language['IS_WRITEABLE_CACHE_DIR'] = 'Beschreibbar: /clansuite/cache';
        $this->language['IS_WRITEABLE_SMARTY_TEMPLATES_C'] = 'Beschreibbar: /templates_c';
        $this->language['IS_WRITEABLE_SMARYT_CACHE'] = 'Beschreibbar: /cache';
        $this->language['IS_WRITEABLE_UPLOADS'] = 'Beschreibbar: /uploads';
        $this->language['IS_READABLE_CONFIG_TEMPLATE'] = 'Lesbar: Config-Vorlagedatei';

        # RECOMMENDED SETTINGS (in order)
        $this->language['PHP_MEMORY_LIMIT'] = 'PHP Memory Limit';
        $this->language['FILE_UPLOADS'] = 'Dateiuploads erlaubt?';
        $this->language['MAX_UPLOAD_FILESIZE'] = 'Maximale Dateigröße für Uploads';
        $this->language['POST_MAX_SIZE'] = 'Maximale Größe von Posts';
        $this->language['REGISTER_GLOBALS'] = 'REGISTER_GLOBALS';
        $this->language['ALLOW_URL_FOPEN'] = 'Zugriff auf entfernte Dateien';
        $this->language['ALLOW_URL_INCLUDE'] = 'Direkteinbindung entfernter Dateien';
        $this->language['SAFE_MODE'] = 'SAFE_MODE';
        $this->language['OPEN_BASEDIR'] = 'OPEN_BASEDIR';
        $this->language['MAGIC_QUOTES_GPC'] = 'Magic Quotes GPC';
        $this->language['MAGIC_QUOTES_RUNTIME'] = 'Magic Quotes Runtime';
        $this->language['OUTPUT_BUFFERING'] = 'Output Buffering';
        $this->language['XSLT_PROCESSOR'] = 'XSLT-Prozessor';
        $this->language['EXTENSION_HASH'] = 'PHP Bibliothek: Hash';
        $this->language['EXTENSION_GETTEXT'] = 'PHP Bibliothek: Gettext';
        $this->language['EXTENSION_TOKENIZER'] = 'PHP Bibliothek: Tokenizer';
        $this->language['EXTENSION_GD'] = 'PHP Bibliothek: GD';
        $this->language['EXTENSION_XML'] = 'PHP Bibliothek: XML';
        $this->language['EXTENSION_PCRE'] = 'PHP Bibliothek: PCRE (Perl Regexp)';
        $this->language['EXTENSION_SIMPLEXML'] = 'PHP Bibliothek: SimpleXML';
        $this->language['EXTENSION_SUHOSIN'] = 'PHP Bibliothek: Suhosin';
        $this->language['EXTENSION_SKEIN'] = 'PHP Bibliothek: Skein';
        $this->language['EXTENSION_GEOIP'] = 'PHP Bibliothek: GeoIP';
        $this->language['EXTENSION_CURL'] = 'PHP Bibliothek: cURL';
        $this->language['EXTENSION_SYCK'] = 'PHP Bibliothek: SYCK';
        $this->language['EXTENSION_APC'] = 'PHP Bibliothek: APC';
        $this->language['EXTENSION_MEMCACHE'] = 'PHP Bibliothek: MEMCACHE';
        $this->language['EXTENSION_MCRYPT'] = 'PHP Bibliothek: MCRYPT';
        $this->language['EXTENSION_CALENDAR'] = 'PHP Bibliothek: CALENDAR';

        // STEP 3 - Licence
        $this->language['STEP3_LICENCE'] = 'Schritt [3] GNU/GPL Lizenz';

        $this->language['STEP3_SENTENCE1'] = 'Bitte nehmen Sie zur Kenntnis, dass der Clansuite Quellcode unter der GNU/GPL Lizenz Version 2 und jeder späteren Version veröffentlicht wurde! Die Urheberin der nachfolgenden GNU/GPL Lizenz ist die Free Software Foundation.';
        $this->language['STEP3_REVIEW_THIRDPARTY'] = 'Bitte überprüfen Sie nach Abschluß der Installation die Lizenzbestimmungen der Fremdbibliotheken die von Clansuite eingesetzt werden. Sie sind in der Datei THIRD-PARTY-LIBRARIES.txt im Verzeichnis "/doc" zu finden.';
        $this->language['STEP3_REVIEW_CLANSUITE'] = 'Bitte überprüfen Sie die Lizenzbestimmungen bevor Sie Clansuite installieren:';
        $this->language['STEP3_MUST_AGREE'] = 'Sie müssen der GNU/GPL Lizenz zustimmen um Clansuite zu installieren.';
        $this->language['STEP3_CHECKBOX'] = 'Ich habe zur Kenntnis genommen und stimme zu, dass Clansuite unter der GNU/GPL Lizenz steht!';

        // STEP 4 - Database
        $this->language['STEP4_DATABASE'] = 'Schritt [4] Datenbank';

        $this->language['STEP4_SENTENCE1'] = 'In Schritt [4] geben Sie Ihre MySQL-Datenbank Verbindungsdaten an und wir werden bei erfolgreicher Verbindung mit der Datenbank einige grundlegende Tabellen und Inhalte für Clansuite darin ablegen.';
        $this->language['STEP4_SENTENCE2'] = 'Diese Verbindungsdaten, insbesondere Ihren Nutzernamen und das dazugehörige Passwort, erhalten Sie von Ihrem Provider. Wenn Sie für Ihren Server selbst verantwortlich sind, dann registrieren Sie einen neuen MySQL Nutzer.';

        $this->language['STEP4_SENTENCE3'] = 'Wenn der Nutzer die Berechtigung zum Erstellen einer neuen Tabelle besitzt, so kann eine neue Tabelle mit dem gewünschten Namen automatisch angelegt werden - andernfalls, ist eine bereits existierende Datenbanktabelle anzugeben.';

        $this->language['STEP4_SENTENCE4'] = 'Tabellen und Einträge werden angelegt.';
        $this->language['STEP4_SENTENCE5'] = 'Datenbanktabellen eines anderen CMS importieren.';

        $this->language['HOST'] = 'Datenbank Hostname';
        $this->language['DRIVER'] = 'Databank Driver';
        $this->language['NAME'] = 'Datenbank Name';
        $this->language['CREATE_DATABASE'] = 'Datenbank erstellen';
        $this->language['USERNAME'] = 'Datenbank Benutzer';
        $this->language['PASSWORD'] = 'Datenbank Passwort';
        $this->language['PREFIX'] = 'Tabellen Präfix';

        $this->language['ERROR_NO_DB_CONNECT'] = 'Es konnte keine Datenbankverbindung aufgebaut werden.';
        $this->language['ERROR_WHILE_CREATING_DATABASE'] = 'Die Datenbank konnte nicht erstellt werden.';
        $this->language['ERROR_FILL_OUT_ALL_FIELDS'] = 'Bitte füllen Sie alle Felder aus!';

        // STEP 5 - Konfiguration
        $this->language['STEP5_CONFIG'] = 'Schritt [5] Konfiguration';

        $this->language['STEP5_SENTENCE1'] = 'Bitte nehmen Sie nun die grundlegenden Einstellungen für Ihre Internetpräsenz mit Clansuite vor.';
        $this->language['STEP5_SENTENCE2'] = 'Nach der Installation, können sie umfangreiche Einstellungen über das Admin-Control-Panel (ACP) vornehmen.';

        $this->language['STEP5_CONFIG_SITENAME'] = 'Name der Website';
        $this->language['STEP5_CONFIG_EMAILFROM'] = 'Systemmail';
        $this->language['STEP5_CONFIG_USERACCOUNT_ENCRYPTION'] = 'Verschlüsselung';
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

        $this->language['STEP6_ERROR_COULD_NOT_CREATE_ADMIN'] = 'Benutzerkonto für den Administrator konnte nicht erstellt werden.';

        // STEP 7 - Abschluss
        $this->language['STEP7_FINISH'] = 'Schritt [7] Abschluss';

        $this->language['STEP7_SENTENCE1'] = 'Geschafft! Gratulation - Sie haben Clansuite erfolgreich installiert.';
        $this->language['STEP7_SENTENCE2'] = 'Das Entwicklerteam wünscht Ihnen nun viel Freude beim Entdecken und Nutzen von Clansuite.';
        $this->language['STEP7_SENTENCE3'] = 'Sie finden nachfolgend die Links zur Hauptseite und zum Adminbereich, sowie ihre Logindaten als Administrator.';
        $this->language['STEP7_SENTENCE4'] = 'Besuchen Sie Ihre neue';
        $this->language['STEP7_SENTENCE5'] = 'Clansuite Webseite';
        $this->language['STEP7_SENTENCE6'] = 'oder das';

        $this->language['STEP7_SENTENCE8'] = 'Hilfe zur Benutzung und Konfiguration der Clansuite-Software finden Sie im ';
        $this->language['STEP7_SENTENCE9'] = 'Benutzerhandbuch';
        $this->language['STEP7_SENTENCE10'] = 'Sicherheitshinweis';
        $this->language['STEP7_SENTENCE11'] = 'Vergessen Sie Bitte nicht, das Verzeichnis "/installation" aus Sicherheitsgründen umzubenennen bzw. zu löschen.';

        // GLOBAL
        # Buttons
        $this->language['NEXTSTEP'] = 'Weiter >>';
        $this->language['BACKSTEP'] = '<< Zurück';
        # Help Text for Buttons
        $this->language['CLICK_NEXT_TO_PROCEED'] = 'Klicken Sie den Button ['. $this->language['NEXTSTEP'] .'] um fortzufahren.';
        $this->language['CLICK_BACK_TO_RETURN'] = 'Klicken Sie den Button ['. $this->language['BACKSTEP'] .'] um zum vorherigen Installationsschritt zurückzukehren.';
        # Right Side Menu
        $this->language['INSTALL_PROGRESS'] = 'Installations- fortschritt';
        $this->language['COMPLETED'] = 'Fertig';
        $this->language['CHANGE_LANGUAGE'] = 'Sprachauswahl';
        $this->language['SHORTCUTS'] = 'Links';
        $this->language['LIVESUPPORT'] = 'Möchten Sie Hilfe?';
        $this->language['GETLIVESUPPORT_STATIC'] = 'Live Support (Starte Chat.)';

        # Left Side Menu
        $this->language['MENU_HEADING'] = 'Installationsschritte';
        $this->language['MENUSTEP1'] = '[1] Sprachauswahl';
        $this->language['MENUSTEP2'] = '[2] Systemprüfung';
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
