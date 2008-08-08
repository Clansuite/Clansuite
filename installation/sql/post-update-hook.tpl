@REM   ,------------------------------------.
@REM   | POST UPDATE HOOK for TortoiseSVN   |
@REM   | by Jens-Andre Koch (jakoch@web.de) |
@REM   | for clansuite.com                  |
@REM   | LICENCE: BSD                    <3 |
@REM   `------------------------------------'

@ECHO OFF

@REM This compares the old revision number of clansuite.sql ($WCREV$)
@REM with the current REVISION number constant (%3) of TSVN
@REM and inserts the sql in case a diff is detected

@REM compare old revision number of clansuite.sql with the revision from tortoisesvn
IF "$WCREV$" == %3 (GOTO :noinsert) else (GOTO :insert)

:noinsert
echo "NOT inserting SQL, because no DIFF: $WCREV$ = %3" > notinsert.txt
exit

:insert
echo "Inserting SQL, because DIFF: $WCREV$ not %3" > insert.txt
insert.bat
exit