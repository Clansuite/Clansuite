@REM   ,------------------------------------.
@REM   | QUICK MYSQL DB INSERT via BATCH    |
@REM   | by Jens-Andre Koch (jakoch@web.de) |
@REM   | for clansuite.com                  |
@REM   | LICENCE: BSD                    <3 |
@REM   `------------------------------------'

@ECHO OFF

@REM Current Path
SET ROOT=%~dp0
@REM \installation (1 folder up)
CALL :RESOLVE "%ROOT%\.." PARENT_ROOT
@REM \serverpack-dir (6 folders up with nginx serverpack - you might need to adjust this)
@REM MYSQL_PARENT_ROOT should contain the folder above the mysql dir
CALL :RESOLVE "%ROOT%\..\..\..\..\..\.." MYSQL_PARENT_ROOT

@REM Verzeichnisse 
SET basedir=%PARENT_ROOT%
SET mysqldir=%MYSQL_PARENT_ROOT%\mysql\bin
SET mysqluser=clansuite
SET mysqlpassword=toop
SET dbname=clansuite

@REM Get timestamp
FOR /F "tokens=1,2,3,4 delims=/. " %%a in ('date/T') do set CDATE=%%a-%%b-%%c
FOR /F "tokens=1,2 delims=/: " %%a in ('time/T') do set CTIME=%%a-%%b

@REM Clansuite DB insert

@REM Backup
@ECHO Creating backup of %dbname% as \sql\%dbname%_%CDATE%_%CTIME%_backup.sql ...
%mysqldir%/mysqldump -u %mysqluser% -p%mysqlpassword% --skip-add-locks --add-drop-table --databases %dbname% >  %basedir%\sql\backup\%dbname%_%CDATE%_%CTIME%_backup.sql

@REM Insert 
@ECHO Beginning insert of \sql\%dbname%.sql ...
%mysqldir%/mysql -u %mysqluser% -p%mysqlpassword% %dbname% < %basedir%\sql\%dbname%.sql

GOTO :HITKEY

@REM Subroutine for leveling up on paths
:RESOLVE
SET %2=%~f1
GOTO :EOF

:HITKEY
@ECHO Finished!
PAUSE