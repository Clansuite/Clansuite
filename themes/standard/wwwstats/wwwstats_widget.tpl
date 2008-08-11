{* {$stats|@var_dump} *}

<table cellpadding="3" cellspacing="0" border="0" width="100%" align="center">
    <tr>
        <td class="td_header" colspan="2">{t}Statistics{/t}</td>
    </tr>
    <tr>
        <td>
            Online:
        </td>
        <td>
            {$stats.online}
        </td>
    </tr>
    <tr>
        <td>
             - Users:
        </td>
        <td>
             {$stats.authed_users}
        </td>
    </tr>
    <tr>
        <td>
             - Guests:
        </td>
        <td>
             {$stats.guest_users}
        </td>
    </tr>
    <tr>
        <td>
              Today:
        </td>
        <td>
              {$stats.today_impressions}
        </td>
    </tr>
    <tr>
        <td>
              Yesterday:
        </td>
        <td>
              {$stats.yesterday_impressions}
        </td>
    </tr>
    <tr>
        <td>
              Month:
        </td>
        <td>
              {$stats.month_impressions}
        </td>
    </tr>
    <tr>
        <td>
              This Page:
        </td>
        <td>
              {$stats.page_impressions}
        </td>
    </tr>
    <tr>
        <td>
              Total Impressions:
        </td>
        <td>
              {$stats.all_impressions}
        </td>
    </tr>
</table>
