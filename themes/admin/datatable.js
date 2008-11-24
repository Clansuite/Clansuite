var datatable_search_timeout = new Array();

function createRowsArray (table) {
  var rows = new Array();
  var r = 0;
  if (table.tHead == null && table.tFoot == null)
    for (var r1 = 0; r1 < table.rows.length; r1++, r++)
      rows[r] = table.rows[r1];
  else  
    for (var t = 0; t < table.tBodies.length; t++)
      for (var r1 = 0; r1 < table.tBodies[t].rows.length; r1++, r++)
        rows[r] = table.tBodies[t].rows[r1];
  return rows;
}

function visibleRows( rows )
{
  var n = 0;
  var vrows = new Array( );
  for( var i in rows )
  {
    if( rows[i].style.display != 'none' )
      vrows[vrows.length] = rows[i];
  }
  return vrows;
}

function insertSortedRows(table, rows) 
{
  var cycle = parseInt( table.getAttribute('pp_cycle') );

  if (document.all) var rowsCopy = new Array(rows.length)
  for (var r = 0; r < rows.length; r++) {
    if (document.all) rowsCopy[r] = rows[r].cloneNode(true);
    table.deleteRow(rows[r].rowIndex);
  }

  if( document.all )
    rowsCopy = tableSearch( table, rowsCopy );
  else
    rows = tableSearch( table, rows );

  var tableSection = table.tBodies[table.tBodies.length - 1];
  for (var r = 0; r < rows.length; r++)
  {
    var row = document.all ? rowsCopy[r] : rows[r];
    if( cycle )
      row.className = (r % 2 != 0 ? 'h' : 'n')
    tableSection.appendChild(row);
  }
}

function tableSearch( table, rows )
{
  var srch = document.getElementsByName( 'txt_'+table.id );
  if( srch && srch.length && srch[0].value.length )
  {
    var processed=0;
    srch = srch[0].value.toLowerCase();
    for( var i=0; i<rows.length; i++ )
    {
      var is_in = 0;
      for( j=0; j<rows[i].childNodes.length; j++ )
      {
        if( !rows[i].childNodes[j].firstChild )
          continue;
        val = rows[i].childNodes[j].firstChild.nodeValue;
//        is_in |= ( val.toLowerCase().search( srch ) != -1 );
        is_in |= ( val.toLowerCase().indexOf( srch ) != -1 );
        processed++;
      }
      if( !is_in )
        rows[i].style.display = 'none';
      else
        rows[i].style.display = '';
    }
//    alert( processed);
  }
  else
  {
    for( var i=0; i<rows.length; i++ )
    {
        rows[i].style.display = '';
    }
  }
  return rows;
}


function sortRowsAlpha (row1 , row2) {
  var column = sortRowsAlpha.col;
  var cell1 = row1.cells[column].firstChild.nodeValue;
  var cell2 = row2.cells[column].firstChild.nodeValue;
  return cell1 < cell2 ? - 1 : (cell1 == cell2 ? 0 : 1);
}
function sortRowsNumber (row1 , row2) {
  var column = sortRowsNumber.col;
  var cell1 = parseFloat(row1.cells[column].firstChild.nodeValue);
  var cell2 = parseFloat(row2.cells[column].firstChild.nodeValue);
  return cell1 < cell2 ? - 1 : (cell1 == cell2 ? 0 : 1);
}
function findFirstLinkChild (el) {
  var child = el.firstChild;
  while (child.tagName != 'A')
    child = child.nextSibling;
  return child;
}
function testSortTableAlpha(table, col) {
  sortRowsAlpha.col = col;
  sortTable(table, sortRowsAlpha);
  table.setAttribute( 'last_sortCol', col );
  table.setAttribute( 'last_sortFun', 'Alpha' );
}
function testSortTableNumerical (table, col) {
  sortRowsNumber.col = col;
  sortTable(table, sortRowsNumber);
  table.setAttribute( 'last_sortCol', col );
  table.setAttribute( 'last_sortFun', 'Numerical' );
}

function sortTable (table, sortFun) 
{
  var rows = createRowsArray(table);
  if (rows.length > 0) 
  {
    rows.sort(sortFun);
    insertSortedRows(table, rows);
  }
}

function do_searchTable( table_id )
{
  var table = document.getElementById( table_id );
  var sortCol = parseInt( table.getAttribute('last_sortCol') );
  var sortFun = table.getAttribute('last_sortFun');
  if( sortFun == 'Numerical' )
    testSortTableNumerical( table, sortCol );
  else if( sortFun == 'Alpha' )
    testSortTableAlpha( table, sortCol );
  else if( sortFun == '' )
  {
    var rows = createRowsArray( table );
    insertSortedRows( table, rows );
  }
}

function searchTable( table_id, event )
{
  if( event && event.keyCode == 13 )
  {
    var vrows;
    var table = document.getElementById( table_id );
    do_searchTable( table_id );
    vrows = visibleRows( createRowsArray(table) );
    if( vrows.length == 1 )
    {
      if( vrows[0].getAttribute('onClick').length )
      {
        eval( vrows[0].getAttribute('onClick') );
        return false;
      }
    }
    return false;
  }
  if( datatable_search_timeout[table_id] )
  {
    window.clearTimeout( datatable_search_timeout[table_id] );
    datatable_search_timeout[table_id] = undefined;
  }
  datatable_search_timeout[table_id] = window.setTimeout( 'do_searchTable("'+table_id+'")', 500 );
  return true;
}

function row_hl( table, row )
{
  if( row.className.match(/sel/) )
    return;
  row.className = row.getAttribute('dflt_class') + '_hl';
}

function row_ll( table, row )
{
  if( row.className.match(/sel/) )
    return;
  row.className = row.getAttribute('dflt_class');
}

function row_sel( table, row )
{
  var selectable = parseInt( table.getAttribute('pp_selectable') );
  if( !selectable )
    return;
  else if( selectable == 2 )
  {
    if( row.className.match(/sel/) )
      row.className = row.getAttribute('dflt_class');
    else
      row.className = row.getAttribute('dflt_class') + '_sel';
  }
  else
  {
    for (var t = 0; t < table.tBodies.length; t++)
      for (var r1 = 0; r1 < table.tBodies[t].rows.length; r1++)
        table.tBodies[t].rows[r1].className = table.tBodies[t].rows[r1].getAttribute('dflt_class');

    row.className = row.getAttribute('dflt_class') + '_sel';
  }
}

function datatable_get_selected( table )
{
  var sel = new Array();
  var r=0;
  for (var t = 0; t < table.tBodies.length; t++)
    for (var r1 = 0; r1 < table.tBodies[t].rows.length; r1++, r++)
    {
      if( table.tBodies[t].rows[r1].className.match(/sel/) )
        sel[sel.length] = r;
    }
  return sel;
}