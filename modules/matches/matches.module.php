<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-Andr� Koch � 2005 - onwards
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
    * @author     Jens-Andr� Koch   <vain@clansuite.com>
    * @author     Florian Wolf      <xsign.dll@clansuite.com>
    * @copyright  Jens-Andr� Koch (2005 - onwards), Florian Wolf (2006-2007)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    * @since      File available since Release 0.1
    *
    * @version    SVN: $Id$
    */

// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}

/**
 * Clansuite
 *
 * Module:      Matches
 *
 * @author     Jens-Andr� Koch <vain@clansuite.com>
 * @copyright  Jens-Andr� Koch (2005 - onwards)
 *
 * @package     clansuite
 * @category    module
 * @subpackage  matches
 */
class Module_Matches extends Clansuite_ModuleController implements Clansuite_Module_Interface
{
    /**
     * Module_Guestbook -> Execute
     */
    public function execute(Clansuite_HttpRequest $request, Clansuite_HttpResponse $response)
    {
        parent::initRecords('matches');
    }

    public function action_show()
    {
        // Set Pagetitle and Breadcrumbs
        Clansuite_Trail::addStep( _('Show'), '/index.php?mod=matches&amp;action=show');

        # fetch nextmatches
        $matches = Doctrine::getTable('CsMatches')->fetchAMatches($num);


        #clansuite_xdebug::printr($matches);

        $view = $this->getView();
        $view->assign('matches', $matches);

        $this->prepareOutput();
    }


    public function widget_nextmatches($num)
    {
        $this->getView()->assign('nextmatches_widget', Doctrine::getTable('CsMatches')->fetchNextMatches($num));
    }

    public function widget_latestmatches($num)
    {
        $this->getView()->assign('latestmatches_widget', Doctrine::getTable('CsMatches')->fetchLatestMatches($num));
    }

    public function widget_topmatch()
    {
        $this->getView()->assign('topmatch_widget', Doctrine::getTable('CsMatches')->fetchTopmatch());
    }
}
?>