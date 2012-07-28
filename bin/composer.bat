@echo off

::
:: Composer CLI Shortcut
::

if "%PHPBIN%" == "" call::no_phpcommand_found

:: leave "/bin" folder, for composer to find "composer.json"
cd..

:: call composer
"%PHPBIN%" "bin\composer\composer.phar" %*

:no_phpcommand_found - displayes a hint for the user to setup env var PHPBIN
echo ------------------------------------------------------------------------
echo WARNING: Set environment variable PHPBIN to the location of your php.exe
echo          executable. Hint: set PHPBIN=C:\PHP\php.exe
echo ------------------------------------------------------------------------
:: fallback to hardcoded path
set PHPBIN=i:\wpnxm\bin\php\php.exe
goto:eof

pause
