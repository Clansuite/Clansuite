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
<form autocomplete="off" action="index.php?step=4" method="POST">
<table style="border: 1px solid black" width="400">
    <tr>
        <td height="50" colspan="2" align="center">
            <?php   if( !$errors ) echo '<b>DONE!</b>';
                    else echo '<b>Step 4:</b> ERROR!';
            ?>
        </td>
    </tr>

    <tr>
        <td>
        <?php
            if( !$errors ) { echo $output . '<br /><center><div style="color: green; font-weight: bold;">ClanSuite has been setup. You can enter it <a href="../index.php">here</a>.</font></center><p>&nbsp;</p>'; }
            else { echo $output; }
        ?>
        </td>
    </tr>
</table>
</form>
</center>

</body>
</html>
