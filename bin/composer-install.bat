@echo off

::
:: Composer Install
::
:: This will fetch the defined dependencies into the local project.
:: The dependencies are defined in "..\composer.json".
::

if "%PHPBIN%" == "" call::no_phpcommand_found

:: leave "/bin" folder, for composer to find "composer.json"
cd..

"%PHPBIN%" "bin\composer\composer.phar" "install" > composer-install.report.txt

:no_phpcommand_found - displayes a hint for the user to setup env var PHPBIN
echo ---------------------------------------------------------------------------
echo  WARNING  Set environment variable PHPBIN to the location of your php.exe.
echo           Hint: "set PHPBIN=C:\PHP\php.exe"
echo ---------------------------------------------------------------------------
:: fallback to hardcoded path
set PHPBIN=i:\wpnxm\bin\php\php.exe
goto:eof

pause
