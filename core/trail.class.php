<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-Andre Koch © 2005-2007
    * http://www.clansuite.com/
    *
    * File:         trail.class.php
    * Requires:     PHP 5.1.4+
    *
    * Purpose:      Clansuite Core Class for Trail / Breadcrumb Handling
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
 * This Clansuite Core Class for Trail / Breadcrumb Handling
 *
 * - Headings: Entrance >> News
 * - You are here : Entrance >> News (with links)
 *
 * @author     Quentin Zervaas
 * @author     Jens-Andre Koch   <vain@clansuite.com>
 * @author     Florian Wolf      <xsign.dll@clansuite.com>
 * @copyright  Jens-Andre Koch (2005-$LastChangedDate$), Florian Wolf (2006-2007)
 * @since      Class available since Release 0.1
 *
 * @package     clansuite
 * @category    core
 * @subpackage  trail
 */
class trail
{
        /**
         * @access private
         * @var array $path contains the complete path structured as array
         */

        public $path = array();

        /**
         * Control Variable for the adding of trails
         * if not set to '0' trail adding is stopped
         * This is used for instant loading of modules via {mod}.
         * @see modules::get_instant_content()
         *
         * @access public
         * @var string $trail_stop trail adding can be stopped if false
         */

        public $trail_stop = false;

        /**
         * CONSTRUCTOR
         *
         * This is auto-executed at the initialization of the class trail
         * and assigns "Home >>"
         *
         * @param bool $homeArea if true, adds the home-level at first
         * @param string $homeLabel contains the Home-Name shown at the trail, standard is Home
         * @param string $homeLink contains the link as url, standard is '/' refering to base_url
         */

        function trail($homeArea = true, $homeLabel = 'Home', $homeLink = '/')
        {
            if ($homeArea == true )
            {
                $this->addStep($homeLabel, $homeLink);
            }
        }

        /**
         * This adds a step or level to the trail-path / pagetitle
         *
         * @param string $title contains the Name shown at the trail
         * @param string $link contains the link as url
         */

        public function addstep($title, $link = '')
        {
           /**
            * Add step procedure is controlled by class variable $trail_stop
            * if not set to '0' the adding of trails is stopped
            * This used for instant loading of modules via {mod}
            */
           if ($this->trail_stop == true) 
           {
                $item = array('title' => $title);

                if (strlen($link) > 0)
                {
                    $item['link'] = WWW_ROOT . $link;
                }

                $this->path[] = $item;                
           }
        }
        
        /**
         * trail_stop toggle         
         * default false
         * @param $condition boolean
         */
        public function trail_stop($condition = false)
        {
          $this->trail_stop = ($condition == true) ? true : false;  
        }       
    }
?>