#!/bin/sh
#PATH=/usr/bin

# Location of the configuration file.
config="/home/clansuite/reobackup/clansuite_settings.conf"

# Change to reflect where REOBack is installed
reoback="/usr/bin/reoback.pl"

# Do not modify this line.
$reoback $config > /home/clansuite/reobackup/backup_output.txt ; mail -s "clansuite.com - Automatisches ReoBackup angelegt!" jakoch@web.de < /home/clansuite/reobackup/backup_output.txt

# Because we download the backup folders and files via ftp
# we need to set the appropriate user and group (chmod)
chown -R clansuitecom:www-users /srv/www/clansuite.com/reobacks