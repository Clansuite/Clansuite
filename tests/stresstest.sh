#!/bin/sh
# 
# Stress test for Clansuite using Apache AB
#
echo "
Stress test for Clansuite
=========================
"
ab -n5000 -c50 "http://localhost/work/clansuite/trunk/index.php?mod=news"