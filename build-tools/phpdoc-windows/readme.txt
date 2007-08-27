You will find the Clansuite PHPDocumentor Settings in this directory.
This readme itself describes the way, on how to recreate the Documentation.

The current Clansuite Documentation was created on a Windows Machine. 
But as PHPDocumentor itself is a PHP Script, you should be able to create a Doc 
on every System with a PHP Interpreter avaiable.

How to do it? 
-------------
I wanted to be able to call the documentation generation script several times.
So i installed PHPDocumentor, created a ini for Clansuite and a Batch-File to fire it via console.

----- Steps ----
1) download phpdocumentor
2) extract it to a directory, reachable by a webbrowser, on your local webserver
   at this point you can use the webinterface, navigating to the extraction directory
   
   if you want console usage, add these steps:
3) you find the .ini and .bat in this directory. 
   change the path settings in both files in accordance to your system. 

Explaination of the Batch File
--------------------------------

-phpdoc calls the doc-generator
-option -c adds the ini
        -f adds the files to process
        -t adds the target path for the docs
- > batch-debug.txt puts the output of the console this file, important because of error-reporting from the parser

--- snip ---
@echo on
phpdoc -c smartydocb.ini 
       -f c:/webserverpath/workingdir/*.php 
       -t c:/webserverpath/workingdir/doc 
       > batch-debug.txt
PAUSE
--- snip ---


