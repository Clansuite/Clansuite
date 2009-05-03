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
 * @version SVN $Id: $
 *
 * Name:     	tabpane
 * Type:     	function
 * Purpose:     This TAG inserts a tabpage.
 *
 */
function smarty_block_tabpane($params, $content, &$smarty, &$repeat)
{
    $name = isset($params['name']) ? $params['name'] : '1';

    # Initialize
    # @todo addJavascript('header', 'once', $js)
    # @todo addCss('once', $css);
    $start_tabpane  = '<!-- Tabs with Tabpane -->';
    $start_tabpane .= '<link rel="stylesheet" type="text/css" href="'.WWW_ROOT_THEMES_CORE.'/css/tabpane/luna.css" />';
    $start_tabpane .= '<script type="text/javascript" src="'.WWW_ROOT_THEMES_CORE.'/javascript/tabpane/tabpane.js"></script>';

    # Start TAB Pane
    $start_tabpane .= '<!-- NEW TAB PANE : '.$name.' -->';
    $start_tabpane .= '<div class="tab-pane" id="tab-pane-'.$name.'"> ';

    # End TAB Pane

    $end_tabpane = '</div><!-- END TAB PANE : '.$name.' -->';
    # @todo addJavascript('bottom','once', $js);
    $end_tabpane .= '<!-- Init TabPane -->
                    <script type="text/javascript">setupAllTabs();</script>';

    # Construct content for whole BLOCK
    $content = $start_tabpane . $content . $end_tabpane;

    return $content;
}
?>