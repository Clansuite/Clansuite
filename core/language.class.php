<?php
   /**
    * Clansuite - just an E-Sport CMS
    * Jens-Andre Koch, Florian Wolf © 2005-2007
    * http://www.clansuite.com/
    *
    * File:         language.class.php
    * Requires:     PHP 5.1.4+
    *
    * Purpose:      Clansuite Core Class for Language Handling
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
    * @author     Florian Wolf      <xsign.dll@clansuite.com>
    * @copyright  Jens-Andre Koch (2005-$LastChangedDate$), Florian Wolf (2006-2007)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    * @since      File available since Release 0.1
    *
    * @version    SVN: $Id$
    */

/**
 * Security Handler
 */
if (!defined('IN_CS')){ die('You are not allowed to view this page.' );}

/**
 * Start of Core - Language Class
 * @package Clansuite Core
 * @subpackage Language
 */
class language
{
    /**
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
	public $lang    = array();

    /**
     * Constructor sets up {translate} block in SMARTY Template Engine
     * {@link function smarty_translate}
     *
     * @global $tpl object Contains Smarty Template Data
     */
    function __construct()
    {
        global $tpl;

        /**
         * Sets up {translate} block in SMARTY Template Engine
         */
        $tpl->register_block("translate", array('language',"smarty_translate"), false);
    }

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
    static function smarty_translate($params, $string, &$smarty)
    {
        global $lang;

        foreach ($params as $key => $value)
        {
            $params["%" . $key] = $value;
            unset($params[$key]);
        }
        // print the return of translation function $lang->t();
        echo($lang->t($string, $params));
    }

    /**
     * Translates a string into the given language.
     *
     * @global object $lang
     * @param string $string stringname to translate
     * @param array $args value of the name
     * @return string
     */
    function t($string, $args = array() )
    {
		global $lang;

        if(isset($lang->lang[ $string ]))
		{
			return strtr($lang->lang[ $string ], $args);
		}
        return strtr($string, $args);
    }

    /**
     * Browser Locale Detection
     *
     * Basically locales are composed by 3 elements:
     * PREFIX-SUBCLASS ; QUALITY=value
     *
     * PREFIX:      is the main language identifier
     *              (i.e. en-us, en-ca => both have prefix EN)
     * SUBCLASS:    is a subdivision for main language (prefix)
     *              (i.e. en-us runs for english - united states) IT CAN BE BLANK
     * QUALITY:     is the importance for given language
     *              primary language setting defaults to 1 (100%)
     *              secondary and further selections have a lower Q value (value <1).
     *
     *
     *
     */


    /**
     * Adds another XML File to $lang array (xml tree)
     *
     * @global $cfg
     * @param $xml_file_name string filename of languagefile
     * @param $language sets locale
     */
    function load_lang( $xml_file_name = '', $language = 'en' )
    {
        global $cfg;

        echo 'loaded = ' . $xml_file_name . ' -- lang: ' . $language;
        
        // construct $file with path and xml_file_name        
        $file = ROOT_LANG . '/' . $language . '/' . $xml_file_name . '.xml';

		/**
		 * Parse translations via Simple XML
		 * and write to array
		 */
		 if (file_exists($file))
         {  
            // if xml_file_name was not already added
            if (!in_array($file, $this->loaded) )
            {
                // push name into array to know which translations were loaded
				array_push($this->loaded, $file );

                // extract xml data from file
				$xml = new SimpleXMLElement(file_get_contents($file));
                
                if (!$xml)
         	    {
         	      $error->show( $lang->t('Languagefile loading Failure'), $lang->t('The Languagefile exists, but could not be loaded.') . '<br />' . $file, 2);
                  #return false;
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
					$this->lang = array_merge($this->lang, array((string)$msg->id => (string)$msg->string));
				}
				
            }
         }

    }

}
?>