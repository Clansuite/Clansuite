<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-Andre Koch © 2005-2007
    * http://www.clansuite.com/
    *
    * File:         language.class.php
    * Requires:     PHP 5.1.4+
    *
    * Purpose:      Clansuite Core Class for Localization (l10n) & Internationalization (i18n) Handling
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
    * @since      File available since Release 0.2
    *
    * @version    SVN: $Id$
    */

// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}

/**
 * Clansuite Core Class for Localization (l10n) & Internationalization (i18n) Handling
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
    # Locale Variables
	public  $locale    = null;
	private $domain    = null;
	private $encoding  = null;

    public function __construct()
    {
       $this->domain = 'clansuite';    # sets the text domain as 'clansuite' => "clansuite.mo" filename
       $this->encoding = 'UTF-8';      # sets encoding -> @todo: get charset encoding from config

       /**
        * Set Locale
        *
        * Order of Language-Detection: URL -> SESSION -> BROWSER -> DEFAULT LANGUAGE (from Config)
    	*/

    	/*
    	if ($_SESSION['user']['language_via_url'] == '1')
        {
            $this->locale = $_SESSION['user']['language_via_url'];
        }
        elseif (isset($_SESSION['user']['language']))
    	{
    		# use language setting from session
    		$this->locale = $_SESSION['user']['language'];
    	}
    	elseif
    	{
            # get language from the browser or
            # get the default language as fallback
            $this->locale = $this->getLanguage();
    	}
    	else
        {
        */
            $this->locale = 'de_DE';      # sets locale @todo: get $cfg->language from config
        #}

        /**
         * Require PHP-gettext's emulative functions, if PHP gettext extension is off
         *
         * The library provides a simple gettext replacement that works independently from
         * the system's gettext abilities.
         * It can read the MO files and use them for the translation of strings.
         *
         * Review the following articles/manual to understand how this works:
         * @link http://www.php.net/gettext PHP Manual Gettext
         * @link https://savannah.nongnu.org/projects/php-gettext PHP-GETTEXT Library
         * @link http://www.gnu.org/software/gettext/manual/gettext.html GNU Gettext
         */
        require ROOT_LIBRARIES.'/php-gettext/gettext.inc';

        # Load Clansuite Domain
        $this->loadTextDomain('LC_ALL', $this->domain, $this->locale);
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
     * @access public
     */
    public function loadTextDomain($category, $domain, $locale, $baseDir = null)
    {
         # Environment Variable LANGUAGE has priority above any local setting
        putenv("LANGUAGE=$locale");
        if (!defined('LC_MESSAGES')) { define('LC_MESSAGES', 5); }; # workaround for php on windows, to make LC_MESSAGES avaiable
		T_setlocale(LC_MESSAGES, $locale);
		#T_setlocale(LC_ALL, $language);                          # LC_ALL disabled, because possible damage of sql queries
		#T_setlocale(LC_TIME, $locale . '.UTF8', $locale);        # LC_TIME not figured out yet
		if($baseDir == NULL)
		{
		  $domain_directory = ROOT_LANGUAGES;                     # set ROOT_LANGUAGES
		}
		else
		{
		  $domain_directory = ROOT_MOD .'/'. $baseDir . '/languages'; # set baseDir/languages
		}
		T_bindtextdomain($domain, $domain_directory);       # for domain 'clansuite' it's the ROOT_LANGUAGES directory
		T_bind_textdomain_codeset($domain, $this->encoding);
		T_textdomain($domain);
        return true;
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
}
?>
