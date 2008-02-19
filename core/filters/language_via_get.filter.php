<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-Andre Koch © 2005-2008
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
    * @copyright  Jens-Andre Koch (2005-2008)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    * @since      File available since Release 0.2
    *
    * @version    SVN: $Id$
    */

/**
 * language_via_url Filter Function
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
 * @implements FilterInterface
 */
class language_via_get implements FilterInterface
{
    private $config     = null;     # holds instance of config
    private $locale     = null;     # holds instance of localization

    function __construct(configuration $config, localization $locale)
    {
       $this->config    = $config;      # set instance of config to class
       $this->locale    = $locale;      # set instance of localization to class
    }

    public function executeFilter(httprequest $request, httpresponse $response)
    {
        // take the initiative of filtering, if language switching is enabled in CONFIG
        // or pass through (do nothing) if disabled
        if($this->config['languageswitch_via_url'] == 1)
        {
            # @todo change $request to $request['get']
            # @todo security check of the incomming lang parameter, if not already handled by $httprequest class
            if(isset($request['lang']) && !empty($request['lang']) )
            {
            	/* Security Handler
                  if( !$input->check( $request['lang'], 'is_abc|is_custom', '_' ) )
                  {
                    $security->intruder_alert();
                }
                 Update Session
                else
                {*/
            	   $_SESSION['user']['language']           = strtolower($request['lang']).'_'.strtoupper($request['lang']);
            	   $_SESSION['user']['language_via_url']   = 1;
            	#}
            }
        }
    }
}

?>