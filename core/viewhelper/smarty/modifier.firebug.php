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
 * Smarty Modifier to debug output the variable content to the firebug console.
 *
 * @example
 * {$variable|firebug}
 *
 * @param mixed Variable to firedebug
 *
 * @return string
 */
function smarty_modifier_firebug($var)
{
    Clansuite_Xdebug::firebug($var);
}
?>