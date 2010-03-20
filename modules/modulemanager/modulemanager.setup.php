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
    * @version    SVN: $Id: news.module.php 2672 2009-01-09 01:51:22Z vain $
    */

//Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}

/**
 * Module:       Modulemanager Setup
 *
 * @author     Florian Wolf <xsign.dll@clansuite.com>
 * @copyright  Copyleft: All rights reserved. Florian Wolf (2006-onwards)
 *
 * @category    Clansuite
 * @package     Modules
 * @subpackage  Modulemanager
 */
class Clansuite_Module_Modulemanager_Setup extends Clansuite_Module_Controller implements Clansuite_Module_Interface
{
    /**
     * Module_Modulemanager_Setup -> Execute
     */
    public function execute(Clansuite_HttpRequest $request, Clansuite_HttpResponse $response)
    {
        # read module config
        $this->config->readConfig( ROOT_MOD . 'modulemanager/modulemanager.config.php');

        $this->loadModuleInformations( ROOT_MOD . 'modulemanager/modulemanager.info.php');
    }

    /**
     * Loads the module informations from file
     *
     * @param $info_file Informations File to Load
     */
    public function loadModuleInformations(Clansuite_ModuleInformationsController $modInfoController, $info_file)
    {
        require $info_file;

        #$modInfoController
        #$this->moduleInformations = array();

    }

    /**
     * Install the module
     */
    public function install()
    {

    }

    /**
     * Uninstall the module
     */
    public function uninstall()
    {

    }
}
?>