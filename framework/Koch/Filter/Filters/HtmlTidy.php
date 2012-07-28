<?php

/**
 * Koch Framework
 * Jens-André Koch © 2005 - onwards
 *
 * This file is part of "Koch Framework".
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

namespace Koch\Filter\Filters;

use Koch\Mvc\HttpRequestInterface;
use Koch\Mvc\HttpResponseInterface;

/**
 * Koch Framework - Filter for HTML Tidy (postfilter).
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
class HtmlTidy implements FilterInterface
{
    private $config     = null;

    public function __construct(Koch\Config $config)
    {
        $this->config     = $config;
    }

    public function executeFilter(HttpRequestInterface $request, HttpResponseInterface $response)
    {
        // htmltidy must be enabled in configuration
        if ( $this->config['htmltidy']['enabled'] == 1 and extension_loaded('tidy')) {
            // bypass
            return;
        }

        // get output from response
        $content = $response->getContent();

        // init tidy
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

        // tidy the output
        $tidy->parseString($content, $tidyoptions, 'utf8');
        $tidy->cleanRepair();

        // @todo diagnose? errorreport?

        // set output to response
        $response->setContent(tidy_get_output($tidy), true);
    }
}
