<?php 
/**
* @ Package: Clansuite
* @ Subpackage: Clansuite Installation
* @ License: http://www.gnu.org/copyleft/gpl.html GNU/GPL
* @ Description: English installation language
*/

class language implements ArrayAccess
{
    private $language = array();
    
    function __construct()
    {
        // STEP 1 - Language Selection
        $this->language['STEP1_LANGUAGE_SELECTION'] = 'Step [1] Language Selection';
        
        $this->language['STEP1_WELCOME'] = 'Welcome to the the Clansuite Installer.';   
        $this->language['STEP1_APPINSTALL_STEPWISE'] = 'This application will guide you you in several steps through the installation.';   
        $this->language['STEP1_CHOOSELANGUAGE'] = 'Please select your language.';
   
        
        // STEP 2 - System Check
        $this->language['STEP2_SYSTEMCHECK'] = 'Step [2] Systemcheck';
        
        // STEP 3 - Licence
        $this->language['STEP3_LICENCE'] = 'Step [3] GNU/GPL Licence';
        
        $this->language['STEP3_SENTENCE1'] = 'Realize, that Clansuite is released under GNU/GPL Licence!';
        $this->language['STEP3_CHECKBOX'] = 'I realized that Clansuite is released under the GNU/GPL License!';
        
        $this->language['STEP4_DATABASE'] = 'Step [4] Database';
        
        $this->language['STEP5_CONFIG'] = 'Step [5] Configuration';
        
        $this->language['STEP6_ADMINUSER'] = 'Step [6] Create Administrator';
        
        $this->language['STEP7_FINISH'] = 'Step [7] Finish';
        
        //GLOBAL
        # Buttons
        $this->language['NEXTSTEP'] = 'Next >>';
        $this->language['BACKSTEP'] = '<< Back';
        # Help Text for Buttons
        $this->language['CLICK_NEXT_TO_PROCEED'] = 'Click the Button ['. $this->language['NEXTSTEP'] .'] to proceed with the next Installstep.';
        $this->language['CLICK_BACK_TO_RETURN'] = 'Click the Button ['. $this->language['BACKSTEP'] .'] to return to the prior one.';
        # Right Side Menu
        $this->language['INSTALL_PROGRESS'] = 'Install Progress';
        $this->language['COMPLETED'] = 'COMPLETED'; 
        $this->language['CHANGE_LANGUAGE'] = 'Change Language';
        $this->language['SHORTCUTS'] = 'Clansuite Shortcuts';
                
        # Left Side Menu
        $this->language['MENU_HEADING'] = 'Installationsteps';
        $this->language['MENUSTEP1'] = '[1] Select Language';
        $this->language['MENUSTEP2'] = '[2] Systemcheck';
        $this->language['MENUSTEP3'] = '[3] GPL Licence';
        $this->language['MENUSTEP4'] = '[4] Database';
        $this->language['MENUSTEP5'] = '[5] Configuration';
        $this->language['MENUSTEP6'] = '[6] Create Admin';
        $this->language['MENUSTEP7'] = '[7] Finish';
    
        ###
        
        //GLOBAL
        
        $this->language['HELP'] = 'Help';
        $this->language['LICENSE'] = 'License';        
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