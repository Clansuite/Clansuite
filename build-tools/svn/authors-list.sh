#!/usr/bin/env bash

# SVN Authorslist
# ---------------
# Generates a list of authors based on the subversion history.
# The output is ready to be inserted into a svn.authorsfile.
 
authors=$(svn log -q | grep -e '^r' | awk 'BEGIN { FS = "|" } ; { print $2 }' | sort | uniq)
for author in ${authors}; do
  echo "${author} = NAME <USER@DOMAIN>";
done