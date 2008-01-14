<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-Andre Koch © 2005-2008
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
    * @license    GNU/GPL, see COPYING.txt
    *
    * @author     Jens-Andre Koch <vain@clansuite.com>
    * @copyright  Jens-Andre Koch (2005-2008)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    * @since      File available since Release 0.2
    *
    * @version    SVN: $id$
    */
/**
 * Clansuite Filter - Theme via URL
 *
* @package clansuite
 * @subpackage filters
 *
 * Purpose: Sets Theme via URL by appendix $_GET['theme']
 * Usage example: index.php?theme=themename
 * When request parameter 'theme' is set, the user session value for theme will be updated
 *
 * @implements IFilter
 */
class theme_via_get implements FilterInterface
{
    private $config     = null;
    private $input      = null;
    private $security   = null;
    
    function __construct(configuration $config, input $input, security $security)
    {
       $this->config    = $config;
       $this->input     = $input;
       $this->security  = $security;
    }   
    
    public function execute(httprequest $request, httpresponse $response)
    { 
        // take the initiative, if themeswitching is enabled in CONFIG
        // or pass through (do nothing)
        if($this->config['themeswitch_via_url'] == 1)
        {              
            if(isset($request['theme']) && !empty($request['theme']))
            {   
                #@todo debug traceing message           
                #echo 'processing themefilter';
            	
            	// Security Handler for $_GET['theme']
            	if( !$this->input->check( $request['theme'], 'is_abc|is_custom', '_' ) )
                {   
                    // @todo umstellen auf thrown Exception
                    $this->security->intruder_alert();
                }
                
                // If $_GET['theme'] dir exists, set it as session-user-theme
                if(is_dir(ROOT_TPL . '/' . $request['theme'] . '/'))
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