<?php
/**
 * @category        Clansuite
 * @package         Installation
 * @subpackage      Languages
 * @author          Jens-André Koch <vain@clansuite.com>
 * @copyright       Jens-André Koch & Clansuite Development Team
 * @license         http://www.gnu.org/copyleft/gpl.html GNU/GPL Public Licence
 * @description     English Installation Language
 * @version         SVN: $Id$
 * @implements      ArrayAccess
 *
 * Encoding: UTF-8
 * Contributors: vain
 */

class language implements ArrayAccess
{
    private $language = array();

    // table of strings
    function __construct()
    {
        // STEP 1 - Language Selection
        $this->language['STEP1_LANGUAGE_SELECTION'] = 'Step [1] Language Selection';

        $this->language['STEP1_WELCOME'] = 'Welcome to the Clansuite Installation.';
        $this->language['STEP1_THANKS_CHOOSING'] = 'Thanks for choosing to use Clansuite!';
        $this->language['STEP1_APPINSTALL_STEPWISE'] = 'This application will guide you in several steps through the installation.';
        $this->language['STEP1_CHOOSELANGUAGE'] = 'Please select your language.';

        // STEP 2 - System Check
        $this->language['STEP2_SYSTEMCHECK'] = 'Step [2] Systemcheck';

        $this->language['STEP2_IN_GENERAL'] = 'In Step [2] we\'ll perform a requirements check on your webserver.';

        $this->language['STEP2_SYSTEMSETTINGS_REQUIRED'] = 'Some of these system settings are required for the correct work of Clansuite.';
        $this->language['STEP2_SYSTEMSETTINGS_RECOMMENDED'] = 'While other settings are only recommended to enhance the security or performance.';
        $this->language['STEP2_SYSTEMSETTINGS_TAKEACTION'] = 'Please ensure that all of the required system check items listed below are green. The red marked settings show where you have to take action.';
        $this->language['STEP2_SYSTEMSETTINGS_CHECK_VALUES'] = 'The System-Check resulted in:';

        $this->language['STEP2_SYSTEMSETTING_REQUIRED'] = 'Required Settings (must)';
        $this->language['STEP2_SYSTEMSETTING_RECOMMENDED'] = 'Recommended Settings (optional)';

        $this->language['STEP2_SETTING'] = 'Settings';
        $this->language['STEP2_SETTING_ACTUAL'] = 'Actual';
        $this->language['STEP2_SETTING_EXPECTED'] = 'Expected';
        $this->language['STEP2_SETTING_STATUS'] = 'Status';

        # REQUIRED SETTINGS (in order)
        $this->language['PHP_VERSION'] = 'PHP version 5.2+';
        $this->language['SESSION_FUNCTIONS'] = 'Session Functions';
        $this->language['PDO_LIBRARY'] = 'PDO - Library';
        $this->language['PDO_MYSQL_LIBRARY'] = 'PDO - MySQL - Library';
        $this->language['CLASS_REFLECTION'] = 'PHP Reflection Support';
        $this->language['EXTENSION_SPL'] = 'Standard PHP Library (SPL)';
        $this->language['IS_WRITEABLE_TEMP_DIR'] = 'Useable: Temporary Directory';
        $this->language['IS_WRITEABLE_CLANSUITE_ROOT'] = 'Can write: /clansuite';
        $this->language['IS_WRITEABLE_SMARTY_TEMPLATES_C'] = 'Can write: /templates_c';
        $this->language['IS_WRITEABLE_SMARYT_CACHE'] = 'Can write: /cache';
        $this->language['IS_WRITEABLE_UPLOADS'] = 'Can write: /uploads';
        $this->language['IS_READABLE_CONFIG_TEMPLATE'] = 'Can read: config-template file';

        # RECOMMENDED SETTINGS (in order)
        $this->language['PHP_MEMORY_LIMIT'] = 'Checking PHP memory limit';
        $this->language['FILE_UPLOADS'] = 'File Uploads enabled?';
        $this->language['MAX_UPLOAD_FILESIZE'] = 'Checking maximal upload filesize';
        $this->language['POST_MAX_SIZE'] = 'Checking maximal size of posts';
        $this->language['REGISTER_GLOBALS'] = 'REGISTER_GLOBALS';
        $this->language['ALLOW_URL_FOPEN'] = 'Remote File Open';
        $this->language['ALLOW_URL_INCLUDE'] = 'Remote File Inclusion';
        $this->language['SAFE_MODE'] = 'SAFE_MODE';
        $this->language['OPEN_BASEDIR'] = 'OPEN_BASEDIR';
        $this->language['MAGIC_QUOTES_GPC'] = 'Magic Quotes GPC';
        $this->language['MAGIC_QUOTES_RUNTIME'] = 'Magic Quotes Runtime';
        $this->language['OUTPUT_BUFFERING'] = 'Output Buffering';
        $this->language['XSLT_PROCESSOR'] = 'XSLT Processor';
        $this->language['EXTENSION_HASH'] = 'PHP Extension: Hash';
        $this->language['EXTENSION_GETTEXT'] = 'PHP Extension: Gettext';
        $this->language['EXTENSION_TOKENIZER'] = 'PHP Extension: Tokenizer';
        $this->language['EXTENSION_GD'] = 'PHP Extension: GD';
        $this->language['EXTENSION_XML'] = 'PHP Extension: XML';
        $this->language['EXTENSION_PCRE'] = 'PHP Extension: PCRE (Perl Regexp)';
        $this->language['EXTENSION_SIMPLEXML'] = 'PHP Extension: SimpleXML';
        $this->language['EXTENSION_SUHOSIN'] = 'PHP Extension: Suhosin';
        $this->language['EXTENSION_SKEIN'] = 'PHP Extension: Skein';
        $this->language['EXTENSION_GEOIP'] = 'PHP Extension: GeoIP';
        $this->language['EXTENSION_CURL'] = 'PHP Extension: cURL';
        $this->language['EXTENSION_SYCK'] = 'PHP Extension: SYCK';
        $this->language['EXTENSION_APC'] = 'PHP Extension: APC';
        $this->language['EXTENSION_MEMCACHE'] = 'PHP Extension: MEMCACHE';
        $this->language['EXTENSION_MCRYPT'] = 'PHP Extension: MCRYPT';

        // STEP 3 - Licence
        $this->language['STEP3_LICENCE'] = 'Step [3] GNU/GPL Licence';

        $this->language['STEP3_SENTENCE1'] = 'Realize, that Clansuite as an instance of Code is released under GNU/GPL Licence Version 2 or any later version! The GNU/GPL Licence which you find below, itself is copyrighted by the Free Software Foundation.';
        $this->language['STEP3_REVIEW'] = 'Please review the Licence Terms before installing Clansuite:';
        $this->language['STEP3_MUST_AGREE'] = 'You must agree with the GNU/GPL Licence to install Clansuite.';
        $this->language['STEP3_CHECKBOX'] = 'I agree and have realized that Clansuite is released under the GNU/GPL License!';

        // STEP 4 - Database
        $this->language['STEP4_DATABASE'] = 'Step [4] Database';

        $this->language['STEP4_SENTENCE1'] = 'In Step [4] you\'ll provide MySQL-Database Access Information and we\'ll try to connect and store some basic tables and content of Clansuite.';
        $this->language['STEP4_SENTENCE2'] = 'Please provide the username and password to connect to the server here.';
        $this->language['STEP4_SENTENCE3'] = 'If this account has permission to create databases, then we will create the database for you; otherwise, you must give the name of a database that already exists.';

        $this->language['STEP4_SENTENCE4'] = 'Tables und Entries created.';
        $this->language['STEP4_SENTENCE5'] = 'Import Databasetables of another CMS?';

        $this->language['DB_HOST'] = 'Database Hostname';
        $this->language['DB_TYPE'] = 'Databank Type';
        $this->language['DB_NAME'] = 'Database Name';
        $this->language['DB_CREATE_DATABASE'] = 'Create Database?';
        $this->language['DB_USERNAME'] = 'Database Username';
        $this->language['DB_PASSWORD'] = 'Database Password';
        $this->language['DB_PREFIX'] = 'Table Prefix';

        $this->language['ERROR_NO_DB_CONNECT'] = 'Database-Connection could not be established.';
        $this->language['ERROR_WHILE_CREATING_DATABASE'] = 'Database database could not be created.';
        $this->language['ERROR_FILL_OUT_ALL_FIELDS'] = 'Please fill out all fields!';

        // STEP 5 - Configuration
        $this->language['STEP5_CONFIG'] = 'Step [5] Configuration';

        $this->language['STEP5_SENTENCE1'] = 'Please enter the basic configurations of your Clansuite-Website.';
        $this->language['STEP5_SENTENCE2'] = 'When the installation is done, you\'ll be able to configure more details from the administrative control panel (ACP).';

        $this->language['STEP5_CONFIG_SITENAME'] = 'Name of Website';
        $this->language['STEP5_CONFIG_EMAILFROM'] = 'Email Adress of Website';
        $this->language['STEP5_CONFIG_USERACCOUNT_ENCRYPTION'] = 'Encryption';
        $this->language['STEP5_CONFIG_TIMEZONE'] = 'Timezone';

        // STEP 6 - Create Administrator
        $this->language['STEP6_ADMINUSER'] = 'Step [6] Create Administrator';

        $this->language['STEP6_SENTENCE1'] = 'In Step [6] we create a User-Account with the Userdata you\'ll provide.';
        $this->language['STEP6_SENTENCE2'] = 'We\'ll give this account Administrator-Permissions, which means that you\'ll be able to login and set all configurations with it.';
        $this->language['STEP6_SENTENCE3'] = 'Please enter Name and Password as well as E-Mail and Language of the Administrator Account.';

        $this->language['STEP6_ADMIN_NAME']     = 'Administrator Name';
        $this->language['STEP6_ADMIN_PASSWORD'] = 'Administrator Password';
        $this->language['STEP6_ADMIN_LANGUAGE'] = 'Language';
        $this->language['STEP6_ADMIN_EMAIL']    = 'Email Adress';

        $this->language['STEP6_ERROR_COULD_NOT_CREATE_ADMIN'] = 'FEHLER -  Admin Account not created.';

        // STEP 7 - Finish
        $this->language['STEP7_FINISH'] = 'Step [7] Finish';

        $this->language['STEP7_SENTENCE1'] = 'Done! Congratulation - You successfully installed Clansuite.';
        $this->language['STEP7_SENTENCE2'] = 'The Developers Team hopes that you take pleasure in exploring and using Clansuite.';
        $this->language['STEP7_SENTENCE3'] = 'Underneath you\'ll find the Links to the Frontend-Website, to the Admin Control Panel (ACP) and your Accounts Logindata.';
        $this->language['STEP7_SENTENCE4'] = 'Visit your new';
        $this->language['STEP7_SENTENCE5'] = 'Clansuite Website';
        $this->language['STEP7_SENTENCE6'] = 'or the';

        $this->language['STEP7_SENTENCE8'] = 'For help and informations about the usage and configuration of the clansuite-software feel free to visit the ';
        $this->language['STEP7_SENTENCE9'] = 'User-Manual';
        $this->language['STEP7_SENTENCE10'] = 'Security Advise';
        $this->language['STEP7_SENTENCE11'] = 'Please don\'t forget to rename or remove the "/installation" directory for security reasons.';

        // GLOBAL
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