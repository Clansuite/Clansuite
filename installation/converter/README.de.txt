                            _________________________
                            C  L  A  N  S  U  I  T  E



    Clansuite Converter - Version 0.1
    ---------------------------------

    Der Clansuite Converter ermöglicht die Übername von Daten aus fremden Content-Management-Systemen.


    Arbeitsweise des Converters
    ---------------------------

    1. Daten aus fremder DB holen
        a. DB Verbindung herstellen
        b. Query-File laden (jeder einlesende Query liegt in einer Query File vor)
        c. Query ausführen
        
    2. Daten verarbeiten
        Je nach Tabelle und Spaltenname
        a. Convert-File laden (jede Umwandlung wird in einer Convert-File beschrieben)
        b. die beschriebene Umwandlung vornehmen
        c. dabei zusätzliche Callbacks ausführen (z.B. Bilder kopieren )
        
    3. Daten in eigene DB schreiben
        a. DB Verbindung herstellen
        b. Daten an die vorhandnen Insert-Queries übergeben (siehe Models/Records),
           falls kein Insert-Query vorhanden: mitliefern           


    Verzeichnisstruktur
    -------------------

    /converter
      |
      \- systems
          |
          \- cms-name = (nukedklan, webspell, clansphere, cmpro, etc.)
              |
              \- versionsnummer = (1.1.0, etc.)


    Namenskonventionen
    ------------------

    Verzeichnisse

        1. "system"  : kleingeschrieben, Bsp.: WebSpell = webspell
        2. "version" : Versionsnummer mit Punkt als Trennzeichen, Bsp.: 1.2.1

    Dateien

        /installation/converter/import/system/version/tablename.query.php
        /installation/converter/import/system/version/tablename.query.php

        Bsp.:

        /installation/converter/import/webspell/0.2/news.convert.php
        /installation/converter/import/webspell/0.2/news.query.php


    Last Words
    ----------

    Thanks for using Clansuite!

      Best Regards,

        Jens-André Koch
        Clansuite Maintainer

    Version: $Id$