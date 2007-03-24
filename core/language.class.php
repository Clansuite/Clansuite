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
     * $loaded array contains the filenames
     * of the loaded xml language files
     *
     * @var array
     * @access public
     */
    public $loaded  = array();

    /**
     * $lang array for languagefile data after parsing the xml-files
     * Contains all translation of a file in the following form:
     * array(%id% => %message%);
     *
     * @var array
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
     * Adds another XML File to $lang->xml tree
     *
     * @global $cfg
     * @param $xml_file_name string filename of languagefile
     */
    function load_lang( $xml_file_name='' )
    {
        global $cfg;

        $file = ROOT_LANG . '/' . $cfg->language . '/' . $xml_file_name . '.xml';

		/**
		 * Parse translations via Simple XML
		 * and write to array
		 */
		 if (file_exists($file))
         {
            if (!in_array($xml_file_name, $this->loaded) )
            {
				array_push($this->loaded, $xml_file_name );

				$xml = new SimpleXMLElement(file_get_contents($file));

				foreach($xml->message as $msg)
                {
					$id = $msg->id;
					$string = $msg->string;

                    $this->lang = array_merge($this->lang, array((string)$id => (string)$string));
				}
            }
         }
    }

}
?>