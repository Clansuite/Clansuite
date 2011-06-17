<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-André Koch © 2005 - onwards
    * http://www.clansuite.com/
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
    * @author     Florian Wolf <xsign.dll@clansuite.com>
    * @copyright  Jens-André Koch (2005 - onwards), Florian Wolf (2006-2007)
    * @link       http://www.clansuite.com
    *
    * @version    SVN: $Id$
    */

//Security Handler
if(defined('IN_CS') === false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

/**
 * Sanitizes the HTML body content.
 * Removes dangerous tags and attributes which might lead
 * to security issues like XSS or HTTP response splitting.
 *
 * @author     Frederic Minne <zefredz@claroline.net>
 * @copyright  Copyright &copy; 2006-2007, Frederic Minne
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @version    1.0
 */
class Clansuite_Sanitizer
{
    # Protected private fields
    protected $_allowedTags;
    protected $_allowJavascriptEvents;
    protected $_allowJavascriptInUrls;
    protected $_allowObjects;
    protected $_allowScript;
    protected $_allowStyle;
    protected $_allowInlineStyle;
    protected $_additionalTags;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->resetAll();
    }

    /**
     * (re)set all options to default value
     */
    public function resetAll()
    {
        $this->_allowDOMEvents = false;
        $this->_allowJavascriptInUrls = false;
        $this->_allowStyle = false;
        $this->_allowScript = false;
        $this->_allowObjects = false;
        $this->_allowInlineStyle = false;

        # HTML5 Tags
        # @link http://www.w3schools.com/html5/html5_reference.asp
        $this->_allowedTags = '<a><abbr><address><area><article><aside>'        # <audio>
                . '<b><base><bdo><blockquote><body><br><button>
                   <canvas><caption><cite><code><col><colgroup><command>
                   <datalist><dd><details><del><dfn><div><dl><dt>'
                . '<em><eventsource>'                                           # <embed>
                . '<fieldset><figcaption><figure><footer><form>
                   <head><header><hgroup><hr><html><h1><h2><h3><h4><h5><h6>'
                . '<i><img><input><ins>'                                        # <iframe>
                . '<kbd><keygen>
                   <label><legend><li><link>
                   <mark><map><menu><meta><meter>
                   <nav><noscript>
                   <object><ol><optgroup><option><output>
                   <p><param><pre><progress>
                   <q>'
                . '<rp><rt>'                                                    # add <ruby> chinese characters
                . '<samp><script><section><select><small><source><span><strong><style><sub><summary><sup>
                   <table><tbody><td><textarea><tfoot><th><thead><time><title><tr>
                   <ul>
                   <var><video>
                   <wbr>';

        $this->_additionalTags = '';
    }

    /**
     * Add additional tags to allowed tags
     *
     * @param   string $tags
     */
    public function addAdditionalTags($tags)
    {
        $this->_additionalTags .= $tags;
    }

    /**
     * Allow object, embed, applet and param tags in html
     */
    public function allowObjects()
    {
        $this->_allowObjects = true;
    }

    /**
     * Allow DOM event on DOM elements
     */
    public function allowDOMEvents()
    {
        $this->_allowDOMEvents = true;
    }

    /**
     * Allow script tags
     */
    public function allowScript()
    {
        $this->_allowScript = true;
    }

    /**
     * Allow the use of javascript: in urls
     */
    public function allowJavascriptInUrls()
    {
        $this->_allowJavascriptInUrls = true;
    }

    /**
     * Allow style tags and attributes
     */
    public function allowStyle()
    {
        $this->_allowStyle = true;
    }

    /**
     * Helper to allow all javascript related tags and attributes
     */
    public function allowAllJavascript()
    {
        $this->allowDOMEvents();
        $this->allowScript();
        $this->allowJavascriptInUrls();
    }

    /**
     * Allow all tags and attributes
     */
    public function allowAll()
    {
        $this->allowAllJavascript();
        $this->allowObjects();
        $this->allowStyle();
    }

    /**
     * Filter URLs to avoid HTTP response splitting attacks
     *
     * @param   string $url
     * @return  string filtered url
     */
    protected function filterHTTPResponseSplitting($url)
    {
        $dangerousCharactersPattern = '~(\r\n|\r|\n|%0a|%0d|%0D|%0A)~';
        return preg_replace($dangerousCharactersPattern, '', $url);
    }

    /**
     * Remove potential javascript in urls
     *
     * @param   string $url
     * @return  string filtered url
     */
    protected function removeJavascriptURL($str)
    {
        $HTML_Sanitizer_stripJavascriptURL = 'javascript:[^"]+';

        $str = preg_replace("/$HTML_Sanitizer_stripJavascriptURL/i", '', $str);

        return $str;
    }

    /**
     * Remove potential flaws in urls
     *
     * @param   string $url
     * @return  string filtered url
     */
    protected function sanitizeURL($url)
    {
        if(!$this->_allowJavascriptInUrls)
        {
            $url = $this->removeJavascriptURL($url);
        }

        $url = $this->filterHTTPResponseSplitting($url);

        return $url;
    }

    /**
     * Callback for PCRE
     *
     * @param   array $matches
     * @return  string
     * @see     sanitizeURL
     */
    protected function _sanitizeURLCallback($matches)
    {
        return 'href="' . $this->sanitizeURL($matches[1]) . '"';
    }

    /**
     * Remove potential flaws in href attributes
     *
     * @param   string $str html tag
     * @return  string filtered html tag
     */
    protected function sanitizeHref($str)
    {
        $HTML_Sanitizer_URL = 'href="([^"]+)"';

        return preg_replace_callback("/$HTML_Sanitizer_URL/i", array(&$this, '_sanitizeURLCallback'), $str);
    }

    /**
     * Callback for PCRE
     *
     * @param   array $matches
     * @return  string
     * @see     sanitizeURL
     */
    protected function _sanitizeSrcCallback($matches)
    {
        return 'src="' . $this->sanitizeURL($matches[1]) . '"';
    }

    /**
     * Remove potential flaws in href attributes
     *
     * @param   string $str html tag
     * @return  string filtered html tag
     */
    protected function sanitizeSrc($str)
    {
        $HTML_Sanitizer_URL = 'src="([^"]+)"';

        return preg_replace_callback("/$HTML_Sanitizer_URL/i", array(&$this, '_sanitizeSrcCallback'), $str);
    }

    /**
     * Remove dangerous attributes from html tags
     *
     * @param   string $str html tag
     * @return  string filtered html tag
     */
    protected function removeEvilAttributes($str)
    {
        if(!$this->_allowDOMEvents)
        {
            $str = preg_replace_callback('/<(.*?)>/i', array(&$this, '_removeDOMEventsCallback'), $str);
        }

        if(!$this->_allowStyle)
        {
            $str = preg_replace_callback('/<(.*?)>/i' , array(&$this, '_removeStyleCallback'), $str);
        }

        return $str;
    }

    /**
     * Remove DOM events attributes from html tags
     *
     * @param   string $str html tag
     * @return  string filtered html tag
     */
    protected function removeDOMEvents($str)
    {
        $str = preg_replace('/\s*=\s*/', '=', $str);

        $HTML_Sanitizer_stripAttrib = '(onclick|ondblclick|onmousedown|'
                . 'onmouseup|onmouseover|onmousemove|onmouseout|onkeypress|onkeydown|'
                . 'onkeyup|onfocus|onblur|onabort|onerror|onload)'
        ;

        $str = stripslashes(preg_replace("/$HTML_Sanitizer_stripAttrib/i", 'forbidden', $str));

        return $str;
    }

    /**
     * Callback for PCRE
     *
     * @param   array $matches
     * @return  string
     * @see     removeDOMEvents
     */
    protected function _removeDOMEventsCallback($matches)
    {
        return '<' . $this->removeDOMEvents($matches[1]) . '>';
    }

    /**
     * Remove style attributes from html tags
     *
     * @param   string $str html tag
     * @return  string filtered html tag
     */
    protected function removeStyle($str)
    {
        $str = preg_replace('/\s*=\s*/', '=', $str);

        $HTML_Sanitizer_stripAttrib = '(style)';

        $str = stripslashes(preg_replace("/$HTML_Sanitizer_stripAttrib/i", 'forbidden', $str));

        return $str;
    }

    /**
     * Callback for PCRE
     *
     * @param   array $matches
     * @return  string
     * @see     removeStyle
     */
    protected function _removeStyleCallback($matches)
    {
        return '<' . $this->removeStyle($matches[1]) . '>';
    }

    /**
     * Remove dangerous HTML tags
     *
     * @access  private
     * @param   string $str html code
     * @return  string filtered url
     */
    protected function removeEvilTags($str)
    {
        $allowedTags = $this->_allowedTags;

        if($this->_allowScript)
        {
            $allowedTags .= '<script>';
        }

        if($this->_allowStyle)
        {
            $allowedTags .= '<style>';
        }

        if($this->_allowObjects)
        {
            $allowedTags .= '<object><embed><applet><param>';
        }

        $allowedTags .= $this->_additionalTags;

        // $str = strip_tags($str, $allowedTags );

        $str = $this->_stripTags($str, $allowedTags);

        return $str;
    }

    /**
     * Remove unwanted tags
     *
     * @param   string $str html
     * @param   string $tagList allowed tag list
     */
    protected function _stripTags($str, $tagList)
    {
        // 1. prepare allowed tags list
        $tagList = str_replace('<', ''
                , str_replace('>', ''
                        , str_replace('><', '|', $tagList)));

        // 2. replace </tag> by [[/tag]] in close tags for allowed tags
        $closeTags = '~' . '\</(' . $tagList . ')([^\>\<]*)\>' . '~'; // close tag

        $str = preg_replace($closeTags, "[[/\\1]]", $str);

        // ?! = do not match
        $autoAndOpenTags = '~('
                . '\<(?!' . $tagList . ')[^\>\<]*(/){0,1}\>' // auto
                . ')~';

        // 3. replace not allowed tags by ''
        $str = preg_replace($autoAndOpenTags, '', $str);

        // 4. replace [[/tag]] by </tag> for allowed tags
        $closeTags = '~' . '\[\[/(' . $tagList . ')([^\]]*)\]\]' . '~'; // close tag

        $str = preg_replace($closeTags, "</\\1>", $str);

        return $str;
    }

    /**
     * Sanitize HTML
     *
     * - removes  dangerous tags and attributes
     * - cleand urls
     *
     * @param   string $html html code
     * @return  string sanitized html code
     */
    public function sanitize($html)
    {
        $html = $this->removeEvilTags($html);

        $html = $this->removeEvilAttributes($html);

        $html = $this->sanitizeHref($html);

        $html = $this->sanitizeSrc($html);

        return $html;
    }
}


/**
 * Sanitize HTML code
 *
 * @param   string $str html code
 * @return  string sanitized code
 */
function html_sanitize_all($str)
{
    static $san = null;

    if(empty($san))
    {
        $san = new Clansuite_Html_Sanitizer;
    }

    return $san->sanitize($str);
}

/**
 * Sanitize HTML code, but allowObjects for editor
 *
 * @param   string $str html code
 * @return  string sanitized code
 */
function html_sanitize_editor($str)
{
    static $san = null;

    if(empty($san))
    {
        $san = new Clansuite_Html_Sanitizer;
        $san->allowObjects();
    }

    return $san->sanitize($str);
}

/**
 * Usage: Run *every* variable passed in through it.
 * The goal of this function is to be a generic function that can be used to
 * parse almost any input and render it XSS safe. For more information on
 * actual XSS attacks, check out http://ha.ckers.org/xss.html. Another
 * excellent site is the XSS Database which details each attack and how it
 * works.
 *
 * Used with permission by the author.
 * URL: http://quickwired.com/smallprojects/php_xss_filter_function.php
 *
 * Check XSS attacks on http://ha.ckers.org/xss.html
 *
 * License:
 * This code is public domain, you are free to do whatever you want with it,
 * including adding it to your own project which can be under any license.
 *
 * @author      Travis Puderbaugh <kallahar@quickwired.com>
 * @author      Jigal van Hemert <jigal@xs4all.nl>
 * @package     RemoveXSS
 */
final class RemoveXSS
{
    /**
     * Removes potential XSS code from an input string.
     *
     * Using an external class by Travis Puderbaugh <kallahar@quickwired.com>
     *
     * @param       string          Input string
     * @param       string          replaceString for inserting in keywords (which destroyes the tags)
     * @return      string          Input string with potential XSS code removed
     */
    public static function process($val, $replaceString = '<x>')
    {
        # don't use empty $replaceString because then no XSS-remove will be done
        if($replaceString == '')
        {
            $replaceString = '<x>';
        }

        # remove all non-printable characters. CR(0a) and LF(0b) and TAB(9) are allowed
        # this prevents some character re-spacing such as <java\0script>
        # note that you have to handle splits with \n, \r, and \t later since they *are* allowed in some inputs
        $val = preg_replace('/([\x00-\x08][\x0b-\x0c][\x0e-\x19])/', '', $val);

        # straight replacements, the user should never need these since they're normal characters
        # this prevents like <IMG SRC=&#X40&#X61&#X76&#X61&#X73&#X63&#X72&#X69&#X70&#X74&#X3A&#X61&#X6C&#X65&#X72&#X74&#X28&#X27&#X58&#X53&#X53&#X27&#X29>
        $searchHexEncodings = '/&#[xX]0{0,8}(21|22|23|24|25|26|27|28|29|2a|2b|2d|2f|30|31|32|33|34|35|36|37|38|39|3a|3b|3d|3f|40|41|42|43|44|45|46|47|48|49|4a|4b|4c|4d|4e|4f|50|51|52|53|54|55|56|57|58|59|5a|5b|5c|5d|5e|5f|60|61|62|63|64|65|66|67|68|69|6a|6b|6c|6d|6e|6f|70|71|72|73|74|75|76|77|78|79|7a|7b|7c|7d|7e);?/ie';
        $searchUnicodeEncodings = '/&#0{0,8}(33|34|35|36|37|38|39|40|41|42|43|45|47|48|49|50|51|52|53|54|55|56|57|58|59|61|63|64|65|66|67|68|69|70|71|72|73|74|75|76|77|78|79|80|81|82|83|84|85|86|87|88|89|90|91|92|93|94|95|96|97|98|99|100|101|102|103|104|105|106|107|108|109|110|111|112|113|114|115|116|117|118|119|120|121|122|123|124|125|126);?/ie';

        while(preg_match($searchHexEncodings, $val) || preg_match($searchUnicodeEncodings, $val))
        {
            $val = preg_replace($searchHexEncodings, "chr(hexdec('\\1'))", $val);
            $val = preg_replace($searchUnicodeEncodings, "chr('\\1')", $val);
        }

        # now the only remaining whitespace attacks are \t, \n, and \r
        $ra1 = array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'style', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base', 'onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');
        $ra_tag = array('applet', 'meta', 'xml', 'blink', 'link', 'style', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base');
        $ra_attribute = array('style', 'onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');
        $ra_protocol = array('javascript', 'vbscript', 'expression');

        # remove the potential &#xxx; stuff for testing
        $val2 = preg_replace('/(&#[xX]?0{0,8}(9|10|13|a|b);)*\s*/i', '', $val);
        $ra = array();

        foreach($ra1 as $ra1word)
        {
            # stripos is faster than the regular expressions used later
            # and because the words we're looking for only have chars < 0x80
            # we can use the non-multibyte safe version
            if(stripos($val2, $ra1word) !== false)
            {
                # keep list of potential words that were found
                if(in_array($ra1word, $ra_protocol))
                {
                    $ra[] = array($ra1word, 'ra_protocol');
                }
                if(in_array($ra1word, $ra_tag))
                {
                    $ra[] = array($ra1word, 'ra_tag');
                }
                if(in_array($ra1word, $ra_attribute))
                {
                    $ra[] = array($ra1word, 'ra_attribute');
                }
                # some keywords appear in more than one array
                # these get multiple entries in $ra, each with the appropriate type
            }
        }

        # only process potential words
        if(count($ra) > 0)
        {
            # keep replacing as long as the previous round replaced something
            $found = true;

            while($found == true)
            {
                $val_before = $val;
                for($i = 0; $i < sizeof($ra); $i++)
                {
                    $pattern = '';
                    for($j = 0; $j < strlen($ra[$i][0]); $j++)
                    {
                        if($j > 0)
                        {
                            $pattern .= '((&#[xX]0{0,8}([9ab]);)|(&#0{0,8}(9|10|13);)|\s)*';
                        }
                        $pattern .= $ra[$i][0][$j];
                    }

                    # handle each type a little different (extra conditions to prevent false positives a bit better)
                    switch($ra[$i][1])
                    {
                        case 'ra_protocol':
                            # these take the form of e.g. 'javascript:'
                            $pattern .= '((&#[xX]0{0,8}([9ab]);)|(&#0{0,8}(9|10|13);)|\s)*(?=:)';
                            break;
                        case 'ra_tag':
                            # these take the form of e.g. '<SCRIPT[^\da-z] ....';
                            $pattern = '(?<=<)' . $pattern . '((&#[xX]0{0,8}([9ab]);)|(&#0{0,8}(9|10|13);)|\s)*(?=[^\da-z])';
                            break;
                        case 'ra_attribute':
                            # these take the form of e.g. 'onload='  Beware that a lot of characters are allowed
                            # between the attribute and the equal sign!
                            $pattern .= '[\s\!\#\$\%\&\(\)\*\~\+\-\_\.\,\:\;\?\@\[\/\|\\\\\]\^\`]*(?==)';
                            break;
                    }

                    $pattern = '/' . $pattern . '/i';

                    # add in <x> to nerf the tag
                    $replacement = substr_replace($ra[$i][0], $replaceString, 2, 0);

                    # filter out the hex tags
                    $val = preg_replace($pattern, $replacement, $val);

                    if($val_before == $val)
                    {
                        # no replacements were made, so exit the loop
                        $found = false;
                    }
                }
            }
        }

        return $val;
    }

}
?>