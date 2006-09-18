@REM   ,------------------------------------.
@REM   | QUICK MYSQL DB INSERT via BATCH    |
@REM   | by Jens-Andre Koch (jakoch@web.de) |
@REM   | for clansuite.com                  |
@REM   | LICENCE: BSD                    <3 |
@REM   `------------------------------------'

@ECHO OFF

@REM Verzeichnisse 
SET basedir=D:\Homepage\clansuite.com\workplace\trunk\install
SET mysqldir=D:\xampp\mysql\bin
SET mysqlpassword=toop
SET mysqluser=clansuite
SET dbname=clansuite

@REM Clansuite DB insert

@ECHO Creating backup of %dbname% as %dbname%_backup.sql...
%mysqldir%/mysqldump -u %mysqluser% -p%mysqlpassword% --skip-add-locks --add-drop-table %dbname% > %basedir%\%dbname%_backup.sql
@ECHO Beginning insert of %dbname%...
%mysqldir%/mysql -u %mysqluser% -p%mysqlpassword% %dbname% < %basedir%\%dbname%.sql

@ECHO Finished insert!  - Press any Key -
pause