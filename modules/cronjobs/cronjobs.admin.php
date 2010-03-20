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
    * @link       http://gna.org/projects/
    *
    * @version    SVN: $Id: index.module.php 2873 2009-03-27 01:50:12Z vain $
    */

// Security Handler
if (!defined('IN_CS')){die('Clansuite not loaded. Direct Access forbidden.');}

/**
 * Clansuite Administration Module - Cronjobs
 *
 * Purpose: The Cronjob Administration Module provides the GUI and Actions for the administration of repetitive tasks.
 */
class Module_Cronjobs_Admin extends Clansuite_ModuleController implements Clansuite_Module_Interface
{
    /**
     * Constructor with call to ModuleController as Parent
     */
    public function __construct(Phemto $injector=null)
    {
        parent::__construct(); # run constructor on controller_base
    }

    /**
     * Module_Cronjobs_Admin->Execute()
     *
     * Sets up module specific stuff, needed by all actions of the module
     */
    public function execute(Clansuite_HttpRequest $request, Clansuite_HttpResponse $response)
    {

    }

    /**
     * Overview of Cronjobs
     */
    public function action_admin_show()
    {
        # Applying a Layout Template
        $view = $this->getView()->setLayoutTemplate('index.tpl');

        $cronjobs = array();

        $this->getView()->assign('cronjobs', $cronjobs);

        # Prepare the Output
        $this->prepareOutput();
    }
}
?>