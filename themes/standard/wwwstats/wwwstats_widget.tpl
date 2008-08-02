{* {$stats|@var_dump} *}

<div style="margin-top: 10px">
    <table cellpadding="0" cellspacing="0" border="0" width="60%" align="center">
        <tr>
            <td class="td_header" colspan="2">{t}Statistics{/t}</td>
        </tr>
        <tr>
            <td class="cell1">
              Online: {$stats.online} <br/>
              - Users : {$stats.authed_users} <br />
              - Guests : {$stats.guest_users} <br/>
              Today: {$stats.today_impressions} <br/>
              Yesterday: {$stats.yesterday_impressions} <br/>
              Month: {$stats.month_impressions} <br/>
              <hr width="80%"/>
              This Page: {$stats.page_impressions} <br/>
              Total Impressions: {$stats.all_impressions} <br/>
            </td>
        </tr>
  </table>
</div>