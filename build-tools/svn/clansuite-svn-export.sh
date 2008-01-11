# Clansuite - just an eSports CMS
# 
# -- SVN Checkout and Export --
# Author:   Jens-Andre Koch
# Version:  0.1 
# Date:     28.11.2007
# Licence:  GPL
#
# SVN: $Id$
#
# Working Procedure
# -----------------
# 1. SVN checkout the /trunk
# 2. If checkout exists, svn update will incrementally update the checkout
# 3. Based on that checkout, perform an svn export
#    This safes bandwidth, instead of exporting directly from gna.

# Variables
# SVN Directory to work on
SVN_DIR_TRUNK=http://svn.gna.org/svn/clansuite/trunk/
CHECKOUT_DIR=/home/clansuite/svn
EXPORT_DIR=/home/clansuite/svn-export

# CHECKOUT 
# check if we have an checked out version already, (.svn) dir would exist then
if [ -d $CHECKOUT_DIR/.svn ]; then
    # SVN UPDATE (incremental)
    svn update $CHECKOUT_DIR
else
    # if directory exists, remove
    rm -rf $CHECKOUT_DIR
    # SVN CHECKOUT 
    svn checkout $SVN_DIR_TRUNK $CHECKOUT_DIR
fi

# EXPORT
# export the /trunk from SVN to the export directory  
rm -rf $EXPORT_DIR
svn export $SVN_DIR_TRUNK $EXPORT_DIR