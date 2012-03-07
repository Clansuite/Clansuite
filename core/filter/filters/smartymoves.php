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
    * @author     Jens-André Koch <vain@clansuite.com>
    * @copyright  Jens-André Koch (2005 - onwards)
    * @link       http://www.clansuite.com
    *
    * @version    SVN: $Id$
    */

# Security Handler
if (defined('IN_CS') === false)
{
    die('Clansuite not loaded. Direct Access forbidden.' );
}

/**
 * Clansuite Filter - Smarty2 Subtemplate Moves
 *
 * This is a Smarty2 related Filter.
 * Before the Smarty3 {block} tag was invented, there was no functionality
 * for assigning content from a child-template to the master-template.
 *
 * This filter works together with the {move_to} smarty viewhelper.
 * In the subtemplate the {move_to} command is used.
 * This inserts special text fragments into the template,
 * marking the positions of texts which are to be moved by this filter.
 * This filter detects these special text fragments in the output of smarty
 * and performs the moves accordingly.
 *
 * PRE_HEAD_CLOSE
 * POST_BODY_OPEN
 * PRE_BODY_CLOSE
 *
 * Purpose: detect block-tags, move content of such blocks, remove tags afterwards.
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Filters
 */
class Clansuite_Filter_SmartyMoves implements Clansuite_Filter_Interface
{
    public function executeFilter(Clansuite_HttpRequest $request, Clansuite_HttpResponse $response)
    {
        /**
         * If the renderer is not smarty, then bypass the filter.
         */
        if($request->getRoute()->getRenderEngine() != 'smarty')
        {
            return;
        }

        /**
         * Get HttpResponse output buffer
         */
        $content = $response->getContent();

        /**
         * This matches the PRE_HEAD_CLOSE tag.
         * The X marks the position: X</head>
         */
        $matches = array();
        preg_match_all('!@@@SMARTY:PRE_HEAD_CLOSE:BEGIN@@@(.*?)@@@SMARTY:PRE_HEAD_CLOSE:END@@@!is', $content, $matches);
        $content = preg_replace('!@@@SMARTY:PRE_HEAD_CLOSE:BEGIN@@@(.*?)@@@SMARTY:PRE_HEAD_CLOSE:END@@@!is', '', $content);
        $matches = array_keys(array_flip($matches[1]));
        foreach($matches as $value)
        {
            $content = str_replace('</head>', $value."\n".'</head>', $content);
        }

        /**
         * This matches the POST_BODY_OPEN tag.
         * The X marks the position: <body>X
         */
        $matches = array();
        preg_match_all('!@@@SMARTY:POST_BODY_OPEN:BEGIN@@@(.*?)@@@SMARTY:POST_BODY_OPEN:END@@@!is', $content, $matches);
        $content = preg_replace('!@@@SMARTY:POST_BODY_OPEN:BEGIN@@@(.*?)@@@SMARTY:POST_BODY_OPEN:END@@@!is', '', $content);
        $matches = array_keys(array_flip($matches[1]));
        foreach($matches as $values)
        {
            $content = str_replace('<body>', '<body>'."\n".$value, $content);
        }

        /**
         * This matches the POST_BODY_OPEN tag.
         * The X marks the position: X</body>
         */
        $matches = array();
        preg_match_all('!@@@SMARTY:PRE_BODY_CLOSE:BEGIN@@@(.*?)@@@SMARTY:PRE_BODY_CLOSE:END@@@!is', $content, $matches);
        $content = preg_replace('!@@@SMARTY:PRE_BODY_CLOSE:BEGIN@@@(.*?)@@@SMARTY:PRE_BODY_CLOSE:END@@@!is', '', $content);
        $matches = array_keys(array_flip($matches[1]));
        foreach($matches as $values)
        {
            $content = str_replace('</body>', $value."\n".'</body>', $content);
        }

        /**
         * Replace the http response buffer
         */
        $response->setContent($content, true);
    }
}
?>