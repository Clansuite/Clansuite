<?php ?>
<html>
<head>
<title>ClanSuite.com - just an eSport CMS - Installer</title>
<link rel="stylesheet" type="text/css" href="installation.css" />
</head>
<body>
<center>
<h1>Installer</h1>
<br />
This Installer will guide you in 3 small steps through the hole installation of the <b>C</b>ontent <b>M</b>anagement <b>S</b>ystem (CMS) ClanSuite.
<p>&nbsp;</p>
<form autocomplete="off" action="index.php?step=3" method="POST">
<table style="border: 1px solid black" width="400">
    <tr>
        <td height="50" colspan="2" align="center"><b>Step 2:</b> Database data</td>
    </tr>
            <?php
                if( $_GET['error'] == 'fill_form' )
                {
                    echo '<tr><td height="30" align="center" colspan="2"><div class="error">Please fill all fields in the form!</div></td></tr>';
                }
                if( $_GET['error'] == 'no_connection' )
                {
                    echo '<tr><td height="30" align="center" colspan="2"><div class="error">The connection could not be established with the given data!</div></td></tr>';
                }
            ?>
    <tr>
        <td width="150" align="right">DB type:</td>
        <td align="left">
        <select name="config[db_type]" class="inputs">
            <option value="mysql">MySQL</option>
        </select>
        </td>
    </tr>
    <tr>
        <td width="100" align="right">Host:</td>
        <td align="left"><input class="inputs" type="text" name="config[db_host]" value="<?=$_SESSION['config']['db_host'] ?>" /></td>
    </tr>
    <tr>
        <td width="100" align="right">DB name:</td>
        <td align="left"><input class="inputs" type="text" name="config[db_name]" value="<?=$_SESSION['config']['db_name'] ?>" /></td>
    </tr>
    <tr>
        <td width="100" align="right" class="desc">Prefix:</td>
        <td align="left"><input class="inputs" type="text" name="config[db_prefix]" value="<?=$_SESSION['config']['db_prefix'] ?>" /></td>
    </tr>
    <tr>
        <td width="100" align="right" class="desc">Username:</td>
        <td align="left"><input class="inputs" type="text" name="config[db_username]" value="<?=$_SESSION['config']['db_username'] ?>" /></td>
    </tr>
    <tr>
        <td width="100" align="right" class="desc">Password:</td>
        <td align="left"><input class="inputs" type="password" name="config[db_password]" value="<?=$_SESSION['config']['db_password'] ?>" /></td>
    </tr>
    <tr>
        <td height="70" colspan="2" align="center">
            <input type="submit" style="border: 1px solid black" value="Generate Tables..." name="submit" />
            <br /><br />
            <a href="/install/index.php?step=1"><<< Back to Step 1</a>
            <br />
            <small>By clicking the button above you accept the <a href="/COPYING.txt">GPL Licensing</a>.</small>
        </td>
    </tr>
</table>
</form>
</center>

</body>
</html>