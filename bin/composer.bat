@echo off

::
:: Composer CLI Shortcut
::

if "%PHPBIN%" == "" set PHPBIN=i:\wpnxm\bin\php\php.exe

:: leave "/bin" folder, for composer to find "composer.json"
cd..

:: call composer
"%PHPBIN%" "bin\composer\composer.phar" %*

pause