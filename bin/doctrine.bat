@echo off

::
:: Doctrine CLI Shortcut
::

if "%PHPBIN%" == "" set PHPBIN=i:\wpnxm\bin\php\php.exe

"%PHPBIN%" "doctrine.php" %*
