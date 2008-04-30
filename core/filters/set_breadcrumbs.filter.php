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

// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}

/**
 * Clansuite Filter - Set Breadcrumbs
 *
 * Purpose: Sets the Breadcrumbs
 *
 * @package clansuite
 * @subpackage filters
 * @implements FilterInterface
 */
class set_breadcrumbs implements Filter_Interface
{
    private $config     = null;     # holds instance of config

    function __construct(configuration $config)
    {
       $this->config    = $config;      # set instance of config to class
    }

    function cut_string($haystack, $needle)
    {
        $needle_length = strlen($needle);

        if(($i = strpos($haystack,$needle) !== false))
        {
            return substr($haystack, 0, -$needle_length);
        }
        return $haystack;
    }

    public function executeFilter(httprequest $request, httpresponse $response)
    {
        $moduleName     = Clansuite_ModuleController_Resolver::getModuleName();     # $request->getParameter('mod');
        $submoduleName  = Clansuite_ModuleController_Resolver::getSubModuleName();  # $request->getParameter('sub');
        $actionName     = $request->getParameter('action');

        # add module Part
        if(strlen($moduleName) > 0)
        {   
            # Strip String ModuleName at "_admin", example: guestbook_admin
            #$moduleName = strstr($moduleName, '_Admin', true);     # php6
            $moduleName = $this->cut_string($moduleName, '_admin');
            
            # Strip String ModuleName at "admin_", example: admin_menueditor
            if(strpos($moduleName,'admin_')!==false) 
            {
                #$moduleName = substr($moduleName, 6);
                $moduleName_exploded = explode("_", $moduleName);
                $moduleName = $moduleName_exploded[0];
            }

            # BASE
            $URL  = '/index.php';
            $URL .= '?mod=' . $moduleName;
            $trailName = $moduleName;

            # Add action Part only, if not no submodule following
            if( (strlen($actionName) > 0) && (strlen($submoduleName) == 0))
            {
                $URL .= '&amp;action=' . $actionName;
            }

            # Set Pagetitle and Breadcrumbs for that Module
            # >> MODULENAME
            trail::addStep( T_( ucfirst($trailName) ), $URL );            
        }

        # add submodule part
        if(strlen($submoduleName) > 0)
        {
            $URL .= '&amp;sub=' . $submoduleName;
            $trailName = $submoduleName;

            # Add action Part now
            if(strlen($actionName) > 0)
            {
                $URL .= '&amp;action=' . $actionName;
            }

            # Set Pagetitle and Breadcrumbs for that Module
            # >> SUBMODULENAME
            trail::addStep( T_( ucfirst($trailName) ), $URL );
        }
    }
}
?>