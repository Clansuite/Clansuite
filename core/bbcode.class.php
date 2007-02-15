<?php
/**
* Modulename:   bbcode
* Description:  Edit or add BB Code Styles
*
* PHP >= version 5.1.4
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
* @link       http://gna.org/projects/clansuite
*
* @author     Florian Wolf
* @copyright  ClanSuite Group
* @license    GPL v2
* @version    SVN: $Id$
* @link       http://www.clansuite.com
*/

// Security Handler
if (!defined('IN_CS')) { die('You are not allowed to view this page.' ); }

// Begin of class module_admin_bbcode
class bbcode
{
    public $bb_code;

    function __construct()
    {
        global $db;

        /**
        * @desc Generate the object
        */
        require_once( ROOT_CORE . '/bbcode/stringparser_bbcode.class.php' );
        $this->bb_code = new StringParser_BBCode();

        /**
        * @desc Load the BB Code Vars
        */
        $stmt = $db->prepare('SELECT * FROM ' . DB_PREFIX . 'bb_code');
        $stmt->execute();
        $bb_codes = $stmt->fetchAll(PDO::FETCH_NAMED);

        /**
        * @desc Conversions & Filters
        */
        $this->bb_code->addFilter (STRINGPARSER_FILTER_PRE, array( $this, 'convertlinebreaks' ) );
        $this->bb_code->addParser (array ('block', 'inline', 'link', 'listitem'), 'htmlspecialchars');
        $this->bb_code->addParser (array ('block', 'inline', 'link', 'listitem'), 'nl2br');

        /**
        * @desc Generate Standard BB Codes such as [url][/url] etc.
        */
        $this->bb_code->addCode ('url', 'usecontent?', array( $this, 'do_bbcode_url'), array ('usecontent_param' => 'default'),
                          'link', array ('listitem', 'block', 'inline'), array ('link'));
        $this->bb_code->addCode ('link', 'callback_replace_single', array( $this, 'do_bbcode_url' ), array (),
                          'link', array ('listitem', 'block', 'inline'), array ('link'));
        $this->bb_code->addCode ('img', 'usecontent', array( $this, 'do_bbcode_img' ), array (),
                          'image', array ('listitem', 'block', 'inline', 'link'), array ());
        $this->bb_code->addCode ('bild', 'usecontent', array( $this, 'do_bbcode_img' ), array (),
                          'image', array ('listitem', 'block', 'inline', 'link'), array ());
        $this->bb_code->setOccurrenceType ('img', 'image');
        $this->bb_code->setOccurrenceType ('bild', 'image');

        /**
        * @desc Implement DB BB Codes
        */
        foreach( $bb_codes as $key => $code )
        {
            $allowed_in = explode(',', $code['allowed_in']);
            $not_allowed_in = explode(',', $code['not_allowed_in']);
            $this->bb_code->addCode ($code['name'], 'simple_replace', null, array ('start_tag' => $code['start_tag'], 'end_tag' => $code['end_tag']),
                              $code['content_type'], $allowed_in, $not_allowed_in);
        }
    }

    function parse($text)
    {
        return $this->bb_code->parse($text);
    }

    // Handle BB Code URLs
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

    // Handle Pictures
    function do_bbcode_img ($action, $attributes, $content, $params, $node_object)
    {
        if ($action == 'validate')
        {
            return true;
        }
        return '<img src="'.htmlspecialchars($content).'" alt="">';
    }

    // Convert linebreak of different OS
    function convertlinebreaks ($text)
    {
        return preg_replace ("/\015\012|\015|\012/", "\n", $text);
    }
}