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
    * @version    SVN: $Id: news.module.php 2753 2009-01-21 22:54:47Z vain $
    */

//Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}

/**
 * Clansuite Module - Quotes
 * 
 * @author     Jens-André Koch <vain@clansuite.com>
 * @copyright  Jens-André Koch (2005 - onwards)
 * @version    0.1
 */
class Module_Quotes extends Clansuite_ModuleController implements Clansuite_Module_Interface
{
    /**
     * Module_Quotes -> Execute
     */
    public function execute(Clansuite_HttpRequest $request, Clansuite_HttpResponse $response)
    {
        # nothing to do
    }
    
    public function action_show()
    {
        $smarty = $this->getView();

        # @todo fetchOne()?
        $quotes = Doctrine_Query::create()
                          ->select('q.*')
                          ->from('CsQuotes q')
                          ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
                          ->limit(1)
                          ->orderby('q.quote_id = '. rand())
                          ->execute();

        $smarty->assign('quote', $quotes[0]);
        
        # Prepare the Output
        $this->prepareOutput();
    }

    public function widget_quotes($item)
    {
        $smarty = $this->getView();

        # @todo fetchOne()?
        $quotes = Doctrine_Query::create()
                          ->select('q.*')
                          ->from('CsQuotes q')
                          ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
                          ->limit(1)
                          ->orderby('q.quote_id = '. rand())
                          ->execute();

        $smarty->assign('quote', $quotes[0]);
    }
}
?>