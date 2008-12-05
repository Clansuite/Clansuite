<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-Andr� Koch � 2005 - onwards
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
    * @author     Jens-Andr� Koch   <vain@clansuite.com>
    * @author     Florian Wolf      <xsign.dll@clansuite.com>
    * @copyright  Copyleft: All rights reserved. Jens-Andr� Koch (2005-onwards)
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
 *  Clansuite Core Class for Trail / Breadcrumb Handling
 *
 * - Headings: Entrance >> News
 * - You are here : Entrance >> News (with links)
 *
 * @author     Quentin Zervaas
 * @author     Jens-Andr� Koch   <vain@clansuite.com>
 * @author     Florian Wolf      <xsign.dll@clansuite.com>
 * @copyright  Jens-Andr� Koch (2005-onwards), Florian Wolf (2006-2007)
 * @since      Class available since Release 0.1
 *
 * @package     clansuite
 * @category    core
 * @subpackage  trail
 */
class Clansuite_Trail
{
        /**
         * @access private
         * @var array $path contains the complete path structured as array
         */
        private static $path = array();

        /**
         * Executed by addstep and assigns "Home >>"
         *
         * @param string $homeLabel contains the Home-Name shown at the trail, standard is Home
         * @param string $homeLink contains the link as url, standard is '/' refering to base_url
         */
        public static function addHomeTrail($homeLabel = 'Home', $homeLink = '/')
        {
            # check if it's the first trail step, then let it be >> HOME
            if(count(self::$path) == 0)
            {
                self::$path[] = array(  'title' => $homeLabel,
                                        'link'  => WWW_ROOT . $homeLink);
            }
        }

        /**
         * This adds a step or level to the trail-path / pagetitle
         *
         * @param string $title contains the Name shown at the trail
         * @param string $link contains the link as url
         */
        public static function addstep($title, $link = '')
        {
            # let the first Trail, be "HOME >>"
            self::addHomeTrail();

            $item = array('title' => $title);

            if (strlen($link) > 0)
            {
                $item['link'] = WWW_ROOT . $link;
            }

            self::$path[] = $item;
        }

        /**
         * Get Method for the Trail
         */
        public static function getTrail()
        {
            return self::$path;
        }
}
?>