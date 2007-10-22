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

// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}

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
	public static $lang    = array();

    private $request    = null;
    private $error      = null;

    function __construct(httprequest $request)
    {
        $this->request = $request;
        #$this->error   = $error;
        // determine user_locale + set it
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
        //check if $string exists in language array
        if(isset(self::$lang[$string]))
        {

            return strtr(self::$lang[ $string ], $args);
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
     * @global $config
     * @param $xml_file_name string filename of languagefile
     * @param $language sets locale
     * @access public
     */
    public function load_lang( $xml_file_name = '', $language = 'en' )
    {

        // construct $file with path and xml_file_name
        $file = ROOT_LANGUAGES . '/' . $language . '/' . $xml_file_name . '.xml';
        #echo '<br>loaded Language => '. $file;

        /**
         * Parse translations via Simple XML
         * and write to array
         */
         if (is_file($file))
         {
            // if xml_file_name was not already added
            if (!in_array($file, $this->loaded) )
            {
                // push name into array to know which translations were loaded
                array_push($this->loaded, $file );

                try
                {
                    // extract xml data from file
                    $xml = simplexml_load_file($file);
                }
                catch (Exception $e)
                {
                    // file content is not valid XML
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
