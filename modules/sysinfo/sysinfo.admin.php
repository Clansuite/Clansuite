<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-Andre Koch (c) 2005 - onwards
    *
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
    * @license    GNU/GPL, see COPYING.txt
    *
    * @author     Jens-Andre Koch <vain@clansuite.com>
    * @copyright  Copyleft: All rights reserved. Jens-Andre Koch (2005-onwards)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    *
    * @version    SVN: $Id: $
    */

/**
 * System Informations Admin Module (Backend)
 * (sysinfo)
 *
 * @license    GPLv2 or any later version
 * @author     Jens-Andre Koch
 * @link       http://www.clansuite.com
 */

// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}

/**
 * @package Clansuite
 * @subpackage module_admin_sysinfo
 */
class Module_Sysinfo_Admin extends ModuleController implements Clansuite_Module_Interface
{
    /**
     * Main Method of sysinfo Module
     *
     * Sets up module specific stuff, needed by all actions of the module
     * Calls the requested Action $_REQUEST['action']
     */
    public function execute(httprequest $request, httpresponse $response)
    {
        # read module config
        $this->config->readConfig( ROOT_MOD . 'sysinfo/sysinfo.config.php');

        # proceed to the requested action
        $this->processActionController($request);
    }

    /**
     * The action_admin_show method for the sysinfo module
     * @param void
     * @return void
     */
    public function action_admin_show()
    {
        # Set Pagetitle and Breadcrumbs
        trail::addStep( _('Show'), '/index.php?mod=sysinfo&amp;action=show');

        # get system informations and server variables

        // WEBSERVER
        $sysinfos['apache_get_version']     = apache_get_version();
        $sysinfos['apache_modules']         = apache_get_modules();

        // PHP
        $sysinfos['php_uname']             = php_uname();
        $sysinfos['php_os']                = PHP_OS;
        $sysinfos['phpversion']            = phpversion();
        $sysinfos['php_extensions'] = get_loaded_extensions();

        // MYSQL
        #$mysql_get_server_info  = mysql_get_server_info();
        #$mysql_get_host_info    = mysql_get_host_info();
        #$mysql_get_client_info  = mysql_get_client_info();
        #$mysql_client_encoding  = mysql_client_encoding($db->connection);
        #$status                 = explode('  ', mysql_stat($db->connection));

        // Set Layout Template
        $this->getView()->setLayoutTemplate('admin/index.tpl');

        $this->getView()->assign('sysinfos', $sysinfos);

        # Prepare the Output
        $this->prepareOutput();
    }

    /**
     * The action_admin_show method for the sysinfo module
     * @param void
     * @return void
     */
    public function action_admin_showapc()
    {
        # Set Pagetitle and Breadcrumbs
        trail::addStep( _('Show'), '/index.php?mod=sysinfo&amp;action=showapc');

        // get ap infos
        Clansuite_Loader::loadCoreClass('clansuite_cache');
        $cache_apc = new Clansuite_Cache_APC();

        // Set Layout Template
        $this->getView()->setLayoutTemplate('admin/index.tpl');

        $this->getView()->assign('apc_sysinfos', $cache_apc->stats());

        # Prepare the Output
        $this->prepareOutput();
    }
}
?>