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
 * @category    Clansuite
 * @package     Modules
 * @subpackage  Systeminfo
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
        # Set Pagetitle and Breadcrumbs - not needed
        # Clansuite_Trail::addStep( _('Show'), '/index.php?mod=sysinfo&amp;action=show');

        // Set Layout Template
        $this->getView()->setLayoutTemplate('index.tpl');

        $sysinfo = array_merge($this->assembleSystemInfos(),
                               $this->assembleDatabaseInfos());

        $this->getView()->assign('sysinfos', $sysinfo);
        unset($sysinfo);

        # Prepare the Output
        $this->prepareOutput();
    }

    /**
     * Assemble the complete stack of System Informations
     *
     * @return array
     */
    private function assembleSystemInfos()
    {
        # get system informations and server variables

        # WEBSERVER
        if ( is_callable('apache_get_version') )
        {
            $sysinfos['apache_get_version'] = apache_get_version();
            $sysinfos['apache_modules']     = apache_get_modules();
            asort($sysinfos['apache_modules']);
        }

        # fetch server's IP address and it's name
        $sysinfos['server_ip']   = gethostbyname($_SERVER['SERVER_NAME']);
        $sysinfos['server_name'] = gethostbyaddr($sysinfos['server_ip']);

        # PHP
        # Get Interface Webserver<->PHP (Server-API)
        $sysinfos['php_sapi_name']      = php_sapi_name();

        # Is the SERVER-API an CGI (until PHP 5.3) or CGI_FCGI?
        if ( substr($sysinfos['php_sapi_name'], 0, 3) == 'cgi') # this will take care of 'cgi' and 'cgi-fcgi'
        {
            $sysinfos['php_sapi_cgi'] = true;
        }

        $sysinfos['php_uname']                    = php_uname();
        $sysinfos['php_os']                       = PHP_OS;
        $sysinfos['php_os_bit']                   = (PHP_INT_SIZE * 8).'Bit';
        $sysinfos['php_sapi']                     = PHP_SAPI; # @todo check out, if this is the same as php_sapi_name?
        $sysinfos['phpversion']                   = phpversion();
        $sysinfos['php_extensions']               = get_loaded_extensions();
        asort($sysinfos['php_extensions']);
        $sysinfos['zendversion']                  = zend_version();
        $sysinfos['path_to_phpini']               = php_ini_loaded_file();
        $sysinfos['cfg_include_path']             = get_cfg_var('include_path');
        $sysinfos['cfg_file_path']                = realpath(get_cfg_var("cfg_file_path"));
        $sysinfos['zend_thread_safty']            = (int) function_exists('zend_thread_id');
        $sysinfos['safe_mode']                    = (int) ini_get('safe_mode');
        $sysinfos['open_basedir']                 = (int) ini_get('open_basedir');
        $sysinfos['memory_limit']                 = ini_get('memory_limit');
        $sysinfos['allow_url_fopen']              = (int) ini_get('allow_url_fopen');
        $sysinfos['allow_url_include']            = (int) ini_get('allow_url_include');
        $sysinfos['file_uploads']                 = ini_get('file_uploads');
        $sysinfos['upload_max_filesize']          = ini_get('upload_max_filesize');
        $sysinfos['post_max_size']                = ini_get('post_max_size');
        $sysinfos['disable_functions']            = (int) ini_get('disable_functions');
        $sysinfos['disable_classes']              = (int) ini_get('disable_classes');
        $sysinfos['enable_dl']                    = (int) ini_get('enable_dl');
        $sysinfos['magic_quotes_gpc']             = (int) ini_get('magic_quotes_gpc');
        $sysinfos['register_globals']             = (int) ini_get('register_globals');
        $sysinfos['filter_default']               = ini_get('filter.default');
        $sysinfos['zend_ze1_compatibility_mode']  = (int) ini_get('zend.ze1_compatibility_mode');
        $sysinfos['unicode_semantics']            = (int) ini_get('unicode.semantics');
        $sysinfos['mbstring_func_overload']       = ini_get('mbstring.func_overload');
        $sysinfos['max_input_time']               = ini_get('max_input_time');
        $sysinfos['max_execution_time']           = ini_get('max_execution_time');

        return $sysinfos;
    }

    /**
     * Fetch Database infos from PDO
     *
     * @return array
     */
    private function assembleDatabaseInfos()
    {
        # get PDO Object from Doctrine
        $pdo = Doctrine_Manager::connection()->getDbh();
        # fetch PDO::getAttributes and store them in
        $sysinfos['pdo']['driver_name']        = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
        $sysinfos['pdo']['server_version']     = $pdo->getAttribute(PDO::ATTR_SERVER_VERSION);
        $sysinfos['pdo']['client_info']        = $pdo->getAttribute(PDO::ATTR_CLIENT_VERSION);
        # Driver does not support this function: driver does not support that attribute
        # $sysinfos['pdo']['timeout']          = $pdo->getAttribute(PDO::ATTR_TIMEOUT);
        # $sysinfos['pdo']['prefetch']         = $pdo->getAttribute(PDO::ATTR_PREFETCH);
        $sysinfos['pdo']['oracle_nulls']       = $pdo->getAttribute(PDO::ATTR_ORACLE_NULLS);
        $sysinfos['pdo']['connection_status']  = $pdo->getAttribute(PDO::ATTR_CONNECTION_STATUS);
        $sysinfos['pdo']['persistent']         = (int) $pdo->getAttribute(PDO::ATTR_PERSISTENT);
        $sysinfos['pdo']['attr_case']          = $pdo->getAttribute(PDO::ATTR_CASE);
        $sysinfos['pdo']['server_infos']       = explode('  ', $pdo->getAttribute(PDO::ATTR_SERVER_INFO));

        return $sysinfos;
    }

    /**
     * action_admin_return_ofc_hitrates()
     *
     * the function returns dynamic data (hitrate of apc) and visualizes an piechart with ofc.
     * function consists of 4 segments:
     * (1) get data
     * (2) init ofc
     * (3) draw the chart
     * (4) render it
     *
     * @return dynamic data for an open flash chart
     */
    public function action_admin_return_ofc_hitrates()
    {
        /**
         * (1) get DATA for Visualization
         */

        # get apc cache
        $cache_apc = Clansuite_Cache_Factory::getCache('apc', $this->getInjector());
        $apc_stats = $cache_apc->stats();

        # debug display of the stats data
        # var_dump($apc_stats);

        # setup the data array
        $data[] = $apc_stats['cache_info']['num_hits'];
        $data[] = $apc_stats['cache_info']['num_misses'];

        /**
         * (2) initialize Open Flash Chart
         */
        require 'libraries/ofc/php-ofc-library/open-flash-chart.php';
        $g = new graph();

        /**
         * (3) draw the ofc chart
         */
        # title
        $g->title( 'APC Hitrate', '{font-size:18px; color: #d01f3c}' );

        # ok, now draw one piece of the pie :)
        $g->pie(60,'#505050','{font-size: 11px; color: #404040;');

        /**
         * we have to pass in 2 arrays
         * (1) $data
         * (2) labels for the data
         */
        $g->pie_values( $data, array('Hits','Misses') );

        # colours for each slice (hits = green, misses = red)
        $g->pie_slice_colours( array('#acb132','#d01f3c') );

        # mouseover tooltip displayes the values
        $g->set_tool_tip( '#val#' );

        /**
         * (4) output/generate the dynamic data for the swf
         */

        echo $g->render();

        # eject here unnicely, because of headers exist error
        # @todo debug and find out, where after $g->render any output is done
        exit();
    }

    /**
     * The action_admin_show method for the sysinfo module
     * @param void
     * @return void
     */
    public function action_admin_show_apc()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Trail::addStep( _('Alternative PHP Cache'), '/index.php?mod=sysinfo&amp;action=showapc');

        # Get APC Cache
        $cache_apc = Clansuite_Cache_Factory::getCache('apc', $this->getInjector());

        # Assign Data to the View
        $this->getView()->assign('apc_sysinfos', $cache_apc->stats());

        # Set Layout Template
        $this->getView()->setLayoutTemplate('index.tpl');

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
        $this->getView()->setLayoutTemplate('index.tpl');

        # Prepare the Output
        $this->prepareOutput();
    }
}
?>