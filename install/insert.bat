@REM   ,------------------------------------.
@REM   | QUICK MYSQL DB INSERT via BATCH    |
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

@REM Clansuite DB insert

@ECHO Beginning insert of %dbname%...
%mysqldir%/mysql --user=root %dbname% < %basedir%\%dbname%.sql

@ECHO Finished insert!  - Press any Key -
pause