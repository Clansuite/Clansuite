<h2>User :: Search</h2>

{move_to}
<link rel="stylesheet" type="text/css" href="{$www_root_themes_core}/admin/datatable.css" />
<script type="text/javascript" src="{$www_root_themes_core}/admin/datatable.js"></script>
{/move_to}

{* Debugoutput *}
{if $smarty.const.DEBUG eq "1"} Debugausgabe des Arrays:  {html_alt_table loop=$users}   {/if}
{$users|@var_dump}

<br />

<center>

 {datatable data=$users 
            sortable=1 
            cycle=1 
            mouseover=1 
            searchable=1 
            width="80%" 
            row_onclick="row_clicked( '\$user_id', '\$nick', '\$email')"}
      
      {column id="user_id" name="User ID" align="left" sorttype="Numerical"}
      {column id="email" name="Email" align="left"}
      {column id="first_name" name="Firstname" align="left"}
      {column id="nick" name="Nick" align="left"}
      {column id="last_name" name="Lastname" align="left"}
      {column id="joined" name="Joined" align="left"}
 
 {/datatable}


<a href="index.php?mod=controlcenter&sub=users&action=add_new_user">- Create User-Account -</>
<br />
<a href="index.php?mod=controlcenter&sub=users&action=search">- Search -</a>

</center>

{literal}
<script type="text/javascript">
function row_clicked( user_id, nick, email )
{
  alert( 'You clicked user_id '+user_id+', name is '+nick+', email is '+email+'.' );
  window.open( 'index.php?mod=controlcenter&sub=users&action=edit&userid=+user_id+','popup','resizable=0,location=0,directories=0,status=0,menubar=0,scrollbars=0,toolbar=0,width=400,height=400');

}
</script>
{/literal}