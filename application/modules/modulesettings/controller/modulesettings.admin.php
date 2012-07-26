<?php

/**
 * Clansuite - just an eSports CMS
 * Jens-André Koch © 2005 - onwards
 * http://www.clansuite.com/
 *
 * This file is part of "Clansuite - just an eSports CMS".
 *
 * License: GNU/GPL v2 or any later version, see LICENSE file.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 */

namespace Clansuite\Module;

/**
 * Clansuite_Module_Modulemanager_Admin
 *
 * @category    Clansuite
 * @package     Modules
 * @subpackage  Modulemanager
 */
class Modulesettings_Admin extends Controller
{
    public function _initializeModule()
    {
        $this->getModuleConfig();
    }

    /**
     * Show the modulemanager
     */
    public function action_admin_list()
    {
        $success = $error = array();

        // Permission check
        #$Clansuite_ACL::checkPermission('modulesettings.action_admin_show');

        // Set Pagetitle and Breadcrumbs
        Clansuite_Breadcrumb::add( _('Show'), '/modulesettings/admin/show');

        $formdata = array();
        $modulename = $this->request->getParameterFromGet('modulename');

        // Get Render Engine
        $view = $this->getView();
        $view->assign( 'modulename', $modulename );

        /* ---------------------------------------------------------
         Form Submitted
        --------------------------------------------------------- */
        if( $this->request->issetParameter('submit', 'POST')) $submitted = true; else $submitted = false;

        if ($submitted === true) {
            // get parameter for module data
            $configfile = $this->request->getParameter('mod_settings_configfile');

            if ( !is_writeable( ROOT_MOD.$modulename. DIRECTORY_SEPARATOR .$modulename.'.config.php' ) ) {
                $error['mod_config_not_writeable'] = true;
            } else {
                file_put_contents(ROOT_MOD.$modulename. DIRECTORY_SEPARATOR .$modulename.'.config.php', utf8_decode($configfile) );
                $success['mod_config_success'] = true;
            }

        }
        /* ---------------------------------------------------------
         else
        --------------------------------------------------------- */

        // --------------------------------------------
        //  read module config file
        // --------------------------------------------
        if ( file_exists( ROOT_MOD.$modulename. DIRECTORY_SEPARATOR .$modulename.'.config.php' )) {
            $configfile = file_get_contents( ROOT_MOD.$modulename. DIRECTORY_SEPARATOR .$modulename.'.config.php' );
        } else {
            $configfile = '';
        }
        $view->assign( 'mod_settings_configfile', $configfile );

        // --------------------------------------------
        //  read module info file
        // --------------------------------------------
        if ( file_exists( ROOT_MOD.$modulename. DIRECTORY_SEPARATOR .$modulename.'.info.php' )) {
            $infofile = file_get_contents( ROOT_MOD.$modulename. DIRECTORY_SEPARATOR .$modulename.'.info.php' );
        } else {
            $infofile = '';
        }
        $view->assign( 'mod_settings_infofile', utf8_encode($infofile) );

        // --------------------------------------------
        //  read routes
        // --------------------------------------------

        // --------------------------------------------
        //  output
        // --------------------------------------------
        $view->assign('error', $error);
        $view->assign('success', $success);
        $this->display();
    }

}
