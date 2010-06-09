; <?php die('Access forbidden.'); /* DO NOT MODIFY THIS LINE! ?>
; 
; Clansuite Configuration File : 
; D:\xampplite\htdocs\work\clansuite\trunk\configuration\clansuite.config.php
; 
; This file was generated on 12-05-2010 23:55
;


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
; paths
;----------------------------------------
[paths]
core_folder = "core"
libraries_folder = "libraries"
language_folder = "languages"
themes_folder = "themes"
mod_folder = "modules"
upload_folder = "uploads"
logfiles_folder = "logs"

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
favicon = ""
theme = "standard"
backend_theme = "admin"
layout = "index.tpl"
css = "standard.css"
javascript = "standard.js"

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
; login
;----------------------------------------
[login]
login_method = "nick"
remember_me_time = 90
max_login_attempts = 5
login_ban_minutes = 30
min_pass_length = 6
hash_algorithm = "sha1"
salt = "1-3-5-8-4-1-7-2-4-1-4-1"
registration_term = ""

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
use_cookies = 1
use_cookies_only = 0
session_expire_time = 30
session_name = ""
check_browser = 0
check_host = 0
check_ip = 0

;----------------------------------------
; error
;----------------------------------------
[error]
development = 1
debug = 1
xdebug = 1
webdebug = 0
debug_popup = 0
help_edit_mode = 0
compression = 0
suppress_errors = 0

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
author = "Jens-Andr&#233; Koch & Clansuite Development Team"
email = "system@clansuite.com"
keywords = "Clansuite, open-source, eSport, cms, clan,content management system, portal, online gaming"

;----------------------------------------
; locale
;----------------------------------------
[locale]
dateformat = "%A, %B %e, %Y"
timezone = 0
locale = 3600

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
enabled = 1

;----------------------------------------
; clan
;----------------------------------------
[clan]
name = "clanname"
tag = "[clantag]::"

;----------------------------------------
; editor
;----------------------------------------
[editor]
type = "tinymce"

;----------------------------------------
; htmltidy
;----------------------------------------
[htmltidy]
enabled = 1

;----------------------------------------
; antispam
;----------------------------------------
[antispam]
captchatype = "recaptcha"

;----------------------------------------
; recaptcha
;----------------------------------------
[recaptcha]
public_key = "keystring"
private_key = "keystring"

; DO NOT REMOVE THIS LINE */ ?>