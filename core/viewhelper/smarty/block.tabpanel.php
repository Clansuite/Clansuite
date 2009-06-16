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
 * Name:        tabpanel
 * Type:        function
 * Purpose:     This TAG inserts a jquery tabpanel
 *
 */
function smarty_block_tabpanel($params, $content, &$smarty, &$repeat)
{
    $name = isset($params['name']) ? $params['name'] : '1';

    # Initialize
    # @todo addJavascript('header', 'once', $js)
    # @todo addCss('once', $css);
    $start_tabpane  = "<!-- Tabs with jQuery + YAML Accessible Tabs Plugin -->\n ";
    $start_tabpane .= "\n";
    $start_tabpane .= '<link rel="stylesheet" type="text/css" href="'.WWW_ROOT_THEMES_CORE.'/css/tabs.css" />';
    $start_tabpane .= "\n";
    $start_tabpane .= '<script type="text/javascript" src="'.WWW_ROOT_THEMES_CORE.'/javascript/jquery/jquery.tabs.js"></script>';
    $start_tabpane .= "\n";

$html = <<< EOF
<script type="text/javascript">
    $(document).ready(function(){
        $(".tabs").accessibleTabs({
                                    fx:"fadeIn",
                                    tabbody:'.tab-page',
                                    tabhead: '.tab',
                                    currentInfoText: '&raquo; ',
                                    currentInfoPosition: 'prepend',
                                    currentInfoClass: 'current-info'
                                  });
    });
</script>
EOF;

    $start_tabpane .= $html;
    $start_tabpane .= "\n";

    # Start TAB Pane
    $start_tabpane .= '<!-- START - TAB PANEL "'.$name.'" -->';
    $start_tabpane .= "\n";
    $start_tabpane .= '<div class="tabs" id="tab-panel-'.$name.'"> ';
    $start_tabpane .= "\n";

    # End TAB Pane
    $end_tabpane = '</div><!-- END - TAB PANEL "'.$name.'" -->';
    $end_tabpane .= "\n";

    # Construct content for whole BLOCK
    $content = $start_tabpane . $content . $end_tabpane;

    return $content;
}
?>