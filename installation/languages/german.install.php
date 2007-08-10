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
       
    // STEP 2 - System Check
    $this->language['STEP2_SYSTEMCHECK'] = 'Schritt [2] Systemcheck';
    
    // STEP 3 - Licence
    $this->language['STEP3_LICENCE'] = 'Schritt [3] GNU/GPL Lizenz';
    
    $this->language['STEP3_SENTENCE1'] = 'Nehmen Sie zur Kenntnis, dass Clansuite unter der GNU/GPL Lizenz ver&#246;ffentlicht wurde! Sie finden die Lizenz nachfolgend.';
    $this->language['STEP3_CHECKBOX'] = 'Ich habe zur Kenntnis genommen, dass Clansuite unter der GNU/GPL Lizenz steht!';
    
    $this->language['STEP4_DATABASE'] = 'Schritt [4] Datenbank';
    
    $this->language['STEP5_CONFIG'] = 'Schritt [5] Konfiguration';
    
    $this->language['STEP6_ADMINUSER'] = 'Schritt [6] Administrator anlegen';
    
    $this->language['STEP7_FINISH'] = 'Schritt [7] Abschluss';
    
    
    //GLOBAL
    $this->language['NEXTSTEP'] = 'Weiter >>';
    $this->language['BACKSTEP'] = '<< Zurueck';
    
    ####
    
    $this->language['HELP'] = 'Hilfe';
    $this->language['COMPLETED'] = 'Durchgeführt';
    $this->language['PRE_INSTALLATION_CHECK'] = 'Vorinstallation Überprüfung';
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
