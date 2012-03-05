{modulenavigation}
<div class="ModuleHeading">{t}Doctrine - Overview{/t}</div>
<div class="ModuleHeadingSmall">{t}You can execute Doctrine commands to manage (import, export, backup) your records.{/t}</div>

For the understanding of this module 3 things a very important.
1) The schema files are configuration files describing your database structure.
2) The models are php files. They represent object implementing the active record pattern.
   Your data is interacting with these objects.
3) The database itself stored via the mysql server daemon.
If you update one of these things manually, you have to update the other two
things to keep them synchronized.

<h3>{t}Tasks{/t}</h3>

<h4>1. DB &raquo; Models</h4>
This will generate Models files from your Database.

<h4>2. DB &raquo; YAML</h4>
This will generate YAML schema files from your Database.

<h4>3. Models &raquo; SQL</h4>
This will generate a SQL schema file from your Database.

<h4>4. Models &raquo; YAML</h4>
This will generate YAML schema files from your Models.

<h4>5. YAML &raquo; Models</h4>
This will generate YAML from Models.