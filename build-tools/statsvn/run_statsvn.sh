#!/bin/bash
#
# Generate the StatSVN web pages
#

JAVA_OPTS="-Djava.awt.headless=true -Xms512M -Xmx2048M -XX:MaxPermSize=256M"

# Generate Subversion log file
svn log -v --xml /home/clansuite/svn/ > /home/clansuite/statsvn/statsvn.log

# Generate web content
java -jar /home/clansuite/statsvn/statsvn.jar -no-developer root -no-developer creep7 -trac http://trac.clansuite.com/ -cache-dir /home/clansuite/statsvn/cache/ /home/clansuite/statsvn/statsvn.log /home/clansuite/svn -output-dir /var/www/webs/clansuite/statsvn -title "Clansuite - just an eSports CMS"

# chown / chmod the statsvn webserver dir
chown clansuite:clansuite /var/www/webs/clansuite/statsvn
chmod -R 705 /var/www/webs/clansuite/statsvn