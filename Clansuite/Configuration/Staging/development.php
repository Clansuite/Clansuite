; <?php die( 'Access forbidden.' ); /* DO NOT MODIFY THIS LINE! ?>
;
; Koch Framework Configuration File : Development
; This file was generated on 26-08-2012 13:58
;

;----------------------------------------
; database
;----------------------------------------
[database]
host = "localhost"
driver = "pdo_mysql"
user = "root"
password = 123
dbname = "clansuitetest"
prefix = "cs_"
charset = "UTF8"

;----------------------------------------
; config
;----------------------------------------
[config]
staging = 0

;----------------------------------------
; template
;----------------------------------------
[template]
frontend_theme = "standard"
backend_theme = "admin"
pagetitle = "Team Clansuite"

;----------------------------------------
; defaults
;----------------------------------------
[defaults]
module = "news"
controller = "news"
action = "list"

;----------------------------------------
; locale
;----------------------------------------
[locale]
locale = "de"
outputcharset = "UTF-8"
timezone = "Europe/Paris"
gmtoffset = 3600
dateformat = "d.m.Y H:i"

;----------------------------------------
; error
;----------------------------------------
[error]
debug = 1
xdebug = 0
development = 1
debug_popup = 0
webdebug = 0
help_edit_mode = 0
compression = 0

;----------------------------------------
; email
;----------------------------------------
[email]
from = "webmaster@website.com"
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
; prefilter
;----------------------------------------
[prefilter]
theme_via_get = 0
language_via_get = 0

;----------------------------------------
; maintenance
;----------------------------------------
[maintenance]
enabled = 0

;----------------------------------------
; meta
;----------------------------------------
[meta]
description = "description"
language = "en"
author = "name of author"
email = "webmaster@domain.com"
keywords = "Keyword, Keyword"

;----------------------------------------
; router
;----------------------------------------
[router]
mod_rewrite = 0
caching = 0

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
session_expire_time = 30
check_browser = 0
check_host = 0
check_ip = 0

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

;----------------------------------------
; cache
;----------------------------------------
[cache]
enabled = 1
driver = "apc"
lifetime = 3600

;----------------------------------------
; smarty
;----------------------------------------
[smarty]
cache = 1
cache_lifetime = 3600

;----------------------------------------
; webserver
;----------------------------------------
[webserver]
mod_rewrite = 0

;----------------------------------------
; editor
;----------------------------------------
[editor]
type = "ckeditor"

;----------------------------------------
; language
;----------------------------------------
[language]
gmtoffset = 3600
timezone = "Europe/Paris"

; DO NOT REMOVE THIS LINE */ ?>
