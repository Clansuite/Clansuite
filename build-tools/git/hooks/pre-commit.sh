#!/bin/sh
# copy this file into the folder /Clansuite/.git/hooks

PROJECTROOT=`echo $(cd ${0%/*}/../../ && pwd -P)`/
FIXER="bin/php-cs-fixer/php-cs-fixer.phar"
PHPBIN="i:/wpnxm/bin/php/php.exe"

RES=`${PHPBIN} ${PROJECTROOT}${FIXER} fix ${PROJECTROOT} --verbose --level=all --dry-run`
if [ "$RES" != "" ]; then
    echo ""
    echo -e "\e[1;31;40m Detected Coding Standards Violation. Cancelling your commit. \033[0m "
    echo ""
    echo $RES
    echo ""
    echo ""
    echo -e "\e[1;32;40m To fix the issues please run /bin/fix-codingstyle.bat \033[0m "
    echo ""
    exit 1
fi
exit 0