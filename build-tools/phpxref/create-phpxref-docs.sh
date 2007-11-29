# Clansuite - just an esports CMS
# 
# -- Create phpxref Documentation --

# Define Variables
PHPXREF_TARGET_DIR=/srv/www/clansuite.com/public_html/docs/phpxref

# Remove the old and Re-Create the PHPXREF TARGET DIR
rm -rf $PHPXREF_TARGET_DIR
mkdir $PHPXREF_TARGET_DIR
# Create PHPXREF Documentation
/home/clansuite/build_tools/phpxref-0.7/phpxref.pl -c /home/clansuite/build_tools/clansuite-phpxref-linux.cfg >log_phpxref.txt
# set the appropriate user and group (chmod)
chown -R clansuitecom:www-users /srv/www/clansuite.com/public_html/docs/phpxref