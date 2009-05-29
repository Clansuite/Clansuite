#!/bin/sh
#
# Clansuite - just an esports CMS
#
# -- Create Asciidoc Documentation --

PROGRAM_NAME=$(basename $0)
PACKAGE_NAME="$1"

INPUT_DIR="/home/clansuite/svn-export/doc/manuals"
OUTPUT_DIR="/srv/www/clansuite.com/public_html/documentation"
ASCIIDOC_INI_PATH="/home/clansuite/svn-export/build-tools/asciidoc"
ASCIIDOC_PATH="/home/clansuite/build_tools/asciidoc-8.4.5"

# create subdirectories of INPUT_DIR under OUTPUT_DIR
files=$(find $INPUT_DIR -name "*.asc")

for file  in $files
do
 dirnamewithfile=${file#*$INPUT_DIR}  # cut the input_dir
 subdir=${dirnamewithfile%/*}      # cut the filename

 #echo $subdir;

 if [ ! -d $OUTPUT_DIR/$subdir ] # if directory not exists, create it
 then
   echo Creating directory $OUTPUT_DIR$subdir
   mkdir --parents $OUTPUT_DIR$subdir
 fi

 htmlfilename=$(basename $file .asc).html
 echo ASCIIDOC processes file: $file
 echo writing to $OUTPUT_DIR - $subdir - $htmlfilename
 python $ASCIIDOC_PATH/asciidoc.py -a toc -a toclevels=3 -a icons -a numbered -b xhtml11 -a linkcss --conf-file=$ASCIIDOC_INI_PATH/asciidoc-options.ini -o $OUTPUT_DIR/$subdir/$htmlfilename $file
done

# Create Index
echo Creating index.html Files
cp $OUTPUT_DIR/user/manual/de/user-manual.html $OUTPUT_DIR/user/manual/de/index.html
cp $OUTPUT_DIR/developer/manual/de/developers-manual.html $OUTPUT_DIR/developer/manual/de/index.html
cp $OUTPUT_DIR/user/quick-guide/de/quick-guide.html $OUTPUT_DIR/user/quick-guide/de/index.html
cp $OUTPUT_DIR/clansuite_manuals.html $OUTPUT_DIR/index.html