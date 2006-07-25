<h2>Show All Users</h2>

{doc_raw}
<link rel="stylesheet" type="text/css" href="{$www_core_tpl_root}/admin/datatable.css" />
<script type="text/javascript" src="{$www_core_tpl_root}/admin/datatable.js"></script>
{/doc_raw}

Debugausgabe des Arrays:
{html_alt_table loop=$users}

<br />
<center>
Formatierte Tabelle mit Javascript: <a href="/index.php?mod=admin&sub=users&action=search">Advanced User-Search</>
 {datatable data=$users  sortable=1 cycle=1 mouseover=1 selectable=2 searchable=1 width="80%" row_onClick="row_clicked( '\$user_id', '\$nick', '\$email')"}
  {column id="user_id" name="User ID" align="left" sorttype="Numerical"}
  {column id="email" name="Email" align="left"}
  {column id="first_name" name="Firstname" align="left"}
  {column id="nick" name="Nick" align="left"}
  {column id="last_name" name="Lastname" align="left"}
  {column id="joined" name="Joined" align="left"}
 {/datatable}
</center>

{literal}
<script language="JavaScript" type="text/javascript">
function row_clicked( user_id, nick, email )
{
  alert( 'You clicked user_id '+user_id+', name is '+nick+', email is '+email+'.' );
}
</script>
{/literal}
