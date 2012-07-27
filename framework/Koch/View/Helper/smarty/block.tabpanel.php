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
 * along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 */

/**
 * Koch Framework Smarty Viewhelper
 *
 * @category Koch
 * @package Smarty
 * @subpackage Viewhelper
 *
 * Name:        tabpanel
 * Type:        function
 * Purpose:     This TAG inserts a jquery tabpanel
 *
 */
function smarty_block_tabpanel($params, $content, $smarty, &$repeat)
{
    $name = isset($params['name']) ? $params['name'] : '1';

    // replace whitespaces with underscore
    // else javascript selection will not work properly
    $name = str_replace(' ', '_', $name);

    // Initialize
    // @todo addJavascript('header', 'once', $js)
    // @todo addCss('once', $css);
    $start_tabpane  = '<!-- Tabs with jQuery + YAML Accessible Tabs Plugin -->' . CR;
    $start_tabpane .= '<link rel="stylesheet" type="text/css" href="' . WWW_ROOT_THEMES_CORE . 'css/tabs.css" />' . CR;
    $start_tabpane .= '<script type="text/javascript" src="' . WWW_ROOT_THEMES_CORE . 'javascript/jquery/jquery.tabs.js"></script>' . CR;

    $js = <<< EOF
<script type="text/javascript">
    $(document).ready(function(){
        $(".tabs").accessibleTabs({
            fx: "fadeIn",
            tabbody: '.tab-page',
            tabhead: '.tab',
            currentInfoText: '&raquo; ',
            currentInfoPosition: 'prepend',
            currentInfoClass: 'current-info'
          });
    });
</script>
EOF;

    $start_tabpane .= $js . CR;

    // Start TAB Pane
    $start_tabpane .= '<!-- START - TAB PANEL "'.$name.'" -->' . CR;
    $start_tabpane .= '<div class="tabs" id="tab-panel-'.$name.'"> ' . CR;

    // End TAB Pane
    $end_tabpane = '</div><!-- END - TAB PANEL "'.$name.'" -->' . CR;

    // Construct content for whole BLOCK
    /**
     * As of Smarty v3.1.6 the block tag is rendered at the opening AND closing tag
     * This results in a duplication of content.
     * To prevent this, we need to check that the content is oCRy rendered when the inner block (content)
     * is present.
     */
    if (isset($content)) {
        return $start_tabpane . $content . $end_tabpane;
    }
}
