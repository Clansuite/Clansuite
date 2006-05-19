<?php
//============================================================+
// File name   : TMXResourceBundle.php
// Begin       : 2004-10-19
// Last Update : 2005-03-18
//                                                             
// Description : TMX-PHP Bridge Class
// Platform    : PHP 5
//
// Author: Nicola Asuni
//                                                             
// (c) Copyright:
//               Tecnick.com S.r.l.
//               Via Ugo Foscolo n.19
//               09045 Quartu Sant'Elena (CA)
//               ITALY
//               www.tecnick.com
//               info@tecnick.com                                                            
//============================================================+
 
/**
 * TMX-PHP Bridge Class (TMXResourceBundle).
 * @package com.tecnick.tmxphpbridge
 */
 
/**
 * This PHP Class reads resource text data directly from a TMX (XML) file.
 * First, the XMLTMXResourceBundle class instantiates itself with two parameters:
 * a TMX file name and a target language name. Then, using an XML parser, it
 * reads all of a translation unit's properties for the key information and
 * specified language data and populates the resource array with them (key -> value).
 *
 * @name TMXResourceBundle
 * @package com.tecnick.tmxphpbridge
 * @abstract TMX-PHP Bridge Class
 * @link http://tmxphpbridge.sourceforge.net
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 * @author Nicola Asuni [www.tecnick.com]
 * @copyright Copyright (c) 2004-2005 - Tecnick.com S.r.l (www.tecnick.com) - Via Ugo Foscolo n.19 - 09045 Quartu Sant'Elena (CA) - ITALY - www.tecnick.com - info@tecnick.com
 * @version 1.1.001
 */
class TMXResourceBundle {
	
	/**
	 * @var Array used to contain key-translation couples.
	 * @access private
	 */
	private $resource = array();
	
	/**
	 * @var Current tu -> tuid value.
	 * @access private
	 */
	private $current_key = "";
	
	/**
	 * @var Current data value.
	 * @access private
	 */
	private $current_data = "";
		
	/**
	 * @var Current tuv -> xml:lang value.
	 * @access private
	 */
	private $current_language = "";
	
	/**
	 * @var Is TRUE when we are inside a seg element
	 * @access private
	 */
	private $segdata = false;
			
	/**
	 * @var ISO language identifier (a two- or three-letter code)
	 * @access private
	 */
	private $language = "";
	
	/**
	 * Class constructor.
	 * @param string $tmxfile TMX (XML) file name
	 * @param string $language ISO language identifier (a two- or three-letter code)
	 */
	public function __construct($tmxfile, $language) {
		// reset array
		$this->resource = array();
		// set selecteed language
		$this->language = strtoupper($language);
		// creates a new XML parser to be used by the other XML functions
		$this->parser = xml_parser_create();
		// the following function allows to use parser inside object
		xml_set_object($this->parser, $this);
		// disable case-folding for this XML parser
		xml_parser_set_option($this->parser, XML_OPTION_CASE_FOLDING, 0);
		// sets the element handler functions for the XML parser
		xml_set_element_handler($this->parser, "startElementHandler", "endElementHandler");
		// sets the character data handler function for the XML parser
		xml_set_character_data_handler($this->parser, "segContentHandler");
		// start parsing an XML document
		if(!xml_parse($this->parser, file_get_contents($tmxfile))) {
			die(sprintf("ERROR TMXResourceBundle :: XML error: %s at line %d",
			xml_error_string(xml_get_error_code($this->parser)),
			xml_get_current_line_number($this->parser)));
		}
		// free this XML parser
		xml_parser_free($this->parser);
	}
	
	/**
	 * Class destructor; resets $resource array.
	 */
	public function __destruct() {
		$resource = array(); // reset resource array
	}
	
	/**
	 * Sets the start element handler function for the XML parser parser.start_element_handler.
	 * @param resource $parser The first parameter, parser, is a reference to the XML parser calling the handler.
	 * @param string $name The second parameter, name, contains the name of the element for which this handler is called. If case-folding is in effect for this parser, the element name will be in uppercase letters. 
	 * @param array $attribs The third parameter, attribs, contains an associative array with the element's attributes (if any). The keys of this array are the attribute names, the values are the attribute values. Attribute names are case-folded on the same criteria as element names. Attribute values are not case-folded. The original order of the attributes can be retrieved by walking through attribs the normal way, using each(). The first key in the array was the first attribute, and so on. 
	 * @access private
	 */
	private function startElementHandler($parser, $name, $attribs) {
		switch(strtolower($name)) {
			case 'tu': {
				// translation unit element, unit father of every element to be translated. It can contain a unique identifier (tuid). 
				if (array_key_exists('tuid', $attribs)) {
					$this->current_key = $attribs['tuid'];
				}
				break;
			}
			case 'tuv': {
				// translation unit variant, unit that contains the language code of the translation (xml:lang)
				if (array_key_exists('xml:lang', $attribs)) {
					$this->current_language = $attribs['xml:lang'];
				}
				break;
			}
			case 'seg': {
				// segment, it contains the translated text
				$this->segdata = true;
				$this->current_data = "";
				break;
			}
			default: {
				break;
			}
		}
	}
	
	/**
	 * Sets the end element handler function for the XML parser parser.end_element_handler.
	 * @param resource $parser The first parameter, parser, is a reference to the XML parser calling the handler.
	 * @param string $name The second parameter, name, contains the name of the element for which this handler is called. If case-folding is in effect for this parser, the element name will be in uppercase letters. 
	 * @access private
	 */
	private function endElementHandler($parser, $name) {
		switch(strtolower($name)) {
			case 'tu': {
				// translation unit element, unit father of every element to be translated. It can contain a unique identifier (tuid). 
				$this->current_key = "";
				break;
			}
			case 'tuv': {
				// translation unit variant, unit that contains the language code of the translation (xml:lang)
				$this->current_language = "";
				break;
			}
			case 'seg': {
				// segment, it contains the translated text
				$this->segdata = false;
				if (!empty($this->current_data) OR !array_key_exists($this->current_key, $this->resource)) {	
					$this->resource[$this->current_key] = $this->current_data; // set new array element
				}
				break;
			}
			default: {
				break;
			}
		}
	}
	
	/**
	 * Sets the character data handler function for the XML parser parser.handler.
	 * @param resource $parser The first parameter, parser, is a reference to the XML parser calling the handler.
	 * @param string $data The second parameter, data, contains the character data as a string. 
	 * @access private
	 */
	private function segContentHandler($parser, $data) {
		if ($this->segdata AND (strlen($this->current_key)>0) AND (strlen($this->current_language)>0)) {
			// we are inside a seg element
			if (strcasecmp($this->current_language, $this->language) == 0) {
				// we have reached the requested language translation
				$this->current_data .= $data;
			}
		}
	}
	
	/**
	 * Returns the resource array containing the translated word/sentences.
	 * @return Array.
	 */
	public function getResource() {
		return $this->resource;
	}
	
} // END OF CLASS

//============================================================+
// END OF FILE                                                 
//============================================================+
?>
