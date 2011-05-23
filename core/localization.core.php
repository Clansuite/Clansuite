<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-André Koch © 2005 - onwards005 - onwards
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

# Security Handler
if (defined('IN_CS') === false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

/**
 * Clansuite Core Class for Localization (l10n) & Internationalization (i18n) Handling
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Localization
 */
class Clansuite_Localization
{
    # Locale Variables
    public $locale    = null;

    /**
     * @var Set Locale Defaults: the textdomain. 'clansuite' => 'clansuite.mo' filename
     */
    private $domain    = null;

    /**
     * @var Sets Encoding.
     */
    private $encoding  = null;

    # References
    private static $config    = null;

    public function __construct(Clansuite_Config $config)
    {
        # Set Reference to Config
        self::$config = $config;

        # Set Locale Defaults
        $this->domain = 'clansuite';
        $this->encoding = self::$config['language']['outputcharset'];

        # Get Locale
        $locale = $this->getLocale();

        /**
         * Important Notice:
         *
         * List new available languages in the method head of getLanguage( $supported=array( 'en', 'de' ))
         * to make them a valid target for a browser detected language!
         */

        /**
         * Require PHP-gettext's emulative functions, if PHP gettext extension is off
         *
         * The library provides a simple gettext replacement that works independently from
         * the system's gettext abilities.
         * It can read the MO files and use them for the translation of strings.
         * It can also cache the mo files.
         * Note that gettext.inc includes gettext.php and streams.php.
         *
         * Review the following articles/manual to understand how this works:
         * @link http://www.php.net/gettext PHP Manual Gettext
         * @link https://savannah.nongnu.org/projects/php-gettext PHP-GETTEXT Library
         * @link http://www.gnu.org/software/gettext/manual/gettext.html GNU Gettext
         */
        if(function_exists('_get_reader') === false)
        {
            include ROOT_LIBRARIES . 'php-gettext/gettext.inc';
        }

        # Load Clansuite Domain
        $this->loadTextDomain('LC_ALL', $this->domain, $locale);
    }

    /**
     * Get Locale
     *
     * Order of Language-Detection:
     * URL (language filter) -> SESSION -> BROWSER -> DEFAULT LANGUAGE (from Config)
     */
    public function getLocale()
    {
        # if language_via_url was used, the filter set the URL value to the session
        if(isset($_SESSION['user']['language_via_url']) === true
            and ($_SESSION['user']['language_via_url'] == 1))
        {
            # use language setting from session
            $this->locale = $_SESSION['user']['language'];
        }
        else # get language from the browser AND set it to session
        {
            $this->locale = $this->getLanguage();
            $_SESSION['user']['language'] = $this->locale;

            if(empty($this->locale)) # 3) get the default language from config as fallback
            {
                $this->locale = self::$config['language']['default'];
            }
        }

        return $this->locale;
    }

    /**
     * loadTextDomain
     *
     * This loads a new domain with a certain path into the domainstable
     *
     * Info about Gettext Paths:
     * Give a path/to/your/mo/files without LC_MESSAGES and locale!!
     * If you use: T_bindtextdomain('clansuite', '/html/locales');
     * The mo.file would be looked up in /html/locales/de_DE/LC_MESSAGES/clansuite.mo
     * The $domain string specifies the mo-filename => "$domain.mo"
     * So if $domain = 'clansuite'; => clansuite.mo
     *
     * @link http://www.php.net/function.bindtextdomain
     */
    public function loadTextDomain($category, $domain, $locale, $module = null)
    {
        #Clansuite_Debug::firebug($module);

        # if, $locale string is not over 3 chars long -> $locale = "en", build "en_EN"
        if(isset($locale{3}) == false)
        {
            $locale = mb_strtolower($locale) . '_' . mb_strtoupper($locale);
        }

        # Environment Variable LANGUAGE has priority above any local setting
        putenv('LANGUAGE=' . $locale);
        putenv('LANG=' . $locale);
        setlocale(LC_ALL, $locale . '.UTF-8');
        T_setlocale(LC_ALL, $locale . '.UTF8', $locale);

        /**
         * Set the domain_directory (where look for MO files named $domain.po)
         */
        if($module === null)
        {
            # for domain 'clansuite', it's the ROOT_LANGUAGES directory
            $domain_directory = ROOT_LANGUAGES;
        }
        else # set a specific module directory
        {
            $domain_directory = ROOT_MOD . $module . DS . 'languages';
        }

        # Set the Domain
        T_bindtextdomain($domain, $domain_directory);
        T_bind_textdomain_codeset($domain, $this->encoding);
        T_textdomain($domain);

        #Clansuite_Debug::firebug('<p>Textdomain "' .$domain .'" loaded from path "'. $domain_directory .'" for "'. $module .'"</p>');
        return true;
    }

    /**
     *  getLanguage
     *
     *  This function will return a language, which is supported by both
     *  the browser and the clansuite language system.
     *
     *  @param $supported   (optional) An array with the list of supported languages.
     *                      Default Setting is 'en' for english.
     *  @return $language Returns a $language string, which is supported by browser and system.
     */
    public function getLanguage($supported = array('en', 'de'))
    {
        # start with the default language
        $language = $supported[0];

        # get the list of languages supported by the browser
        $browserLanguages = $this->getBrowserLanguages();

        # look if the browser language is a supported language, by checking the array entries
        foreach($browserLanguages as $browserLanguage)
        {
            # if a supported language is found, set it and stop
            if(in_array($browserLanguage, $supported))
            {
                $language = $browserLanguage;
                break;
            }
        }

        # return the found language
        return $language;
    }

    /**
     * Browser Locale Detection
     *
     * This functions check the HTTP_ACCEPT_LANGUAGE HTTP-Header
     * for the supported browser languages and returns an array.
     *
     * Basically HTTP_ACCEPT_LANGUAGE locales are composed by 3 elements:
     * PREFIX-SUBCLASS ; QUALITY=value
     *
     * PREFIX:      is the main language identifier
     *              (i.e. en-us, en-ca => both have prefix EN)
     * SUBCLASS:    is a subdivision for main language (prefix)
     *              (i.e. en-us runs for english - united states) IT CAN BE BLANK
     * QUALITY:     is the importance for given language
     *              primary language setting defaults to 1 (100%)
     *              secondary and further selections have a lower Q value (value <1).
     * EXAMPLE:     de-de,de;q=0.8,en-us;q=0.5,en;q=0.3
     *
     * @return Array containing the list of supported languages
     */
    public function getBrowserLanguages()
    {
        # check if environment variable HTTP_ACCEPT_LANGUAGE exists
        if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) == false)
        {
            # if not return an empty language array
            return array();
        }
        elseif(extension_loaded('intl'))
        {
            # Try to find best available locale based on HTTP "Accept-Language" header
            $lang =  Locale::acceptFromHttp($_SERVER['HTTP_ACCEPT_LANGUAGE']);
            return (array) mb_substr($lang, 0, 2);
        }
        # @todo consider code block marked as deprecated
        # INTL is default extension as of php5.3
        else # fallback for no ext/intl environments
        {
            # explode environment variable HTTP_ACCEPT_LANGUAGE at ,
            $browserLanguages = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);

            # convert the headers string to an array
            $browserLanguagesSize = count($browserLanguages);

            for($i = 0; $i < $browserLanguagesSize; $i++)
            {
                # explode string at ;
                $browserLanguage = explode(';', $browserLanguages[$i]);
                # cut string and place into array
                $browserLanguages[$i] = mb_substr($browserLanguage[0], 0, 2);
            }

            # remove the duplicates and return the browser languages
            return array_values(array_unique($browserLanguages));
        }
    }
}
?>