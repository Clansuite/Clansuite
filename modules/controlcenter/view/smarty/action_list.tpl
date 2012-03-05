<div align="center">
<!-- Control Center Heading -->
<h3>
    {t}Welcome.{/t}
    <br />
    {t}This is the Control Center of Clansuite.{/t}
</h3>

<!-- Table with Shortcuts, Security Info and News Feed -->
<table cellspacing="6" width="100%">
<tbody>
    <tr>
        <td width="70%" valign="top">
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