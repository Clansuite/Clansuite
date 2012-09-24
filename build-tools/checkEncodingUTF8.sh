#!/bin/bash

#
# CLI Tool for checking the UTF-8 encoding of files in a directory tree.
#

function usage() {
        echo -e "Usage: $0 <directory>"
}

function checkDirectory() {
        local directory=$1

        if [ -d ${directory} ]; then
                echo "Checking $1"
                for name in ${directory}/*
                do
                        if [ -f ${name} ]; then
                                charset=`file -bi ${name} | grep -o 'utf-8'`
                                if [ 'utf-8' != "${charset}" ]; then
                                    file -bi ${name} | grep -v 'charset=utf-8' | xargs echo -e "\e[00;31m ${name} is encoded in"
                                    echo  -e " \e[00m"
                                fi
                        elif [ -d ${name} ]; then
                                checkDirectory ${name}
                        fi
                done
        else
                echo "  ! ${directory} is not a directory"
        fi
}


if [ "$1" = "" ]; then
  usage
  exit 1
else
  rootpath=$1
fi

checkDirectory ${rootpath}

exit 0
