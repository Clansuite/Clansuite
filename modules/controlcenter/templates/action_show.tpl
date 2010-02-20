<div align="center">
<!-- Control Center Heading -->
<h3>
    {t}Welcome.{/t}
    <br />
    {t}This is the Control Center (CC) of Clansuite.{/t}
</h3>

<!-- Table for Shortcuts and News/Updates Feed -->
<table cellspacing="6" width="100%">
<tbody>
    <tr>
        <td width="70%" valign="top">
            <!-- Shortcuts Template from the Module or the Theme (autodetected) -->
            {include file="shortcuts.tpl"}
            
            &nbsp;
            {include file='security.tpl'}
        </td>
        <td width="30%" valign="top">
            {include file='newsfeed.tpl'}            
        </td>
    </tr>
</tbody>
</table>
</div>