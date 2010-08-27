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
    * @version    SVN: $Id: downloads.module.php 2753 2009-01-21 22:54:47Z vain $
    */

# Security Handler
if(defined('IN_CS') === false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

/**
 * Clansuite_Module_Downloads
 *
 * @category    Clansuite
 * @package     Modules
 * @subpackage  Downloads
 */
class Clansuite_Module_Downloads extends Clansuite_Module_Controller
{
    public function initializeModule()
    {
        parent::initModel('downloads');
    }

    public function action_show()
    {
        // Set Pagetitle and Breadcrumbs
        Clansuite_Breadcrumb::add( _('Show'), '/index.php?mod=downloads&amp;action=show');

        # fetch nextmatches
        $downloads = Doctrine::getTable('CsDownloads')->findAll()->toArray();

        #Clansuite_Debug::printr($downloads);

        $view = $this->getView();
        $view->assign('downloads', $downloads);

        $this->display();
    }

    /**
     * Widget LatestFiles
     *
     * @param integer $number Number of Files to fetch
     */
    public function widget_latestfiles($number)
    {
        $this->getView()->assign('widget_latestfiles', Doctrine::getTable('CsDownloads')->fetchLatestFiles($number));
    }

    /**
     * Widget TopFiles
     *
     * @param integer $number Number of Files to fetch
     */
    public function widget_topfiles($number)
    {
        $this->getView()->assign('widget_topfiles', Doctrine::getTable('CsDownloads')->fetchTopFiles($number));
    }
}
?>