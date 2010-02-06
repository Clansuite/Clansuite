@REM   ,------------------------------------.
@REM   | QUICK MYSQL DB INSERT via BATCH    |
@REM   | by Jens-Andre Koch (jakoch@web.de) |
@REM   | by F. Wolf (xsign.dll@gmail.com)   |
@REM   | for clansuite.com                  |
@REM   | LICENCE: BSD                    <3 |
@REM   `------------------------------------'

@ECHO OFF

@REM Verzeichnisse 
SET basedir=%CLANSUITE%\installation
SET mysqldir=%XAMPP%\mysql\bin
SET mysqluser=root
SET mysqlpassword=toop
SET dbname=clansuite

@REM Dump clansuite

@ECHO Beginning backup of %dbname%...
%mysqldir%/mysqldump -u %mysqluser% -p%mysqlpassword% --skip-add-locks --add-drop-table --databases %dbname% > %basedir%\sql\%dbname%.sql

@ECHO Finished backup!  - Press any Key -
pause