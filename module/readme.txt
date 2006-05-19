Moduldocumentation

Im folgenden wird beispielhaft der Aufbau eines Moduls für das Clansuite-CMS erklärt.

A. Zur allgemeinen Verzeichnisstruktur innerhalb eines Moduls

Module liegen im Verzeichnis "/module" unterhalb des Hauptpfades von Clansuite, 
der standardmäßig "/clansuite" ist. Das vollständige Modulverzeichnis ist "/clansuite/module".
Jedes Modul bringt ein eigenes Verzeichnis mit aussagekräftigem Namen mit.
Der Verzeichnisname für das News-Modul ist demnach "/news" und für das Gästebuch "/guestbook".
Das Beispielmodul findet man im Ordner "/clansuite/module/skull".

Jedes Modul folgt diesem Verzeichnisaufbau:

/			- im Hauptpfad liegen: 
				1. die Config in Form von 
					a. PHP (config.php)
					b. XML (plugin.modulname.xml)
				2. ein SQL-Dump der Modultabellen
				3. der öffentlicher Modulzugang, d.h. index.php bzw. modulname.php
				   Aufruf des News-Moduls: 
				   		  	  			   	"/clansuite/module/news/news.php"

/admin			- Admininterface des Moduls:
				1. strukturiert nach Handlungen 
				   (zB: news_add.php, news_del.php)
				   späer zusammenfassen zu einer datei mit case/switch
				2. handlungen nach public / private trennen
/images			- Modulzugehörige Bilddaten (Bilder, Icons, etc.) 
/language		- Sprachen bzw. Übersetzungsdatei
/templates		- Vorlagen für das Modul


B. Zum Beispielmodul /skulls

Der Aufbau eines Moduls folgt aus Übersichtlichkeitsgründen einem festen Schema.

1. Beginn der Moduldatei
<?php
require '../../shared/prepend.php'; 
header einbinden

2. Daten einlesen (zB aus DB oder aber aus XML)
$array aus Bsp-Query

3. Daten für Smarty zuweisen
Die Daten werden nun der Templateengine verfügbar gemacht
$ModulPage->assign(daten, $array);

4. template des moduls laden 
$ModulPage->display('modultpl.tpl');

5. footer einbinden





