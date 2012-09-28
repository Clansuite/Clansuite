@echo off

::
:: Simple Batch file forwarding a call to the Netbeans IDE
:: Place this file in an ENV path, like C:\Windows
::

setlocal enableextensions enabledelayedexpansion
set NETBEANS=%1
set FILE=%~2
%NETBEANS% --nosplash --console suppress --open "%FILE:~19%"
::nircmd win activate process netbeans.exe