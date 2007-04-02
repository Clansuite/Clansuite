@REM   ,------------------------------------.
@REM   | QUICK MYSQL DB INSERT via BATCH    |
@REM   | by Jens-Andre Koch (jakoch@web.de) |
@REM   | for clansuite.com                  |
@REM   | LICENCE: BSD                    <3 |
@REM   `------------------------------------'

@ECHO OFF

@REM Verzeichnisse 
SET basedir=D:\Homepage\clansuite.com\installation
SET mysqldir=D:\Homepage\xampp\mysql\bin
SET mysqlpassword=toop
SET mysqluser=clansuite
SET dbname=clansuite

@REM Zeitstempel ermitteln
FOR /F "tokens=1,2,3,4 delims=/. " %%a in ('date/T') do set CDATE=%%a-%%b-%%c
FOR /F "tokens=1,2 delims=/: " %%a in ('time/T') do set CTIME=%%a-%%b

@REM Clansuite DB insert

@ECHO Creating backup of %dbname% as %dbname%_backup.sql...
%mysqldir%/mysqldump -u %mysqluser% -p%mysqlpassword% --skip-add-locks --add-drop-table %dbname% > %basedir%\%dbname%_%CDATE%_%CTIME%_backup.sql
@ECHO Beginning insert of %dbname%...
%mysqldir%/mysql -u %mysqluser% -p%mysqlpassword% %dbname% < %basedir%\%dbname%.sql

@ECHO Finished insert!  - Press any Key -
pause