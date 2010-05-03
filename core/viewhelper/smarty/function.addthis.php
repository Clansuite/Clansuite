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
 * Smarty Function to output the addthis social bookmark/sharing button.
 * @link http://www.addthis.com
 *
 * @example
 * {addthis}
 *
 * @return string
 */
function smarty_function_addthis()
{
    $str = <<<EOD
<!--
     AddThis - Social Bookmarks  http://www.addthis.com/
-->
<div class="addthis_toolbox addthis_default_style">
    <a href="http://www.addthis.com/bookmark.php?v=250&amp;username=xa-4bb5e0ae7fd45828" class="addthis_button_compact">Share</a>
    <span class="addthis_separator">|</span>
    <a class="addthis_button_facebook"></a>
    <a class="addthis_button_myspace"></a>
    <a class="addthis_button_google"></a>
    <a class="addthis_button_twitter"></a>
</div>
<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#username=xa-4bb5e0ae7fd45828"></script>
<!-- AddThis Button END -->
EOD;

    return $str;
}
?>