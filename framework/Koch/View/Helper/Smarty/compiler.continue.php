<?php
/**
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
function Smarty_compiler_continue($contents, $smarty)
{
    return 'continue;';
}
