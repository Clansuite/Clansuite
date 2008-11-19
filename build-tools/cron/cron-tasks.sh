#!/bin/sh
#
# Clansuite - just an esports CMS
#
# -- Buildtool Cronjobs  --

# SVN: Synchronize
/home/clansuite/build_tools/svn/svn-sync.sh

# Metrics: StatSVN
/home/clansuite/build_tools/statsvn/run_statsvn.sh

# Documentation - PHP Cross Reference
/home/clansuite/build_tools/phpxref/create-phpxref-docs.sh

# Documentation - PHPDocumentor
/home/clansuite/build_tools/phpdoc/create-phpdoc-docs.sh

# Documentation - AsciiDoc
/home/clansuite/build_tools/asciidoc/create-asciidoc-docs.sh