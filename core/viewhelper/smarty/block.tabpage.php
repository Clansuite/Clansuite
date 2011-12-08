<?php
/**
 * Clansuite Smarty Viewhelper
 *
 * @category Clansuite
 * @package Smarty
 * @subpackage Viewhelper
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
    if(isset($params['name']))
    {
        $name = _($params['name']);
    }
    else
    {
        $smarty->trigger_error("Tabpage Name not set! Please add Parameter 'name=tabpagename'!");
        return;
    }

    # Start TAB Page
    $start_tabpage  = '<!-- START - TABPAGE "'.$name.'" -->' . CR;
    $start_tabpage .= '<div class="tab-page">' . CR;
    $start_tabpage .= '<h2 class="tab">'.$name.'</h2>' . CR;

    # End TAB Page
    $end_tabpage = '</div><!-- END - TABPAGE "'.$name.'" -->' . CR;

    /**
     * As of Smarty v3.1.6 the block tag is rendered at the opening AND closing tag
     * This results in a duplication of content.
     * To prevent this, we need to check that the content is oCRy rendered when the inner block (content)
     * is present.
     */
    if(isset($content))
    {
        # Construct content for whole BLOCK
        return $start_tabpage . $content . $end_tabpage;
    }
}
?>