<h2>Usercenter</h2>

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




{* Debugausgabe des $usercenterdata Arrays: *}
{html_alt_table loop=$usercenterdata}

<div>
<h3>todo List Usercentermodul</h3>

A. MessageBox 
-In- 
-Out- 
-Archiv- 
-Compose-

<br>

B. ShortProfil 
-Userdata- 
-Change-

<br>

C. Stats 
-Posts- 
-OnlineTime- 

<br>

D. Terminmanagement
-Abgeschlossene Termin & related Links -
-Nächste Termine- 
-Anfragen auf Verfügbarkeit zu Terminen-

<br>

</div>