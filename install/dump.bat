@REM   ,------------------------------------.
@REM   | QUICK MYSQL DB DUMP via BATCH *    |
@REM   | by Jens-Andre Koch (jakoch@web.de) |
@REM   | for clansuite.com                  |
@REM   | LICENCE: BSD                    <3 |
@REM   `------------------------------------'

@ECHO OFF

@REM Verzeichnisse 
SET basedir=C:\xampplite\htdocs\work\clansuite\trunk\install
SET mysqldir=C:\xampplite\mysql\bin
SET mysqlpassword=
SET mysqluser=root
SET dbname=clansuite

@REM Clansuite DB dumpen

@ECHO Beginning backup of %dbname%...
%mysqldir%/mysqldump -u %mysqluser% -p%mysqlpassword% --opt %dbname% > %basedir%\%dbname%.sql

@ECHO Finished backup!  - Press any Key -
pause