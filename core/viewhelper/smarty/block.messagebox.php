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
 * Name:         messagebox
 * Type:         function
 * Purpose:     This TAG inserts a formatted messagebox (hint, notice, alert).
 *
 * @return string HTML of a messagebox.
 */
function smarty_block_messagebox($params, $text, $smarty)
{
    $text = stripslashes($text);
    $textbox_type = null;
    $textbox_level = null;

    # set default type of messagebox to "div", if no type was given
    if(empty($params['type']) == true)
    {
        $textbox_type = 'div';
    }
    else
    {
        $textbox_type = $params['type'];
    }

    # whitelist for messagebox_levels 
    $messagebox_level = array( 'hint', 'notice', 'alert', 'info');

    if (isset($params['level']) and in_array(strtolower($params['level']), $messagebox_level))
    {        
        $textbox_level = strtolower($params['level']);
        unset($params['level']);
    }
    else
    {
        return trigger_error('Please define a parameter level. You have the following options: hint, notice, alert.');
    }

    $tpl_vars = $smarty->getTemplateVars();

    $sprintf_textbox_text  = '<link rel="stylesheet" type="text/css" href="' . $tpl_vars['www_root_themes_core'] . '/css/error.css" />';

    switch ($textbox_type)
    {
        default:
        case "div":
            $textbox_type = 'div';
            $sprintf_textbox_text .= '<div class="messagebox %2$s">%3$s</div>';
            break;        
        case "fieldset":
            $sprintf_textbox_text .= '<fieldset class="error_help %s"><legend>%s</legend><em>%s</em></fieldset>';
            break;
    }
    
    $text = sprintf($sprintf_textbox_text, $textbox_type, $textbox_level, $text);

    return $text;
}
?>