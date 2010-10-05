<?php
   /**
    * Clansuite - just an E-Sport CMS
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
    * @author     Jens-André Koch   <vain@clansuite.com>
    * @copyright  Jens-André Koch (2005 - onwards)
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
 * Clansuite_Gettext_Extractor_Template
 *
 * Extracts translation strings from templates by scanning for certain placeholders, like {t}, {_}.
 *
 * @author Karel Klíma
 * @author Jens-Andre Koch
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Gettext
 */
class Clansuite_Gettext_Extractor_Template extends Clansuite_Gettext_Extractor_Base implements Clansuite_Gettext_Extractor_Interface
{
    /**
     * @const regex to match the curly bracket syntax
     */
    #const REGEX = '#{(__PLACEHOLDERS__)("[^"\\\\]*(?:\\\\.[^"\\\\]*)*"|\'[^\'\\\\]*(?:\\\\.[^\'\\\\]*)*\')+(\|[a-z]+(:[a-z0-9]+)*)*}#u';
    const REGEX = '#{(__PLACEHOLDERS__)(.*?)+(\|[a-z]+(:[a-z0-9]+)*)*}#u';


    /**
     * The function tags to extract translation strings from
     *
     * @var array
     */
    protected $tags_to_scan = array('t', '_');

    /**
     * Parses given file and returns found gettext phrases
     *
     * @param string $file
     *
     * @return array
     */
    public function extract($file)
    {
        $pathinfo = pathinfo($file);

        if(false === count($this->tags_to_scan))
        {
            return;
        }

        $data = array();
        $filecontent = file($file);

        # construct the regular expression pattern
        $placeholders = join('|', $this->tags_to_scan);
        $pattern = str_replace('__PLACEHOLDERS__', $placeholders, self::REGEX);

        Clansuite_Debug::firebug($pattern);

        # parse file by lines
        foreach($filecontent as $line => $line_content)
        {
            # match all {t ... } or {_ ... } tags if prefixes are "t" and "_"
            preg_match_all($pattern, $line_content, $matches);

            Clansuite_Debug::firebug($line_content);
            Clansuite_Debug::firebug($matches);

            if(empty($matches))
            {
                continue;
            }

            if(empty($matches[2]))
            {
                continue;
            }

            foreach($matches[2] as $match)
            {
                $match_arrayname = substr($match, 1, -1);

                # strips trailing apostrophes or double quotes
                $data[$match_arrayname][] = $pathinfo['basename'] . ':' . $line;

                unset($match_arrayname);
            }
        }
        unset($filecontent);

        return $data;
    }
}
?>