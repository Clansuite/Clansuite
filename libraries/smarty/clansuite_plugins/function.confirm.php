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
 * @author Jens-Andre Koch <jakoch@web.de>
 * @copyright Copyright (C) 2008 Jens-André Koch
 * @license GNU Public License (GPL) v2 or any later version
 * @version SVN $Id: $
 *
 * Name:     	gravatar
 * Type:     	function
 * Purpose: This TAG inserts a valid Gravatar Image.
 *
 * See http://en.gravatar.com/ for further information.
 *
 * Parameters:
 * - email      = the email to fetch the gravatar for (required)
 * - size       = the images width
 * - rating     = the highest possible rating displayed image [ G | PG | R | X ]
 * - default    = full url to the default image in case of none existing OR
 *                invalid rating (required, only if "email" is not set)
 *
 * Example usage:
 *
 * {gravatar email="example@example.com" size="40" rating="R" default="http://myhost.com/myavatar.png"}
 *
 * @param array $params as described above (emmail, size, rating, defaultimage)
 * @param Smarty $smarty
 * @return string
 */
function smarty_function_confirm($params, &$smarty)
{
	if( !isset( $params['class'] ) ) { $smarty->trigger_error("Parameter 'class' not specified!"); return; }
    if( !isset( $params['html'] ) ) { $smarty->trigger_error("Parameter 'html' not specified!"); return; }
    if( !isset( $params['title'] ) ) { $smarty->trigger_error("Parameter 'title' not specified!"); return; }
    
    $smarty->assign('confirmHTML', $params['html']);
    $smarty->assign('confirmClass', $params['class']);
    $smarty->assign('confirmTitle', $params['title']);
    
    return $smarty->fetch( ROOT_THEMES . 'core/tools/confirm_mocha.tpl' );
}
?>