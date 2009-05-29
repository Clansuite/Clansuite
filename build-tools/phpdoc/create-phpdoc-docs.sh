#!/bin/bash
# $Id$
#
# Clansuite - just an esports CMS
# 
# -- Create phpDocumentor Documentation --

# Define Variables
PHPDOC_TARGET_DIR=/srv/www/clansuite.com/public_html/documentation/developer/phpdoc
# Remove and Re-Create the PHPDoc TARGET DIR
rm -rf $PHPDOC_TARGET_DIR
mkdir $PHPDOC_TARGET_DIR
# Create PHPDOC Documentation
phpdoc -c /home/clansuite/build_tools/clansuite-phpdoc.ini > log_phpdoc.txt
# set the appropriate user and group (chmod)
chown -R clansuitecom:www-users $PHPDOC_TARGET_DIR