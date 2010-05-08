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

# Security Handler
if(defined('IN_CS') == false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

/**
 * Clansuite Filter - Set Breadcrumbs
 *
 * Purpose: Sets the Breadcrumbs
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Filters
 * @implements  Clansuite_Filter_Interface
 */
class Clansuite_Filter_SetBreadcrumbs implements Clansuite_Filter_Interface
{
    public function executeFilter(Clansuite_HttpRequest $request, Clansuite_HttpResponse $response)
    {
        $moduleName     = Clansuite_Module_Controller_Resolver::getModuleName();
        $submoduleName  = Clansuite_Module_Controller_Resolver::getSubModuleName();
        $actionName     = Clansuite_Action_Controller_Resolver::getActionName();

        /**
         *  This adds the FIRST PART of the TRAIL.
         *  We have 3 cases.
         */
        if(strlen($moduleName) >= 0 and ($moduleName != 'controlcenter') and ($submoduleName != 'admin'))
        {
            Clansuite_Breadcrumb::addHomeTrail();
        }

        if(strlen($moduleName) > 0 and ($moduleName == 'controlcenter'))
        {
            # Set Pagetitle "Control Center" and Breadcrumb-Link = '/index.php?mod=controlcenter'
            Clansuite_Breadcrumb::addControlCenterTrail();
        }

        if(strlen($submoduleName) > 0 and ($submoduleName == 'admin'))
        {
            # Set Pagetitle "Control Center"" and Breadcrumb-Link = '/index.php?mod=controlcenter'
            Clansuite_Breadcrumb::addControlCenterTrail();
        }

        /**
         * This adds the SECCOND PART of the TRAIL.
         */
        if(strlen($moduleName) > 0  and ($moduleName != 'controlcenter'))
        {
            # Construct URL
            # BASE URL
            $url  = '/index.php?mod=' . $moduleName;
            $trailName = $moduleName;

            # Add action Part only, if not no submodule following
            if( (strlen($actionName) > 0) and (strlen($submoduleName) == 0))
            {
                $url .= '&amp;action=' . $actionName;
            }

            # if this is an request to an submodule admin, we append that to the URL
            if( (strlen($submoduleName) > 0)  and ($submoduleName == 'admin'))
            {
                $url .= '&amp;sub=admin';
            }

            # Set Pagetitle and Breadcrumbs for that Module
            Clansuite_Breadcrumb::add( T_( ucfirst($trailName) ), $url );
        }

        # add submodule part
        if(strlen($submoduleName) > 0 and ($submoduleName != 'admin'))
        {
            # Construct URL
            $url .= '&amp;sub=' . $submoduleName;
            $trailName = $submoduleName;

            # Add action Part now
            if(strlen($actionName) > 0)
            {
                $url .= '&amp;action=' . $actionName;
            }

            # Set Pagetitle and Breadcrumbs for that SubModule
            Clansuite_Breadcrumb::add( T_( ucfirst($trailName) ), $url );
        }
    }
}
?>