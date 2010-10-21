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
    const L_DELIMITER = '{';
    const R_DELIMITER = '}';

    /**
     * This modified regexp is based on "tsmarty2c.php" by
     * @author Sagi Bashari <sagi@boom.org.il>
     * @license LGPL v2.1+
     *
     * @const regexp to match the smarty curly bracket syntax
     */
    const REGEXP = "/__LD__\s*(__TAGS__)\s*([^__RD__]*)__RD__([^__LD__]*)__LD__\/\\1__RD__/";

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
        # load file
        $filecontent = file($file);

        # ensure we got the filecontent
        if(empty($filecontent))
        {
            return;
        }

        # ensure we got defined some tags to scan for
        if(false === count($this->tags_to_scan))
        {
            return;
        }

        # init vars
        $pathinfo = pathinfo($file);
        $data = array();

        /**
         *  construct the regular expression pattern
         */
        # join placeholders for multi-tag scan
        #$tags = $this->tags_to_scan[0];
        $tags = join('|', $this->tags_to_scan);

        # setup search/replace arrays
        $search  = array('__TAGS__', '__LD__', '__RD__');
        $replace = array($tags, self::L_DELIMITER, self::R_DELIMITER);

        # replace tags and delimiters in regexp pattern
        $pattern = str_replace($search, $replace, self::REGEXP);

        # parse file by lines
        foreach($filecontent as $line => $line_content)
        {
            # grab the prefixed tags
            preg_match_all($pattern, $line_content, $matches);

            # no match
            if(empty($matches))
            {
                continue;
            }

            # correct line number, because file[line1] = array[0]
            $calc_line = 1 + $line;

            foreach($matches[3] as $match)
            {
                /**
                 *  $data array has the following structure
                 *  array('language-string') => array([0] => 'file:line')
                 */
                 $data[$match][] = $pathinfo['basename'] . ':' . $calc_line;

                unset($match);
            }
        }
        unset($filecontent);

        return $data;
    }
}
?>