<?php
/**
 * @category        Clansuite
 * @package         Installation
 * @subpackage      Languages
 * @author          Jens-Andr� Koch <vain@clansuite.com>
 * @copyright       Jens-Andr� Koch & Clansuite Development Team
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
        $this->language['STEP1_THANKS_CHOOSING'] = 'Vielen Dank, dass Sie sich f�r Clansuite entschieden haben!';
        $this->language['STEP1_APPINSTALL_STEPWISE'] = 'Der Installationsassistent f�hrt Sie schrittweise durch die Installation.';
        $this->language['STEP1_CHOOSELANGUAGE'] = 'W�hlen Sie bitte die Sprache aus.';

        // STEP 2 - System Check
        $this->language['STEP2_SYSTEMCHECK'] = 'Schritt [2] Systempr�fung';

        $this->language['STEP2_IN_GENERAL'] = 'In Schritt [2] pr�fen wir, ob Ihr Webserver die Installationsanforderungen von Clansuite erf�llt.';

        $this->language['STEP2_SYSTEMSETTINGS_REQUIRED'] = 'Einige der Systemeinstellungen sind zwingend erforderlich, damit Clansuite ordnungsgem�� funktioniert.';
        $this->language['STEP2_SYSTEMSETTINGS_RECOMMENDED'] = 'Andere sind lediglich empfohlene Einstellungen, sei es aus Sicherheits- oder Performancegr�nden.';
        $this->language['STEP2_SYSTEMSETTINGS_TAKEACTION'] = 'Bitte stellen Sie sicher, dass die erforderlichen Einstellungen alle gr�n markiert sind. Die rot markierten Einstellungen zeigen Ihnen auf, wo noch Handlungsbedarf besteht.';
        $this->language['STEP2_SYSTEMSETTINGS_PHPINI'] = 'Falls �nderungen an der PHP-Konfiguration erforderlich sind, dann nehmen sie diese in der folgenden "php.ini"-Datei vor ';
        $this->language['STEP2_SYSTEMSETTINGS_CHECK_VALUES'] = 'Die System�berpr�fung ergab folgendes:';

        $this->language['STEP2_SYSTEMSETTING_REQUIRED'] = 'Erforderliche Einstellungen (Muss)';
        $this->language['STEP2_SYSTEMSETTING_RECOMMENDED'] = 'W�nschenswerte Einstellungen (Kann)';

        $this->language['STEP2_SETTING'] = 'Einstellung';
        $this->language['STEP2_SETTING_ACTUAL'] = 'Tats�chlich';
        $this->language['STEP2_SETTING_EXPECTED'] = 'Erwartet';
        $this->language['STEP2_SETTING_STATUS'] = 'Status';
        
        $this->language['STEP2_SETTING_EXPECTED_ON'] = 'an';
        $this->language['STEP2_SETTING_EXPECTED_OFF'] = 'aus';

        # REQUIRED SETTINGS (in order)
        $this->language['PHP_VERSION'] = 'PHP Version';
        $this->language['SESSION_FUNCTIONS'] = 'Session Funktionen';
        $this->language['SESSION_AUTO_START'] = 'Session Autostart';
        $this->language['PDO_LIBRARY'] = 'PDO - Bibliothek';
        $this->language['PDO_MYSQL_LIBRARY'] = 'PDO - MySQL - Bibliothek';
        $this->language['CLASS_REFLECTION'] = 'PHP Reflection';
        $this->language['EXTENSION_SPL'] = 'Standard PHP Library (SPL)';
        $this->language['IS_WRITEABLE_TEMP_DIR'] = 'Nutzbar: Tempor�res Verzeichnis';
        $this->language['IS_WRITEABLE_CLANSUITE_ROOT'] = 'Beschreibbar: /clansuite';
        $this->language['IS_WRITEABLE_CACHE_DIR'] = 'Beschreibbar: /clansuite/cache';
        $this->language['IS_WRITEABLE_UPLOADS'] = 'Beschreibbar: /uploads';
        $this->language['IS_READABLE_CONFIG_TEMPLATE'] = 'Lesbar: Config-Vorlagedatei';
        $this->language['DATE_TIMEZONE'] = 'Gesetzt: Date/Timezone';        

        # RECOMMENDED SETTINGS (in order)
        $this->language['PHP_MEMORY_LIMIT'] = 'PHP Memory Limit';
        $this->language['FILE_UPLOADS'] = 'Dateiuploads erlaubt?';
        $this->language['MAX_UPLOAD_FILESIZE'] = 'Maximale Dateigr��e f�r Uploads';
        $this->language['POST_MAX_SIZE'] = 'Maximale Gr��e von Posts';
        $this->language['REGISTER_GLOBALS'] = 'REGISTER_GLOBALS';
        $this->language['ALLOW_URL_FOPEN'] = 'Zugriff auf entfernte Dateien';
        $this->language['ALLOW_URL_INCLUDE'] = 'Direkteinbindung entfernter Dateien';
        $this->language['SAFE_MODE'] = 'SAFE_MODE';
        $this->language['OPEN_BASEDIR'] = 'OPEN_BASEDIR';
        $this->language['MAGIC_QUOTES_GPC'] = 'Magic Quotes GPC';
        $this->language['MAGIC_QUOTES_RUNTIME'] = 'Magic Quotes Runtime';
        $this->language['SHORT_OPEN_TAG'] = 'Kurzversion �ffnender PHP-Tags';
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

        $this->language['STEP3_SENTENCE1'] = 'Bitte nehmen Sie zur Kenntnis, dass der Clansuite Quellcode unter der GNU/GPL Lizenz Version 2 und jeder sp�teren Version ver�ffentlicht wurde! Die Urheberin der nachfolgenden GNU/GPL Lizenz ist die Free Software Foundation.';
        $this->language['STEP3_REVIEW_THIRDPARTY'] = 'Bitte �berpr�fen Sie nach Abschlu� der Installation die Lizenzbestimmungen der Fremdbibliotheken die von Clansuite eingesetzt werden. Sie sind in der Datei THIRD-PARTY-LIBRARIES.txt im Verzeichnis "/doc" zu finden.';
        $this->language['STEP3_REVIEW_CLANSUITE'] = 'Bitte �berpr�fen Sie die Lizenzbestimmungen bevor Sie Clansuite installieren:';
        $this->language['STEP3_MUST_AGREE'] = 'Sie m�ssen der GNU/GPL Lizenz zustimmen um Clansuite zu installieren.';
        $this->language['STEP3_CHECKBOX'] = 'Ich habe zur Kenntnis genommen und stimme zu, dass Clansuite unter der GNU/GPL Lizenz steht!';

        // STEP 4 - Database
        $this->language['STEP4_DATABASE'] = 'Schritt [4] Datenbank';

        $this->language['STEP4_SENTENCE1'] = 'In Schritt [4] geben Sie Ihre MySQL-Datenbank Verbindungsdaten an und wir werden bei erfolgreicher Verbindung mit der Datenbank einige grundlegende Tabellen und Inhalte f�r Clansuite darin ablegen.';
        $this->language['STEP4_SENTENCE2'] = 'Diese Verbindungsdaten, insbesondere Ihren Nutzernamen und das dazugeh�rige Passwort, erhalten Sie von Ihrem Provider. Wenn Sie f�r Ihren Server selbst verantwortlich sind, dann registrieren Sie einen neuen MySQL Nutzer.';

        $this->language['STEP4_SENTENCE3'] = 'Wenn der Nutzer die Berechtigung zum Erstellen einer neuen Tabelle besitzt, so kann eine neue Tabelle mit dem gew�nschten Namen automatisch angelegt werden - andernfalls, ist eine bereits existierende Datenbanktabelle anzugeben.';

        $this->language['STEP4_SENTENCE4'] = 'Tabellen und Eintr�ge werden angelegt.';
        $this->language['STEP4_SENTENCE5'] = 'Datenbanktabellen eines anderen CMS importieren.';

        $this->language['HOST'] = 'Datenbank Hostname';
        $this->language['DRIVER'] = 'Datenbank Treiber';
        $this->language['NAME'] = 'Datenbank Name';
        $this->language['CREATE_DATABASE'] = 'Datenbank erstellen';
        $this->language['USERNAME'] = 'Datenbank Benutzer';
        $this->language['PASSWORD'] = 'Datenbank Passwort';
        $this->language['PREFIX'] = 'Tabellen Pr�fix';

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
        $this->language['SHORTCUTS'] = 'Links';
        $this->language['LIVESUPPORT'] = 'M�chten Sie Hilfe?';
        $this->language['GETLIVESUPPORT_STATIC'] = 'Live Support (Starte Chat.)';

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
        return utf8_encode($this->language[$offset]);
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
}
?>
