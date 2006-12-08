{doc_raw}
    {* Tabs *}
    <link rel="stylesheet" type="text/css" href="{$www_core_tpl_root}/admin/luna.css" />
    <script type="text/javascript" src="{$www_core_tpl_root}/javascript/tabpane.js"></script>
{/doc_raw}

<h2>Usercenter</h2>

<table border="0" cellpadding="5" cellspacing="2" style="border: 1px solid #FFA4A4; background-color: #FFF3F3; border-left: 5px solid #FF6666; margin-left: auto; margin-right: auto; width: 80%">
<tr>
<td style="font-size: 95%; text-align:left">Dieser Artikel wird gerade erst entwickelt und stellt daher noch keine abgestimmte Meinung dar. Bitte hilf mit und beteilige Dich hier und auf der <strong class="selflink">Diskussionsseite</strong>.</td>
</tr>
</table>

<br/>

    <div class="tab-pane" id="tab-pane-1">
        <div class="tab-page">
              <h2 class="tab">MessageBox</h2>
                
                A. MessageBox
                -In-
                -Out-
                -Archiv-
                -Compose-

                {* {include file="admin/users/messagebox.tpl"} *}
        </div>

        <div class="tab-page">
              <h2 class="tab">Profil</h2>

                B. ShortProfil
                -Userdata-
                -Change-

<div>
 <a href="http://www.esl.eu/de/upload_photo/353688/" target="_blank">
 <img src="{$www_core_tpl_root}/images/users/default_medium.gif" border="0" height="133" width="100"></a>
</div>


<div>Nick: <b>{$usercenterdata.nick}</b></div>

<div>Alter Geschlecht: <b>{$usercenterdata.age} Years</b>
<img src="{$www_core_tpl_root}/images/users/16_male.png" height="16" width="16"><b>{$usercenterdata.birthday}</b></div>
<div>Mitglied seit: <b>{$usercenterdata.joindate}</b></div>
<div>Wohnort : </div>
<div>Wohnsitz / Nationalität<img src="/flags/de.gif" border="0" height="12" width="16">&nbsp;{$usercenterdata.country}</div>
<div>Beruf: </div>
<div>Arbeitgeber / Uni / Schule</div>
<div>Haupt-Team&nbsp; / Squads <b><a href="http://www.esl.eu/de/team/190093/">TEST KnD</a></b>&nbsp;</div>
                {* {include file="admin/users/messagebox.tpl"} *}
        </div>



            <div class="tab-page">
              <h2 class="tab">Personal Stats</h2>
                  C. Stats
                    -Posts-
                    -OnlineTime-
                    -eigene Beitr„ge

             </div>

            <div class="tab-page">
               <h2 class="tab">Termine</h2>
                      
                D. Terminmanagement
                -Abgeschlossene Termin & related Links -
                -Nächste Termine-
                -Anfragen auf Verfügbarkeit zu Terminen-

             </div>


    </div>  <!-- tab-pane-1 closed -->



{* Debugausgabe des $usercenterdata Arrays:
{html_alt_table loop=$usercenterdata}*}