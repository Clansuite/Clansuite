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
    * @since      File available since Release 0.2
    *
    * @version    SVN: $Id: index.module.php 2625 2008-12-09 00:04:43Z vain $
    */

// Security Handler
if (!defined('IN_CS')){die('Clansuite not loaded. Direct Access forbidden.');}

/**
 * Clansuite Module - Systeminfo
 *
 * Provides: System Informations
 *
 * @license    GPLv2 or any later version
 * @author     Jens-André Koch
 * @link       http://www.clansuite.com
 *
 * @package Clansuite
 * @subpackage module_admin_sysinfo
 */
class Module_Systeminfo_Admin extends Clansuite_ModuleController implements Clansuite_Module_Interface
{
    /**
     * Main Method of sysinfo Module
     *
     * Sets up module specific stuff, needed by all actions of the module
     * Calls the requested Action $_REQUEST['action']
     */
    public function execute(Clansuite_HttpRequest $request, Clansuite_HttpResponse $response)
    {
        # read module config
        $this->getModuleConfig();
    }

    /**
     * The action_admin_show method for the sysinfo module
     * @param void
     * @return void
     */
    public function action_admin_show()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Trail::addStep( _('Show'), '/index.php?mod=sysinfo&amp;action=show');

        # get system informations and server variables

        // WEBSERVER
        $sysinfos['apache_get_version'] = apache_get_version();
        $sysinfos['apache_modules']     = apache_get_modules();

        // PHP
        $sysinfos['php_sapi_name']      = php_sapi_name();
        $sysinfos['php_uname']          = php_uname();
        $sysinfos['php_os']             = PHP_OS;
        $sysinfos['php_sapi']           = PHP_SAPI;
        $sysinfos['phpversion']         = phpversion();
        $sysinfos['php_extensions']     = get_loaded_extensions();
        $sysinfos['zendversion']        = zend_version();
        $sysinfos['path_to_phpini']     = php_ini_loaded_file();
        $sysinfos['cfg_include_path']   = get_cfg_var('include_path');
        $sysinfos['cfg_file_path']      = realpath(get_cfg_var("cfg_file_path"));

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
    public function action_admin_show_apc()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Trail::addStep( _('Show Info - Alternative PHP Cache'), '/index.php?mod=sysinfo&amp;action=showapc');

        # Get APC Cache
        $cache_apc = Clansuite_Cache_Factory::getCache('apc', $this->getInjector());

        # Assign Data to the View
        $this->getView()->assign('apc_sysinfos', $cache_apc->stats());

        # Set Layout Template
        $this->getView()->setLayoutTemplate('admin/index.tpl');

        # Prepare the Output
        $this->prepareOutput();
    }

    public function action_admin_show_logfiles()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Trail::addStep( _('Show'), '/index.php?mod=sysinfo&amp;action=showapc');

        # Get APC Cache
        $cache_apc = Clansuite_Cache_Factory::getCache('apc', $this->getInjector());

        # Assign Data to the View
        $this->getView()->assign('apc_sysinfos', $cache_apc->stats());

        # Set Layout Template
        $this->getView()->setLayoutTemplate('admin/index.tpl');

        # Prepare the Output
        $this->prepareOutput();
    }
}
?>