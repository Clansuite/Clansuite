; <?php die('Access forbidden.'); /* DO NOT MODIFY THIS LINE! ?>
; 
; Clansuite Configuration File : 
; D:\xampplite\htdocs\work\clansuite\trunk\configuration/clansuite.config.php
; 
; This file was generated on 24-09-2010 ;)
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
; template
;----------------------------------------
[template]
frontend_theme = "standard"
backend_theme = "admin"
layout = "index.tpl"
css = "standard.css"
javascript = "standard.js"
pagetitle = "Team Clansuite"
favicon = ""

;----------------------------------------
; defaults
;----------------------------------------
[defaults]
module = "news"
action = "show"

;----------------------------------------
; language
;----------------------------------------
[language]
language = "de"
outputcharset = "UTF-8"
timezone = "Europe/Paris"
gmtoffset = 3600

;----------------------------------------
; locale
;----------------------------------------
[locale]
locale = 3600
dateformat = "%A, %B %e, %Y"
timezone = 0

;----------------------------------------
; error
;----------------------------------------
[error]
debug = 1
xdebug = 0
development = 0
suppress_errors = 0
debug_popup = 0
webdebug = 0
help_edit_mode = 0
compression = 0

;----------------------------------------
; email
;----------------------------------------
[email]
from = "system@website.com"
mailmethod = "mail"
mailerhost = ""
mailerport = ""
mailencryption = "SWIFT_OPEN"
smtp_username = ""
smtp_password = ""
from_name = ""

;----------------------------------------
; clan
;----------------------------------------
[clan]
name = ""
tag = ""

;----------------------------------------
; switches
;----------------------------------------
[switches]
themeswitch_via_url = 0
languageswitch_via_url = 0

;----------------------------------------
; maintenance
;----------------------------------------
[maintenance]
maintenance = 0

;----------------------------------------
; meta
;----------------------------------------
[meta]
description = "description"
language = "language of this website"
author = "name of author"
email = "webmaster@domain.com"
keywords = "Keyword, Keyword"

;----------------------------------------
; routing
;----------------------------------------
[routing]
mod_rewrite = 0
cache_routes = 0

;----------------------------------------
; login
;----------------------------------------
[login]
login_method = "nick"
remember_me_time = 30
max_login_attempts = 5
login_ban_minutes = 180
min_pass_length = 8
hash_algorithm = "sha1"
registration_term = ""

;----------------------------------------
; session
;----------------------------------------
[session]
use_cookies = 0
use_cookies_only = 0
session_expire_time = 30
session_name = "CSuite"
check_browser = 0
check_host = 0
check_ip = 0

;----------------------------------------
; cache
;----------------------------------------
[cache]
adapter = 0
caching = 0
cache_lifetime = 0

;----------------------------------------
; updater
;----------------------------------------
[updater]
enabled = 0
interval = ""

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

; DO NOT REMOVE THIS LINE */ ?>