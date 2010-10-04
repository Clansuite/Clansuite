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
    * @author     Jens-André Koch <vain@clansuite.com>
    * @copyright  Jens-André Koch (2005 - onwards)
    * @link       http://www.clansuite.com
    *
    * @version    SVN: $Id$
    */

# Security Handler
if(defined('IN_CS') === false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

/**
 * Clansuite_Module_Languages_Admin
 *
 * @category    Clansuite
 * @package     Modules
 * @subpackage  Languages
 */
class Clansuite_Module_Languages_Admin extends Clansuite_Module_Controller
{
    public function initializeModule()
    {

    }

    public function action_admin_show()
    {
        $view = $this->getView();

        $gettext_extractor = new Clansuite_Gettext_Extractor();
        $gettext_extractor->doScan(ROOT_MOD . 'languages/view/smarty/action_admin_show.tpl');
        Clansuite_Debug::firebug($gettext_extractor);

        #$view->themes = $themes;

        $this->display();
    }
}
?>