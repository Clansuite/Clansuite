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
    * @copyright  Copyleft: All rights reserved. Jens-André Koch (2005-onwards)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    * @since      File available since Release 0.1
    *
    * @version    SVN: $Id$
    */

// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.');}

/**
 * Clansuite_HTML
 *
 * Clansuite_HTML provides helper methods to output html-tag elements.
 *
 * @author     Jens-André Koch   <vain@clansuite.com>
 * @copyright  Jens-André Koch (2005-onwards)
 *
 * @category    Clansuite
 * @package     Viewhelper
 * @subpackage  HTML
 */

class Clansuite_HTML /* extends DOMDocument */
{
    /**
     * Renders the HTML Tag <a href=""></a>
     *
     * @param $url
     * @param $attributes
     * @param $text string
     *
     * @return HTML String
     */
    public static function a($url, $text, $attributes = array())
    {
        $html_attributes = '';
        $html_attributes .= Clansuite_HTML::renderAttributes($attributes);

        return '<a href="'.$url.'" '.$html_attributes.'>'.$text.'</a>';
    }

    /**
     * Renders the HTML Tag <span></span>
     *
     * @param $text string
     * @param $attributes array of attributes
     *
     * @return HTML String
     */
    public static function span($text, $attributes = array())
    {
        $html_attributes = '';
        $html_attributes .= Clansuite_HTML::renderAttributes($attributes);

        return '<span'.$html_attributes.'>'.$text.'</div>';
    }

    /**
     * Renders the HTML Tag <div></div>
     *
     * @param $text string
     * @param $attributes array of attributes
     *
     * @return HTML String
     */
    public static function div($text, $attributes = array())
    {
        $html_attributes = '';
        $html_attributes .= Clansuite_HTML::renderAttributes($attributes);

        return '<div'.$html_attributes.'>'.$text.'</div>';
    }

    /**
     * Renders the HTML Tag <p></p>
     *
     * @param $text string
     * @param $attributes array of attributes
     *
     * @return HTML String
     */
    public static function p($text, $attributes = array())
    {
        $html_attributes = '';
        $html_attributes .= Clansuite_HTML::renderAttributes($attributes);

        return '<p'.$html_attributes.'>'.$text.'</p>';
    }

    /**
     * Renders the HTML Tag <img></img>
     *
     * @param $attributes
     * @param $tagname
     * @param $text string
     *
     * @return HTML String
     */
    public static function image($link_to_image, $attributes = array())
    {
        $html_attributes = '';
        $html_attributes .= Clansuite_HTML::renderAttributes($attributes);

        return '<img'.$html_attributes.' src="$link_to_image" />';
    }

    /**
     * HTML Tag Rendering
     * Builds a list from an multidimensional attributes array
     *
     * @example
     * $attributes = array('UL-Heading-A',
     *                  array('LI-Element-A','LI-Element-B'),
     *                     'UL-Heading-1',
     *                  array('LI-Element-1','LI-Element-2')
     *                    );
     * Clansuite_HTML::list($attributes);
     *
     * @param $attributes array of attributes
     *
     * @return HTML String
     */
    public static function liste($attributes = array())
    {
        $html = '';

        $html .= '<ul>';
        foreach($attributes as $attribute)
        {
            if (is_array($attribute))
            {
                # watch out! recursion
                $html .= Clansuite_HTML::liste($attribute);
            }
            else
            {
                $html .= '<li>'.$attribute.'</li>';
            }
        }
        $html .= '</ul>';


        return $html;
    }

    /**
     * HTML Tag <h1>
     *
     * @param $text string
     *
     * @return HTML String
     */
    public static function h1($text)
    {
        return '<h1>'.$text.'</h1>';
    }

    /**
     * HTML Tag <h2>
     *
     * @param $text string
     *
     * @return HTML String
     */
    public static function h2($text)
    {
        return '<h2>'.$text.'</h2>';
    }

    /**
     * HTML Tag <h3>
     *
     * @param $text string
     *
     * @return HTML String
     */
    public static function h3($text)
    {
        return '<h3>'.$text.'</h3>';
    }

    /**
     * Render the attributes for usage in an tag element
     *
     * @param $attributes array of attributes
     *
     * @return Render the HTML String of Attributes
     */
    public static function renderAttributes($attributes = array())
    {
        $html = '';

        if(is_array($attributes))
        {
            # insert all attributes
            foreach ($attributes as $key=>$value)
            {
                $html .= " $key=\"$value\"";
            }
        }

        return $html;
    }

    /**
     * Render an HTML Element
     *
     * @example
     * echo Clansuite_HTML::renderElement('tagname', array('attribute_name'=>'attribut_value'), 'text');
     *
     * @param $tagname Name of the tag to render
     * @param $text string
     * @param $attributes array of attributes
     *
     * @return HTML String with Attributes
     */
    public static function renderElement($tagname, $text = null, $attributes = array())
    {
        if(method_exists('Clansuite_HTML', $tagname))
        {
            return Clansuite_HTML::$tagname($text, $attributes);
        }
        else
        {
            $html = "<$tagname";
            $html .= Clansuite_HTML::renderAttributes($attributes);

            # close tag with slash, if we got no text to append
            if ($text === null)
            {
                $html .= '/>';
            }
            else # just close the opening tag
            {
                $html .= '>';
                $html .= $text;
                $html .= "</$tagname>";
            }

            return $html;
        }
    }
}
?>