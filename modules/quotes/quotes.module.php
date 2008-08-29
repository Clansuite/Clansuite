<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-Andre Koch © 2005 - onwards
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
    * @copyright  Copyleft: All rights reserved. Jens-Andre Koch (2005-onwards)
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
class Module_Quotes extends ModuleController implements Clansuite_Module_Interface
{
    /**
     * Module_Quotes -> Execute
     */
    public function execute(httprequest $request, httpresponse $response)
    {
        # proceed to the requested action
        $this->processActionController($request);
    }

    /**
     * widget_quotes
     *
     * Displayes random quotes in the quotes_widget.tpl.
     * This is called from template-side by adding:
     * {load_module name="quotes" action="widget_quotes"}
     *
     * @param $item unused
     * @param $smarty Smarty Render Engine Object
     * @returns content of quotes_widget.tpl
     */
    public function widget_quotes($item, &$smarty)
    {
        $quotes = Doctrine_Query::create()
                          ->select('q.*')
                          ->from('CsQuote q')
                          ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
                          ->limit(1)
                          ->orderby('q.quote_id = '. rand())
                          ->execute();

        $smarty->assign('quote', $quotes);

        # check for theme tpl / else take module tpl
        if($smarty->template_exists('quotes/quotes_widget.tpl'))
        {
            echo $smarty->fetch('quotes/quotes_widget.tpl');
        }
        else
        {
            echo $smarty->fetch('quotes/templates/quotes_widget.tpl');
        }
    }

}