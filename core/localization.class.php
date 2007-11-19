<?php
   /**
    * Clansuite - just an esports CMS
    * Jens-Andre Koch © 2005-2007
    * http://www.clansuite.com/
    *
    * File:         language.class.php
    * Requires:     PHP 5.1.4+
    *
    * Purpose:      Clansuite Core Class for Localization (l10n) Handling
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
    * @license    GNU/GPL, see COPYING.txt
    *
    * @author     Jens-Andre Koch   <vain@clansuite.com>
    * @copyright  Jens-Andre Koch (2005-$LastChangedDate$)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    * @since      File available since Release 0.1
    *
    * @version    SVN: $Id$
    */

// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}

/**
 * Start of Clansuite Core Class for Localization (l10n) Handling
 *
 * @author     Jens-Andre Koch   <vain@clansuite.com>
 * @copyright  Jens-Andre Koch (2005-$LastChangedDate$)
 *
 * @package     clansuite
 * @category    core
 * @subpackage  localization
 */
class localization
{
    /**
     * @deprecated
     * @var array $loaded contains the filenames of the loaded xml language files
     * @access public
     */
    public $loaded  = array();

    /**
     * Contains all translation of a file in the following form:
     * array(%id% => %message%);
     *
     * @var array $lang contains array for languagefile data after parsing the xml-files
     * @access public
     */
	public static $lang    = array();

	# Locale Variables
	private $locale    = null;
	private $domain    = null;
	private $encoding  = null;

	# Locale Domains Table - containing all avaiable Domains
	# the "main" domain = 'clansuite', then domains for modules
	private $localeDomainsTable = array();

    private $request    = null;
    private $error      = null;

    function __construct(httprequest $request)
    {
        #$this->request = $request;
        #$this->error   = $error;

       /**
        * Set Locale
        *
        * 0th) Highest priority for url setting of a language (index.php?lang=)
        * 1nd) If there is a language setting in the session, use this instead
    	* 2st) Try to get the language from the browser
    	* 3rd) If none of the above is true, use the default language as fallback
    	*/
    	/*
    	if (isset($_SESSION['user']['language']))
    	{
    		# use language setting from session
    		$language = $_['user']['language'];
    	}
    	else
    	{
            # get language from the browser or
            # get the default language as fallback
            $language = $this->getLanguage();
    	}*/

        /**
         * Default Language
         * pretending this came form the accept-language header
         * in general words: from a language-detection-mechanism
         */
        /*if ($_SESSION['user']['language_via_url'] == '1')
        {
            $this->locale = $_SESSION['user']['language_via_url'];
        }
        else
        {*/
            $this->locale = 'de_DE.UTF-8';      # sets locale @todo: get $cfg->language from config
        #}

        # sets the text domain as 'clansuite'
        $this->domain = 'clansuite';

        # sets encoding -> @todo: get charset encoding from config
        $this->encoding = 'UTF-8';

        /**
         * Require PHP-gettext Functions
         * It provides a simple gettext replacement that works independently from
         * the system's gettext abilities.
         * It can read MO files and use them for translating strings.
         *
         * Review the following articles to understand how this works:
         * @link http://www.php.net/gettext Original php gettext manual
         * @link https://savannah.nongnu.org/projects/php-gettext php-gettext classes
         * @link http://www.gnu.org/software/gettext/manual/gettext.html gnu gettext
         * @link http://www.onlamp.com/pub/a/php/2002/06/13/php.html php gettext tutorial
         */
        #require ROOT_LIBRARIES.'/php-gettext/gettext.php';

        /**
         * gettext setup
         *
         * Short Info about Gettext Paths:
         * Give a path/to/your/mo/files without LC_MESSAGES and locale!!
         * If you use: T_bindtextdomain('clansuite', '/html/locales');
         * The mo.file would be looked up in /html/locales/de_DE/LC_MESSAGES/clansuite.mo

         * The $domain string specifies the mo-filename => "$domain.mo"
         * So if $domain = 'clansuite'; => clansuite.mo
         */

        # Environment Variable LANGUAGE has priority above any local setting
        putenv("LANGUAGE=$this->locale");

        if (!defined('LC_MESSAGES')) { define('LC_MESSAGES', 5); };     # workaround for php on windows
		T_setlocale(LC_MESSAGES, $this->locale);
		#T_setlocale(LC_ALL, $language);                                # LC_ALL disabled, because possible damage of sql queries
		#T_setlocale(LC_TIME, $locale . '.UTF8', $locale);              # LC_TIME not figured out yet
		T_bindtextdomain($this->domain, ROOT_LANGUAGES);                # for domain 'clansuite' it's the ROOT_LANGUAGES directory
		T_bind_textdomain_codeset($this->domain, $this->encoding);
		T_textdomain($this->domain);

        /**
         * Using bindtextdomain for binding domains from different paths
        bindtextdomain('index', ROOT_MOD. '/index');
        bindtextdomain('bar','/another/mo_file/dir');
        echo dgettext('foo','Text to be translated using the foo-catalog.');
        echo dgettext('bar','Text from a different catalog named bar.');
        */

        #bindtextdomain(OSC_I18N_DOMAIN, $controller->getModuleDirectory() . OSC_I18N_MSG_DIR);
        #bind_textdomain_codeset(OSC_I18N_DOMAIN, OSC_I18N_CHARSET);
        #textdomain(OSC_I18N_DOMAIN);
    }

    /**
     * loadTextDomain
     *
     * This loads a new domain with a certain path into the $localeDomainsTable.
     *
     * @link http://www.php.net/function.bindtextdomain
     * @access public
     */
    public function loadTextDomain($domainname, $domainpath)
    {

    }

    /**
	 *  getLanguage
	 *
	 *	This function will return a language, which is supported by both
	 *  the browser and the clansuite language system.
	 *
	 *	@param $supported	(optional) An array with the list of supported languages.
	 *                      Default Setting is 'en' for english.
	 *  @return $language Returns a $language string, which is supported by browser and system.
	 *  @access public
	 */
	public function getLanguage( $supported=array( 'en' ) )
	{
		# start with the default language
		$language = $supported[0];

		# get the list of languages supported by the browser
		$browserLanguages = $this->getBrowserLanguages();

		# look if the browser language is a supported language, by checking the array entries
		foreach ( $browserLanguages as $browserLanguage )
		{
		    # if a supported language is found, set it and stop
			if ( in_array( $browserLanguage, $supported ) )
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
     * @access public
     * @return Array containing the list of supported languages
     * @todo: $_SERVER is an httprequest object...
     *        the method should be placed there and data fetch from the httprequest->method
     */
    public function getBrowserLanguages()
    {
        # check if environment variable HTTP_ACCEPT_LANGUAGE exists
        if(!isset($_SERVER['HTTP_ACCEPT_LANGUAGE']))
        {
            # if not return an empty language array
        	return array();
        }

        # explode environment variable HTTP_ACCEPT_LANGUAGE at ,
        $browserLanguages = explode( ',', $_SERVER['HTTP_ACCEPT_LANGUAGE'] );

        # convert the headers string to an array
        $browserLanguagesSize = sizeof( $browserLanguages );
        for ( $i = 0; $i < $browserLanguagesSize; $i++ )
        {
        	# explode string at ;
        	$browserLanguage = explode( ';', $browserLanguages[$i] );
        	# cut string and place into array
        	$browserLanguages[$i] = substr( $browserLanguage[0], 0, 2 );
        }

        # remove the duplicates and return the browser languages
        return array_values( array_unique( $browserLanguages ) );
	}

	/**
	 * ----------------------------------------------------------------
	 * The next functions are the deprecated XML-File Language Handling
     * - smarty_translate()
     * - t()
     * - load_lang()
     * ----------------------------------------------------------------
     */

    /**
     * Function for SMARTY to handle {translate}word{/translate} blocks
     * Prints the translated word, if found.
     *
     * @access public
     * @static
     * @global array $lang language data array
     * @param $params
     * @param $string string to translate
     * @param $smarty smarty data
     */
    public static function smarty_translate($params, $string, &$smarty)
    {
        /**
         * this handles dynamic content in the translation string
         * example "Hello, %u!", where %u is substituted by a username
         */
        foreach ($params as $key => $value)
        {
            $params["%" . $key] = $value;
            unset($params[$key]);
        }

        // print the return of translation function $lang->t();
        echo(language::t($string, $params));
    }

    /**
     * Translates a string into the given language.
     *
     * @global object $lang
     * @param string $string stringname to translate
     * @param array $args value of the name
     * @return string
     */
    public static function t($string, $args = array() )
    {
        # check if $string exists in language array
        if(isset(self::$lang[$string]))
        {

            return strtr(self::$lang[ $string ], $args);
        }
        return strtr($string, $args);
    }


    /**
     * Adds another XML File to $lang array (xml tree)
     *
     * @global $config
     * @param $xml_file_name string filename of languagefile
     * @param $language sets locale
     * @access public
     */
    public function load_lang( $xml_file_name = '', $language = 'en' )
    {

        # construct $file with path and xml_file_name
        $file = ROOT_LANGUAGES . '/' . $language . '/' . $xml_file_name . '.xml';
        #echo '<br>Loaded Language => '. $file;

        /**
         * Parse translations via Simple XML
         * and write to array
         */
         if (is_file($file))
         {
            # if xml_file_name was not already added
            if (!in_array($file, $this->loaded) )
            {
                # push name into array to know which translations were loaded
                array_push($this->loaded, $file );

                try
                {
                    # extract xml data from file
                    $xml = simplexml_load_file($file);
                }
                catch (Exception $e)
                {
                    # file content is not valid XML
                    throw new clansuite_exception('Language File exists, but an XML Parsing Error occured: ' .  $e->getMessage(), 500);
                }

                /**
                 * process xml lang file
                 *
                 * foreach <message> </message> container extract the
                 * element <id> and <string> and merge each into the
                 * $lang array (id => string)
                 *
                 * @todo: note for vain: change element names to exleaf specification
                 */
                foreach($xml->message as $msg)
                {
                    self::$lang = array_merge(self::$lang, array((string)$msg->id => (string)$msg->string));
                }
            }
         }
    }
}
?>
