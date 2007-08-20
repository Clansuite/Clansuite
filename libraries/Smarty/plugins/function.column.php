<?php

/**
 * Smarty plugin
 * @package Smarty
 * @subpackage actindo_plugins
 */


/**
 * Smarty {columnn} block plugin
 *
 * This function does virtually nothing except adding the column to the table column description stack,
 * see block.datatable.php for reference.
 *
 * File:       function.column.php<br>
 * Type:       function<br>
 * Name:       column<br>
 * Date:       29.Nov.2005<br>
 * Purpose:    Define datatable column, for documentation see block "datatable" <br>
 * Parameters:<br>
 *           - id         (required) - column-ID in data array
 *           - name       (required) - text in table heading
 *           - align      (optional) - align of data in cell
 *           - headeralign (optional) - align of text in header cell
 *           - sortable   (optional) - override global table "sortable" attribute for this column
 *           - sorttype   (optional) - set sort algorithm ('Alpha' for alphanumeric(default), 'Numerical' for numeric, 'Link' for Link sorting, <b>obey case!</b>)
 *           - truncate   (optional) - truncate cell contents at char number (default 0, don't truncate)
 *           - checkboxes (optional) - to display checkboxes in this row, set this arg to the name of the boxes ('[]' is added automagically)
 *           - default    (optional) - Text to display if display text is empty
 *           - output_printf (optional) - Format for output (default "%s")
 * @author Patrick Prasse <pprasse@actindo.de>
 * @version $Revision$
 * @param array
 * @param Smarty
 * @return string
 */
function smarty_function_column( $params, &$smarty )
{
  global $_smarty_datatable_stack;

  for( $i=count($smarty->_tag_stack)-1; $i>=0; $i-- )
  {
    if( $smarty->_tag_stack[$i][0] == 'datatable' )
    {
      $datatable_idx = $i;
      $datatable_params = $smarty->_tag_stack[$i][1];
      break;
    }
  }
  if( !isset($datatable_params) )
    $smarty->trigger_error( 'Tag {column} without {datatable}!', E_USER_ERROR );

  $_smarty_datatable_stack[$datatable_idx][] = $params;

  return '';
}

?>