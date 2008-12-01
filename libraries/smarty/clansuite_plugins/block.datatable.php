<?php

/**
 * Smarty plugin
 * @package Smarty
 * @subpackage actindo_plugins
 */


/**
 * Smarty {datatable} block plugin
 *
 * Uses lots of javascript and css and does basically everything except cooking coffee.
 *
 * File:       block.datatable.php<br>
 * Type:       block<br>
 * Name:       datatable<br>
 * Date:       29.Nov.2005<br>
 * Purpose:    Prints out a datatable with lots of options<br>
 * Parameters:<br>
 *           - data       (required) - data-Array (or data-Object) (for example array( array('col1'=>1, 'col2'=>2, 'col3'=>3), ... )
 *           - sortable   (optional) - make tabs sortable, default 0
 *           - searchable (optional) - make table sortable, default 0
 *           - mouseover  (optional) - mouseover effect, default 0
 *           - selectable (optional) - make rows selectable (1=yes, 2=multiple), default 0
 *           - cycle      (optional) - cycle btw. alternate row colors, default 0
 *           - width      (optional) - Width of table
 *           - id         (optional) - id tag of datatable
 *           - class      (optional) - class tag of datatable
 *           - row_*      (optional) - tags of <tr> elements can be defined here (like row_onClick="" --> <tr onClick="">)<br>
 *             With all row_on* tags you can do variable substitution (Example: row_onClick="click_handler( \$col1 )" becomes <tr onClick="click_handler( [value of col1] )"> )
 * Example:
 * <pre>
 * {datatable data=$data_array sortable="1" cycle="1" width="100%" row_onClick="click_handler( \$col1 )"}
 *  {column id="col1" name="A" align="center" checkboxes="box"}
 *   - this one creates a &lt;input type="checkbox" name="box[]" value="[value-of-col1]"&gt; in this column
 *     and checks for "checked" using get_var with the name "box"
 *  {column id="col1" name="Column #1 Table Heading" align="right" headeralign="left"}
 *  {column id="col2" name="Column #2 Table Heading" align="left"}
 *  {column id="col3" name="Column #3 Table Heading" align="center" default="&nbsp;"}
 * {/datatable}
 * </pre>
 *
 * @author Patrick Prasse <pprasse@actindo.de>
 * @version $Revision$
 * @param array
 * @param string
 * @param Smarty
 * @param int
 * @return string
 */
function smarty_block_datatable( $params, $content, &$smarty, &$repeat )
{
  global $_smarty_datatable_stack, $__smarty_datatable_js_output;
  $datatable_idx = count($smarty->_tag_stack)-1;

  require_once $smarty->_get_plugin_filepath('shared','escape_special_chars');
  require_once $smarty->_get_plugin_filepath('shared','get_var');
  require_once $smarty->_get_plugin_filepath('modifier','truncate');
  require_once $smarty->_get_plugin_filepath('function','html_button');
  require_once $smarty->_get_plugin_filepath('function','html_text');
  require_once $smarty->_get_plugin_filepath('function','html_checkboxes');

  if( !isset($content) )
  {
    if( !isset($_smarty_datatable_stack[$datatable_idx]) )
      $_smarty_datatable_stack[$datatable_idx] = array();
    return '';
  }

  $selectable = 0;
  $row_attribs = array( );
  foreach($params as $_key => $_val)
  {
    if( substr($_key,0,4) == 'row_' )
    {
      $row_attribs[substr($_key,4)] = $_val;
      continue;
    }
    switch($_key) 
    {
      case 'class':
      case 'id':
        $$_key = $_val;
        break;

      case 'sortable':
      case 'cycle':
      case 'searchable':
      case 'mouseover':
      case 'selectable':
        $$_key = (int)$_val;
        break;

      case 'data':
        $$_key = (array)$_val;
        break;

      default:
        if(!is_array($_val))
          $extra .= ' '.$_key.'="'.smarty_function_escape_special_chars($_val).'"';
        else
          $smarty->trigger_error("html_select: extra attribute '$_key' cannot be an array", E_USER_NOTICE);
        break;
    }
  }

  isset($class) or $class = 'artikel_liste';
  isset($id) or $id = 'datatable_'.$datatable_idx;

  $htmlcode = '';
  if( $searchable )
  {
    $htmlcode .= '<table class="artikel_liste" '.$extra.' cellpadding="0" cellspacing="0"><tr class="heading">';
    $htmlcode .= '<td style="text-align: left; width: 150px;">Suchen:</td>';
    $htmlcode .= '<td style="text-align: left; width: 150px;">'.smarty_function_html_text( array('name'=>'txt_'.$id, 'size'=>20, 'maxlength'=>20, 'onKeyUp'=>"searchTable('{$id}',event)", 'onKeyDown'=>"searchTable('{$id}',event)", 'autocomplete'=>'off'), $smarty ).'</td>';
    $htmlcode .= '<td style="text-align: left;">'.smarty_function_html_button( array('template'=>'reset', 'type'=>'button', 'onClick'=>"document.getElementsByName( 'txt_{$id}' )[0].value = ''; searchTable('{$id}');"), $smarty ).'</td>';
    $htmlcode .= '<td>&nbsp;</td></tr></table>';
  }

  $htmlcode .= '<table id="'.$id.'" border="0" class="'.$class.'" cellpadding="0" cellspacing="0" '.$extra.' pp_cycle="'.(int)$cycle.'" last_sortFun="" last_sortCol="-1" pp_selectable="'.$selectable.'">';
  $htmlcode .= '<thead><tr class="heading">';
  $col_idx = 0;
  foreach( $_smarty_datatable_stack[$datatable_idx] as $col_id => $col )
  {
    isset($col['sortable']) or $col['sortable'] = 1;
    isset($col['sorttype']) or $col['sorttype'] = 'Alpha';
    $htmlcode .= '<td style="'.(isset($col['width']) ? 'width: '.$col['width'].';' : '').(isset($col['headeralign']) ? 'text-align: '.$col['headeralign'].';' : '').'">';
    if( $sortable && $col['sortable'] )
    {
      $htmlcode .= '<a class="nodeco" href="javascript: testSortTable'.$col['sorttype'].'( document.getElementById(\''.$id.'\'), '.$col_idx.' )">';
    }
    $htmlcode .= $col['name'];
    if( $sortable && $col['sortable'] )
      $htmlcode .= '</a>';
    $htmlcode .= '</td> ';
    $col_idx++;
  }
  $htmlcode .= '</tr></thead><tbody>';

  $rowidx = 0;
  foreach( $data as $rowid => $row )
  {
    if( $cycle )
      $tr_class = ( $rowidx % 2 != 0 ? 'h' : 'n' );
    else
      $tr_class = 'n';
    $tr_extra = '';
    $row_js = array();
    foreach( $row_attribs as $_key => $_val )
    {
      if( substr($_key,0,2) != 'on' )
      {
        $_val = "{$_val}";
        $tr_extra .= " {$_key}=\"{$_val}\"";
      }
      else
      {
        $row['__rowidx'] = $rowidx;
        $row_js[strtolower($_key)][] = smarty_block_datatable_fill_var( $_val, $row );
      }
    }
    if( $mouseover )
    {
      $row_js['onmouseover'][] = 'row_hl( document.getElementById(\''.$id.'\'), this )';
      $row_js['onmouseout'][] = 'row_ll( document.getElementById(\''.$id.'\'), this )';
    }
    if( $selectable )
    {
      $row_js['onclick'][] = 'row_sel( document.getElementById(\''.$id.'\'), this )';
    }
    foreach( $row_js as $_key => $codes )
    {
      is_array($codes) or $codes = array( $codes );
      $str = '';
      foreach( $codes as $cod )
      {
        $cod = trim( $cod );
        if( $cod{strlen($cod)-1} != ';' )
          $cod .= ';';
        $str .= $cod;
      }
      $tr_extra .= " {$_key}=\"{$str}\"";
    }

    $htmlcode .= '<tr class="'.$tr_class.'" dflt_class="'.$tr_class.'" '.$tr_extra.'>';
    foreach( $_smarty_datatable_stack[$datatable_idx] as $col_id => $col )
    {
      if( empty($col['checkboxes']) )
      {
        $title = '';
        isset($col['output_printf']) or $col['output_printf'] = '%s';
        $tmp = sprintf( $col['output_printf'], $row[$col['id']] );
        if( $col['truncate'] )
        {
          $tmp1 = smarty_modifier_truncate( $tmp, (int)$col['truncate'] );
          if( strlen($tmp) != strlen($tmp1) )
            $title = $tmp;
        }
        else
          $tmp1 = $tmp;

        if( !strlen($tmp1) && isset($col['default']) )
          $tmp1 = $col['default'];
      }
      elseif( !empty($col['checkboxes']) )
      {
        if( !isset($col['checkboxes_sel']) )
        {
          $tmp = smarty_function_get_var( $col['checkboxes'], $smarty );
          $_smarty_datatable_stack[$datatable_idx][$col_id]['checkboxes_sel'] = $col['checkboxes_sel'] = is_array($tmp) ? $tmp : array( );
        }
        $tmp1 = smarty_function_html_checkboxes_output( $col['checkboxes'], $row[$col['id']], '', $col['checkboxes_sel'], '', '', 0 );
      }

      $htmlcode .= '<td style="'.(isset($col['align']) ? 'text-align: '.$col['align'].';' : '').'" '.(!empty($title) ? 'title="'.$title.'"' : '').'>';
      $htmlcode .= $tmp1;
      $htmlcode .= '</td>';
    }
    $htmlcode .= '</tr>';   // DO NOT add linebreak here, triggers mozilla bug (sorting takes like 10 seconds for 2 rows!)
    $rowidx++;
  }

  $htmlcode .= '</tbody></table>';

  if( !$__smarty_datatable_js_output )
  {
    $__smarty_datatable_js_output = true;
    $htmlcode .= <<<ENDJS
\n<script language="JavaScript" type="text/javascript">
var sort_col = 0;
</script>\n
ENDJS;
  }
    $htmlcode .= <<<ENDJS
\n<script language="JavaScript" type="text/javascript">
var sort_{$id}_idx = -1;
var sortorder_{$id} = false;\n
var rows_{$id} = new Array();
var sort_col = 0;
</script>
ENDJS;

  unset( $_smarty_datatable_stack[$datatable_idx] );
  return $htmlcode;
}


function smarty_block_datatable_fill_var( $def, $row )
{
  return eval( 'extract($row); return "'.$def.'";' );
}

?>