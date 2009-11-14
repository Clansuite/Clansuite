#!/bin/sh
#PATH=/usr/bin

chown -R clansuitecom:www-users /srv/www/clansuite.com/public_html/trac
echo "User clansuitecom : Group www-users applied recursive to /TRAC."

chown -r root:root /srv/www/clansuite.com/public_html/trac/db
echo "User root : Group root applied recursive to /trac/db."