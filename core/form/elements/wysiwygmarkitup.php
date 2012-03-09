<?php
   /**
    * Koch Framework
    * Jens-Andr� Koch � 2005 - onwards
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
    * @author     Jens-Andr� Koch <vain@clansuite.com>
    * @copyright  Jens-Andr� Koch (2005-onwards)
    * @link       http://www.clansuite.com
    *
    * @version    SVN: $Id$
    */

namespace Koch\Formelement;

# Security Handler
if(defined('IN_CS') === false)
{
    exit('Koch Framework not loaded. Direct Access forbidden.');
}

/**
 * Koch_Formelement_Wysiwygmarkitup
 *
 * @see http://markitup.jaysalvat.com/home/ Official Website of markItUp!
 */
class Wysiwygmarkitup extends Textarea implements Formelement
{
    public function __construct()
    {
        self::checkDependencies();
    }

    /**
     * Ensure, that the library is available, before the client requests a non-existant file.
     */
    public static function checkDependencies()
    {
        if (!is_file(ROOT_THEMES_CORE . 'javascript/markitup/jquery.markitup.js'))
        {
            exit('MarkitUp Javascript Library missing!');
        }
    }

    /**
     * This renders a textarea with the WYSWIWYG editor markItUp! attached.
     */
    public function render()
    {
        # a) loads the markitup javascript files
        #$javascript = '<script type="text/javascript" src="'.WWW_ROOT_THEMES_CORE . 'javascript/jquery/jquery.js"></script>';
        $javascript = '<script type="text/javascript" src="'.WWW_ROOT_THEMES_CORE . 'javascript/markitup/jquery.markitup.js"></script>'.CR;

        # b) load JSON default settings
        $javascript .= '<script type="text/javascript" src="'.WWW_ROOT_THEMES_CORE . 'javascript/markitup/sets/default/set.js"></script>'.CR;

        # c) include CSS
        $css = '<link rel="stylesheet" type="text/css" href="'.WWW_ROOT_THEMES_CORE . 'javascript/markitup/skins/markitup/style.css" />'.CR.'
                 <link rel="stylesheet" type="text/css" href="'.WWW_ROOT_THEMES_CORE . 'javascript/markitup/sets/default/style.css" />'.CR;

        # d) plug it to an specific textarea by ID
        $javascript .= '<script type="text/javascript">// <![CDATA[
                           jQuery(document).ready(function($){
                              $("textarea:visible").markItUp(mySettings);
                           });
                        // ]]></script>';

        return $javascript.$css;
    }
}
?>