<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-Andre Koch © 2005-2008
    * http://www.clansuite.com/
    *
    * File:         bbcode.class.php
    * Requires:     PHP 5.1.4+
    *
    * Purpose:      Clansuite Core Class for for BBCode Handling
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
if (!defined('IN_CS')){ die('Clansuite Framework not loaded. Direct Access forbidden.' );}

/**
 * This Clansuite Core Class for BBCode Handling (Wrapper)
 *
 * It's a wrapper for GeShi Code Highligther and bbcode_stringparser.
 *
 * @author     Jens-Andre Koch   <vain@clansuite.com>
 * @author     Florian Wolf      <xsign.dll@clansuite.com>
 * @copyright  Jens-Andre Koch (2005-$LastChangedDate$), Florian Wolf (2006-2007)
 * @since      Class available since Release 0.1
 *
 * @package     clansuite
 * @category    core
 * @subpackage  bbcode
 */
class bbcode
{
    /**
     * @var array
     */
    
    public $bb_code;
    
    /**
     * CONSTRUCTOR
     *
     * @
     */

    function __construct()
    {
        global $db;

        /**
         * Include Stringpaser_bbcode Class
         * Generate the object
         */
         
        require_once( ROOT_CORE . '/bbcode/stringparser_bbcode.class.php' );
        $this->bb_code = new StringParser_BBCode();

        /**
         * Load the BB Code Vars
         */
         
        $stmt = $db->prepare('SELECT * FROM ' . DB_PREFIX . 'bb_code');
        $stmt->execute();
        $bb_codes = $stmt->fetchAll(PDO::FETCH_NAMED);

        /**
         * Conversions & Filters
         */
         
        $this->bb_code->addFilter (STRINGPARSER_FILTER_PRE, array( $this, 'convertlinebreaks' ) );
        $this->bb_code->addParser (array ('block', 'inline', 'link', 'listitem',), 'htmlspecialchars');
        $this->bb_code->addParser (array ('block', 'inline', 'link', 'listitem',), 'nl2br');

        /**
         * Generate Standard BB Codes such as [url][/url] etc.
         */
         
        $this->bb_code->addCode ('url', 'usecontent?', array( $this, 'do_bbcode_url'), array ('usecontent_param' => 'default'),
                          'link', array ('listitem', 'block', 'inline'), array ('link'));
        $this->bb_code->addCode ('link', 'callback_replace_single', array( $this, 'do_bbcode_url' ), array (),
                          'link', array ('listitem', 'block', 'inline'), array ('link'));
        $this->bb_code->addCode ('img', 'usecontent', array( $this, 'do_bbcode_img' ), array (),
                          'image', array ('listitem', 'block', 'inline', 'link'), array ());
        $this->bb_code->addCode ('bild', 'usecontent', array( $this, 'do_bbcode_img' ), array (),
                          'image', array ('listitem', 'block', 'inline', 'link'), array ());
        $this->bb_code->addCode ('code', 'usecontent?', array( $this, 'do_bbcode_code' ), array ('usecontent_param' => 'default'),
                          'code', array ('listitem', 'block', 'inline'), array ('code'));
        $this->bb_code->setOccurrenceType ('img', 'image');
        $this->bb_code->setOccurrenceType ('bild', 'image');

        /**
         * Implement DB BB Codes
         */
        foreach( $bb_codes as $key => $code )
        {
            $allowed_in = explode(',', $code['allowed_in']);
            $not_allowed_in = explode(',', $code['not_allowed_in']);
            $this->bb_code->addCode ($code['name'], 'simple_replace', null, array ('start_tag' => $code['start_tag'], 'end_tag' => $code['end_tag']),
                              $code['content_type'], $allowed_in, $not_allowed_in);
        }
    }

    /**
     * Parse the BB Code
     *
     * @param string
     * @return bb_code parsed text
     */
     
    function parse($text)
    {
        return $this->bb_code->parse($text);
    }

    /**
     * Handle BB Code URLs
     *
     * @param string
     * @param array
     * @param string
     * @param mixed
     * @param mixed
     * @return return url
     *
     * @todo $params and $node_objects are unuseed check
     */
     
    function do_bbcode_url ($action, $attributes, $content, $params, $node_object)
    {
        if ($action == 'validate')
        {
            return true;
        }

        if (!isset ($attributes['default']))
        {
            return '<a href="'.htmlspecialchars ($content).'">'.htmlspecialchars ($content).'</a>';
        }
        return '<a href="'.htmlspecialchars ($attributes['default']).'">'.$content.'</a>';
    }

    /** 
     * Handle Pictures
     * 
     * @todo comment params
     * @return image string
     */
     
    function do_bbcode_img ($action, $attributes, $content, $params, $node_object)
    {
        if ($action == 'validate')
        {
            return true;
        }
        return '<img src="'.htmlspecialchars($content).'" alt="">';
    }

    /**
     * Handle PHP Code Hightlightning with GeShi
     * 
     * @return codehighlighted string
     */
     
    function do_bbcode_code ($action, $attributes, $content, $params, $node_object)
    {
        if ($action == 'validate')
        {
            return true;
        }

        /**
         * Include the GeSHi library
         */
         
        require_once( ROOT_CORE . '/geshi/geshi.php' );

        /**
         * Create a GeSHi object
         */
         
        $geshi =& new GeSHi($content, $attributes['default']);

        /**
         * And echo the result!
         */
         
        return $geshi->parse_code();
    }



    /**
     * Convert linebreak of different OS
     *
     * @param string
     * @return line_break_converted string
     *
     * @todo note by vain: why is this needed? describe problem?
     */
     
    function convertlinebreaks ($text)
    {
        return preg_replace ("/\015\012|\015|\012/", "\n", $text);
    }
}