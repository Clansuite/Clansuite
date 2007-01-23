<?php
/**
* Breadcrumb Class
*
* PHP versions 5.1.4
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
* @author     Quentin Zervaas
* @author     Jens-Andre Koch <vain@clansuite.com>
* @copyright  2006 Clansuite Group
* @license    see COPYING.txt
* @version    $Id$
* @link       http://gna.org/projects/clansuite
* @since      File available since Release 0.1
*/

// Security Handler
if (!defined('IN_CS'))
{
    die('You are not allowed to view this page statically.' );
}

/**
* This class handles the Breadcrumbs / Trails
* - Headings: Entrance >> News
* - You are here : Entrance >> News (with links)
*/
class trail
    {
        public $path = array();

        // auto-executed at initialization of class trail
        function trail($homeArea = true, $homeLabel = 'Home', $homeLink = '/')
        {
            global $lang;

            if ($homeArea == true )
            {
                $this->addStep($homeLabel, $homeLink);
            }
        }

        // adds a step to the trail-path / pagetitle
        function addstep($title, $link = '')
        {
            $item = array('title' => $title);

            if (strlen($link) > 0)
            {
                $item['link'] = $link;
            }

            $this->path[] = $item;

        }
    }
?>