@echo off

REM if "%PHPBIN%" == "" set PHPBIN=@php_bin@
if "%PHPBIN%" == "" set PHPBIN=C:\andre\nginx-serverpack\php\php.exe
REM if "%PHPBIN%" == "" set PHPBIN=C:\Programme\Zend\ZendServer\bin\php.exe
if not exist "%PHPBIN%" if "%PHP_PEAR_PHP_BIN%" neq "" goto USE_PEAR_PATH
GOTO RUN
:USE_PEAR_PATH
set PHPBIN=%PHP_PEAR_PHP_BIN%
:RUN
"%PHPBIN%" "doctrine.php" %*

REM CALL COMMAND ,)
cmd.exe