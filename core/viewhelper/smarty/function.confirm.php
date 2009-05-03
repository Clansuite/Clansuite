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
 * @author Florian Wolf <xsign.dll@clansuite.com>
 * @copyright Copyright (C) 2008 Florian Wolf
 * @license GNU Public License (GPL) v2 or any later version
 * @version SVN $Id: $
 *
 * Name:     	confirm
 * Type:     	function
 * Purpose: This TAG inserts Mocha UI JavaScript for a confirmation message
 *
 *
 * Example usage:
 *
 * {confirm class="delete" title="Please confirm...." html="<center>Are you sure you want to delete the module?</center><br />"} 
 *
 * @param array $params as described above (class,html,htmlTemplate,title,link)
 * @param Smarty $smarty
 * @return string
 */
function smarty_function_confirm($params, &$smarty)
{
	if( !isset( $params['class'] ) ) { $smarty->trigger_error("Parameter 'class' not specified!"); return; }
    if( !isset( $params['html'] ) && !isset( $params['htmlTemplate'] ) ) { $smarty->trigger_error("Parameter 'html' or 'htmlTemplate' not specified!"); return; }
    if( !isset( $params['link'] ) && isset( $params['htmlTemplate'] ) ) { $smarty->trigger_error("Parameter 'link' not specified!"); return; }

    if( !isset( $params['title'] ) ) { $params['title'] = _('Please confirm your request'); }
    
    $smarty->assign('confirmClass', $params['class']);
    $smarty->assign('confirmTitle', $params['title']);
    $smarty->assign('confirmGrabValueFrom', $params['grabValueFrom']);
    $smarty->assign('confirmMessage', $params['confirmMessage']);
    
    if( isset( $params['htmlTemplate'] ) )
    {
        $smarty->assign('confirmLink', $params['link']);
        #$tpl = str_replace( '"', '\\"', $smarty->fetch(ROOT_THEMES . 'core/tools/' . $params['htmlTemplate']) );
        $smarty->assign('confirmTpl', $smarty->fetch(ROOT_THEMES . 'core/tools/' . $params['htmlTemplate']));
    }
    else
    {
        $smarty->assign('confirmHTML', $params['html']);
    }
    
    return $smarty->fetch( ROOT_THEMES . 'core/tools/confirm_mocha.tpl' );
}
?>