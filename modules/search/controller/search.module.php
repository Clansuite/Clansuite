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
    * @version    SVN: $Id: about.module.php 4744 2010-09-26 23:13:04Z vain $
    */

//Security Handler
if(defined('IN_CS') === false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

/**
 * Clansuite_Module_News
 *
 * @category    Clansuite
 * @package     Modules
 * @subpackage  Toolbox
 */
class Clansuite_Module_Search extends Clansuite_Module_Controller
{

    public function initializeModule()
    {
        # read module config
        $this->getModuleConfig();
    }

    public function action_show()
    {
        $this->display();
    }

    public function action_multisearch()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Breadcrumb::add( _('Show'), '/search/multisearch');

        #Clansuite_Debug::printR( $_POST );

        $qmod = $this->request->getParameterFromPost('qmod');
        $qmodOut = ucwords($qmod);
        $qfields = $this->request->getParameterFromPost('qfields');
        $qfieldsOut = str_replace( ',', ', ', $qfields);
        $qstring = trim($this->request->getParameterFromPost('q'));
        $qstringOut = $qstring;

        # Get Render Engine
        $view = $this->getView();

        $view->assign('qstring', $qstringOut);
        $view->assign('qmod', $qmodOut);
        $view->assign('qfields', $qfieldsOut);

        $this->display();
    }

    public function widget_multisearch()
    {
    }

}
?>