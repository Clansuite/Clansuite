<?php
/**
* settings
* This is the Admin Control Panel
*
* PHP >= version 5.1.4
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
*    You should have received a copy of the GNU General Public License
*    along with this program; if not, write to the Free Software
*    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*
* @author     Jens-Andre Koch <vain@clansuite.com>
* @copyright  2006 Clansuite Group
* @link       http://gna.org/projects/clansuite
*
* @author     Jens-André Koch
* @copyright  Clansuite Group
* @license    GPL
* @version    SVN: $Id: settings.module.php 2105 2008-06-25 15:49:38Z vain $
* @link       http://www.clansuite.com
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
class Module_Settings_Admin extends ModuleController implements Clansuite_Module_Interface
{
    public function execute(httprequest $request, httpresponse $response)
    {
        # proceed to the requested action
        $this->processActionController($request);
    }

    /**
     * action_settings_show
     */
    function action_admin_show()
    {
        # Set Pagetitle and Breadcrumbs
        trail::addStep( _('Show'), '/index.php?mod=admin&amp;sub=settings&amp;action=show');

        # Get Render Engine
        $smarty = $this->getView();

        # Get Configuration from Injector
        $config = $this->injector->instantiate('Clansuite_Config')->toArray();

        # Assign Config to Smarty
        $smarty->view->assign('config', $config);

        # Set Admin Layout Template
        $smarty->setLayoutTemplate('admin/index.tpl');

        # Specifiy the template manually
        $this->setTemplate('settings.tpl');

        # Prepare the Output
        $this->prepareOutput();
    }

    /**
     * action_settings_update
     */
    function action_settings_update()
    {
        # Set Pagetitle and Breadcrumbs
        trail::addStep( _('Update'), '/index.php?mod=admin&amp;sub=settings&amp;action=update');

        # Incomming Data
        # @todo get post via request object, sanitize
        $data = $_POST['config'];

        # Get Configuration from Injector
        $config = $this->injector->instantiate('Clansuite_Config');

        $config->writeConfig( ROOT . 'clansuite.config.php', $data);

        # Redirect
        header('index.php?mod=admin&sub=settings'); #'metatag|newsite', 3, $lang->t( 'The config file has been succesfully updated...' ), 'admin' );
    }
}
?>