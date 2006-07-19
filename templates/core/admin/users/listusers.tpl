<h2>Show All Users</h2>

Debugausgabe des Arrays:
{html_alt_table loop=$users}

<br />
<center>
Formatierte Tabelle mit Javascript:
 {datatable data=$users sortable=1 cycle=1 mouseover=1 selectable=2 width="80%" row_onClick="row_clicked( \$__rowidx, \$user_id, '\$email')"}
  {column id="user_id" name="User ID" align="left" sorttype="Numerical"}
  {column id="email" name="Email" align="left"}
  {column id="first_name" name="Firstname" align="left"}
  {column id="nick" name="Nick" align="left"}
  {column id="last_name" name="Lastname" align="left"}
  {column id="joined" name="Joined" align="left"}
  {column id="chkact" name="ACTION" align="right" checkboxes="chk"}
  {column id="chkdel" name="DEL" align="right" checkboxes="chk"}
 {/datatable}
</center>

{literal}
<script language="JavaScript" type="text/javascript">
function row_clicked( nr, user_id, email )
{
  alert( 'You clicked row nr '+nr+', user_id '+user_id+', name is '+email+'.' );
}
</script>
{/literal}
