#!/bin/sh 
# Clansuite - just an esports CMS

# -- Installer for Documentation Tools --
# Author:   Jens-Andre Koch
# Version:  0.1 
# Date:     29.11.2007
# Licence:  GPL
#
# SVN: $Id$
#
# -- Installer for Documentation Tools --
#
# Working Procedure
# -----------------
# 1. get PHPDocumentor
# 2. get PHPXref
# 3. get AsciiDoc

# 1) get PHPDocumentor

# Installation of "phpDocumentor" via PEAR
# if this fails, because memory_limit to low
# edit "/etc/php5/cli/php.ini" 
# set  "memory_limit 32MB"

#pear install --alldeps phpDocumentor

# phpDocumentor should now reside in
# /usr/share/php5/PEAR/
# ======================================

# 2) get PHPXref

# Installation of "phpXRef" via wget
# put myself into the build_tools directory
#cd /home/clansuite/build_tools 
# wget the phpxref-archive from sourceforge
#wget http://surfnet.dl.sourceforge.net/sourceforge/phpxref/phpxref-0.7.tar.gz
# un-archive the package
#gunzip < phpxref-0.7.tar.gz | tar -xv
# step into directory
#cd phpxref-0.7


# 3) get AsciiDoc