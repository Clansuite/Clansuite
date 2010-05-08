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
 * Clansuite Filter - HTML Tidy
 *
 * Purpose: this repairs or converts the html output by tidying it.
 *
 * @link http://de3.php.net/manual/de/ref.tidy.php PHP Extension Tidy
 * @link http://de3.php.net/manual/de/function.tidy-get-config.php Tidy Config Parameters
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Filters
 * @implements  Clansuite_Filter_Interface
 */
class Clansuite_Filter_HtmlTidy implements Clansuite_Filter_Interface
{
    private $config     = null;

    function __construct(Clansuite_Config $config)
    {
        $this->config     = $config;
    }

    public function executeFilter(Clansuite_HttpRequest $request, Clansuite_HttpResponse $response)
    {
        // take the initiative or pass through (do nothing)
        if( $this->config['htmltidy']['enabled'] == 1 && extension_loaded('tidy'))
        {
            # get output from response
            $content = $this->response->getContent();

            # init tidy
            $tidy = new tidy;

            /*
                $tidyoptions = array( 'indent-spaces'    => 4,
                                      'wrap'             => 120,
                                      'indent'           =>  auto,
                                      'tidy-mark'        => true,
                                      'show-body-only'   => true,
                                      'force-output'     => true,
                                      'output-xhtml'     => true,
                                      'clean'            => true,
                                      'hide-comments'    => false,
                                      'join-classes'     => false,
                                      'join-styles'      => false,
                                      'doctype'          => 'strict',
                                      'lower-literals'   => true,
                                      'quote-ampersand'  => true,
                                      'wrap'             => 0,
                                      'drop-font-tags'   => true,
                                      'drop-empty-paras' => true,
                                      'drop-proprietary-attributes' => true);
            */

            $tidyoptions = array(
                    'clean' => true,
                    'output-xhtml' => true,
                    'drop-proprietary-attributes' => true,
                    'show-body-only' => true,
                    'indent-spaces' => 4,
                    'wrap' => 130,
                    'indent' => auto);

            # tidy the output
            $tidy->parseString($content, $config, 'utf8');
            $tidy->cleanRepair();

            # @todo diagnose? errorreport?

            # set output to response
            $this->response->setContent(tidy_get_output($tidy), true);

        }// else => bypass
    }
}
?>