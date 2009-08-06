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
    * @version    SVN: $Id: renderer.base.core.php 2614 2008-12-05 21:18:45Z vain $
    */

//Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}

/**
 * Clansuite
 *
 * Module:       Admin Settings
 *
 * @package clansuite
 * @subpackage module_admin_settings
 * @category modules
 */
class Module_Settings_Admin extends Clansuite_ModuleController implements Clansuite_Module_Interface
{
    public function execute(Clansuite_HttpRequest $request, Clansuite_HttpResponse $response)
    {
    }

    /**
     * action_settings_show
     */
    function action_admin_show()
    {
        # Set Pagetitle and Breadcrumbs
        #Clansuite_Trail::addStep( _('Show'), '/index.php?mod=settings&sub=admin');

        # Get Render Engine
        $view = $this->getView();

        # Get Configuration from Injector
        $config = $this->injector->instantiate('Clansuite_Config')->toArray();

        # Assign array with all cache adapters to smarty
        $cache_adapters = array('apc', 'memcached', 'xcache', 'eaccelerator', 'file-based');
        $view->assign('cache_adapters', $cache_adapters);

        $timezones = array('Berlin', 'Rio');
        $view->assign('timezones', $timezones);

        # Assign Config to Smarty
        $view->assign('config', $config);

        # Set Admin Layout Template
        $view->setLayoutTemplate('index.tpl');

        # Specifiy the template manually
        $this->setTemplate('settings.tpl');

        # Prepare the Output
        $this->prepareOutput();
    }

    /**
     * action_settings_update
     */
    function action_admin_update()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Trail::addStep( _('Update'), '/index.php?mod=controlcenter&amp;sub=settings&amp;action=update');

        # Incomming Data
        # @todo get post via request object, sanitize
        $data = $_POST['config'];

        # Get Configuration from Injector
        $config = $this->injector->instantiate('Clansuite_Config');

        #clansuite_xdebug::printr($config->confighandler);

        $config->confighandler->writeConfig( ROOT_CONFIG . 'clansuite.config.php', $data);

        # Redirect
        $this->redirect('index.php?mod=settings&amp;sub=admin');
        #'metatag|newsite', 3, $lang->t( 'The config file has been succesfully updated...' ), 'admin' );
    }
}
?>