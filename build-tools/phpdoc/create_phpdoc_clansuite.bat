@REM   ,------------------------------------.
@REM   | Generate PHPDocumentor Docs        |
@REM   | by Jens-Andre Koch (jakoch@web.de) |
@REM   | for clansuite.com                  |
@REM   | LICENCE: GPL                    <3 |
@REM   `------------------------------------'
@REM SVN: $Id$

@echo on
phpdoc -c clansuite-phpdoc.ini -f c:/xampplite/htdocs/work/clansuite/*.php -t c:/xampplite/htdocs/work/clansuite/doc > debug.txt
PAUSE
@REM ======= EOF makedocs.bat =======