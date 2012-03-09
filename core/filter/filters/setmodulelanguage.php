<?php
   /**
    * Koch Framework
    * Jens-André Koch © 2005 - onwards
    * http://www.clansuite.com/
    *
    * This file is part of "Koch Framework".
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

namespace  Koch\Filter;

# Security Handler
if(defined('IN_CS') === false)
{
    exit('Koch Framework not loaded. Direct Access forbidden.');
}


/**
 * Koch FrameworkFilter - Set Module Language
 *
 * Purpose: Sets the TextDomain for the requested Module
 *
 * @category    Koch
 * @package     Core
 * @subpackage  Filters
 */
class SetModuleLanguage implements Filter
{
    private $locale = null;     # holds instance of localization

    public function __construct(Koch_Localization $locale)
    {
        $this->locale = $locale;      # set instance of localization to class
    }

    public function executeFilter(Koch_HttpRequest $request, Koch_HttpResponse $response)
    {
        #$route = Koch_HttpRequest::getRoute();
        $modulename = Koch_TargetRoute::getController();
        $this->locale->loadTextDomain('LC_ALL', $modulename, $this->locale->getLocale(), $modulename);
    }
}
?>