This directory is the main theme directory.
There are 3 types of themes: Frontend, Backend and Core.
Custom or contributed themes should be placed in their own subdirectory inside Frontend or Backend folder.
Frontend theme represents a layout and might represent layouts for each module.
If a frontend theme doesn't provide a module layout, the layout of the module itself is used (fallback).
Backend themes are layouts for the controlcenter module and modify the look of the admin control center.
Themes must provide a theme_info.xml file to be recognized by Clansuite.
Please don't touch files in the Core folder. This will allow you to more easily update Clansuite core files later on. 