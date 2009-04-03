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
        <td width="60%" valign="top">

                <!-- Shortcuts Template from the Module or the Theme (autodetected) -->
                {include file="shortcuts.tpl"}

        </td>

        <td width="40%" valign="top">

        <!-- Clansuite Newsfeed Box -->
        <table cellspacing="0" cellpadding="0" width="60%">
            <tbody>
                <tr height="14">
                    <td bgcolor="#FF924F" valign="bottom" width="30%" class="t2">&nbsp;&raquo; Clansuite News </td>
                    <td nowrap="" background="{$www_root}/modules/controlcenter/images/red-triangle.gif" width="100%" style="background-repeat: no-repeat;" class="bg">
                        <div align="right" class="gr">Last Updated: {$smarty.now|date_format:"%d-%m-%Y"} </div>
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