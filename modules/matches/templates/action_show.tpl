{* DEBUG OUTPUT of assigned Arrays:
   {$smarty.session|@var_dump}
   <hr>
   {$matches|@var_dump}
*}

<!--
matchstats (entweder als balken oder wie im beispiel als text)
gameicon        pfad
matchtime       date
teamname1       text
teamname2       text
team1_score     integer
team2_score     integer
matchlink       text
//-->

<!-- Start Matches_Show //-->
<div class="content" id="matches_show">
    <!-- Matchauswertung Gesamt //-->
    <div class="stats" id="matches_show_stats">
        {$matches.stats} <!-- [ Won: 1171 (68.8%) ] - [ Draw: 34 (2%) ] - [ Loss: 498 (29.2%) ] mit oder ohne Statistikbalken //-->
    </div>
    <!-- Matches Übersicht //-->
  <div class="showall" id="matches_show_list">
        <span class="matches_row">{$matches.matches}</span>
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
            <tr>
                <td>{$matches.game}</td>                    <!-- name of the game "cs, bf, dota" //-->
                <td>{$matches.gametype}</td>                <!-- type of the game "5on5, capture the flag" //-->
                <td>{$matches.gameicon}</td>                <!-- kann auch matchcategory sein //-->
                <td>{$matches.matchtime}</td>

                <td>{$matches.teamname1}</td>
                <td>vs</td>                                 <!-- Trennzeichen zwischen den Teamnamen //-->
                <td>{$matches.teamname2}</td>
                <td>{$matches.team1_score}</td>             <!-- Anzeige des Gesamtergebnisses (bestehend aus den zwei Teamscores) -->
                <td>:</td>                                  <!-- Trennzeichen zwischen den Teamscores //-->
                <td>{$matches.team2_score}</td>
                <td>{$matches.matchlink}</td>               <!-- Link zu den Matchdetails //-->
            </tr>
        </table>

    </div>
</div>
<!-- End Matches_Show //-->