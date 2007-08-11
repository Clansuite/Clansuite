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
    
    // STEP 3 - Licence
    $this->language['STEP3_LICENCE'] = 'Schritt [3] GNU/GPL Lizenz';
    
    $this->language['STEP3_SENTENCE1'] = 'Nehmen Sie zur Kenntnis, dass Clansuite unter der GNU/GPL Lizenz ver&#246;ffentlicht wurde! Sie finden die Lizenz nachfolgend.';
    $this->language['STEP3_CHECKBOX'] = 'Ich habe zur Kenntnis genommen, dass Clansuite unter der GNU/GPL Lizenz steht!';
    
    $this->language['STEP4_DATABASE'] = 'Schritt [4] Datenbank';
    
    $this->language['STEP5_CONFIG'] = 'Schritt [5] Konfiguration';
    
    $this->language['STEP6_ADMINUSER'] = 'Schritt [6] Administrator anlegen';
    
    $this->language['STEP7_FINISH'] = 'Schritt [7] Abschluss';
    
    
    // GLOBAL
    # Buttons
    $this->language['NEXTSTEP'] = 'Weiter >>';
    $this->language['BACKSTEP'] = '<< Zurueck';
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
