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

    public $xml     = array();
    public $loaded     = array();
    
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
            $params["%$key"] = $value;
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
        
        foreach ($lang->xml as $parent)
        {
            foreach ($parent[0]['children'] as $tag)
            {
                if ($tag['tag'] == "MESSAGE")
                {
                    if ($tag['children'][0]['value'] == $string)
                    {
                        if ($tag['children'][1]['value'] != "")
                        {
                            return strtr($tag['children'][1]['value'], $args);
                        }
                    }
                }
            }
        }
        
        return strtr($string, $args);
    }
    
    /**
    * @desc Just add another XML File to $lang->xml tree
    */

    function load_lang($xml_file_name='' )
    {
        global $cfg;
        
        $file = LANG_ROOT . '/' . $cfg->language . '/' . $xml_file_name . '.xml';
        
        if (file_exists($file))
        {
            if (!in_array($xml_file_name, $this->loaded ) )
            {
                array_push($this->xml, $this->get_xml_tree($file) );
                array_push($this->loaded, $xml_file_name );
            }
        }
    }
    
    /**
    * @desc Handle all children attributes from XML file
    */

    function get_children($vals, &$i)
    {
        global $lang;
        
        $children = array();
        
        if (!isset($vals[$i]['attributes']))
        {
            $vals[$i]['attributes'] = "";
        }
        
        while (++$i < count($vals))
        {
            if (!isset($vals[$i]['attributes']))
            {
                $vals[$i]['attributes'] = "";
            }
            
            if (!isset($vals[$i]['value']))
            {
                $vals[$i]['value'] = "";
            }
            
            switch ($vals[$i]['type'])
            {
                case 'complete':
                    array_push($children, array('tag' => $vals[$i]['tag'], 'attributes' => $vals[$i]['attributes'], 'value' => $vals[$i]['value']));
                    break;
                case 'open':
                    array_push($children, array('tag' => $vals[$i]['tag'], 'attributes' => $vals[$i]['attributes'], 'children' => $lang->get_children($vals, $i)));
                    break;
                case 'close':
                    return $children;
                    break;
            }
        }
        
        return $children;
    }
    
    /**
    * @desc XML file parsing
    * @desc Return Treestructure
    */

    function get_xml_tree($file)
    {
        global $lang;
        
        $data = implode("", file($file));
        $xml  = xml_parser_create();
        xml_parser_set_option($xml, XML_OPTION_SKIP_WHITE, 1);
        xml_parse_into_struct($xml, $data, &$vals, &$index);
        xml_parser_free($xml);
        
        $tree = array();
        $i = 0;
        array_push($tree, array('tag' => $vals[$i]['tag'], 'attributes' => $vals[$i]['attributes'], 'children' => $lang->get_children($vals, $i)));
        
        return $tree;
    }
}
?>