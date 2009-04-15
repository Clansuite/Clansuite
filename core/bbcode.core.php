<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-André Koch © 2005 - onwards
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
    *
    * @author     Jens-André Koch   <vain@clansuite.com>
    * @author     Florian Wolf      <xsign.dll@clansuite.com>
    * @copyright  Jens-André Koch (2005-onwards, Florian Wolf (2006-2007)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    *
    * @version    SVN: $Id$
    */

//Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}

/**
 * Clansuite Core Class for BBCode Handling (Wrapper) and Syntax Highlighting
 *
 * It's a wrapper class for
 * a) GeShi Code/Syntax Highligther
 * b) bbcode_stringparser.
 *
 * @author     Jens-André Koch   <vain@clansuite.com>
 * @author     Florian Wolf      <xsign.dll@clansuite.com>
 * @copyright  Jens-André Koch (2005-onwards), Florian Wolf (2006-2007)
 * @since      Class available since Release 0.1
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  BBCode
 */
class Clansuite_Bbcode
{
    /**
     * @var array
     */
    public $bb_code;

    /**
     *
     */
    function __construct($injector)
    {
        /**
         * Include Stringpaser_bbcode Class
         * Generate the object
         */

        require_once( ROOT_LIBRARIES . '/bbcode/stringparser_bbcode.class.php' );
        $this->bb_code = new StringParser_BBCode();

        // Load the BB Code Vars
        #$db = $injector->instantiate('clansuite_doctrine');
        $bb_codes = Doctrine_Query::create()
                                  ->select('*')
                                  ->from('CsBbCode')
                                  ->execute();

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
         * Add the BBCodes from DB via addCode
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
    public function parse($text)
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
    private function do_bbcode_url ($action, $attributes, $content, $params, $node_object)
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
    private function do_bbcode_img ($action, $attributes, $content, $params, $node_object)
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
    private function do_bbcode_code ($action, $attributes, $content, $params, $node_object)
    {
        if ($action == 'validate')
        {
            return true;
        }

        // Include & Instantiate GeSHi
        require_once( ROOT_LIBRARIES . '/geshi/geshi.php' );
        $geshi = new GeSHi($content, $attributes['default']);

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
    private function convertlinebreaks ($text)
    {
        return preg_replace ("/\015\012|\015|\012/", "\n", $text);
    }
}