@echo off

::
:: php-cs-fixer - self-update shortcut
::

if "%PHPBIN%" == "" call::no_phpcommand_found

"%PHPBIN%" "php-cs-fixer.phar" self-update

:no_phpcommand_found - displayes a hint for the user to setup env var PHPBIN
echo ---------------------------------------------------------------------------
echo  WARNING  Set environment variable PHPBIN to the location of your php.exe.
echo           Hint: "set PHPBIN=C:\PHP\php.exe"
echo ---------------------------------------------------------------------------
:: fallback to hardcoded path
set PHPBIN=i:\wpnxm\bin\php\php.exe
goto:eof

pause

pause
