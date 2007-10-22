@REM   ,------------------------------------.
@REM   | QUICK MYSQL DB DUMP via BATCH *    |
@REM   | by Jens-Andre Koch (jakoch@web.de) |
@REM   | for clansuite.com                  |
@REM   | LICENCE: BSD                    <3 |
@REM   `------------------------------------'

@ECHO OFF

@REM Verzeichnisse 
SET basedir=C:\xampplite\htdocs\work\clansuite\trunk\installation
SET mysqldir=C:\xampp\mysql\bin
SET mysqlpassword=toop
SET mysqluser=clansuite
SET dbname=clansuite

@REM Clansuite DB dumpen

@ECHO Beginning backup of %dbname%...
%mysqldir%/mysqldump -u %mysqluser% -p%mysqlpassword% --skip-add-locks --add-drop-table %dbname% > %basedir%\sql-backups\%dbname%.sql

@ECHO Finished backup!  - Press any Key -
pause