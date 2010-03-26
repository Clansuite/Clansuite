<h2>User :: Search</h2>

{move_to target="pre_head_close"}
<link rel="stylesheet" type="text/css" href="{$www_root_themes_core}/admin/datatable.css" />
<script type="text/javascript" src="{$www_root_themes_core}/admin/datatable.js"></script>
{/move_to}

{* Debugoutput {$users|@var_dump}*}

<br />

<center>

<a href="index.php?mod=controlcenter&sub=users&action=add_new_user">- Create User-Account -</>
<br />
<a href="index.php?mod=controlcenter&sub=users&action=search">- Search -</a>

</center>


<script type="text/javascript">
function row_clicked( user_id, nick, email )
{
  alert( 'You clicked user_id '+user_id+', name is '+nick+', email is '+email+'.' );
  window.open( 'index.php?mod=controlcenter&sub=users&action=edit&userid=+user_id+','popup'
  ,'resizable=0,location=0,directories=0,status=0,menubar=0,scrollbars=0,toolbar=0,width=400,height=400');

}
</script>