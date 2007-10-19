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

    $this->language['STEP1_THANKS_CHOOSING'] = 'Vielen Dank, dass Sie sich f&#252;r Clansuite entschieden haben!';
    $this->language['STEP1_WELCOME'] = 'Willkommen zur Installation von Clansuite.';
    $this->language['STEP1_APPINSTALL_STEPWISE'] = 'Diese Anwendung f&#252;hrt Sie schrittweise durch die Installation.';
    $this->language['STEP1_CHOOSELANGUAGE'] = 'W&#228;hlen Sie bitte die Sprache aus.';

    // STEP 2 - System Check
    $this->language['STEP2_SYSTEMCHECK'] = 'Schritt [2] Systempr&#252;fung';

    $this->language['STEP2_IN_GENERAL'] = 'In Schritt [2] pr&#252;fen wir, ob Ihr Webserver die Installationsanforderungen von Clansuite erf&#252;llt.';
	
	$this->language['STEP2_SYSTEMSETTINGS_REQUIRED'] = 'Einige der Systemeinstellungen sind zwingend erforderlich, damit Clansuite ordnungsgem&#228;&szlig; funktioniert.';
	$this->language['STEP2_SYSTEMSETTINGS_RECOMMENDED'] = 'Andere sind lediglich empfohlene Einstellungen, sei es aus Sicherheits- oder Performancegr&#252;nden.';
	$this->language['STEP2_SYSTEMSETTINGS_TAKEACTION'] = 'Die rot markierten Einstellungen zeigen Ihnen auf, wo noch Handlungsbedarf besteht.';
	$this->language['STEP2_SYSTEMSETTINGS_CHECK_VALUES'] = 'Die System&#252;berpr&#252;fung ergab folgendes:';

	$this->language['STEP2_SYSTEMSETTING_REQUIRED'] = 'Erforderliche Einstellungen';
	$this->language['STEP2_SYSTEMSETTING_RECOMMENDED'] = 'W&#252;nschenswerte Einstellungen';

	$this->language['STEP2_SETTING'] = 'Einstellung';
	$this->language['STEP2_SETTING_ACTUAL'] = 'Tats&#228;chlich';
	$this->language['STEP2_SETTING_EXPECTED'] = 'Erwartet';



    # REQUIRED SETTINGS (in order)
    $this->language['PHP_VERSION'] = 'Pr&#252;fe auf PHP Version 5.2+';
    $this->language['SESSION_FUNCTIONS'] = 'Pr&#252;fe auf Session Funktionen';
    $this->language['MAGIC_QUOTES_RUNTIME'] = 'Pr&#252;fe, ob Magic Quotes Runtime';
    $this->language['TOKENIZER'] = 'Pr&#252;fe, ob Tokenizer exists';

    # RECOMMENDED SETTINGS (in order)
    $this->language['PHP_MEMORY_LIMIT'] = 'Pr&#252;fe auf PHP memory limit (Minimum 8M, recommend 16M)';
    $this->language['FILE_UPLOADS'] = 'Pr&#252;fe, ob Dateiuploads erlaubt sind';
    $this->language['REGISTER_GLOBALS'] = 'Pr&#252;fe auf globale Registrierung';
    $this->language['ALLOW_URL_FOPEN'] = 'Checking for ALLOW_URL_FOPEN';
    $this->language['SAFE_MODE'] = 'Checking for SAFE_MODE';
    $this->language['OPEN_BASEDIR'] = 'Checking for OPEN_BASEDIR';
    $this->language['EXTENSION_GD'] = 'Checking for EXTENSION_GD';
    $this->language['MAGIC_QUOTES_GPC'] = 'Checking for Magic Quotes GPC';

    // STEP 3 - Licence
    $this->language['STEP3_LICENCE'] = 'Schritt [3] GNU/GPL Lizenz';

    $this->language['STEP3_SENTENCE1'] = 'Bitte nehmen Sie zur Kenntnis, dass der Clansuite Quellcode unter der GNU/GPL Lizenz ver&#246;ffentlicht wurde! Die Urheberin der nachfolgenden GNU/GPL Lizenz ist die Free Software Foundation.';
    $this->language['STEP3_REVIEW'] = 'Bitte &#252;berpr&#252;fen Sie die Lizenzbestimmungen bevor Sie Clansuite installieren:';
    $this->language['STEP3_MUST_AGREE'] = 'Sie m&#252;ssen der GNU/GPL Lizenz zustimmen um Clansuite zu installieren.';
    $this->language['STEP3_CHECKBOX'] = 'Ich habe zur Kenntnis genommen und stimme zu, dass Clansuite unter der GNU/GPL Lizenz steht!';
    
    // STEP 4 - Database
	$this->language['STEP4_DATABASE'] = 'Schritt [4] Datenbank';

    $this->language['STEP4_SENTENCE1'] = 'In Schritt [4] geben Sie Ihre MySQL-Datenbank Verbingdungsdaten an und wir werden bei erfolgreicher Verbingung einige grundlegende Tabellen und Inhalte fr Clansuite in der Datenbank abzulegen.';
	$this->language['STEP4_SENTENCE2'] = 'Bitte geben Sie Ihren Nutzernamen und das dazugeh”rige Passwort an.';
	$this->language['STEP4_SENTENCE3'] = 'Wenn der Nutzer die Berechtigung zum Erstellen einer neuen Tabelle besitzt, so kann eine neue Tabelle mit dem gewnschten Namen automatisch angelegt werden - andernfalls, ist eine bereits existierende Datenbank Tabelle anzugeben.';
       
	$this->language['STEP4_SENTENCE4'] = 'Tabellen und Eintr&#228;ge werden angelegt.';
	$this->language['STEP4_SENTENCE5'] = 'Datenbanktabellen eines anderen CMS importieren.';

	$this->language['DB_HOST'] = 'Datenbank Hostname';
	$this->language['DB_NAME'] = 'Datenbank Name';
	$this->language['DB_CREATE_DATABASE'] = 'Datenbank erstellen?';
	$this->language['DB_USER'] = 'Datenbank Benutzer';
	$this->language['DB_PASS'] = 'Datenbank Passwort';
	$this->language['DB_PREFIX'] = 'Tabellen Pr&#228;fix';

	$this->language['ERROR_NO_DB_CONNECT'] = 'Es konnte keine Datenbankverbindung aufgebaut werden.';

	// STEP 5 - Konfiguration
    $this->language['STEP5_CONFIG'] = 'Schritt [5] Konfiguration';

    $this->language['STEP5_SENTENCE1'] = 'Bitte nehmen Sie nun die grundlegenden Einstellungen f&#252;r Ihre Internetpr&#228;senz mit Clansuite vor.';

    $this->language['STEP5_CONFIG_SITENAME'] 	= 'Name der Website';
    $this->language['STEP5_CONFIG_SYSTEMEMAIL'] = 'Systemmail';
    $this->language['STEP5_CONFIG_USERACCOUNT_ENCRYPTION'] 	= 'Verschl&#252;sselungsart der Benutzer-Passw&#246;rter';
    $this->language['STEP5_CONFIG_SALTING'] = 'Salting';
	$this->language['STEP5_CONFIG_TIMEZONE'] = 'Zeitzone';

	// STEP 6
	$this->language['STEP6_ADMINUSER'] = 'Schritt [6] Administrator anlegen';

	$this->language['STEP6_SENTENCE1'] = 'Bitte geben Sie Name und Passwort des Administrator-Benutzerkontos ein.';

	$this->language['STEP6_ADMIN_NAME'] 	= 'Administrator Name';
	$this->language['STEP6_ADMIN_PASSWORD'] = 'Administrator Passwort';
	$this->language['STEP6_ADMIN_LANGUAGE'] = 'Sprache';
	$this->language['STEP6_ADMIN_EMAIL']	= 'E-Mail Adresse';

	$this->language['STEP6_ERROR_COULD_NOT_CREATE_ADMIN'] = 'FEHLER -  Admin konnte nicht erstellt werden.';

	// STEP 7 - Abschluss
    $this->language['STEP7_FINISH'] = 'Schritt [7] Abschluss';

	// GLOBAL
    # Buttons
    $this->language['NEXTSTEP'] = 'Weiter &rsaquo;';
    $this->language['BACKSTEP'] = '&lsaquo; Zur&#252;ck';
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
