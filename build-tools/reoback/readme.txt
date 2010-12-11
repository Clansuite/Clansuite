                         _________________________
                         C  L  A  N  S  U  I  T  E


    This file explains the Clansuite Reoback Setup.

    1. Ausfhrliche Anleitung

        [ ReoBack Website ] -->  http://reoback.sourceforge.net/
        [ ReoBack Readme  ] -->  http://reoback.sourceforge.net/docs/README
        [ Strato FAQ      ] -->  http://www.strato-faq.de/view.php4?articleid=1193&subcatid=2.4.4.12

    2. Steuerungsdateien von Reoback

        [clansuite_files.conf]

        Mittels der Steuerdatei "clansuite_files.conf" kann man einstellen,
        welche Verzeichnisse gesichert werden sollen.

        [clansuite_settings.conf]

        Mittels der Steuerdatei "clansuite_settings" kann man einstellen,
        in welche Verzeichnise gesichert wird. Und ob die Backups mittels FTP
        auf einen Sicherungsserver bertragen werden.

    3. CRONTAB - Backup-Automatisierung

        Crontab bearbeiten mit: "crontab -e". Das ”ffnet den Editor Vi.
        Mittels Taste "a" in den Insert Modus wechseln.
        Die n„chste Zeile anh„ngen.
        Per "ESC" in den Befehlsmodus zurckwechseln und das Command ":wq"
        fr Speichern und Schlieáen von Vi eingeben.

        Zeile eintragen:
        30 19 * * * /etc/reoback/run_clansuite_reoback.sh

        Wirkung:
        - t„glich um 19:30 Uhr wird das Backup gestartet
        - Ausgabe des Backup-Skriptes wird in backup.txt gesichert
        - E-Mail mit Betreff "automatisches Backup" und Anhang "backup.txt"
          wird an "admin@wunschname.de" gesenden

   4. FTP-Transfer der Backups

        Um die Backups auf einem anderen Server zu speichern (FTP),
        kann in der settings.conf-Datei remotebackup auf 1 gesetzt werden.