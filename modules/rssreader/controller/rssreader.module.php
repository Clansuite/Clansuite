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
    * @version    SVN: $Id: rssreader.module.php 2753 2009-01-21 22:54:47Z vain $
    */

//Security Handler
if (defined('IN_CS') == false) { die('Clansuite not loaded. Direct Access forbidden.'); }

/**
 * Clansuite_Module_Rssreader
 *
 * @category    Clansuite
 * @package     Modules
 * @subpackage  Rssreader
 */
class Clansuite_Module_Rssreader extends Clansuite_Module_Controller implements Clansuite_Module_Interface
{
    public function initializeModule(Clansuite_HttpRequest $request, Clansuite_HttpResponse $response)
    {
    }

    public function action_show()
    {
        $view = $this->getView();

        $this->prepareOutput();
    }

    public function widget_rssreader()
    {
        # mute all errors, because simplepie.inc is a mess
        error_reporting(0);

		# get clansuite feed component
		$cs_feed = new Clansuite_Feed();

		# fetch the google group clansuite for latest news
		$feeditems = $cs_feed->fetchRSS('http://groups.google.com/group/clansuite/feed/rss_v2_0_topics.xml');

        $this->getModuleConfig('rssreader');

        # assign to smarty
        $view = $this->getView();
        $view->assign('items_newswidget', $this->getConfigValue('items_newswidget', '3'));
		$view->assign('feed', $feeditems);
    }
}
?>