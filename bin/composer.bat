@echo off

::
:: Composer Install
::
:: This will fetch the defined dependencies into the local project.
:: The dependencies are defined in "composer.json".
::

if "%PHPBIN%" == "" set PHPBIN=i:\wpnxm\bin\php\php.exe

:: leave "/bin" folder, so that composer can find "composer.json"
cd..

:: call composer
"%PHPBIN%" "bin\composer\composer.phar" "install"

pause