; <?php die( 'Access forbidden.' ); /* DO NOT MODIFY THIS LINE! ?>
;
; Clansuite Configuration File :
; D:\xampplite\htdocs\work\clansuite\trunk\configuration\clansuite.config.php
; When this file is not existant, Clansuite will redirect you to the Installation process.

;----------------------------------------
; database
;----------------------------------------
[database]
host = "localhost"
type = "mysql"
username = "root"
password = ""
name = "clansuite"
prefix = "cs_"

;----------------------------------------
; doctrine
;----------------------------------------
[doctrine]
datafixtures_path = ""
sql_path = ""
migrations_path = ""
yaml_schema_path = ""
models_path = ""

;----------------------------------------
; email
;----------------------------------------
[email]
mailmethod = "mail"
mailerhost = ""
mailerport = ""
mailencryption = "SWIFT_OPEN"
smtp_username = ""
smtp_password = ""
from = "system@website.com"
from_name = "Clansuite CMS - HAL 9000"

;----------------------------------------
; template
;----------------------------------------
[template]
pagetitle = "Team Clansuite"
theme = "standard"
backend_theme = "admin"

;----------------------------------------
; switches
;----------------------------------------
[switches]
themeswitch_via_url = 0
languageswitch_via_url = 0

;----------------------------------------
; defaults
;----------------------------------------
[defaults]
module = "news"
action = "show"
dateformat = "%A, %d.%m.%Y %H:%M %Z"

;----------------------------------------
; language
;----------------------------------------
[language]
language = "de"
outputcharset = "UTF-8"
timezone = "Europe/Berlin"
gmtoffset = 3600

;----------------------------------------
; openid
;----------------------------------------
[openid]
trustroot = "http://www.clansuite.com/openid/"
showloginbox = 1

;----------------------------------------
; upload
;----------------------------------------
[upload]
max_upload_filesize = 1048576

;----------------------------------------
; session
;----------------------------------------
[session]
session_expire_time = 30
session_name = ""
check_browser = 1
check_host = 1
check_ip = 1

;----------------------------------------
; error
;----------------------------------------
[error]
development = 1
debug = 1
xdebug = 1
webdebug = 0
debug_popup = 0

;----------------------------------------
; cache
;----------------------------------------
[cache]
adapter = apc
caching = 0
cache_lifetime = 90

;----------------------------------------
; maintenance
;----------------------------------------
[maintenance]
maintenance = 0

;----------------------------------------
; meta
;----------------------------------------
[meta]
description = "Clansuite - just an e-sport content management system."
language = "de"
author = "Jens-André Koch & Clansuite Development Team"
email = "system@clansuite.com"
keywords = "Clansuite, open-source, eSport, cms, clan,content management system, portal, online gaming"

;----------------------------------------
; locale
;----------------------------------------
[locale]
dateformat = "l, d.m.Y H:i"
timezone = 0

;----------------------------------------
; statistics
;----------------------------------------
[statistics]
enabled = 1

;----------------------------------------
; updater
;----------------------------------------
[updater]
enabled = 1
interval = 604800

;----------------------------------------
; logs
;----------------------------------------
[logs]
rotation = 1

;----------------------------------------
; minifer
;----------------------------------------
[minifer]
enabled = 0

;----------------------------------------
; help
;----------------------------------------
[help]
tracking = 1

;----------------------------------------
; eventsystem
;----------------------------------------
[eventsystem]
eventsystem_enabled = 1

;----------------------------------------
; clan
;----------------------------------------
[clan]
name=clanname
tag=[clantag]::

;----------------------------------------
; wysiwyg editor
;----------------------------------------
[editor]
type=ckeditor

[htmltidy]
enabled = 1

;----------------------------------------
; ANTI-SPAM / CAPTCHA
;----------------------------------------
[antispam]
captchatype = recaptcha

[recaptcha]
public_key = keystring
private_key = keystring

; DO NOT REMOVE THIS LINE */ ?>