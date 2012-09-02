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

namespace Clansuite\Modules\Rssreader\Controller;

use Clansuite\Core\Mvc\ModuleController;

/**
 * Clansuite_Module_Rssreader
 *
 * @category    Clansuite
 * @package     Modules
 * @subpackage  Rssreader
 */
class RssreaderController extends ModuleController
{
    public function widget_rssreader()
    {
        // fetch the google group clansuite for latest news
        $feeditems = Clansuite_Feed::fetchRSS('http://groups.google.com/group/clansuite/feed/rss_v2_0_topics.xml');

        // assign to smarty
        $view = $this->getView();
        $view->assign('items_newswidget', self::getConfigValue('items_newswidget', '3'));
        $view->assign('feed', $feeditems);
    }
}
