<?php
/**

 *
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
