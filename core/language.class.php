<?php
/**
* Initialize objects, create DB link, load templates, clean input
*
* PHP versions 5.1.4
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
*    You should have received a copy of the GNU General Public License
*    along with this program; if not, write to the Free Software
*    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*
* @author     Florian Wolf <xsign.dll@clansuite.com>
* @author     Jens-Andre Koch <vain@clansuite.com>
* @copyright  2006 Clansuite Group
* @license    see COPYING.txt
* @version    SVN: $Id: language.class.php 129 2006-06-09 12:09:03Z vain $
* @link       http://gna.org/projects/clansuite
* @since      File available since Release 0.1
*/

/**
* @desc Security Handler
*/
if (!defined('IN_CS'))
{
    die('You are not allowed to view this page statically.' );
}

/**
* @desc Start lanugage class
*/
class language
{
    /**
    * @desc Public $xml array for languagefile data after parsing xml-file
    * @desc $loaded array for storing all loaded XML language files
    */

    public  $loaded  = array();	 	// Contains the filenames of the loaded language files
	public	$lang    = array();    	// Contains all translation of a file in the following form:
									// array(%id% => %message%);

    /**
    * @desc Conrtuctor to register {translate} in SMARTY Template Engine
    */


    function __construct()
    {
        global $tpl;

        $tpl->register_block("translate", array('language',"smarty_translate"), false);
    }

    /**
    * @desc Function for SMARTY to handle {translate}
    */

    static function smarty_translate($params, $string, &$smarty)
    {
        global $lang;

        foreach ($params as $key => $value)
        {
            $params["%" . $key] = $value;
            unset($params[$key]);
        }
        echo($lang->t($string, $params));
    }

    /**
    * @desc Translate a string into the given language
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
    * @desc Just add another XML File to $lang->xml tree
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