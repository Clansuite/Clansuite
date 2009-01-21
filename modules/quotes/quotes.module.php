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
    * @version    SVN: $Id: Quotes.module.php 2390 2008-08-04 19:38:54Z vain $
    */

// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}

/**
 * Clansuite 
 *
 * Module:      Quotes
 *
 */
class Module_Quotes extends Clansuite_ModuleController implements Clansuite_Module_Interface
{
    /**
     * Module_Quotes -> Execute
     */
    public function execute(Clansuite_HttpRequest $request, Clansuite_HttpResponse $response)
    {
    }

    public function widget_quotes($item)
    {
        $smarty = $this->getView();

        # @todo fetchOne()?
        $quotes = Doctrine_Query::create()
                          ->select('q.*')
                          ->from('CsQuote q')
                          ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
                          ->limit(1)
                          ->orderby('q.quote_id = '. rand())
                          ->execute();

        $smarty->assign('quote', $quotes);
    }
}
?>