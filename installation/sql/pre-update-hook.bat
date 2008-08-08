@REM   ,------------------------------------.
@REM   | PRE UPDATE HOOK for TortoiseSVN    |
@REM   | by Jens-Andre Koch (jakoch@web.de) |
@REM   | for clansuite.com                  |
@REM   | LICENCE: BSD                    <3 |
@REM   `------------------------------------'

@ECHO OFF

@REM Write Revision Number of clansuite.sql into the post-update-hook.tpl
@REM which is later be executed as post-update-hook.bat by TSVN

IF NOT exist clansuite.sql GOTO :END

SubWCRev.exe D:\xampplite\htdocs\work\clansuite\trunk\installation\sql\clansuite.sql post-update-hook.tpl post-update-hook.bat

:END