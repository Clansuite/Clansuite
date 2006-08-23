<h2>User :: Show All </h2>

{* todo : Debugausgabe nur wenn DEBUG = 1 *}
{if debug == "1"} Debugausgabe des Arrays:  {html_alt_table loop=$users}   {/if}

<br />
<center>

 
 
<a href="index.php?mod=admin&sub=users&action=add_new_user">- Create User-Account -</>
<br />
<a href="index.php?mod=admin&sub=users&action=search">- Search -</a>

</center>