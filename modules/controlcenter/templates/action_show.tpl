<div align="center">
<!-- Control Center Heading -->
<h3>
    {t}Welcome.{/t}
    <br />
    {t}This is the Control Center (CC) of Clansuite.{/t}
</h3>

<!-- Table for Shortscuts and News/Updates Feed -->
<table cellspacing="6" width="100%">
<tbody>
    <tr>
        <td width="70%" valign="top">

                <!-- Shortcuts Template from the Module or the Theme (autodetected) -->
                {include file="shortcuts.tpl"}

        </td>

        <td width="30%" valign="top">

        <!-- Clansuite Newsfeed Box -->
        <table cellspacing="0" cellpadding="0">
            <tbody>
                <tr height="14">
                    <td style="min-width: 120px; background-color: #FF924F;" width="33%" valign="bottom"><b>&nbsp;&raquo; Clansuite News</b></td>
                    <td nowrap="" background="{$www_root}/modules/controlcenter/images/red-triangle.gif" style="background-repeat: no-repeat;">
                        <div align="right">Last Updated: {$smarty.now|date_format:"%d-%m-%Y"} </div>
                    </td>
                </tr>
                <tr>
                    <td height="3" bgcolor="#FF924F" colspan="2"/>
                </tr>
                <tr>
                    <td bgcolor="#dde9cf" valign="top" colspan="2">
                        <table cellspacing="1" border="0" width="100%" border="1">
                        <tbody>
                            <tr bgcolor="#ffffff">
                                <td>
                                <table cellspacing="6" width="100%" >
                                    <tbody>
                                    <tr valign="top">

                                        <!-- Assign Data of Clansuite Newsfeed -->
                                        {$newsfeed}

                                    </tr>
                                    <td>
                                        <div align="right">
                                            <a target="blank" href="http://www.clansuite.com/index.php?page=news"><b>More »</b></a>
                                        </div>
                                    </td>
                                    </tr>
                                    </tbody>
                                </table>
                                </td>
                            </tr>
                        </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>

        </td>
    </tr>
</tbody>
</table>
</div>