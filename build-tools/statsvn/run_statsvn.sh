#!/bin/bash
#
# Generate the StatSVN web pages
#

# Update local Subversion repository

# Generate Subversion log file
svn log -v --xml /home/clansuite/svn/ > /home/clansuite/statsvn/statsvn.log

# Create web content here:
cd /srv/www/clansuite.com/public_html/statsvn

# Generate web content
java -jar /home/clansuite/statsvn/statsvn.jar -trac http://www.clansuite.com/trac/ -cache-dir /home/clansuite/statsvn/cache/ /home/clansuite/statsvn/statsvn.log /home/clansuite/svn -output-dir /srv/www/clansuite.com/public_html/statsvn -title "Clansuite - just an eSports CMS"

# chown / chmod the statsvn webserver dir
chown clansuitecom:www-users /srv/www/clansuite.com/public_html/statsvn
chmod -R 705 /srv/www/clansuite.com/public_html/statsvn