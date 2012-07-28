@echo off

::
:: php-cs-fixer - PHP Coding Style Fixer
::
:: This will analyzes the PHP source code and fix the coding standards violation.
:: Fixing "--level=all" is used to ensure compatibility with PSR-0, PSR-1 and PSR-2.
::
:: Not fixable violations will logged to the file "php-cs-fixer.report.txt".
::
:: The tool takes the file "..\.phpcs" into account, where directory excludes are defined.
::

if "%PHPBIN%" == "" set PHPBIN=i:\wpnxm\bin\php\php.exe

"%PHPBIN%" "php-cs-fixer\php-cs-fixer.phar" --verbose fix ..\ --level=all > php-cs-fixer.report.txt

pause
