<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage actindo_plugins
 */


/**
 * Smarty {html_button} function plugin
 *
 * File:       function.html_button.php<br>
 * Type:       function<br>
 * Name:       html_button<br>
 * Date:       29.Nov.2005<br>
 * Purpose:    Print out a button using templates for common types<br>
 * Input:<br>
 *           - name       (required) - string
 *           - type       (optional) - string default 'submit' (also allowed: 'button','reset')
 *           - template   (optional) - string template
 *           - class      (optional) - string default 'btn'
 *           - image      (optional) - string default none
 *           - text       (optional) - text default ''
 *           - value      (optional) - int, get from assigned variables by default
 *           - arr        (optional) - array / string with params
 *           - orientation (optional) - image orientation, default 'fixr' ('fixr', 'fixl')<br>
 * Possible templates:<br>
 *  ok, newitem, forward, back, finish, edit, save, add, saveandnew, delete, cancel, close, print, search, list, attach, help, reset, restore, import, export
 * The name of the button is the name of the template. 
 * Almost all buttons are of type "submit" per default, exceptions are:
 *  - "close": type="button" onClick="window.close();"
 *  - "cancel": type="button" onClick="window.close();"
 *  - "help": type="button", opens help window onClick
 *  - "reset": type="reset"
 *
 * @author Patrick Prasse <pprasse@actindo.de>
 * @version $Revision$
 * @param array
 * @param Smarty
 * @return string
 * @uses smarty_function_escape_special_chars()
 */
function smarty_function_html_button($params, $smarty)
{
  global $_smarty_html_button_ie_wa_output, $is_pda;
  $is_ie = 0; /* strpos(strtoupper($_SERVER['HTTP_USER_AGENT']), 'MSIE') !== false; */

  require_once $smarty->_get_plugin_filepath('shared','escape_special_chars');
  require_once $smarty->_get_plugin_filepath('shared','get_var');
  require_once $smarty->_get_plugin_filepath('function','png_image');

  if( isset($params['arr']) )
  {
    if( is_string($params['arr']) )
      $params = array( 'template' => $params['arr'] );
    else
      $params = $params['arr'];
  }

  $name = '';
  $value = '1';
  $type = 'submit';
  $template = '';
  $class = 'btn';
  $image = '';
  $text = '';
  $value = null;
  $extra = null;
  $orientation = 'fixr';

  if( isset($params['template']) )
  {
    $p = _html_button_get_template( $params['template'] );
    extract( $p, EXTR_OVERWRITE, '' );
  }

  foreach($params as $_key => $_val) {
      switch($_key) {
      case 'extra':
      case 'name':
      case 'value':
      case 'image':
      case 'text':
      case 'template':
      case 'type':
      case 'orientation':
      case 'onClick':     // everything else is handled in default:
      case 'class':
         $$_key = $_val;
         break;

      case 'size':
         $$_key = (int)$_val;
         break;

      case 'disabled':
        $extra .= $_val ? ' disabled="1"' : '';
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

  if( empty($p['class']) && empty($params['class']) )
  {
    if( $is_pda )
      $class = 'btn';
    else
    {
      strlen($text) >= 7 or $class = 'btn_s';
      strlen($text) <= 14 or $class = 'btn_xl';
    }
  }

  if( $onClick )
  {
    $onClick = rtrim( $onClick );
    $onClick .= $onClick{strlen($onClick)-1} == ';' ? '' : ';';
    if( $is_ie && $type == 'submit' )
      $onClick .= 'IEButtonWa(this);';
    $extra .= ' onClick="'.smarty_function_escape_special_chars($onClick).'"';
  }

  if( $is_pda )
  {
    return '<input type="'.$type.'" class="'.$class.'" name="'.smarty_function_escape_special_chars($name).'" value="'.smarty_function_escape_special_chars($text).'" '.$extra.'>';
  }


  $_html_result = '<button type="'.$type.'" class="'.$class.'" name="'.smarty_function_escape_special_chars($name).'" value="'.smarty_function_escape_special_chars($value).'" '.$extra.'>';
  if( $orientation == 'fixr' )
  {
    empty($text) or $_html_result .= $text;
    empty($image) or $_html_result .= smarty_function_png_image( array('src'=>$image,'class'=>$orientation), $smarty );
  }
  else
  {
    empty($image) or $_html_result .= smarty_function_png_image( array('src'=>$image,'class'=>$orientation), $smarty );
    empty($text) or $_html_result .= $text;
  }

  $_html_result .= '</button>';
  if( $is_ie && !$_smarty_html_button_ie_wa_output )
  {
    $_smarty_html_button_ie_wa_output = 1;
    $_html_result .= <<<ENDJS
\n<script type="text/javascript">
<!--
function IEButtonWa( btn )
{
  var btns = document.getElementsByTagName('button');
  alert( 'IEButtonWa' );
  for( var i in btns )
  {
    alert( btns[i].name );
    if( btn.form != btns[i].form || btns[i] == btn )
      continue;
    btns[i].disabled = true;
  }
}
//-->
</script>
ENDJS;
  }

  return $_html_result;
}


function _html_button_get_template( $template )
{
  global $baseurl, $page;

  switch( strtolower($template) )
  {
    case 'ok':
      $name = 'ok';
      $image = 'images/button/ok.gif';
      $text = 'OK';
      $type = 'submit';
      break;

    case 'yes':
      $name = 'yes';
      $image = 'images/button/ok.gif';
      $text = 'Ja';
      $type = 'submit';
      break;

    case 'no':
      $name = 'no';
      $image = 'images/button/cancel.gif';
      $text = 'Nein';
      $type = 'submit';
      break;

    case 'newitem':
      $name = 'newitem';
      $image = 'images/button/newitem.gif';
      $text = 'Neu...';
      $type = 'submit';
      break;

    case 'forward':
      $name = 'forward';
      $image = 'images/button/pg-next.gif';
      $text = 'Weiter';
      $type = 'submit';
      break;

    case 'back':
      $name = 'back';
      $image = 'images/button/pg-prev.gif';
      $orientation = 'fixl';
      $text = 'Zurück';
      $type = 'submit';
      break;

    case 'finish':
      $name = 'finish';
      $image = 'images/button/';
      $text = 'Fertigstellen';
      $type = 'submit';
      break;

    case 'edit':
      $name = 'edit';
      $image = 'images/button/newitem.gif';
      $text = 'Bearbeiten...';
      $type = 'submit';
      break;

    case 'save':
      $name = 'save';
      $image = 'images/button/save.gif';
      $text = 'Speichern';
      $type = 'submit';
      break;

    case 'add':
      $name = 'save';
      $image = 'images/button/add.gif';
      $text = 'Hinzufügen';
      $type = 'submit';
      break;

    case 'saveandnew':
      $name = 'saveandnew';
      $image = 'images/button/saveandnew.gif';
      $text = 'Speichern & Neu';
      $type = 'submit';
      break;

    case 'delete':
      $name = 'delete';
      $image = 'images/button/delete.gif';
      $text = 'Löschen';
      $type = 'submit';
      break;

    case 'cancel':
      $name = 'cancel';
      $image = 'images/button/cancel.gif';
      $text = 'Abbrechen';
      $type = 'button';
      $onClick = "window.close();";
      break;

    case 'close':
      $name = 'close';
      $image = 'images/button/close.gif';
      $text = 'Schließen';
      $type = 'button';
      $onClick = "window.close();";
      break;

    case 'print':
      $name = 'print';
      $image = 'images/button/print.gif';
      $text = 'Drucken';
      $type = 'submit';
      $class = 'btn_s';
      break;

    case 'search':
      $name = 'search';
      $image = 'images/button/search.gif';
      $text = 'Suchen';
      $type = 'submit';
      break;

    case 'replace':
      $name = 'replace';
      $image = 'images/button/search.gif';
      $text = 'Ersetzen';
      $type = 'submit';
      break;

    case 'list':
      $name = 'list';
      $image = 'images/button/list.gif';
      $text = 'Liste';
      $type = 'submit';
      break;

    case 'attach':
      $name = 'attach';
      $image = 'images/button/attach.gif';
      $text = 'Attachment';
      $type = 'submit';
      break;

    case 'help':
      $name = 'help';
      $image = 'images/button/help.gif';
      $text = 'Hilfe';
      $type = 'button';
      $onClick = "_hlpwin=window.open('{$baseurl}?page=help/help_agent&refpage={$page}','help_agent','screenX=0,screenY=0,top=0,left=0,width='+(screen.availWidth<1024?screen.availWidth:1024)+',height='+(screen.availHeight<720?screen.availHeight:720)+',resizable=yes,scrollbars=no'); _hlpwin.focus();";
      break;

    case 'reset':
      $name = 'b_reset';
//      $image = 'images/button/';
      $text = 'Reset';
      $type = 'reset';
      break;

    case 'restore':
      $name = 'restore';
//      $image = 'images/button/';
      $text = 'Wiederherstellen';
      $type = 'submit';
      break;

    case 'import':
      $name = 'import';
      $image = 'images/button/import.gif';
      $text = 'Import';
      $type = 'submit';
      break;

    case 'export':
      $name = 'export';
      $image = 'images/button/export.gif';
      $text = 'Export';
      $type = 'submit';
      break;

    case 'test':
      $name = 'test';
//      $image = 'images/button/';
      $text = 'Test';
      $type = 'submit';
      break;

    default:
      break;
  }

  $params = compact( 'name', 'image', 'text', 'type', 'onClick', 'value', 'class' );
  return $params;
}

?>