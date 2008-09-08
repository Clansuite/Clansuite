<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-Andre Koch � 2005 - onwards
    * Florian Wolf � 2006 - onwards
    * http://www.clansuite.com/
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
    * @author     Florian Wolf <xsign.dll@clansuite.com>
    * @copyright  Copyleft: All rights reserved. Jens-Andre Koch (2005-onwards)
    * @copyright  Copyleft: All rights reserved. Florian Wolf (2006-onwards)
    * 
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    * @since      File available since Release 0.2
    *
    * @version    SVN: $Id: $
    */

//Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}

/**
 * Module:       Modulemanager Setup
 *
 * @author     Florian Wolf <xsign.dll@clansuite.com>
 * @copyright  Copyleft: All rights reserved. Florian Wolf (2006-onwards)
 *
 * @package clansuite
 * @subpackage modulemanager
 * @category modulesetup
 */
class Module_Modulemanager_Setup extends ModuleController implements Clansuite_Module_Interface
{
    /**
     * Module_Modulemanager_Setup -> Execute
     */
    public function execute(httprequest $request, httpresponse $response)
    {
        # read module config
        $this->config->readConfig( ROOT_MOD . '/modulemanager/modulemanager.config.php');
        
        # proceed to the requested action
        $this->processActionController($request);
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