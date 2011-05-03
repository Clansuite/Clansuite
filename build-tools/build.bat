@echo off
REM   ,------------------------------------.
REM   | Phing: Clansuite Build-Script  w32 |
REM   | by Jens-André Koch (jakoch@web.de) |
REM   | for clansuite.com                  |
REM   | LICENCE: GPL                    <3 |
REM   `------------------------------------'
REM SVN: $Id$

REM Script workflow:
REM a) check for PHING_COMMAND env, if found, use it
REM    else: if not found, inform user and try current path
REM b) check for BUILD_XML_HOME env, if found, use it
REM    else: if not found, use the current path

if "%OS%"=="Windows_NT" @setlocal

REM %~dp0 is expanded pathname of the current script
REM we expect build.xml on this path
set DEFAULT_BUILD_XML_HOME=%~dp0..

goto init
goto cleanup

:init

REM if env var BUILD_XML_HOME is not set, set to the default path for build xml
if "%BUILD_XML_HOME%" == "" set BUILD_XML_HOME=%DEFAULT_BUILD_XML_HOME%
set DEFAULT_BUILD_XML_HOME=

REM if env var PHING_HOME is not set, inform user and use current path
if "%PHING_HOME%" == "" goto phingcommand_missing

goto run
goto cleanup

:run
START /MAX "%PHING_HOME%\phing.bat" -f "%BUILD_XML_HOME%\build.xml" %1
goto cleanup

:phingcommand_missing
REM echo ------------------------------------------------------------------------
REM echo WARNING: Set environment var PHING_COMMAND to the location of your phing
REM echo          executable (e.g. C:\PHP\phing.bat).
REM echo Proceeding with assumption that phing.bat is on current Path
REM echo ------------------------------------------------------------------------
set %PHING_HOME%=%~dp0
goto init

:cleanup
if "%OS%"=="Windows_NT" @endlocal
REM pause

REM ======= EOF build.bat =======