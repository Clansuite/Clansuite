<h2>Wer ist online?</h2>

<h3>Guests</h3>

IP
you-anzeige mit pfeil

<table>
{section name=guestloopid loop=$guestonline}
{strip}
   <tr bgcolor="{cycle values="#aaaaaa,#bbbbbb"}">
      <td>{$guestonline[guestloopid].user_id}</td>
      <td>{$guestonline[guestloopid].SessionWhere}</td>
   </tr>
{/strip}
{/section}
</table>

<h3>Member</h3>

<table>
{section name=memberonid loop=$memberonline}
{strip}
   <tr bgcolor="{cycle values="#aaaaaa,#bbbbbb"}">
      <td>{$memberonline[memberonid].user_id}</td>
      <td>{$memberonline[memberonid].SessionWhere}</td>
   </tr>
{/strip}
{/section}
</table>



<h2>Wer war in den letzten 24h online?</h2>

<table>
{section name=wereonlineid loop=$wereonline}
{strip}
   <tr bgcolor="{cycle values="#aaaaaa,#bbbbbb"}">
      <td>{$wereonline[wereonlineid].user_id}</td>
      <td>{$wereonline[wereonlineid].nick}</td>
      <td>{$wereonline[wereonlineid].first_name}</td>
      <td>{$wereonline[wereonlineid].last_name}</td>
      <td>{$wereonline[wereonlineid].timestamp}</td>
   </tr>
{/strip}
{/section}
</table>
