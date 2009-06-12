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

// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' ); }

/**
 * Clansuite Filter - Smarty Moves
 *
 * Purpose: detect block-tags, move content of such blocks, remove tags afterwards
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Filters
 * @implements  Clansuite_Filter_Interface
 */
class smarty_moves implements Clansuite_Filter_Interface
{
    private $config     = null;

    function __construct(Clansuite_Config $config)
    {
       $this->config     = $config;
    }

    public function executeFilter(Clansuite_HttpRequest $request, Clansuite_HttpResponse $response)
    {
        // take the initiative or pass through (do nothing)
        #if( #@todo renderengine is smarty)
        #{
            # get output from response
            $tpl_output = $response->getContent();

            # PRE_HEAD_CLOSE = x</head>
            $matches = array();
            preg_match_all('!@@@SMARTY:PRE_HEAD_CLOSE:BEGIN@@@(.*?)@@@SMARTY:PRE_HEAD_CLOSE:END@@@!is', $tpl_output, $matches);
            $tpl_output = preg_replace("!@@@SMARTY:PRE_HEAD_CLOSE:BEGIN@@@(.*?)@@@SMARTY:PRE_HEAD_CLOSE:END@@@!is", '', $tpl_output);
            $matches = array_unique($matches[1]);
            foreach($matches as $value)
            {
                $tpl_output = str_replace('</head>', $value."\n".'</head>', $tpl_output);
            }

            # POST_BODY_OPEN = <body>x
            $matches = array();
            preg_match_all('!@@@SMARTY:POST_BODY_OPEN:BEGIN@@@(.*?)@@@SMARTY:POST_BODY_OPEN:END@@@!is', $tpl_output, $matches);
            $tpl_output = preg_replace("!@@@SMARTY:POST_BODY_OPEN:BEGIN@@@(.*?)@@@SMARTY:POST_BODY_OPEN:END@@@!is", '', $tpl_output);
            $matches = array_unique($matches[1]);
            foreach($matches as $values)
            {

                $tpl_output = str_replace('<body>', '<body>'."\n".$value, $tpl_output);
            }

            # PRE_BODY_CLOSE = x</body>
            $matches = array();
            preg_match_all('!@@@SMARTY:PRE_BODY_CLOSE:BEGIN@@@(.*?)@@@SMARTY:PRE_BODY_CLOSE:END@@@!is', $tpl_output, $matches);
            $tpl_output = preg_replace("!@@@SMARTY:PRE_BODY_CLOSE:BEGIN@@@(.*?)@@@SMARTY:PRE_BODY_CLOSE:END@@@!is", '', $tpl_output);
            $matches = array_unique($matches[1]);
            foreach($matches as $values)
            {
                $tpl_output = str_replace('</body>', $value."\n".'</body>', $tpl_output);
            }

            # set output to response
            $response->setContent($tpl_output, true);

        #}// else => bypass
    }
}
?>