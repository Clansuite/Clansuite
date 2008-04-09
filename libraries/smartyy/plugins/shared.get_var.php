<?php
/**
 * Smarty shared plugin
 * @package Smarty
 * @subpackage actindo_plugins
 */


/**
 * get_var common function
 *
 * Function: smarty_function_get_var<br>
 * Purpose:  used to get smarty variables for html elements by elements name
 *           (eg. name "data[name][17]" gets variable $data.name[17])
 * @author Patrick Prasse <pprasse@actindo.de>
 * @version $Revision$
 * @param string Name of the element
 * @return mixed|null Variable
 */
function smarty_function_get_var( $el_name, &$smarty )
{
  parse_str( $el_name.'=1', $arr );
  $varname = smarty_function_do_get_var( $arr );

  return eval( "return \$smarty->_tpl_vars{$varname};" );
}


function smarty_function_do_get_var( $arr, $str='' )
{
  list( $key, $newarr ) = each( $arr );
  if( is_string($key) || is_float($key) )
    $str .= sprintf( "['%s']", $key );
  else
    $str .= sprintf( "[%d]", $key );
  if( is_array($newarr) )
    $str .= smarty_function_do_get_var( $newarr );
  return $str;
}


/* vim: set expandtab: */

?>
