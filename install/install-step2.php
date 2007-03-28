<html>
<head>
<title>ClanSuite.com - just an eSport CMS - Installer</title>
</head>
<body>
<style type="text/css">
    body
    {
        font-size: 11px;
        font-family: Verdana;
    }

    .inputs
    {
        border: 1px solid grey;
    }

    .error
    {
        color: red;
        font-weight: bold;
    }

    table td
    {
        font-size: 11px;
        font-family: Verdana;
    }
</style>
<center>
<h1>Installer</h1>
<br />
This Installer will guide you in 3 small steps through the hole installation of the <b>C</b>ontent <b>M</b>anagement <b>S</b>ystem (CMS) ClanSuite.
<p>&nbsp;</p>
<form action="index.php?step=3" method="POST">
<table style="border: 1px solid black" width="400">
    <tr>
        <td height="50" colspan="2" align="center"><b>Step 2:</b> Generate config (<b>FTP</b>)</td>
    </tr>
            <?php
                if( $_GET['error'] == 'fill_form' )
                {
                    echo '<tr><td height="30" align="center" colspan="2"><div class="error">Please fill all fields in the form!</div></td></tr>';
                }
            ?>
    <tr>
        <td width="150" align="right">Hostname or IP:</td>
        <td align="left"><input class="inputs" type="text" name="ftp_hostname" value="<?=$_SESSION['ftp_hostname'] ?>" /></td>
    </tr>
    <tr>
        <td width="100" align="right" class="desc">Port:</td>
        <td align="left"><input class="inputs" type="text" name="ftp_port" value="<?=$_SESSION['ftp_port'] ?>" /></td>
    </tr>
    <tr>
        <td width="100" align="right" class="desc">Username:</td>
        <td align="left"><input class="inputs" type="text" name="ftp_username" value="<?=$_SESSION['ftp_username'] ?>" /></td>
    </tr>
    <tr>
        <td width="100" align="right" class="desc">Password:</td>
        <td align="left"><input class="inputs" type="password" name="ftp_pass" value="<?=$_SESSION['ftp_pass'] ?>" /></td>
    </tr>
    <tr>
        <td width="100" align="right" class="desc">Folder:</td>
        <td align="left"><input class="inputs" type="password" name="ftp_folder" value="<?=$_SESSION['ftp_folder'] ?>" /></td>
    </tr>
    <tr>
        <td width="100" align="right" class="desc">No FTP Datas?*:</td>
        <td align="left"><input class="inputs" type="checkbox" name="ftp_no_data" value="1" <? if($_SESSION['ftp_no_data']==1) { echo 'checked'; } ?> /></td>
    </tr>
    <tr>
        <td height="70" colspan="2" align="center">
            <input type="submit" style="border: 1px solid black" value="Upload config file..." name="submit" />
            <br /><br />
            <a href="/install/index.php?step=1"><<< Back to Step 1</a>
            <br />
            <small>
                * If you don't have any FTP data you can check the field. After installation you will receive the config file as download, so you can upload it.
                <br /><br />
                By clicking the button above you accept the <a href="/COPYING.txt">GPL Licensing</a>.
            </small>
        </td>
    </tr>
</table>
</form>
</center>

</body>
</html>
