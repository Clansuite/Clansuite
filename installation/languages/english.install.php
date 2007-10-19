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

        $this->language['STEP1_THANKS_CHOOSING'] = 'Thanks for choosing to use Clansuite!';
        $this->language['STEP1_WELCOME'] = 'Welcome to the Clansuite Installation.';
        $this->language['STEP1_APPINSTALL_STEPWISE'] = 'This application will guide you in several steps through the installation.';
        $this->language['STEP1_CHOOSELANGUAGE'] = 'Please select your language.';


        // STEP 2 - System Check
	    $this->language['STEP2_SYSTEMCHECK'] = 'Step [2] Systemcheck';

	    $this->language['STEP2_IN_GENERAL'] = 'In Step [2] we\'ll perform a requirements check on your webserver.';

		$this->language['STEP2_SYSTEMSETTINGS_REQUIRED'] = 'Some of these system settings are required for the correct work of Clansuite.';
		$this->language['STEP2_SYSTEMSETTINGS_RECOMMENDED'] = 'While other settings are only recommended to enhance the security or performance.';
		$this->language['STEP2_SYSTEMSETTINGS_TAKEACTION'] = 'The red marked settings show where you have to take action.';
		$this->language['STEP2_SYSTEMSETTINGS_CHECK_VALUES'] = 'The System-Check resulted in:';

		$this->language['STEP2_SYSTEMSETTING_REQUIRED'] = 'Required Settings';
		$this->language['STEP2_SYSTEMSETTING_RECOMMENDED'] = 'Recommended Settings';

		$this->language['STEP2_SETTING'] = 'Settings';
		$this->language['STEP2_SETTING_ACTUAL'] = 'Actual';
	    $this->language['STEP2_SETTING_EXPECTED'] = 'Expected';

	    # REQUIRED SETTINGS (in order)
	    $this->language['PHP_VERSION'] = 'Checking for PHP version 5.2+';
	    $this->language['SESSION_FUNCTIONS'] = 'Checking for Session Functions';
	    $this->language['MAGIC_QUOTES_RUNTIME'] = 'Checking Magic Quotes Runtime';
	    $this->language['TOKENIZER'] = 'Checking if Tokenizer exists';

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
        $this->language['STEP3_LICENCE'] = 'Step [3] GNU/GPL Licence';

        $this->language['STEP3_SENTENCE1'] = 'Realize, that Clansuite as an instance of Code is released under GNU/GPL Licence! The GNU/GPL Licence which you find below, itself is copyrighted by the Free Software Foundation.';
        $this->language['STEP3_REVIEW'] = 'Please review the Licence Terms before installing Clansuite:';
        $this->language['STEP3_MUST_AGREE'] = 'You must agree with the GNU/GPL Licence to install Clansuite.';
        $this->language['STEP3_CHECKBOX'] = 'I agree and have realized that Clansuite is released under the GNU/GPL License!';

        // STEP 4 - Database
		$this->language['STEP4_DATABASE'] = 'Step [4] Database';

	    $this->language['STEP4_SENTENCE1'] = 'In Step [4] you\'ll provide MySQL-Database Access Information and we\'ll try to connect and store some basic tables and content of Clansuite.';
	    $this->language['STEP4_SENTENCE2'] = 'Please provide the username and password to connect to the server here.';
		$this->language['STEP4_SENTENCE3'] = 'If this account has permission to create databases, then we will create the database for you; otherwise, you must give the name of a database that already exists.';

		$this->language['DB_HOST'] = 'Database Hostname';
		$this->language['DB_NAME'] = 'Database Name';
		$this->language['DB_CREATE_DATABASE'] = 'Create Database?';
		$this->language['DB_USER'] = 'Database Username';
		$this->language['DB_PASS'] = 'Database Password';
		$this->language['DB_PREFIX'] = 'Table Prefix';

		$this->language['ERROR_NO_DB_CONNECT'] = 'Database-Connection could not be established.';

		// STEP 5 - Confioguration
        $this->language['STEP5_CONFIG'] = 'Step [5] Configuration';

		$this->language['STEP5_SENTENCE1'] = 'Please enter the basic configurations of your Clansuite-Website.';

    	$this->language['STEP5_CONFIG_SITENAME'] = 'Name of Website';
    	$this->language['STEP5_CONFIG_SYSTEMEMAIL'] = 'Email Adress of Website';
    	$this->language['STEP5_CONFIG_USERACCOUNT_ENCRYPTION'] 	= 'Encryption of Useraccount-Passwords';
    	$this->language['STEP5_CONFIG_SALTING'] = 'Salting (Use Randomizer to generate!)';
		$this->language['STEP5_CONFIG_TIMEZONE'] = 'Timezone';

        // STEP 6 - Create Administrator
        $this->language['STEP6_ADMINUSER'] = 'Step [6] Create Administrator';

		$this->language['STEP6_SENTENCE1'] = 'Please enter Name and Password of the Administrator Account.';

		$this->language['STEP6_ADMIN_NAME'] 	= 'Administrator Name';
		$this->language['STEP6_ADMIN_PASSWORD'] = 'Administrator Password';
		$this->language['STEP6_ADMIN_LANGUAGE'] = 'Language';
		$this->language['STEP6_ADMIN_EMAIL']	= 'Email Adress';

		$this->language['STEP6_ERROR_COULD_NOT_CREATE_ADMIN'] = 'FEHLER -  Admin Account not created.';

        // STEP 7 - Finish
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