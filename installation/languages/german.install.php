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
    * @copyright  Jens-André Koch (2005 - onwards)
    * @link       http://www.clansuite.com
    *
    * @version    SVN: $Id$
    */

/**
 * German Language for Installation
 *
 * Contributors: vain, vyper
 *
 * @category        Clansuite
 * @package         Installation
 * @subpackage      Languages
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
        $this->language['STEP2_SYSTEMSETTINGS_PHPINI'] = 'Falls Änderungen an der PHP-Konfiguration erforderlich sind, dann nehmen sie diese in der folgenden "php.ini"-Datei vor ';
        $this->language['STEP2_SYSTEMSETTINGS_CHECK_VALUES'] = 'Die Systemüberprüfung ergab folgendes:';

        $this->language['STEP2_SYSTEMSETTING_REQUIRED'] = 'Erforderliche Einstellungen (Muss)';
        $this->language['STEP2_SYSTEMSETTING_RECOMMENDED'] = 'Wünschenswerte Einstellungen (Kann)';

        $this->language['STEP2_SETTING'] = 'Einstellung';
        $this->language['STEP2_SETTING_ACTUAL'] = 'Tatsächlich';
        $this->language['STEP2_SETTING_EXPECTED'] = 'Erwartet';
        $this->language['STEP2_SETTING_STATUS'] = 'Status';

        $this->language['STEP2_SETTING_EXPECTED_ON'] = 'an';
        $this->language['STEP2_SETTING_EXPECTED_OFF'] = 'aus';

        # REQUIRED SETTINGS (in order)
        $this->language['PHP_VERSION'] = 'PHP Version';
        $this->language['SESSION_FUNCTIONS'] = 'Session Funktionen';
        $this->language['SESSION_AUTO_START'] = 'Session Autostart';
        $this->language['PDO_LIBRARY'] = 'PDO - Bibliothek';
        $this->language['EXTENSION_PDO_MYSQL'] = 'Erweiterung: "pdo_mysql"';
        $this->language['CLASS_REFLECTION'] = 'PHP Reflection';
        $this->language['EXTENSION_SPL'] = 'Standard PHP Library (SPL)';
        $this->language['IS_WRITEABLE_TEMP_DIR'] = 'Nutzbar: Temporäres Verzeichnis';
        $this->language['IS_WRITEABLE_CLANSUITE_ROOT'] = 'Beschreibbar: "/clansuite"';
        $this->language['IS_WRITEABLE_CACHE_DIR'] = 'Beschreibbar: "/clansuite/cache"';
        $this->language['IS_WRITEABLE_UPLOADS'] = 'Beschreibbar: "/uploads"';
        $this->language['IS_READABLE_CONFIG_TEMPLATE'] = 'Lesbar: Config-Vorlagedatei';
        $this->language['DATE_TIMEZONE'] = 'Zeitzone eingestellt "date.timezone"';

        # RECOMMENDED SETTINGS (in order)
        $this->language['PHP_MEMORY_LIMIT'] = 'PHP Memory Limit';
        $this->language['FILE_UPLOADS'] = 'Dateiuploads erlaubt?';
        $this->language['MAX_UPLOAD_FILESIZE'] = 'Maximale Dateigröße für Uploads';
        $this->language['POST_MAX_SIZE'] = 'Maximale Größe von Posts';
        $this->language['ALLOW_URL_FOPEN'] = 'Zugriff auf entfernte Dateien';
        $this->language['ALLOW_URL_INCLUDE'] = 'Direkteinbindung entfernter Dateien';
        $this->language['SAFE_MODE'] = 'SAFE_MODE';
        $this->language['OPEN_BASEDIR'] = 'OPEN_BASEDIR';
        $this->language['MAGIC_QUOTES_GPC'] = 'Magic Quotes GPC';
        $this->language['MAGIC_QUOTES_RUNTIME'] = 'Magic Quotes Runtime';
        $this->language['SHORT_OPEN_TAG'] = 'Kurzversion öffnender PHP-Tags';
        $this->language['OUTPUT_BUFFERING'] = 'Output Buffering';
        $this->language['XSLT_PROCESSOR'] = 'XSLT-Prozessor';
        $this->language['EXTENSION_HASH'] = 'PHP Erweiterung: Hash';
        $this->language['EXTENSION_GETTEXT'] = 'PHP Erweiterung: Gettext';
        $this->language['EXTENSION_MBSTRING'] = 'PHP Erweiterung: MBString (Multi-Byte)';
        $this->language['EXTENSION_TOKENIZER'] = 'PHP Erweiterung: Tokenizer';
        $this->language['EXTENSION_GD'] = 'PHP Erweiterung: GD';
        $this->language['EXTENSION_XML'] = 'PHP Erweiterung: XML';
        $this->language['EXTENSION_PCRE'] = 'PHP Erweiterung: PCRE (Perl Regexp)';
        $this->language['EXTENSION_SIMPLEXML'] = 'PHP Erweiterung: SimpleXML';
        $this->language['EXTENSION_SUHOSIN'] = 'PHP Erweiterung: Suhosin';
        $this->language['EXTENSION_SKEIN'] = 'PHP Erweiterung: Skein';
        $this->language['EXTENSION_GEOIP'] = 'PHP Erweiterung: GeoIP';
        $this->language['EXTENSION_CURL'] = 'PHP Erweiterung: cURL';
        $this->language['EXTENSION_SYCK'] = 'PHP Erweiterung: SYCK';
        $this->language['EXTENSION_APC'] = 'PHP Erweiterung: APC';
        $this->language['EXTENSION_MEMCACHE'] = 'PHP Erweiterung: MEMCACHE';
        $this->language['EXTENSION_MCRYPT'] = 'PHP Erweiterung: MCRYPT';
        $this->language['EXTENSION_CALENDAR'] = 'PHP Erweiterung: CALENDAR';

        // STEP 3 - License
        $this->language['STEP3_LICENSE'] = 'Schritt [3] GNU/GPL Lizenz';

        $this->language['STEP3_SENTENCE1'] = 'Bitte nehmen Sie zur Kenntnis, dass der Clansuite Quellcode unter der GNU/GPL Lizenz Version 2 und jeder späteren Version veröffentlicht wurde! Die Urheberin der nachfolgenden GNU/GPL Lizenz ist die Free Software Foundation.';
        $this->language['STEP3_REVIEW_THIRDPARTY'] = 'Bitte überprüfen Sie nach Abschluß der Installation die Lizenzbestimmungen der Fremdbibliotheken die von Clansuite eingesetzt werden. Sie sind in der Datei THIRD-PARTY-LIBRARIES.txt im Verzeichnis "/doc" zu finden.';
        $this->language['STEP3_REVIEW_CLANSUITE'] = 'Bitte überprüfen Sie die Lizenzbestimmungen bevor Sie Clansuite installieren:';
        $this->language['STEP3_MUST_AGREE'] = 'Sie müssen der GNU/GPL Lizenz zustimmen um Clansuite zu installieren.';
        $this->language['STEP3_CHECKBOX'] = 'Ich habe zur Kenntnis genommen und stimme zu, dass Clansuite unter der GNU/GPL Lizenz steht!';

        // STEP 4 - Database
        $this->language['STEP4_DATABASE'] = 'Schritt [4] Datenbank';

        $this->language['STEP4_SENTENCE1'] = 'In Schritt [4] geben Sie Ihre Datenbank Verbindungsdaten an und wir werden bei erfolgreicher Verbindung mit der Datenbank einige grundlegende Tabellen und Inhalte für Clansuite darin ablegen.';
        $this->language['STEP4_SENTENCE2'] = 'Diese Verbindungsdaten, insbesondere Ihren Nutzernamen und das dazugehörige Passwort, erhalten Sie von Ihrem Provider. Wenn Sie für Ihren Server selbst verantwortlich sind, dann registrieren Sie einen neuen Datenbanken-Nutzer.';

        $this->language['STEP4_SENTENCE3'] = 'Wenn der Nutzer die Berechtigung zum Erstellen einer neuen Tabelle besitzt, so kann eine neue Tabelle mit dem gewünschten Namen automatisch angelegt werden - andernfalls, ist eine bereits existierende Datenbanktabelle anzugeben.';

        $this->language['STEP4_SENTENCE4'] = 'Tabellen und Einträge werden angelegt.';
        $this->language['STEP4_SENTENCE5'] = 'Datenbanktabellen eines anderen CMS importieren.';

        $this->language['HOST'] = 'Datenbank Hostname';
        $this->language['DRIVER'] = 'Datenbank Treiber';
        $this->language['NAME'] = 'Datenbank Name';
        $this->language['CREATE_DATABASE'] = 'Datenbank erstellen';
        $this->language['USERNAME'] = 'Datenbank Benutzer';
        $this->language['PASSWORD'] = 'Datenbank Passwort';
        $this->language['PREFIX'] = 'Tabellen Präfix';

        $this->language['HOST_TOOLTIP'] = 'Bitte geben Sie hier den Host ihrer Datenbank ein. Beispiele: 127.0.0.1 oder localhost.';
        $this->language['DRIVER_TOOLTIP'] = 'Bitte wählen Sie den Typ Ihrer Datenbank aus.';
        $this->language['NAME_TOOLTIP'] = 'Geben Sie hier den Namen der Datenbank ein, in die Clansuite installiert werden soll.';
        $this->language['CREATEDB_TOOLTIP'] = 'Falls die Datenbank noch nicht existiert und der Nutzer die entsprechende Berechtigung hat, können Sie die Datenbank anlegen lassen.';
        $this->language['USERNAME_TOOLTIP'] = 'Geben Sie hier einen schreibberechtigten Nutzernamen Ihrer Datenbank ein.';
        $this->language['PASSWORD_TOOLTIP'] = 'Geben Sie hier das Passwort für den Benutzer der Datenbank ein.';
        $this->language['PREFIX_TOOLTIP'] = 'Sie können ein Präfix für die Clansuite Datenbanktabellen festlegen.';
        $this->language['PREFIX_TOOLTIP'] .= ' Wenn Sie eine Vielzahl von Tabellen in nur eine Datenbank installieren, dann können Sie durch Präfigierung der Tabellen Namenskollisionen vermeiden.';

        $this->language['ERROR_NO_DB_CONNECT'] = 'Es konnte keine Datenbankverbindung aufgebaut werden.';
        $this->language['ERROR_WHILE_CREATING_DATABASE'] = 'Die Datenbank konnte nicht erstellt werden.';
        $this->language['ERROR_FILL_OUT_ALL_FIELDS'] = 'Bitte füllen Sie alle Felder aus!';

        // STEP 5 - Konfiguration
        $this->language['STEP5_CONFIG'] = 'Schritt [5] Konfiguration';

        $this->language['STEP5_LEGEND'] = 'Konfiguration';

        $this->language['STEP5_SENTENCE1'] = 'Bitte nehmen Sie nun die grundlegenden Einstellungen für Ihre Internetpräsenz mit Clansuite vor.';
        $this->language['STEP5_SENTENCE2'] = 'Nach der Installation, können sie umfangreiche Einstellungen über das Admin-Control-Panel (ACP) vornehmen.';

        $this->language['STEP5_CONFIG_SITENAME'] = 'Name der Website';
        $this->language['STEP5_CONFIG_EMAILFROM'] = 'Systemmail';
        $this->language['STEP5_CONFIG_USERACCOUNT_ENCRYPTION'] = 'Verschlüsselung';
        $this->language['STEP5_CONFIG_GMTOFFSET'] = 'Zeitzone';

        $this->language['STEP5_SITENAME_TOOLTIP'] = 'Bitte geben Sie ihrer Webseite einen Namen. Er wird in der Titelzeile des Browsers erscheinen.';
        $this->language['STEP5_SYSTEM_EMAIL_TOOLTIP'] = 'Bitte geben sie eine EMail-Adresse an. Clansuite wird diese Email-Adresse nutzen, um Mails an Nutzer zu verschicken.';
        $this->language['STEP5_ACCOUNT_CRYPT_TOOLTIP'] = 'Bitte wählen Sie die das Sicherungsverfahren für die Passwörter der Nutzerkonten. Falls ihre Datenbank in falsche Hände gelangt, sind dann keine Passworter im Klartext enthalten.';
        $this->language['STEP5_GMTOFFSET_TOOLTIP'] = 'Bitte wählen Sie ihre Zeitzone. Diese Einstellung ist für alle Datums- und Zeitberechnungen wesentlich.';

        // STEP 6 - Create Administrator
        $this->language['STEP6_ADMINUSER'] = 'Schritt [6] Administrator anlegen';

        $this->language['STEP6_LEGEND'] = 'Nutzerkonto des Administrators anlegen';

        $this->language['STEP6_SENTENCE1'] = 'In Schritt [6] legen wir ein Benutzerkonto mit den von Ihnen eingegebenen Nutzerdaten an.';
        $this->language['STEP6_SENTENCE2'] = 'Diesem Konto werden wir Administratoren-Rechte geben, d.h. sie werden in der Lage sein, sich mit diesem Konto anzumelden und alle wesentlichen Systemeinstellungen vorzunehmen.';
        $this->language['STEP6_SENTENCE3'] = 'Bitte geben Sie Name und Passwort, sowie E-Mail und Sprache des Administrator-Benutzerkontos ein.';

        $this->language['STEP6_ADMIN_NAME']     = 'Administrator Name';
        $this->language['STEP6_ADMIN_PASSWORD'] = 'Administrator Passwort';
        $this->language['STEP6_ADMIN_LANGUAGE'] = 'Sprache';
        $this->language['STEP6_ADMIN_EMAIL']    = 'E-Mail Adresse';

        $this->language['STEP6_ERROR_COULD_NOT_CREATE_ADMIN'] = 'Benutzerkonto für den Administrator konnte nicht erstellt werden.';

        $this->language['STEP6_ADMIN_NAME_TOOLTIP']     = 'Legen Sie bitte den Namen des Administrator Nutzerkontos fest. Mit diesem Namen können Sie sich später einloggen.';
        $this->language['STEP6_ADMIN_PASSWORD_TOOLTIP'] = 'Bitte setzen Sie ein Passwort für das Nutzerkonto des Administrators.';
        $this->language['STEP6_ADMIN_LANGUAGE_TOOLTIP'] = 'Bitte wählen Sie die Sprache des Nutzerkontos.';
        $this->language['STEP6_ADMIN_EMAIL_TOOLTIP']    = 'Bitte geben Sie die E-Mail Adresse für das Nutzerkonto des Administrators an. Mit dieser Email können Sie sich später einloggen.';

        // STEP 7 - Abschluss
        $this->language['STEP7_FINISH'] = 'Schritt [7] Abschluss';

        $this->language['STEP7_SENTENCE1'] = 'Geschafft! Gratulation - Sie haben Clansuite erfolgreich installiert.';
        $this->language['STEP7_SENTENCE2'] = 'Das Entwicklerteam wünscht Ihnen nun viel Freude beim Entdecken und Nutzen von Clansuite.';
        $this->language['STEP7_SENTENCE3'] = 'Sie finden nachfolgend die Links zur Hauptseite und zum Adminbereich, sowie ihre Logindaten als Administrator.';
        $this->language['STEP7_SENTENCE4'] = 'Besuchen Sie Ihre neue';
        $this->language['STEP7_SENTENCE5'] = 'Clansuite Webseite';
        $this->language['STEP7_SENTENCE6'] = 'oder das';

        $this->language['STEP7_SENTENCE8'] = 'Hilfe zur Benutzung und Konfiguration des Clansuite CMS finden Sie im ';
        $this->language['STEP7_SENTENCE9'] = 'Benutzerhandbuch';
        $this->language['STEP7_SENTENCE10'] = 'Sicherheitshinweis';
        $this->language['STEP7_SENTENCE11'] = 'Bitte vergessen Sie nicht, das Verzeichnis "/installation" aus Sicherheitsgründen umzubenennen oder zu löschen.';
        $this->language['STEP7_SENTENCE12'] = 'Lösche das Unterverzeichnis "/installation" sofort.';

        /* @todo http://trac.clansuite.com/ticket/7
        $this->language['STEP7_SUPPORT_ENTRY_LEGEND'] = 'Eintrag in die Support-Datenbank';
        $this->language['STEP7_SUPPORT_ENTRY_1'] = 'Ihnen wird nun die Möglichkeit gegeben, sich in unsere Support-Datenbank einzutragen.';
        $this->language['STEP7_SUPPORT_ENTRY_2'] = 'Der Eintrag bewirkt die Erstellung eines Nutzerkontos im Clansuite Forum und im Clansuite Bugtracker.';
        $this->language['STEP7_SUPPORT_ENTRY_3'] = 'Der Eintrag erfolgt freiwillig. Sie stimmen der Erfassung ihrer Daten ausdrücklich zu.';
        $this->language['STEP7_SUPPORT_ENTRY_4'] = 'Möchten Sie den Clansuite Newsletter erhalten?';
        */

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
        return utf8_encode($this->language[$offset]);
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
}
?>
