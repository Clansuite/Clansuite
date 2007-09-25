<?php
/**
* @ Package: Clansuite
* @ Subpackage: Clansuite Installation
* @ License: http://www.gnu.org/copyleft/gpl.html GNU/GPL
* @ Description: German installation language
*/

class language implements ArrayAccess
{
    private $language = array();

    function __construct()
    {
    // STEP 1 - Language Selection
    $this->language['STEP1_LANGUAGE_SELECTION'] = 'Schritt [1] Sprachauswahl';

    $this->language['STEP1_WELCOME'] = 'Willkommen zum Installer von Clansuite.';
    $this->language['STEP1_APPINSTALL_STEPWISE'] = 'Diese Anwendung f&#252;hrt Sie schrittweise durch die Installation.';
    $this->language['STEP1_CHOOSELANGUAGE'] = 'W&#228;hlen Sie bitte die Sprache aus.';

    // STEP 2 - System Check
    $this->language['STEP2_SYSTEMCHECK'] = 'Schritt [2] Systempr&#252;fung';

	$this->language['STEP2_SYSTEMSETTINGS_REQUIRED'] = 'Einige der Systemeinstellungen sind zwingend erforderlich, damit Clansuite ordnungsgemäß funktioniert.';
	$this->language['STEP2_SYSTEMSETTINGS_RECOMMENDED'] = 'Andere sind lediglich empfohlene Einstellungen, sei es aus Sicherheits- oder Performancegründen.';
	$this->language['STEP2_SYSTEMSETTINGS_TAKEACTION'] = 'Die rot markierten Einstellungen zeigen Ihnen auf, wo noch Handlungsbedarf besteht.';
	$this->language['STEP2_SYSTEMSETTINGS_CHECK_VALUES'] = 'Die Systemüberprüfung ergab folgendes:';

	$this->language['STEP2_SYSTEMSETTING_REQUIRED'] = 'Erforderliche Einstellungen';
	$this->language['STEP2_SYSTEMSETTING_RECOMMENDED'] = 'Wünschenswerte Einstellungen';

	$this->language['STEP2_SETTING'] = 'Einstellung';
	$this->language['STEP2_SETTING_ACTUAL'] = 'Tats„chlich';
	$this->language['STEP2_SETTING_EXPECTED'] = 'Erwartet';



    # REQUIRED SETTINGS (in order)
    $this->language['PHP_VERSION'] = 'Prüfe auf PHP Version 5.2+';
    $this->language['SESSION_FUNCTIONS'] = 'Prüfe auf Session Funktionen';
    $this->language['MAGIC_QUOTES_RUNTIME'] = 'Prüfe, ob Magic Quotes Runtime';
    $this->language['TOKENIZER'] = 'Prüfe, ob Tokenizer exists';

    # RECOMMENDED SETTINGS (in order)
    $this->language['PHP_MEMORY_LIMIT'] = 'Checking PHP memory limit (Minimum 8M, recommend 16M)';
    $this->language['FILE_UPLOADS'] = 'Checking for File Uploads';
    $this->language['REGISTER_GLOBALS'] = 'Checking if Register Globals';
    $this->language['ALLOW_URL_FOPEN'] = 'Checking for ALLOW_URL_FOPEN';
    $this->language['SAFE_MODE'] = 'Checking for SAFE_MODE';
    $this->language['OPEN_BASEDIR'] = 'Checking for OPEN_BASEDIR';
    $this->language['EXTENSION_GD'] = 'Checking for EXTENSION_GD';
    $this->language['MAGIC_QUOTES_GPC'] = 'Checking for Magic Quotes GPC';

    // STEP 3 - Licence
    $this->language['STEP3_LICENCE'] = 'Schritt [3] GNU/GPL Lizenz';

    $this->language['STEP3_SENTENCE1'] = 'Nehmen Sie zur Kenntnis, dass Clansuite unter der GNU/GPL Lizenz ver&#246;ffentlicht wurde! Sie finden die Lizenz nachfolgend.';
    $this->language['STEP3_CHECKBOX'] = 'Ich habe zur Kenntnis genommen, dass Clansuite unter der GNU/GPL Lizenz steht!';

    // STEP 4 - Database
	$this->language['STEP4_DATABASE'] = 'Schritt [4] Datenbank';

	$this->language['STEP4_SENTENCE1'] = 'Bitte geben Sie Ihre MySQL Verbindungsdaten ein.';
	$this->language['STEP4_SENTENCE2'] = 'Tabellen und Einträge werden angelegt.';
	$this->language['STEP4_SENTENCE3'] = 'Datenbanktabellen eines anderen CMS importieren.';

	$this->language['DB_HOST'] = 'Datenbank Host';
	$this->language['DB_NAME'] = 'Datenbank Name';
	$this->language['DB_USER'] = 'Datenbank Benutzer';
	$this->language['DB_PASS'] = 'Datenbank Passwort';
	$this->language['DB_PREFIX'] = 'Datenbank Präfix';

	$this->language['ERROR_NO_DB_CONNECT'] = 'Es konnte keine Datenbankverbindung aufgebaut werden.';

	// STEP 5 - Konfiguration
    $this->language['STEP5_CONFIG'] = 'Schritt [5] Konfiguration';

    $this->language['STEP5_SENTENCE1'] = 'Bitte nehmen Sie nun die grundlegenden Einstellungen für Ihre Clansuite-Internetpräsenz vor.';

    $this->language['STEP5_CONFIG_SITENAME'] 	= 'Name der Website';
    $this->language['STEP5_CONFIG_SYSTEMEMAIL'] = 'Systemmail';
    $this->language['STEP5_CONFIG_USERACCOUNT_ENCRYPTION'] 	= 'Verschlüsselungs-Art der Benutzer-Passwörter';
    $this->language['STEP5_CONFIG_SALTING'] = 'Salting';
	$this->language['STEP5_CONFIG_TIMEZONE'] = 'Zeitzone';

	// STEP 6
	$this->language['STEP6_ADMINUSER'] = 'Schritt [6] Administrator anlegen';

	$this->language['STEP6_SENTENCE1'] = 'Bitte geben Sie Name und Passwort des Administrator-Benutzerkontos ein.';

	$this->language['STEP6_ADMIN_NAME'] 	= 'Administrator Name';
	$this->language['STEP6_ADMIN_PASSWORD'] = 'Administrator Passwort';
	$this->language['STEP6_ADMIN_LANGUAGE'] = 'Sprache';
	$this->language['STEP6_ADMIN_EMAIL']	= 'Email Adresse';

	$this->language['STEP6_ERROR_COULD_NOT_CREATE_ADMIN'] = 'FEHLER -  Admin konnte nicht erstellt werden.';

	// STEP 7 - Abschluss
    $this->language['STEP7_FINISH'] = 'Schritt [7] Abschluss';

	// GLOBAL
    # Buttons
    $this->language['NEXTSTEP'] = 'Weiter »';
    $this->language['BACKSTEP'] = '« Zurueck';
    # Help Text for Buttons
    $this->language['CLICK_NEXT_TO_PROCEED'] = 'Klicken Sie den Button ['. $this->language['NEXTSTEP'] .'] um fortzufahren.';
    $this->language['CLICK_BACK_TO_RETURN'] = 'Klicken Sie den Button ['. $this->language['BACKSTEP'] .'] um zum vorherigen Installationsschritt zur&#252;ckzukehren.';
    # Right Side Menu
    $this->language['INSTALL_PROGRESS'] = 'Installations- Fortschritt';
    $this->language['COMPLETED'] = 'Fertig';
    $this->language['CHANGE_LANGUAGE'] = 'Sprachauswahl';
    $this->language['SHORTCUTS'] = 'Clansuite Shortcuts';

    # Left Menu
    $this->language['MENU_HEADING'] = 'Installationsschritte';
    $this->language['MENUSTEP1'] = '[1] Sprachauswahl';
    $this->language['MENUSTEP2'] = '[2] Systempr&#252;fung';
    $this->language['MENUSTEP3'] = '[3] GPL Lizenz';
    $this->language['MENUSTEP4'] = '[4] Datenbank';
    $this->language['MENUSTEP5'] = '[5] Konfiguration';
    $this->language['MENUSTEP6'] = '[6] Admin anlegen';
    $this->language['MENUSTEP7'] = '[7] Abschluss';


    ####

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

    // hmm? why should configuration be unset?
    public function offsetUnset($offset)
    {
        unset($this->language[$offset]);
        return true;
    }
}
?>
