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
    * @version    SVN: $Id$
    */

# Security Handler
if (defined('IN_CS') == false)
{ 
    die('Clansuite not loaded. Direct Access forbidden.' );
}

/**
 * Clansuite Filter - Language via URL
 *
 * I10N/I18N Localization and Internationalization
 * Purpose: Set Language via URL by appendix $_GET['lang']
 *
 * 1) Default Language
 *    At system startup the default language is defined by the config.
 *    Up to this point this language is used for any output, like system and error messages.
 *
 * 2) When languageswitch_via_url is enabled in config, the user is able to
 *    override the default language (by adding the URL appendix 'lang').
 *    When request parameter 'lang' is set, the user session value for language will be updated.
 *    Example: index.php?lang=langname
 *
 * Note: The check if a certain language exists is not important,
 *       because there are 1) english hardcoded values and 2) the default language as fallback.
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Filters
 * @implements  Clansuite_Filter_Interface
 */
class Clansuite_Filter_LanguageViaGet implements Clansuite_Filter_Interface
{
    private $config     = null;     # holds instance of config

    public function __construct(Clansuite_Config $config)
    {
        $this->config    = $config;      # set instance of config to class
    }

    public function executeFilter(Clansuite_HttpRequest $request, Clansuite_HttpResponse $response)
    {
        /**
         * take the initiative of filtering, if language switching is enabled in CONFIG
         * or pass through (do nothing) if disabled
         */
        if($this->config['switches']['languageswitch_via_url'] == 1)
        {
            if(isset($request['lang']) && !empty($request['lang']) && (strlen($request['lang']) == 2))
            {
                $_SESSION['user']['language']           = strtolower($request['lang']).'_'.strtoupper($request['lang']);
                $_SESSION['user']['language_via_url']   = 1;
            }
        }
    }
}
?>