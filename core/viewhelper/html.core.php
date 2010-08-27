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
    * @author     Jens-André Koch <vain@clansuite.com>
    * @copyright  Jens-André Koch (2005-onwards)
    * @link       http://www.clansuite.com
    *
    * @version    SVN: $Id$
    */

# Security Handler
if(defined('IN_CS') === false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

/**
 * Clansuite_HTML
 *
 * The class provides helper methods to output html-tag elements.
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
     * @param string $url The URL (href).
     * @param string $text The text linking to the URL.
     * @param array $attributes Additional HTML Attribute as string.
     *
     * @return string html
     */
    public static function a($url, $text, $attributes = array())
    {
        $html_attributes = '';
        $html_attributes .= self::renderAttributes($attributes);

        return '<a href="'.$url.'" '.$html_attributes.'>'.$text.'</a>';
    }

    /**
     * Renders the HTML Tag <span></span>
     *
     * @param string $text
     * @param array $attributes array of attributes
     *
     * @return string html
     */
    public static function span($text, $attributes = array())
    {
        $html_attributes = '';
        $html_attributes .= self::renderAttributes($attributes);

        return '<span'.$html_attributes.'>'.$text.'</div>';
    }

    /**
     * Renders the HTML Tag <div></div>
     *
     * @param string $text string
     * @param array $attributes array of attributes
     *
     * @return string html
     */
    public static function div($text, $attributes = array())
    {
        $html_attributes = '';
        $html_attributes .= self::renderAttributes($attributes);

        return '<div'.$html_attributes.'>'.$text.'</div>';
    }

    /**
     * Renders the HTML Tag <p></p>
     *
     * @param string $text string
     * @param array $attributes array of attributes
     *
     * @return string html
     */
    public static function p($text, $attributes = array())
    {
        $html_attributes = '';
        $html_attributes .= self::renderAttributes($attributes);

        return '<p'.$html_attributes.'>'.$text.'</p>';
    }

    /**
     * Renders the HTML Tag <img></img>
     *
     * @param string $link_to_image
     * @param array $attributes
     *
     * @return string html
     */
    public static function image($link_to_image, $attributes = array())
    {
        $html_attributes = '';
        $html_attributes .= self::renderAttributes($attributes);

        return '<img' . $html_attributes . ' src="' . $link_to_image . '" />';
    }

    /**
     * Convenience/Proxy Method for self::image()
     *
     * @param string $link_to_image
     * @param array $attributes
     */
    public static function img($link_to_image, $attributes = array())
    {
        return self::image($link_to_image, $attributes = array());
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
     * self::list($attributes);
     *
     * @param array $attributes array of attributes
     *
     * @return string html
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
                $html .= self::liste($attribute);
            }
            else
            {
                $html .= '<li>'.$attribute.'</li>' . CR;
            }
        }
        $html .= '</ul>' . CR;


        return $html;
    }

    /**
     * HTML Tag <h1>
     *
     * @param string $text string
     *
     * @return string html
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
     * @return string html
     */
    public static function h2($text)
    {
        return '<h2>'.$text.'</h2>';
    }

    /**
     * HTML Tag <h3>
     *
     * @param string $text string
     *
     * @return string html
     */
    public static function h3($text)
    {
        return '<h3>'.$text.'</h3>';
    }

    /**
     * Render the attributes for usage in an tag element
     *
     * @param array $attributes array of attributes
     *
     * @return Render the HTML String of Attributes
     */
    public static function renderAttributes($attributes = array())
    {
        $html = '';

        if(is_array($attributes))
        {
            # insert all attributes
            foreach($attributes as $key => $value)
            {
                $html .= ' ' . $key . '"' . $value . '"';
            }
        }

        return $html;
    }

    /**
     * Render an HTML Element
     *
     * @example
     * echo self::renderElement('tagname', array('attribute_name'=>'attribut_value'), 'text');
     *
     * @param string $tagname Name of the tag to render
     * @param string $text string
     * @param array $attributes array of attributes
     *
     * @return string html with Attributes
     */
    public static function renderElement($tagname, $text = null, $attributes = array())
    {
        if(method_exists('Clansuite_HTML', $tagname))
        {
            if(isset($attributes['src']))
            {
                $link = $attributes['src'];
                unset($attributes['src']);

                return self::$tagname($link, $text, $attributes);
            }
            elseif(isset($attributes['href']))
            {
                $link = $attributes['href'];
                unset($attributes['href']);

                return self::$tagname($link, $text, $attributes);
            }
            else
            {
                return self::$tagname($text, $attributes);
            }
        }
        else
        {
            $html = '<' . $tagname;
            $html .= self::renderAttributes($attributes);

            # close tag with slash, if we got no text to append
            if($text === null)
            {
                $html .= '/>';
            }
            else # just close the opening tag
            {
                $html .= '>';
                $html .= $text;
                $html .= '</' . $tagname . '>' . CR;
            }

            return $html;
        }
    }
}
?>