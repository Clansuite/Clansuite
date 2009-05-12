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
    * @author     Jens-André Koch <vain@clansuite.com>
    * @copyright  Jens-André Koch (2005 - onwards)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    *
    * @version    SVN: $Id$
    */

// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}

/**
 * Clansuite Filter - Theme via URL
 *
 * Purpose: Sets Theme via URL by appendix $_GET['theme']
 * Usage example: index.php?theme=themename
 * When request parameter 'theme' is set, the user session value for theme will be updated
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Filters
 * @implements  Clansuite_Filter_Interface
 */
class theme_via_get implements Clansuite_Filter_Interface
{
    private $config     = null;
    private $input      = null;

    public function __construct(Clansuite_Config $config, Clansuite_Inputfilter $input)
    {
       $this->config    = $config;
       $this->input     = $input;
    }

    public function executeFilter(Clansuite_HttpRequest $request, Clansuite_HttpResponse $response)
    {
        // take the initiative, if themeswitching is enabled in CONFIG
        // or pass through (do nothing)
        if($this->config['switches']['themeswitch_via_url'] == 1)
        {
            if(isset($request['theme']) && !empty($request['theme']))
            {
                #@todo debug traceing message
                #echo 'processing themefilter';

            	// Security Handler for $_GET['theme']
            	// Allowed Chars: abc, 0-9, underscore
            	if( !$this->input->check( $request['theme'], 'is_abc|is_int|is_custom', '_' ) )
                {
                    // @todo umstellen auf thrown Exception
                    $this->input->display_intrusion_warning();
                }

                // If $_GET['theme'] dir exists, set it as session-user-theme
                if(is_dir(ROOT_THEMES . '/' . $request['theme'] . '/'))
                {
                    $_SESSION['user']['theme']          = $request['theme'];
                    $_SESSION['user']['theme_via_url']  = 1;
                }
                else
                {
                    $_SESSION['user']['theme_via_url']  = 0;
                }
            }// else => no "?theme=xy" appendix => bypass
        }// else => bypass
    }
}
?>