<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * This smarty function is part of "Clansuite - just an eSports CMS"
 * @link http://www.clansuite.com
 *
 * @author Jens-André Koch <jakoch@web.de>
 * @copyright Copyright (C) 2009 Jens-André Koch
 * @license GNU General Public License v2 or any later version
 * @version SVN $Id$
 *
 * Name:         tabpage
 * Type:         function
 * Purpose:     This TAG inserts a tabpage.
 *
 */
function smarty_block_tabpage($params, $content, $smarty, &$repeat)
{
    # check for name
    if(isset($params['name'])) { $name = _($params['name']); }
    else
    {
        $smarty->trigger_error("Tabpage Name not set! Please add Parameter 'name=tabpagename'!");
        return;
    }

    # Start TAB Page
    $start_tabpage  = '<!-- START - TABPAGE "'.$name.'" -->';
    $start_tabpage .= "\n";
    $start_tabpage .= '<div class="tab-page">';
    $start_tabpage .= "\n";
    $start_tabpage .= '<h2 class="tab">'.$name.'</h2>';
    $start_tabpage .= "\n";

    # End TAB Page
    $end_tabpage = '</div><!-- END - TABPAGE "'.$name.'" -->';

    # Construct content for whole BLOCK
    $content = $start_tabpage . $content . $end_tabpage;

    return $content;
}
?>