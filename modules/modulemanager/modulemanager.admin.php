<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-André Koch © 2005 - onwards
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
    * @license    GNU/GPL v2 or (at your option) any later version, see "/doc/LICENSE".
    *
    * @author     Jens-André Koch <vain@clansuite.com>
    * @copyright  Copyleft: All rights reserved. Jens-André Koch (2005-onwards)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    * @since      File available since Release 0.2
    *
    * @version    SVN: $Id: news.admin.php 3747 2009-11-20 14:59:46Z vain $
    */

// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}

/**
 * Clansuite Administration Module - Modulemanager
 *
 * Description: Administration for the Modules
 *
 * @author     Jens-André Koch <vain@clansuite.com>
 * @author     Florian Wolf <xsign.dll@clansuite.com>
 * @copyright  Copyleft: All rights reserved. Florian Wolf (2006-2008).
 * @license    GPL v2 any later version
 *
 * @category    Clansuite
 * @package     Modules
 * @subpackage  Modulemanager
 */

class Module_Modulemanager_Admin extends Clansuite_ModuleController implements Clansuite_Module_Interface
{
    /**
     * Module_Module_Manager -> Execute
     */
    public function execute(Clansuite_HttpRequest $request, Clansuite_HttpResponse $response)
    {
        # read module config
        $this->getModuleConfig();
    }

    /**
     * Get a list of all the module directories
     *
     * @todo figure out, if SPL recursivedirectoryiterator is faster
     * @return array
     */
    private static function getModuleDirsList()
    {
        return glob( ROOT_MOD . '[a-zA-Z]*', GLOB_ONLYDIR);
    }

    /**
     * Show the modulemanager
     */
    public function action_admin_show()
    {
/**
*
* This sourcecode is a property of "Florian 'xsign.dll' Wolf". Every redistribution or use without permission
* is strictly forbidden. The use of this property is effectively forbidden for the "Clansuite" CMS. RIP.
*
* Every overtake/use of my sourcecode will be sued immediately. The base of sueing is 50.000 € (euro) at least.
*
*/
        }
    }
}
?>