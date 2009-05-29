#!/bin/sh
# Clansuite - just an esports CMS
# -- Installer for Documentation Tools --
#
# Author:   Jens-Andre Koch
# Version:  0.2
# Date:     10.07.2008
# Licence:  GPL
#
# Working Procedure
# -----------------
# 1. get PHPDocumentor
# 2. get PHPXref
# 3. get AsciiDoc
#
# SVN: $Id$

# Parse command line
while [ -n "$1" ]
do
    case "$1" in
    -phpdoc)    phpdoc;
                break;

    -phpxref)   phpxref;
                break;

    -asciidoc)  asciidoc;
                break;

    -usage)     usage;
                break;
    esac
    shift
done

# usage and helptext
function usage()
{
	echo "usage:"
	echo "  ${0##*/} [<switches>] <filename> [<filename> ..]"
	echo "switches:"
	echo "  --phpdoc    install phpdocumentor"
	echo "  --phpxref   install phpdocumentor"
	echo "  --asciidoc  install asciidoc"
	exit $1
}

# 1) get PHPDocumentor
function install_phpdoc()
{
	# Installation of "phpDocumentor" via PEAR
    # if this fails, because memory_limit to low
    # edit "/etc/php5/cli/php.ini"
    # set  "memory_limit 32MB"

    #pear install --alldeps phpDocumentor

    # phpDocumentor should now reside in
    # /usr/share/php5/PEAR/
    # ======================================
}

# 2) get PHPXref
function install_phpxref()
{
    # Installation of "phpXRef" via wget
    # put myself into the build_tools directory
    #cd /home/clansuite/build_tools
    # wget the phpxref-archive from sourceforge
    #wget http://surfnet.dl.sourceforge.net/sourceforge/phpxref/phpxref-0.7.tar.gz
    # un-archive the package
    #gunzip < phpxref-0.7.tar.gz | tar -xv
    # step into directory
    #cd phpxref-0.7
    #del /home/clansuite/build_tools/phpxref-0.7.tar.gz
}

# 3) get AsciiDoc
function install_asciidoc()
{
    cd /home/clansuite/build_tools
    wget http://www.methods.co.nz/asciidoc/asciidoc-8.4.5.tar.gz
    gunzip < asciidoc-8.4.5.tar.gz | tar -xv
    del /home/clansuite/build_tools/asciidoc-8.4.5.tar.gz
}