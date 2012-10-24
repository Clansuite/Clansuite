@echo off

::
:: Composer Install
::
:: This will get the latest versions of the defined dependencies and
:: update the "..\composer.lock" file.
:: The dependencies are defined in "..\composer.json".
::

if "%GIT_EXEC_PATH%" == "" call::no_gitcommand_found

if "%PHPBIN%" == "" call::no_phpcommand_found

:: leave "/bin" folder, for composer to find "composer.json"
cd..

:: always run a self-update
"%PHPBIN%" "bin\composer\composer.phar" "self-update"

:: then install the vendor packages
"%PHPBIN%" "bin\composer\composer.phar" "update"

pause

:no_phpcommand_found - displayes a hint for the user to setup env var PHPBIN
echo ---------------------------------------------------------------------------
echo  WARNING  Set environment variable PHPBIN to the location of your php.exe.
echo           Hint: "set PHPBIN=C:\PHP\php.exe"
echo ---------------------------------------------------------------------------
:: fallback to hardcoded path
set PHPBIN=i:\wpnxm\bin\php\php.exe
goto:eof

:no_gitcommand_found - displayes a hint for the user to setup env var GIT_EXEC_PATH
echo --------------------------------------------------------------------------------
echo  WARNING  Set environment variable GIT_EXEC_PATH to the location of your git.exe
echo           Hint: "set GIT_EXEC_PATH=C:\Programs\GIT\bin"
echo --------------------------------------------------------------------------------
:: fallback to hardcoded path
set GIT_EXEC_PATH=C:\Programme\Git\bin
goto:eof
