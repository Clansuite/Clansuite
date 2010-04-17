<?php
/**
 * This smarty function is part of "Clansuite - just an eSports CMS"
 * @link http://www.clansuite.com
 *
 * @author Jens-Andr� Koch <vain@clansuite.com>
 * @copyright Copyright (C) 2005-onwards Jens-Andr� Koch
 * @license GNU Public License (GPL) v2 or any later version
 * @version SVN $Id$
 *
 * Name:         continue
 * Type:         compiler
 * Purpose:     this TAG continues foreach loops
 * Alternative: {php} continue; {/php}
 *
 * Example usage:
 *
 * {foreach ...} ... {continue} {/foreach}
 *
 * @param string $contents
 * @param Smarty $smarty
 * @return string
 */
function smarty_compiler_continue($contents, $smarty)
{
    return 'continue;';
}
?>