{* DEBUG OUTPUT of assigned Arrays:
   {$smarty.session|@var_dump}
   <hr>
   {$matches|@var_dump}
*}

{*
array
  'id' => string '1' (length=1)
  'match_id' => string '1' (length=1)
  'group_id' => string '1' (length=1)
  'server_id' => string '1' (length=1)
  'team1_id' => string '1' (length=1)
  'team2_id' => string '2' (length=1)
  'comment_id' => string '1' (length=1)
  'matchcategory_id' => string '1' (length=1)
  'matchdate' => string '1234567890' (length=10)
  'matchstatus' => string '1' (length=1)
  'team1score' => string '24' (length=2)
  'team2score' => string '24' (length=2)
  'team1map1score' => string '12' (length=2)
  'team1map2score' => string '12' (length=2)
  'team2map1score' => string '12' (length=2)
  'team2map2score' => string '12' (length=2)
  'team1players' => string '0' (length=1)
  'team2players' => string '0' (length=1)
  'matchreport' => string '0' (length=1)
  'matchmedia_screenshots' => string '' (length=0)
  'matchmedia_replays' => string '' (length=0)
  'team1statement' => string 'team1 stmt' (length=10)
  'team2statement' => string 'team2 stmt' (length=10)
  'mapname1' => string '0' (length=1)
  'mapname2' => string '0' (length=1)
*}

<!-- Start Matches_Show -->
<div class="content" id="matches_show">
    <!-- Matchauswertung Gesamt -->
    <div class="stats" id="matches_show_stats">
       {* {$matches.stats} *} <!-- [ Won: 1171 (68.8%) ] - [ Draw: 34 (2%) ] - [ Loss: 498 (29.2%) ] mit oder ohne Statistikbalken -->
    </div>
  <!-- Matches Übersicht -->
  <div class="showall" id="matches_show_list">
        <span class="matches_row">{* {$matches.matches} *}</span>
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td>Kategorie</td>
                <td>Datum</td>
                <td>Teamname</td>
                <td></td>
                <td></td>
                <td>Ergebnis</td>
                <td></td>
                <td></td>
                <td>Details</td>
            </tr>

            {* Loop über alle Matches *}
            {foreach name=matches item="match" from=$matches}
            <tr>

                {* Debugaussgabe des einzelnen Matches: {$match|@var_dump} *}

                <td>{$match.id}</td>
                {*
                <td>{$match.game}</td>                    <!-- name of the game "cs, bf, dota" -->
                <td>{$match.gametype}</td>                <!-- type of the game "5on5, capture the flag" -->
                <td>{$match.gameicon}</td>                <!-- kann auch matchcategory sein -->
                *}
                <td>{$match.matchdate}</td>               <!-- -->

                <!-- Teamnamen -->
                <td>{$match.team1_id.name} vs {$match.team2_id.name}</td>

                <!-- Anzeige des Gesamtergebnisses (bestehend aus den zwei Teamscores) -->
                <td>{$match.team1score} : {$match.team2score}</td>

                <!-- Mapnames und Mapscores-->
                <!-- Scores für Map1 -->
                <td>{$match.mapname1}</td>
                <td>{$match.team1map1score} : {$match.team2map1score}</td>

                <!-- Scores für Map2 -->
                <td>{$match.mapname2}</td>
                <td>{$match.team1map2score} : {$match.team2map2score}</td>

                {* <td>{$match.matchlink}</td>               <!-- Link zu den Matchdetails --> *}
            </tr>
            {/foreach}

        </table>

    </div>
</div>
<!-- End Matches_Show -->