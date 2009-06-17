{* DEBUG OUTPUT of assigned Arrays:
   {$smarty.session|@var_dump}
   <hr>

*}
{$matches|@var_dump}

<!--
matchtitle                      text
matchtime                       date
teamlogo1                       pfad
teamlogo2                       pfad
team1_score                     integer
team2_score                     integer
mappic                          pfad
teamname1                       text
teamname2                       text
team1_round1                    integer
team1_round2                    integer
team2_round1                    integer
team2_round2                    integer
matchcategory                   text
matchstyle                      text
team1_players                   text evtl mit link zum profil falls vorhanden
team2_players                   text evtl mit link zum profil falls vorhanden
matchmedia_replays              pfad
matchmedia_screenshots          pfad
team1_statement                 text
team2_statement                 text
match_comments                  alles was dazu gehört
//-->

<!-- Start Matches_Show_Single //-->
<div class="content" id="matches_show_single">

    <!-- Matchtitle //-->
    <div id="matches_show_single_title">
        <span class="matchtitle">{$matches.matchtitle}</span>
        <span class="matchtime">{$matches.matchtime}</span>
    </div>

    <!-- Matchresults //-->
    <div id="matches_show_single_result">
        <span class="heading">Match Result</span>
        <div id="teams">
          <div class="teamlogo1">{$matches.teamlogo1}</div>                             <!-- float left 30%, image with link//-->
            <div class="result">
                <table class="versus">
                    <tr>
                        <td><span class="team1">{$matches.team1_score}</span></td>      <!-- must be green for win and red for loose //-->
                        <td><span class="score_divider">:</span></td>
                        <td><span class="team2">{$matches.team2_score}</span></td>      <!-- must be green for win and red for loose //-->
                    </tr>
                </table>
            </div>
          <div class="teamlogo2">{$matches.teamlogo2}</div>                             <!-- float right 30%, image with link //-->
          <div style="clear:both"></div>                                                <!-- break float //-->
        </div>
    </div>
        <div id="result">
          <table class="resultview">
              <tr>
                <td rowspan="2"><span class="mappic">{$matches.mappic}</span></td>
                <td><span class="teamname1">{$matches.teamname1}</span></td>
                <td><span class="team1_round1">{$matches.team1_round1}</span></td>      <!-- must be green for win and red for loose //-->
                <td><span class="team1_round2">{$matches.team1_round2}</span></td>      <!-- must be green for win and red for loose //-->
                <td><span class="team1_score">{$matches.team1_score}</span></td>        <!-- must be green for win and red for loose //-->
              </tr>
              <tr>
                <td><span class="teamname2">{$matches.team2name2}</span></td>
                <td><span class="team2_round1">{$matches.team2_round1}</span></td>      <!-- must be green for win and red for loose //-->
                <td><span class="team2_round2">{$matches.team2_round2}</span></td>      <!-- must be green for win and red for loose //-->
                <td><span class="team2_score">{$matches.team2_score}</span></td>        <!-- must be green for win and red for loose //-->
              </tr>
            </table>
        </div>
    </div>

    <!-- Details to the Match //-->
    <div id="matches_show_single_details">
        <span class="heading">Match Details</span>
        <div id="details">
            <div class="matchcategory">matchcategory</div>
            <div class="matchstyle">matchstyle</div>
            <div class="map">map</div>
            <div class="team1_players">team1_players</div>                              <!-- float left 50% //-->
            <div class="team2_players">team2_players</div>                              <!-- float left rest //-->
            <div style="clear:both"></div>                                              <!-- break float //-->
        </div>
    </div>

    <!-- Matchmedia like Screenshots and replays //-->
    <div id="matches_show_single_media">
        <div class="mediawrapper">
            <span class="matchscreenshots">{$matches.matchmedia_screenshots}</span>
            <span class="matchreplays">{$matches.matchmedia_replays}</span>
        </div>
    </div>

    <!-- Statements from the Teams //-->
    <div id="matches_show_single_statements">
        <div class="statements">
            <span class="statements_row">{$matches.team1_statement}</span>
            <span class="statements_row">{$matches.team2_statement}</span>
        </div>
    </div>

    <!-- Matchcomments //-->
    <div id="matches_show_single_comments">
        <div class="commentswrapper">
            <span class="commentsrow">{$matches.match_comments}</span>
        </div>
    </div>

</div>
<!-- End Matches_Show_Single //-->