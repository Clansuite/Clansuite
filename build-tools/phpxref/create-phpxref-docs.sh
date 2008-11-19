# Clansuite - just an eSports CMS
# 
# -- Create phpxref Documentation --

# Define Variables
PHPXREF_TARGET_DIR=/srv/www/clansuite.com/public_html/documentation/developer/phpxref

# Remove and Re-Create the PHPXREF TARGET DIR
rm -rf $PHPXREF_TARGET_DIR
mkdir $PHPXREF_TARGET_DIR

# Chmod / Chown the PHPXREF Stuff
chown -R root:root /home/clansuite/build_tools/phpxref-0.7/
chmod -R 700 /home/clansuite/build_tools/phpxref-0.7

# Create PHPXREF Documentation
/home/clansuite/build_tools/phpxref-0.7/phpxref.pl -c /home/clansuite/build_tools/clansuite-phpxref-linux.cfg >/home/clansuite/logs/log_phpxref.txt

cp $PHPXREF_TARGET_DIR/index.html $PHPXREF_TARGET_DIR/index.php

# Set the appropriate user and group (chmod) on the Target Dir
chown -R clansuitecom:www-users $PHPXREF_TARGET_DIR
