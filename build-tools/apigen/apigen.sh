#!/bin/bash

#
# Generate ApiGen Documentation
#

apigen \
--title "Clansuite & Koch Framework" \
--source /home/clansuite/clansuite-git \
--destination /var/www/webs/clansuite/documentation/developer/apigen/ \
--exclude "*/Clansuite/*" \
--exclude "*/libraries/*" \
--exclude "*/tests/*" \
--source-code yes \
--wipeout yes \
--progressbar yes \
--colors yes \
--tree yes \
--todo yes \
