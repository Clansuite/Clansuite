{* {$security|var_dump} *}

<!-- Clansuite Security Box -->
<table cellspacing="0" cellpadding="0">
<tbody>
    <tr style="height: 15px;">
        <td style="min-width: 120px; color: black; background-color: #CC1717;" width="33%" valign="bottom">
            <b>&nbsp;&raquo; Clansuite Errorlog</b>
        </td>
        <td nowrap="nowrap" style="background-image:url('{$www_root}modules/controlcenter/images/red-triangle.gif'); background-repeat: no-repeat;">
        </td>
    </tr>
    <tr>
        <td style="height: 3px;" bgcolor="#CC1717" colspan="2"/>
    </tr>
    <tr>
        <td bgcolor="#dde9cf" valign="top" colspan="2">

            <table cellspacing="1" width="100%" border="0">
            <tbody>
                <tr bgcolor="#ffffff">
                    <td>
                        <div>
                            <!-- Assign Data of Clansuite Errorlog -->
                            {$errorlog}
                            <div align="right">
                                <a target="blank" href="http://www.clansuite.com/index.php?page=news"><b>Full Report &raquo;</b></a>
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
            </table>

        </td>
    </tr>
</tbody>
</table>