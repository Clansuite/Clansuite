<?php
/**
* Smarty plugin
* @package Smarty
* @subpackage plugins
*/


/**
* Smarty dump modifier plugin
*
* Type:     modifier<br>
* Name:     dump<br>
* Purpose:  dump a variable in a pretty styled box
* Author:   Monte Ohrt
* Credits:  Greg Burghardt (CSS)
*
* Usage:
* {$myvar|@dump}
* {$myvar|@dump:"myvar"}
*
* @param string
* @return string
*/
function smarty_modifier_dump($var,$name = null)
{

$_name = isset($name) ? '<strong style="background-color: #f0f0f0; border-bottom: 1px solid #000; display: block; padding: .33em 10px;">' . $name . '</strong>' : '';
       
return '<!-- start debug box --><div style="text-align: left; border: 1px inset #000; clear: both; font-family: \'Courier New\', Courier, monospace; font-size: 1em; margin: 2em 0;">
' . $_name . '
<pre style="background-color: #e0e0e0; font-family: inherit; font-size: 1em; margin: 0; max-height: 450px; overflow: auto; width: 100%;"><code style="display: block; font-family: inherit; font-size: 1em; padding: 1.67em 10px 10px 10px;">
' . var_export($var,true) . '<!-- dump the variable here -->
</code></pre>
</div><!-- end debug box -->';
   
}

?> 