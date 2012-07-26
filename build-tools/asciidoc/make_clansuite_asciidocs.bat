@REM   ,------------------------------------.
@REM   | Generate Clansuite-Documentations  |
@REM   | by Jens-Andre Koch (jakoch@web.de) |
@REM   | for clansuite.com                  |
@REM   | LICENCE: GPL                    <3 |
@REM   `------------------------------------'

@REM Asciidoc Configuration File "asciidoc-options.ini" is used for the generation.
@REM For the original AsciiDoc Configuration File use "--conf-file=D:/asciidoc/xhtml11.conf"

DEL *.html

SET ASCIIDOCOPTIONS="--conf-file=asciidoc-options.ini" "-a toc" "-a numbered" "-a toclevel 3" "--backend=xhtml11" "--attribute=linkcss=1"

@REM Asciidoc resides in D:/asciidoc
FOR %%F IN (*.asciidoc) DO CALL D:/asciidoc/asciidoc.py %ASCIIDOCOPTIONS% %%F

pause
@REM ======= EOF makedocs.bat =======