<?php
/**
 * This smarty function is part of "Clansuite - just an eSports CMS"
 * @link http://www.clansuite.com
 *
 * @author Jens-André Koch <vain@clansuite.com>
 * @copyright Copyright (C) 2005-onwards Jens-André Koch
 * @license GNU Public License (GPL) v2 or any later version
 * @version SVN $Id$
 *
 * Name:         break
 * Type:         compiler
 * Purpose:     this TAG breaks foreach loops
 * Alternative: {php} break; {/php}
 *
 * Example usage:
 *
 * {foreach ...} ... {break} {/foreach}
 *
 * @param string $contents
 * @param Smarty $smarty
 * @return string
 */
function smarty_compiler_break($contents, $smarty)
{
    return 'break;';
}
?>