<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage actindo_plugins
 */


/**
 * Smarty {html_text} function plugin
 *
 * File:       function.html_text.php<br>
 * Type:       function<br>
 * Name:       html_text<br>
 * Date:       29.Nov.2005<br>
 * Purpose:    Prints out a input type=text<br>
 * Input:<br>
 *           - name       (required) - string
 *           - type       (optional) - string default "text"
 *           - class      (optional) - string default "txt"
 *           - size       (optional) - int default 30
 *           - maxlength  (optional) - int default not set
 *           - value      (optional) - int, get from assigned variables by default
 *           - image      (optional) - URL for image in textfield (size should be 16x16)
 * @author Patrick Prasse <pprasse@actindo.de>
 * @version $Revision$
 * @param array
 * @param Smarty
 * @return string
 * @uses smarty_function_escape_special_chars()
 */
function smarty_function_html_text($params, &$smarty)
{
  global $_smarty_pp_autocomplete_js_output;
  require_once $smarty->_get_plugin_filepath('shared','escape_special_chars');
  require_once $smarty->_get_plugin_filepath('shared','get_var');

  $name = '';
  $type = 'text';
  $class = 'txt';
  $size = 30;
  $value = null;
  $default = null;
  $extra = null;
  $pp_autocomplete = 0;

  foreach($params as $_key => $_val) {
      switch(strtolower($_key)) {
      case 'extra':
      case 'name':
      case 'type':
      case 'value':
      case 'class':
      case 'default':
         $$_key = $_val;
         break;

      case 'onkeyup':
        $onKeyUp = trim( $_val );
        break;
      case 'onkeydown':
        $onKeyDown = trim( $_val );
        break;
      case 'onkeypress':
        $onKeyPress = trim( $_val );
        break;
      case 'onblur':
        $onBlur = trim( $_val );
        break;
      case 'acurl':
        $acURL = trim( $_val );
        break;

      case 'size':
      case 'pp_autocomplete':
      case 'disabled':
         $$_key = (int)$_val;
         break;

      case 'image':
        $extra .= ' style="background-image: url('.$_val.'); background-repeat: no-repeat; background-position: 0px 1px; padding-left: 18px;"';
        break;


      default:
        if(!is_array($_val)) {
          $extra .= ' '.$_key.'="'.smarty_function_escape_special_chars($_val).'"';
        } else {
          $smarty->trigger_error("html_select: extra attribute '$_key' cannot be an array", E_USER_NOTICE);
        }
        break;
      }
   }

  if( $disabled )
    $extra .= ' disabled="disabled"';


  if( !isset($value) )
  {
    $value = smarty_function_get_var( $name, $smarty );
    if( is_null($value) )
      $value = $default;
  }

  $_html_result = '';
  if( $pp_autocomplete )
  {
    if( !$_smarty_pp_autocomplete_js_output )
    {
      require_once $smarty->_get_plugin_filepath('block','javascript');
      $_html_result .= '<script language="JavaScript" type="text/javascript" src="fetch.php?mod=main&what=autocomplete.js"></script>';
      $_smarty_pp_autocomplete_js_output = true;
    }
    $onBlur = (!empty($onBlur)?$onBlur.';':'')."ac_blur(this);";
    $onKeyUp = "autocomplete(this,event,'keyup');".$onKeyUp;
    $onKeyDown = "autocomplete(this,event,'keydown');".$onKeyDown;
    $onKeyPress = "autocomplete(this,event,'keypress');".$onKeyPress;
    $_html_result .= "\n".smarty_block_javascript( array(), "ac_fields[ac_fields.length] = '{$name}';\n", $smarty );
    $extra .= ' autocomplete="off"';
  }

  foreach( compact('onBlur','onKeyUp','onKeyDown','onKeyPress','acURL') as $n => $v )
  {
    if( !is_null($v) )
      $extra .= sprintf( " %s=\"%s\"", $n, smarty_function_escape_special_chars($v) );
  }

  $_html_result .= '<input type="'.$type.'" class="'.$class.'" name="'.smarty_function_escape_special_chars($name).'" value="'.smarty_function_escape_special_chars($value).'" size="'.$size.'" '.$extra.'>';

  return $_html_result;
}

?>