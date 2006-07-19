<h2>Usercenter</h2>

Debugausgabe des $usercenterdata Arrays:
{html_alt_table loop=$usercenterdata}

<div>
<form method='post' action='http://demo.opensourcecms.com/e107/e107_admin/users.php'>
    <div>
        <select name='sitelanguage'>
                                    <option value='' selected='selected'>English</option>
        </select>
    		
    	<br />
    	<br />
    	
    	<input class='button' type='submit' name='setlanguage' value='Set Language' />
	</div>
</form>
</div>