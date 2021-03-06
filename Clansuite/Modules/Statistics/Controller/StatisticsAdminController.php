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
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace Clansuite\Modules\Statistics\Controller;

use Clansuite\Core\Mvc\ModuleController;

/**
 * Clansuite_Module_Statistics_Admin
 *
 * @category    Clansuite
 * @package     Modules
 * @subpackage  Statistics
 */
class StatisticsAdminController extends ModuleController
{
    public function actionList()
    {
        $view = $this->getView();

        $view->assign('stuff', '123');

        $this->display();
    }

    public function actionSettings()
    {
        // Set Pagetitle and Breadcrumbs
        \Koch\View\Helper\Breadcrumb::add( _('Settings'), '/statistics/admin/settings');

        $settings = array();

        $settings['form']   = array(    'name' => 'statistic_settings',
                                        'method' => 'POST',
                                        'action' => '/statistics/admin/settingsUpdate');

        $settings['statistics'][] = array(        'id' => 'deleteTimeWho',
                                                  'name' => 'deleteTimeWho',
                                                'label' => 'delete Time',
                                                'description' => _('Delete time of old database entries for the WhoIsOnline table. Value in days. After x days delete old entries !'),
                                                'formfieldtype' => 'text',
                                                'value' => self::getConfigValue('deleteTimeWho', '1'));

        $settings['statistics'][] = array(        'id' => 'timoutWho',
                                                'name' => 'timoutWho',
                                                'label' => 'Online Timeout',
                                                'description' => _('Defines the timeout for a user or guest. Value in minutes.'),
                                                'formfieldtype' => 'text',
                                                'value' => self::getConfigValue('timoutWho', '5'));

        // fill the settings array into the formgenerator
        $form = new Clansuite_Form($settings);
        // add additional buttons to the form
        $form->addElement('submitbutton')->setName('Save');
        $form->addElement('resetbutton');

        // assign the html of the form to the view
        $this->getView()->assign('form', $form->render());

        $this->display();
    }

    public function actionSettingsUpdate()
    {
        // Incomming Data
        // @todo get post via request object, sanitize
        $data = $this->request->getParameter('statistic_settings');

        // Get Configuration from Injector and write Config
        $this->getInjector()->instantiate('Clansuite_Config')->writeModuleConfig($data);

        // clear the cache / compiled tpls
        $this->getView()->clearCache();

        // Redirect
        $this->response->redirectNoCache(WWW_ROOT . '/statistics/admin/settings', 2, 302, 'The config file has been successfully updated.');
    }
}
