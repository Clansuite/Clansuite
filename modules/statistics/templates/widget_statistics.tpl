{* {$stats|@var_dump} *}

<table cellpadding="3" cellspacing="0" border="0" width="100%" align="center">
    <tr>
        <td class="td_header" colspan="2">{t}Statistics{/t}</td>
    </tr>
    <tr>
        <td class="cell1">
            Online:
        </td>
        <td class="cell2">
            {$stats.online}
        </td>
    </tr>
    <tr>
        <td class="cell1">
             - Users:
        </td>
        <td class="cell2">
             {* Members Online *}
             {$stats.authed_users}
        </td>
    </tr>
    <tr>
        <td class="cell1">
             - Guests:
        </td>
        <td class="cell2">
             {$stats.guest_users}
        </td>
    </tr>
    <tr>
        <td class="cell1">
              Today:
        </td>
        <td class="cell2">
              {$stats.today_impressions}
        </td>
    </tr>
    <tr>
        <td class="cell1">
              Yesterday:
        </td>
        <td class="cell2">
              {$stats.yesterday_impressions}
        </td>
    </tr>
    <tr>
        <td class="cell1">
              Month:
        </td>
        <td class="cell2">
              {$stats.month_impressions}
        </td>
    </tr>
    <tr>
        <td class="cell1">
              This Page:
        </td>
        <td class="cell2">
              {$stats.page_impressions}
        </td>
    </tr>
    <tr>
        <td class="cell1">
              Total Impressions:
        </td>
        <td class="cell2">
              {$stats.all_impressions}
        </td>
    </tr>
</table>