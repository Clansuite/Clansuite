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
    * @author     Jens-André Koch <vain@clansuite.com>
    * @copyright  Jens-André Koch (2005 - onwards)
    * @link       http://www.clansuite.com
    *
    * @version    SVN: $Id: renderer.base.core.php 2614 2008-12-05 21:18:45Z vain $
    */

# Security Handler
if (defined('IN_CS') === false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

/**
 * Clansuite_Module_Settings_Admin
 *
 * @category    Clansuite
 * @package     Modules
 * @subpackage  Settings
 */
class Clansuite_Module_Settings_Admin extends Clansuite_Module_Controller
{
    /**
     * action_settings_show
     */
    public function action_admin_show()
    {
        # Get Render Engine
        $view = $this->getView();

        # Get Configuration from Injector
        $config = $this->getClansuiteConfig();

        # Assign array with all cache driver to smarty
        # @todo detect available cache drivers via sysinfo.core.php
        $cache_driver = array('apc', 'memcached', 'xcache', 'eaccelerator', 'file-based');
        $view->assign('cache_driver', $cache_driver);

        $timezones = array('Berlin', 'Rio');
        $view->assign('timezones', $timezones);

        # Assign Config to Smarty
        $view->assign('config', $config);

        # Specifiy the template manually
        $this->setTemplate('settings.tpl');

        $this->display();
    }

    /**
     * action_settings_easylist
     */
    public function action_admin_easylist()
    {
        # Get Render Engine
        $view = $this->getView();

        # Get Configuration from Injector
        $config = $this->getClansuiteConfig();

        # Assign array with all cache driver to smarty
        $cache_driver = array('apc', 'memcached', 'xcache', 'eaccelerator', 'file-based');
        $view->assign('cache_driver', $cache_driver);

        $timezones = array('Berlin', 'Rio');
        $view->assign('timezones', $timezones);

        # Assign Config to Smarty
        $view->assign('config', $config);

        $this->display();
    }

    /**
     * action_settings_update
     */
    public function action_admin_update()
    {
        # Incomming Data
        # @todo sanitize
        $data = $_POST['config'];

        # Get Configuration from Injector and main clansuite configuration file
        $config = $this->getInjector()->instantiate('Clansuite_Config');
        $config->writeConfig(ROOT . 'configuration' . DS . 'clansuite.config.php', $data);

        # clear the cache / compiled tpls
        $this->getView()->clearCache();

        # Redirect
        $this->response->redirectNoCache('/settings/admin', 2, 302, 'The config file has been successfully updated.');
    }
}
?>