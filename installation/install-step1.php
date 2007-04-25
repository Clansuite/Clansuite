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
<form action="index.php?step=2" method="POST">
<table style="border: 1px solid black" width="400">
    <tr>
        <td height="50" colspan="2" align="center">
            <b>Step 1: </b>Administrator Account
        </td>
    </tr>
            <?php
                if( $_GET['error'] == 'fill_form' )
                {
                    echo '<tr><td height="30" align="center" colspan="2"><div class="error">Please fill all fields in the form!</div></td></tr>';
                }
            ?>
    <tr>
        <td width="150" align="right">First Name:</td>
        <td align="left"><input class="inputs" type="text" name="admin_firstname" value="<?=$_SESSION['admin_firstname'] ?>" /></td>
    </tr>
    <tr>
        <td width="100" align="right" class="desc">Last Name:</td>
        <td align="left"><input class="inputs" type="text" name="admin_lastname" value="<?=$_SESSION['admin_lastname'] ?>" /></td>
    </tr>
    <tr>
        <td width="100" align="right" class="desc">eMail:</td>
        <td align="left"><input class="inputs" type="text" name="config[from]" value="<?=$_SESSION['admin_email'] ?>" /></td>
    </tr>
    <tr>
        <td width="100" align="right" class="desc">Username:</td>
        <td align="left"><input class="inputs" type="text" name="admin_nick" value="<?=$_SESSION['admin_nick'] ?>" /></td>
    </tr>
    <tr>
        <td width="100" align="right" class="desc">Password:</td>
        <td align="left"><input class="inputs" type="password" name="admin_pass" value="<?=$_SESSION['admin_pass'] ?>" /></td>
    </tr>
    <tr>
        <td width="100" align="right" class="desc">Encryption:</td>
        <td align="left">
            <select name="config[encryption]" class="inputs">
                <option value="md5">MD5</option>
                <option value="sha1">SHA1</option>
            </select>
        </td>
    </tr>
    <tr>
        <td height="50" colspan="2" align="center">
            <input type="submit" style="border: 1px solid black" value="Proceed..." name="submit" />
            <br />
            <small>By clicking the button above you accept the <a href="/COPYING.txt">GPL Licensing</a>.</small>
        </td>
    </tr>
</table>
</form>
</center>

</body>
</html>
