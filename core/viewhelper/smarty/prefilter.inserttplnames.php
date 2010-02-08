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
 * Smarty plugin
 * --------------------------------------------------------
 * File:    prefilter.inserttplnames.php
 * Type:    prefilter
 * Name:    inserttplcomment
 * Version: 1.0
 * Date:    03 Jun 2006
 * Purpose: Add Comment with Teplatename at begin & end of
 *		   included tpl
 * Install: Place in your (local) plugins directory and
 *          add the call:
 *          $smarty->load_filter('pre', 'inserttplnames');
 * Author:  Jens-André Koch
 * --------------------------------------------------------
 */
function smarty_prefilter_inserttplnames( $tpl_source, $compiler )
{
    #Clansuite_Xdebug::firebug($compiler);
    return "\n<!-- [-Start-] Included Template {\$smarty.current_dir}".DS."{\$smarty.template} -->\n".$tpl_source."\n<!-- [-End-] Included Template {\$smarty.current_dir}".DS."{\$smarty.template}  -->\n";
}
?>