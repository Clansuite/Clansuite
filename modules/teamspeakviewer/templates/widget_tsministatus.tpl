{* {$serverinfo|@var_dump} *}

<!-- [Start] Widget: Teamspeak Ministatus //-->
<div class="td_header">Teamspeak Online Check</div>

<div class="cell1">

    {if $serverinfo.request_ok == true}

        {$serverinfo.server_name}
        <br /><br />
        Status: <span style="color: green; font-weight: bold;">online</span>
        <br /><br />

        <table cellspacing="0" cellpadding="0">
        <tr>
          <td nowrap="nowrap">Uptime:</td>
          <td width="5" />
          <td nowrap="nowrap">{$serverinfo.server_uptime|formatseconds}</td>
        </tr>
        {*
        <tr>
          <td nowrap="nowrap">Location:</td>
          <td width="5" />
          <td nowrap="nowrap">{$serverinfo.server_location}</td>
        </tr>
        *}
        <tr>
          <td nowrap="nowrap">Users:</td>
          <td width="5" />
          <td nowrap="nowrap">{$serverinfo.server_currentusers} / {$serverinfo.server_maxusers}</td>
        </tr>
        <tr>
          <td nowrap="nowrap">Channels:</td>
          <td width="5" />
          <td nowrap="nowrap">{$serverinfo.server_currentchannels}</td>
        </tr>
        </table>

        <br />

        <a href="teamspeak://{$serverinfo.server_address}:{$serverinfo.server_tcpport}?nickname={$serverinfo.guest_nickname}?password={$serverinfo.server_password}" class="mainlevel">
        Connect
        </a>

    {else}

        {* ({$serverinfo.server_address}:{$serverinfo.server_tcpport}) *}
        <br />
        <span style="color: red; font-weight: bold;">Server offline</span>

    {/if}

</div>
</div>
<!-- [-End-] Widget: Teamspeak Ministatus //-->