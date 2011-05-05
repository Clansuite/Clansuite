@REM   ,------------------------------------.
@REM   | QUICK MYSQL DB DUMP via BATCH      |
@REM   | by Jens-Andre Koch (jakoch@web.de) |
@REM   | for clansuite.com                  |
@REM   | LICENCE: BSD                    <3 |
@REM   `------------------------------------'

@ECHO OFF

@REM Current Path
SET ROOT=%~dp0
@REM \installation (1 folder up)
CALL :RESOLVE "%ROOT%\.." PARENT_ROOT
@REM \serverpack-dir\ (6 folders up with nginx serverpack - you might need to adjust this)
@REM MYSQL_PARENT_ROOT should contain the folder above the mysql dir
CALL :RESOLVE "%ROOT%\..\..\..\..\..\.." MYSQL_PARENT_ROOT

@REM Verzeichnisse 
SET basedir=%PARENT_ROOT%
SET mysqldir=%MYSQL_PARENT_ROOT%\mysql\bin
SET mysqluser=root
SET mysqlpassword=toop
SET dbname=clansuite

@REM Clansuite DB dumpen

@ECHO Beginning dump of %dbname%...
%mysqldir%/mysqldump -u %mysqluser% -p%mysqlpassword% --skip-add-locks --add-drop-table --databases %dbname% > %basedir%\sql\%dbname%.sql
@ECHO Dump successfully written... 
@ECHO %basedir%\sql\%dbname%.sql

GOTO :HITKEY

@REM Subroutine for leveling up on paths
:RESOLVE
SET %2=%~f1
GOTO :EOF

:HITKEY
@ECHO Finished!
PAUSE