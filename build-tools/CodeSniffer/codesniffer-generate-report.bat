@REM   ,------------------------------------.
@REM   | Clansuite Build-Scripts        w32 |
@REM   | by Jens-André Koch (jakoch@web.de) |
@REM   | http://www.clansuite.com           |
@REM   | LICENCE: GPLv2 any later version   |
@REM   `------------------------------------'
@REM SVN: $Id$


echo Calling PHPCS with Clansuite Coding Standard

@REM START /MAX D:\xampplite\php\phpcs.bat --standard=Zend --report-file=d:\xampplite\htdocs\work\clansuite\trunk\build-tools\codesniffer-report.txt --ignore=build-tools,cache,doc,libraries,myrecords,templates,themes,tests d:\xampplite\htdocs\work\clansuite\trunk

START /MAX D:\xampplite\php\phpcs.bat -v --standard=Clansuite --report-file=d:\xampplite\htdocs\work\clansuite\trunk\build-tools\codesniffer-report.txt D:\xampplite\htdocs\work\clansuite\trunk\core

echo PHPCS started in an alternative console ...
echo Press ENTER to close the window

@REM ======= EOF =======