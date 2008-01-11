@REM   ,------------------------------------.
@REM   | Generate Clansuite-Documentations  |
@REM   | by Jens-Andre Koch (jakoch@web.de) |
@REM   | for clansuite.com                  |
@REM   | LICENCE: GPL                    <3 |
@REM   `------------------------------------'
@REM SVN: $Id$

@REM Asciidoc Configuration File "asciidoc-options.ini" is used for the generation.
@REM For the original AsciiDoc Configuration File use "--conf-file=c:/asciidoc/xhtml11.conf"

SET ASCIIDOCOPTIONS="--conf-file=asciidoc-options.ini" "--backend=xhtml11" "--attribute=linkcss=1" 

FOR %%F IN (*.asc) DO CALL c:/asciidoc/asciidoc.py %ASCIIDOCOPTIONS% %%F

pause
@REM ======= EOF makedocs.bat =======