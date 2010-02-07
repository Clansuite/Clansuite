@REM   ,------------------------------------.
@REM   | QUICK MYSQL DB INSERT via BATCH    |
@REM   | by Jens-Andre Koch (jakoch@web.de) |
@REM   | by F. Wolf (xsign.dll@gmail.com)   |
@REM   | for clansuite.com                  |
@REM   | LICENCE: BSD                    <3 |
@REM   `------------------------------------'

@ECHO OFF

SetLocal

REM Verzeichnisse 
SET basedir=%CLANSUITE%\installation
SET mysqldir=%XAMPP%\mysql\bin
SET mysqluser=root
SET mysqlpassword=toop
SET dbname=clansuite

REM Get timestamp
FOR /F "tokens=1,2,3,4 delims=/. " %%a in ('date/T') do set CDATE=%%a-%%b-%%c
FOR /F "tokens=1,2 delims=/: " %%a in ('time/T') do set CTIME=%%a-%%b

REM Clansuite DB insert

REM Check if db exists
ECHO select 1; exit; | %mysqldir%\mysqltest.exe -uroot -ptoop -Dclansuite -s >nul 2>&1
if "%ERRORLEVEL%"=="1" GOTO INSERT
ECHO  Creating backup of database %dbname%
ECHO  File: %~dp0backup\%dbname%_%CDATE%_%CTIME%_backup.sql
%mysqldir%\mysqldump -u %mysqluser% -p%mysqlpassword% --skip-add-locks --add-drop-table --databases %dbname% >  %basedir%\sql\backup\%dbname%_%CDATE%_%CTIME%_backup.sql

REM Insert SQL Code
:INSERT
ECHO  Beginning insert of SQL
ECHO  File: %~dp0%dbname%.sql
%mysqldir%\mysql -u %mysqluser% -p%mysqlpassword% < %basedir%\sql\%dbname%.sql
ECHO  Finished SQL insert!

:END
EndLocal