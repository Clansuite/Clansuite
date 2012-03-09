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

namespace Koch\Filter;

# Security Handler
if(defined('IN_CS') === false)
{
    exit('Koch Framework not loaded. Direct Access forbidden.');
}

/**
 * Koch FrameworkFilter - HTML Tidy
 *
 * Purpose: this repairs or converts the html output by tidying it.
 *
 * @link http://de3.php.net/manual/de/ref.tidy.php PHP Extension Tidy
 * @link http://de3.php.net/manual/de/function.tidy-get-config.php Tidy Config Parameters
 *
 * @category    Koch
 * @package     Core
 * @subpackage  Filters
 */
class HtmlTidy implements Filter
{
    private $config     = null;

    function __construct(Koch_Config $config)
    {
        $this->config     = $config;
    }

    public function executeFilter(Koch_HttpRequest $request, Koch_HttpResponse $response)
    {
        # htmltidy must be enabled in configuration
        if( $this->config['htmltidy']['enabled'] == 1 and extension_loaded('tidy'))
        {
            # bypass
            return;
        }

        # get output from response
        $content = $response->getContent();

        # init tidy
        $tidy = new tidy;

        /*
        $tidyoptions = array(
           'indent-spaces'    => 4,
            'wrap'             => 120,
            'indent'           => auto,
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
            #'doctype' => 'strict',
            'doctype' => 'transitional',
            'output-xhtml' => true,
            'drop-proprietary-attributes' => true,
            'lower-literals' => true,
            #'quote-ampersand' => true,
            'show-body-only' => false,
            'indent-spaces' => 4,
            'wrap' => 130,
            'indent' => 'auto'
        );

        # tidy the output
        $tidy->parseString($content, $tidyoptions, 'utf8');
        $tidy->cleanRepair();

        # @todo diagnose? errorreport?

        # set output to response
        $response->setContent(tidy_get_output($tidy), true);
    }
}
?>